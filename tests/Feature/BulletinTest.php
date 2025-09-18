<?php

namespace Tests\Feature;

use App\Models\BulletinPost;
use App\Models\Post;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class BulletinTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private BulletinPost $bulletinPost;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->bulletinPost = BulletinPost::factory()->create();
    }

    public function test_can_view_bulletin_index(): void
    {
        // Create multiple bulletin posts
        BulletinPost::factory()->count(3)->create();

        $response = $this->get(route('bulletin.index'));

        $response->assertStatus(200);
        $response->assertViewIs('bulletin.index');
        $response->assertViewHas('bulletinPosts');

        // Should have 4 total (3 + 1 from setUp)
        $this->assertCount(4, $response->viewData('bulletinPosts'));
    }

    public function test_can_filter_bulletin_by_category(): void
    {
        // Create posts with different categories
        BulletinPost::factory()->create(['category' => 'anlass']);
        BulletinPost::factory()->create(['category' => 'produktion']);
        BulletinPost::factory()->create(['category' => 'verkauf']);

        $response = $this->get(route('bulletin.index', ['category' => 'produktion']));

        $response->assertStatus(200);

        $bulletinPosts = $response->viewData('bulletinPosts');
        $this->assertCount(1, $bulletinPosts);
        $this->assertEquals('produktion', $bulletinPosts->first()->category);
    }

    public function test_can_view_single_bulletin_post(): void
    {
        $response = $this->get(route('bulletin.show', $this->bulletinPost->slug));

        $response->assertStatus(200);
        $response->assertViewIs('bulletin.show');
        $response->assertViewHas('bulletinPost');

        $viewBulletinPost = $response->viewData('bulletinPost');
        $this->assertEquals($this->bulletinPost->id, $viewBulletinPost->id);
    }

    public function test_archived_posts_not_shown_in_index(): void
    {
        // Create an archived post
        BulletinPost::factory()->create(['status' => 'archived']);

        $response = $this->get(route('bulletin.index'));

        $bulletinPosts = $response->viewData('bulletinPosts');

        // Should only see active posts (1 from setUp)
        $this->assertCount(1, $bulletinPosts);
        $this->assertEquals('published', $bulletinPosts->first()->status);
    }

    public function test_can_access_edit_page_with_valid_token(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('bulletin.edit', [
                'slug' => $this->bulletinPost->slug,
                'token' => $this->bulletinPost->edit_token
            ]));

        $response->assertStatus(200);
        $response->assertViewIs('bulletin.edit');
        $response->assertViewHas('bulletinPost');
    }

    public function test_cannot_access_edit_page_without_token(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('bulletin.edit', $this->bulletinPost->slug));

        $response->assertStatus(403);
    }

    public function test_cannot_access_edit_page_with_wrong_token(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('bulletin.edit', [
                'slug' => $this->bulletinPost->slug,
                'token' => 'wrong-token'
            ]));

        $response->assertStatus(403);
    }

    public function test_can_update_bulletin_with_valid_token(): void
    {
        $updateData = [
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'location' => 'Updated Location',
            'contact_name' => 'Updated Contact',
            'contact_phone' => '123456789',
            'contact_email' => 'updated@example.com',
            'status' => 'published',
            'has_forum' => 'on',  // Checkboxes send 'on' when checked
            // has_shifts not sent means unchecked
        ];

        $response = $this->actingAs($this->user)
            ->put(route('bulletin.update', [
                'slug' => $this->bulletinPost->slug,
                'token' => $this->bulletinPost->edit_token
            ]), $updateData);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Eintrag erfolgreich aktualisiert.');

        // Check database was updated
        $this->assertDatabaseHas('bulletin_posts', [
            'id' => $this->bulletinPost->id,
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'location' => 'Updated Location',
            'has_forum' => 1,
            'has_shifts' => 0,
        ]);
    }

    public function test_cannot_update_bulletin_without_token(): void
    {
        $updateData = [
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'location' => 'Updated Location',
            'contact_name' => 'Updated Contact',
            'status' => 'published',
        ];

        $response = $this->actingAs($this->user)
            ->put(route('bulletin.update', $this->bulletinPost->slug), $updateData);

        $response->assertStatus(403);
    }

    public function test_slug_changes_when_title_updated(): void
    {
        $oldSlug = $this->bulletinPost->slug;

        $updateData = [
            'title' => 'Completely New Title',
            'description' => $this->bulletinPost->description,
            'location' => $this->bulletinPost->location,
            'contact_name' => $this->bulletinPost->contact_name,
            'status' => 'published',
        ];

        $response = $this->actingAs($this->user)
            ->put(route('bulletin.update', [
                'slug' => $this->bulletinPost->slug,
                'token' => $this->bulletinPost->edit_token
            ]), $updateData);

        $this->bulletinPost->refresh();

        // Slug should have changed
        $this->assertNotEquals($oldSlug, $this->bulletinPost->slug);
        $this->assertStringStartsWith('completely-new-title-', $this->bulletinPost->slug);
    }

    public function test_validation_errors_on_update(): void
    {
        $updateData = [
            'title' => '', // Required field
            'description' => '', // Required field
            'location' => '',
            'contact_name' => '',
            'status' => 'invalid-status', // Invalid enum value
        ];

        $response = $this->actingAs($this->user)
            ->put(route('bulletin.update', [
                'slug' => $this->bulletinPost->slug,
                'token' => $this->bulletinPost->edit_token
            ]), $updateData);

        $response->assertSessionHasErrors(['title', 'description', 'location', 'contact_name', 'status']);
    }

    public function test_bulletin_shows_related_posts_and_shifts(): void
    {
        // Create related posts
        Post::create([
            'bulletin_post_id' => $this->bulletinPost->id,
            'user_id' => $this->user->id,
            'author_name' => $this->user->name,
            'body' => 'Test post body',
            'ip_hash' => hash('sha256', '127.0.0.1'),
        ]);

        // Create related shifts
        Shift::create([
            'bulletin_post_id' => $this->bulletinPost->id,
            'role' => 'Test Role',
            'time' => '10:00 - 12:00',
            'needed' => 5,
            'offline_filled' => 0,
        ]);

        $response = $this->get(route('bulletin.show', $this->bulletinPost->slug));

        $viewBulletinPost = $response->viewData('bulletinPost');

        // Check relationships are loaded
        $this->assertTrue($viewBulletinPost->relationLoaded('posts'));
        $this->assertTrue($viewBulletinPost->relationLoaded('shifts'));
        $this->assertCount(1, $viewBulletinPost->posts);
        $this->assertCount(1, $viewBulletinPost->shifts);
    }

    public function test_bulletin_orders_by_priority_labels(): void
    {
        // Create posts with different labels
        BulletinPost::factory()->create(['label' => 'label-yellow']);
        BulletinPost::factory()->create(['label' => 'urgent']);
        BulletinPost::factory()->create(['label' => 'important']);
        BulletinPost::factory()->create(['label' => 'featured']);

        $response = $this->get(route('bulletin.index'));

        $bulletinPosts = $response->viewData('bulletinPosts');

        // Check ordering - urgent should be first
        $labels = $bulletinPosts->pluck('label')->toArray();
        $this->assertEquals('urgent', $labels[0]);
        $this->assertEquals('important', $labels[1]);
        $this->assertEquals('featured', $labels[2]);
    }

    public function test_cannot_view_archived_bulletin_post_directly(): void
    {
        $archivedPost = BulletinPost::factory()->create(['status' => 'archived']);

        $response = $this->get(route('bulletin.show', $archivedPost->slug));

        $response->assertStatus(404);
    }
}
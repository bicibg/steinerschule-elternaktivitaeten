<?php

namespace Tests\Feature;

use App\Models\BulletinPost;
use App\Models\Shift;
use App\Models\ShiftVolunteer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShiftManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private BulletinPost $bulletinPost;
    private Shift $shift;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create();

        // Create a bulletin post
        $this->bulletinPost = BulletinPost::factory()->create();

        // Create a shift
        $this->shift = Shift::create([
            'bulletin_post_id' => $this->bulletinPost->id,
            'role' => 'Aufbau',
            'time' => '10:00 - 12:00',
            'needed' => 5,
            'offline_filled' => 0,
        ]);
    }

    public function test_guest_cannot_signup_for_shift(): void
    {
        $response = $this->post(route('shifts.signup', $this->shift));

        $response->assertRedirect(route('login'));
        // Just verify the redirect happens - the error message is visible to the user
    }

    public function test_authenticated_user_can_signup_for_shift(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('shifts.signup', $this->shift));

        $response->assertSessionHas('success', 'Sie haben sich erfolgreich für die Schicht angemeldet.');

        // Check database
        $this->assertDatabaseHas('shift_volunteers', [
            'shift_id' => $this->shift->id,
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
        ]);
    }

    public function test_user_cannot_signup_twice_for_same_shift(): void
    {
        // First signup
        ShiftVolunteer::create([
            'shift_id' => $this->shift->id,
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
        ]);

        // Try to signup again
        $response = $this->actingAs($this->user)
            ->post(route('shifts.signup', $this->shift));

        $response->assertSessionHas('error', 'Sie sind bereits für diese Schicht angemeldet.');

        // Should still have only one entry
        $this->assertEquals(1, ShiftVolunteer::where('shift_id', $this->shift->id)
            ->where('user_id', $this->user->id)->count());
    }

    public function test_shift_respects_capacity_limit(): void
    {
        // Set shift to almost full (3 offline, need 5 total)
        $this->shift->update(['offline_filled' => 3]);

        // Add one online volunteer
        $user1 = User::factory()->create();
        ShiftVolunteer::create([
            'shift_id' => $this->shift->id,
            'user_id' => $user1->id,
            'name' => $user1->name,
            'email' => $user1->email,
        ]);

        // One more should succeed (4/5)
        $response = $this->actingAs($this->user)
            ->post(route('shifts.signup', $this->shift));

        $response->assertSessionHas('success', 'Sie haben sich erfolgreich für die Schicht angemeldet.');

        // But a 6th should fail (already 5/5)
        $user3 = User::factory()->create();
        $response = $this->actingAs($user3)
            ->post(route('shifts.signup', $this->shift));

        $response->assertSessionHas('error', 'Diese Schicht ist bereits voll besetzt.');
    }

    public function test_shift_capacity_includes_offline_registrations(): void
    {
        // Set shift to full with offline registrations
        $this->shift->update(['offline_filled' => 5, 'needed' => 5]);

        // Try to signup online
        $response = $this->actingAs($this->user)
            ->post(route('shifts.signup', $this->shift));

        $response->assertSessionHas('error', 'Diese Schicht ist bereits voll besetzt.');
    }

    public function test_authenticated_user_can_withdraw_from_shift(): void
    {
        // First signup
        ShiftVolunteer::create([
            'shift_id' => $this->shift->id,
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
        ]);

        // Withdraw
        $response = $this->actingAs($this->user)
            ->delete(route('shifts.withdraw', $this->shift));

        $response->assertSessionHas('success', 'Sie haben sich erfolgreich von der Schicht abgemeldet.');

        // Check database
        $this->assertDatabaseMissing('shift_volunteers', [
            'shift_id' => $this->shift->id,
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_cannot_withdraw_if_not_signed_up(): void
    {
        $response = $this->actingAs($this->user)
            ->delete(route('shifts.withdraw', $this->shift));

        $response->assertSessionHas('error', 'Sie sind nicht für diese Schicht angemeldet.');
    }

    public function test_guest_cannot_withdraw_from_shift(): void
    {
        $response = $this->delete(route('shifts.withdraw', $this->shift));

        $response->assertRedirect(route('login'));
    }

    public function test_shift_filled_calculation(): void
    {
        // Start with 2 offline
        $this->shift->update(['offline_filled' => 2]);
        $this->assertEquals(2, $this->shift->filled);

        // Add one online volunteer
        ShiftVolunteer::create([
            'shift_id' => $this->shift->id,
            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
        ]);

        // Refresh to get updated relationship
        $this->shift->refresh();
        $this->assertEquals(3, $this->shift->filled);

        // Add another online volunteer
        $user2 = User::factory()->create();
        ShiftVolunteer::create([
            'shift_id' => $this->shift->id,
            'user_id' => $user2->id,
            'name' => $user2->name,
            'email' => $user2->email,
        ]);

        // Refresh and check
        $this->shift->refresh();
        $this->assertEquals(4, $this->shift->filled);
    }

    public function test_multiple_users_can_signup_for_different_shifts(): void
    {
        // Create second shift
        $shift2 = Shift::create([
            'bulletin_post_id' => $this->bulletinPost->id,
            'role' => 'Abbau',
            'time' => '14:00 - 16:00',
            'needed' => 3,
            'offline_filled' => 0,
        ]);

        // User1 signs up for shift1
        $response = $this->actingAs($this->user)
            ->post(route('shifts.signup', $this->shift));
        $response->assertSessionHas('success');

        // User1 can also sign up for shift2
        $response = $this->actingAs($this->user)
            ->post(route('shifts.signup', $shift2));
        $response->assertSessionHas('success');

        // Check database
        $this->assertEquals(1, ShiftVolunteer::where('shift_id', $this->shift->id)->count());
        $this->assertEquals(1, ShiftVolunteer::where('shift_id', $shift2->id)->count());
    }
}
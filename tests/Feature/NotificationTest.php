<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityPost;
use App\Models\BulletinPost;
use App\Models\Post;
use App\Models\Shift;
use App\Models\ShiftVolunteer;
use App\Models\User;
use App\Notifications\NewActivityCommentNotification;
use App\Notifications\NewActivityPostNotification;
use App\Notifications\NewForumCommentNotification;
use App\Notifications\NewForumPostNotification;
use App\Notifications\ShiftFullNotification;
use App\Notifications\ShiftSignupNotification;
use App\Notifications\ShiftWithdrawalNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    private User $organizer;

    private User $volunteer;

    private BulletinPost $bulletinPost;

    private Shift $shift;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organizer = User::factory()->create();
        $this->volunteer = User::factory()->create();

        $this->bulletinPost = BulletinPost::factory()->create([
            'has_forum' => true,
        ]);

        // Attach organizer as contact user
        $this->bulletinPost->contactUsers()->attach($this->organizer->id);

        $this->shift = Shift::factory()->create([
            'bulletin_post_id' => $this->bulletinPost->id,
            'needed' => 3,
            'offline_filled' => 0,
        ]);
    }

    public function test_shift_signup_notifies_organizer(): void
    {
        Notification::fake();

        $this->actingAs($this->volunteer)
            ->post(route('shifts.signup', $this->shift));

        Notification::assertSentTo(
            $this->organizer,
            ShiftSignupNotification::class,
            function ($notification) {
                return $notification->shift->id === $this->shift->id
                    && $notification->volunteer->id === $this->volunteer->id;
            }
        );
    }

    public function test_shift_signup_does_not_notify_volunteer(): void
    {
        Notification::fake();

        $this->actingAs($this->volunteer)
            ->post(route('shifts.signup', $this->shift));

        Notification::assertNotSentTo($this->volunteer, ShiftSignupNotification::class);
    }

    public function test_shift_withdrawal_notifies_organizer(): void
    {
        Notification::fake();

        ShiftVolunteer::create([
            'shift_id' => $this->shift->id,
            'user_id' => $this->volunteer->id,
            'name' => $this->volunteer->name,
            'email' => $this->volunteer->email,
        ]);

        $this->actingAs($this->volunteer)
            ->delete(route('shifts.withdraw', $this->shift));

        Notification::assertSentTo(
            $this->organizer,
            ShiftWithdrawalNotification::class,
        );
    }

    public function test_shift_full_notification_sent_when_capacity_reached(): void
    {
        Notification::fake();

        // Set shift to almost full: needs 3, already 2 filled
        $this->shift->update(['needed' => 3, 'offline_filled' => 2]);

        $this->actingAs($this->volunteer)
            ->post(route('shifts.signup', $this->shift));

        Notification::assertSentTo(
            $this->organizer,
            ShiftFullNotification::class,
        );
    }

    public function test_shift_full_notification_not_sent_when_not_full(): void
    {
        Notification::fake();

        // Shift needs 3, only 1 will be filled after signup
        $this->shift->update(['needed' => 3, 'offline_filled' => 0]);

        $this->actingAs($this->volunteer)
            ->post(route('shifts.signup', $this->shift));

        Notification::assertNotSentTo($this->organizer, ShiftFullNotification::class);
    }

    public function test_user_with_notifications_disabled_receives_no_email(): void
    {
        Notification::fake();

        $this->organizer->update(['email_notifications' => false]);

        $this->actingAs($this->volunteer)
            ->post(route('shifts.signup', $this->shift));

        // shouldSend returns false so the notification should not be delivered
        Notification::assertNotSentTo($this->organizer, ShiftSignupNotification::class);
    }

    public function test_forum_post_notifies_organizer(): void
    {
        Notification::fake();

        $this->actingAs($this->volunteer)
            ->postJson("/api/pinnwand/{$this->bulletinPost->slug}/posts", [
                'body' => 'Ich habe eine Frage zur Veranstaltung.',
            ]);

        Notification::assertSentTo(
            $this->organizer,
            NewForumPostNotification::class,
        );
    }

    public function test_forum_post_does_not_notify_author(): void
    {
        Notification::fake();

        // Organizer posts in their own forum
        $this->actingAs($this->organizer)
            ->postJson("/api/pinnwand/{$this->bulletinPost->slug}/posts", [
                'body' => 'Information zur Veranstaltung.',
            ]);

        Notification::assertNotSentTo($this->organizer, NewForumPostNotification::class);
    }

    public function test_forum_comment_notifies_post_author_and_organizer(): void
    {
        Notification::fake();

        $postAuthor = User::factory()->create();
        $post = Post::create([
            'bulletin_post_id' => $this->bulletinPost->id,
            'user_id' => $postAuthor->id,
            'body' => 'Original post content',
            'ip_hash' => 'testhash',
        ]);

        $commenter = User::factory()->create();

        $this->actingAs($commenter)
            ->postJson("/api/posts/{$post->id}/comments", [
                'body' => 'Das ist ein Kommentar.',
            ]);

        Notification::assertSentTo($postAuthor, NewForumCommentNotification::class);
        Notification::assertSentTo($this->organizer, NewForumCommentNotification::class);
        Notification::assertNotSentTo($commenter, NewForumCommentNotification::class);
    }

    public function test_activity_post_notifies_organizer(): void
    {
        Notification::fake();

        $activity = Activity::factory()->withForum()->create();
        $activityOrganizer = User::factory()->create();
        $activity->contactUsers()->attach($activityOrganizer->id);

        $this->actingAs($this->volunteer)
            ->postJson("/api/elternaktivitaeten/{$activity->slug}/posts", [
                'body' => 'Neuer Beitrag zur Aktivität.',
            ]);

        Notification::assertSentTo($activityOrganizer, NewActivityPostNotification::class);
        Notification::assertNotSentTo($this->volunteer, NewActivityPostNotification::class);
    }

    public function test_activity_comment_notifies_post_author_and_organizer(): void
    {
        Notification::fake();

        $activity = Activity::factory()->withForum()->create();
        $activityOrganizer = User::factory()->create();
        $activity->contactUsers()->attach($activityOrganizer->id);

        $postAuthor = User::factory()->create();
        $activityPost = ActivityPost::create([
            'activity_id' => $activity->id,
            'user_id' => $postAuthor->id,
            'body' => 'Original activity post',
            'ip_hash' => 'testhash',
        ]);

        $commenter = User::factory()->create();

        $this->actingAs($commenter)
            ->postJson("/api/activity-posts/{$activityPost->id}/comments", [
                'body' => 'Ein Kommentar zur Aktivität.',
            ]);

        Notification::assertSentTo($postAuthor, NewActivityCommentNotification::class);
        Notification::assertSentTo($activityOrganizer, NewActivityCommentNotification::class);
        Notification::assertNotSentTo($commenter, NewActivityCommentNotification::class);
    }

    public function test_contact_email_receives_notification_when_no_contact_user(): void
    {
        Notification::fake();

        // Create a bulletin post with only contact_email, no contactUsers
        $bulletinPost = BulletinPost::factory()->create([
            'contact_email' => 'organizer@example.com',
        ]);

        $shift = Shift::factory()->create([
            'bulletin_post_id' => $bulletinPost->id,
            'needed' => 5,
        ]);

        $this->actingAs($this->volunteer)
            ->post(route('shifts.signup', $shift));

        Notification::assertSentOnDemand(
            ShiftSignupNotification::class,
            function ($notification, $channels, $notifiable) {
                return $notifiable->routes['mail'] === 'organizer@example.com';
            }
        );
    }

    public function test_profile_notification_toggle(): void
    {
        $user = User::factory()->create(['email_notifications' => true]);

        $this->actingAs($user)
            ->patch(route('profile.update'), [
                'name' => $user->name,
                'email_notifications' => '0',
            ]);

        $user->refresh();
        $this->assertFalse($user->email_notifications);
    }
}

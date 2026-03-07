<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_request_account_deletion(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('test-password'),
        ]);

        $response = $this->actingAs($user)->delete(route('profile.destroy'), [
            'current_password' => 'test-password',
        ]);

        $response->assertRedirect('/pinnwand');

        $fresh = User::withTrashed()->find($user->id);
        $this->assertNotNull($fresh->deleted_at);
        $this->assertNotNull($fresh->deletion_requested_at);
        $this->assertGuest();
    }

    public function test_deletion_requires_password_confirmation(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('test-password'),
        ]);

        $response = $this->actingAs($user)->delete(route('profile.destroy'), [
            'current_password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('current_password');

        $fresh = User::find($user->id);
        $this->assertNull($fresh->deleted_at);
        $this->assertNull($fresh->deletion_requested_at);
    }

    public function test_deletion_requires_password_field(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->delete(route('profile.destroy'), []);

        $response->assertSessionHasErrors('current_password');
    }

    public function test_admin_cannot_delete_own_account(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('test-password'),
            'is_admin' => true,
        ]);

        $response = $this->actingAs($user)->delete(route('profile.destroy'), [
            'current_password' => 'test-password',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('error');

        $fresh = User::find($user->id);
        $this->assertNull($fresh->deleted_at);
    }

    public function test_super_admin_cannot_delete_own_account(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('test-password'),
            'is_super_admin' => true,
        ]);

        $response = $this->actingAs($user)->delete(route('profile.destroy'), [
            'current_password' => 'test-password',
        ]);

        $response->assertRedirect(route('profile.edit'));

        $fresh = User::find($user->id);
        $this->assertNull($fresh->deleted_at);
    }

    public function test_deleted_user_can_reactivate_on_login(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('test-password'),
        ]);
        $user->requestDeletion();

        // Login attempt should redirect to reactivation page
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'test-password',
        ]);

        $response->assertRedirect(route('reactivate'));
        $this->assertGuest();

        // Reactivation page should show
        $response = $this->get(route('reactivate'));
        $response->assertStatus(200);
        $response->assertSee('Konto reaktivieren');

        // Confirm reactivation
        $response = $this->post(route('reactivate.confirm'));
        $response->assertRedirect('/pinnwand');

        $fresh = User::find($user->id);
        $this->assertNull($fresh->deleted_at);
        $this->assertNull($fresh->deletion_requested_at);
        $this->assertAuthenticated();
    }

    public function test_reactivation_page_requires_session(): void
    {
        $response = $this->get(route('reactivate'));
        $response->assertRedirect('/login');
    }

    public function test_reactivation_clears_deletion_request(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('test-password'),
        ]);
        $user->requestDeletion();

        $user->cancelDeletion();

        $fresh = User::find($user->id);
        $this->assertNull($fresh->deletion_requested_at);
        $this->assertNull($fresh->deleted_at);
        $this->assertFalse($fresh->isDeletionRequested());
    }

    public function test_deletion_logs_action(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('test-password'),
        ]);

        $this->actingAs($user)->delete(route('profile.destroy'), [
            'current_password' => 'test-password',
        ]);

        $this->assertDatabaseHas('user_deletion_logs', [
            'user_id' => $user->id,
            'action_type' => 'self_deletion_requested',
        ]);
    }

    public function test_reactivation_logs_action(): void
    {
        $user = User::factory()->create();
        $user->requestDeletion();
        $user->cancelDeletion();

        $this->assertDatabaseHas('user_deletion_logs', [
            'user_id' => $user->id,
            'action_type' => 'self_reactivated',
        ]);
    }

    public function test_auto_anonymize_command_processes_expired_users(): void
    {
        $user = User::factory()->create();
        $user->requestDeletion();

        // Manually backdate the deletion request to 31 days ago
        User::withTrashed()->where('id', $user->id)->update([
            'deletion_requested_at' => now()->subDays(31),
        ]);

        $this->artisan('app:anonymize-expired-users')
            ->expectsOutputToContain('Anonymized 1 expired user account(s)')
            ->assertExitCode(0);

        $fresh = User::withTrashed()->find($user->id);
        $this->assertTrue($fresh->isAnonymized());
        $this->assertEquals('Anonymer Benutzer '.$user->id, $fresh->name);
    }

    public function test_auto_anonymize_skips_recent_deletions(): void
    {
        $user = User::factory()->create([
            'name' => 'Still Active',
        ]);
        $user->requestDeletion();

        // Only 15 days ago — should NOT be anonymized
        User::withTrashed()->where('id', $user->id)->update([
            'deletion_requested_at' => now()->subDays(15),
        ]);

        $this->artisan('app:anonymize-expired-users')
            ->expectsOutputToContain('Anonymized 0 expired user account(s)')
            ->assertExitCode(0);

        $fresh = User::withTrashed()->find($user->id);
        $this->assertFalse($fresh->isAnonymized());
        $this->assertEquals('Still Active', $fresh->name);
    }

    public function test_days_until_anonymization(): void
    {
        $user = User::factory()->create();
        $user->requestDeletion();

        // Should be approximately 30 days
        $this->assertGreaterThanOrEqual(29, $user->daysUntilAnonymization());
        $this->assertLessThanOrEqual(30, $user->daysUntilAnonymization());

        // Backdate to 20 days ago
        User::withTrashed()->where('id', $user->id)->update([
            'deletion_requested_at' => now()->subDays(20)->startOfDay(),
        ]);
        $user->refresh();

        $days = $user->daysUntilAnonymization();
        $this->assertGreaterThanOrEqual(9, $days);
        $this->assertLessThanOrEqual(10, $days);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAnonymizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_anonymize_replaces_personal_data(): void
    {
        $user = User::factory()->create([
            'name' => 'Max Mustermann',
            'email' => 'max@example.com',
            'phone' => '+41 79 123 45 67',
            'remarks' => 'Elternrat Klasse 3',
        ]);

        $user->anonymize(999);

        $fresh = User::withTrashed()->find($user->id);
        $this->assertEquals('Anonymer Benutzer '.$user->id, $fresh->name);
        $this->assertEquals('deleted-'.$user->id.'@anonymous.local', $fresh->email);
        $this->assertNull($fresh->phone);
        $this->assertNull($fresh->remarks);
        $this->assertNull($fresh->remember_token);
        $this->assertNotNull($fresh->anonymized_at);
        $this->assertEquals(999, $fresh->anonymized_by);
    }

    public function test_anonymize_invalidates_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('original-password'),
        ]);

        $user->anonymize();

        $fresh = User::find($user->id);
        $this->assertFalse(Hash::check('original-password', $fresh->password));
    }

    public function test_anonymize_refreshes_in_memory_model(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
        ]);

        $user->anonymize();

        $this->assertEquals('Anonymer Benutzer '.$user->id, $user->name);
        $this->assertEquals('deleted-'.$user->id.'@anonymous.local', $user->email);
        $this->assertNotNull($user->anonymized_at);
    }

    public function test_is_anonymized_returns_correct_state(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->isAnonymized());

        $user->anonymize();

        $this->assertTrue($user->isAnonymized());
    }

    public function test_anonymize_works_on_soft_deleted_user(): void
    {
        $user = User::factory()->create([
            'name' => 'Deleted User',
            'email' => 'deleted@example.com',
        ]);
        $user->delete();

        $trashedUser = User::withTrashed()->find($user->id);
        $trashedUser->anonymize(1);

        $fresh = User::withTrashed()->find($user->id);
        $this->assertEquals('Anonymer Benutzer '.$user->id, $fresh->name);
        $this->assertEquals('deleted-'.$user->id.'@anonymous.local', $fresh->email);
        $this->assertNotNull($fresh->anonymized_at);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MakeAdminCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_grants_and_revokes_admin(): void
    {
        $this->artisan('hub:make-admin', ['email' => 'Mariano@network-service.it'])->assertSuccessful();
        $this->assertTrue(User::firstWhere('email', 'mariano@network-service.it')->is_admin);

        $this->artisan('hub:make-admin', ['email' => 'mariano@network-service.it', '--revoke' => true])->assertSuccessful();
        $this->assertFalse(User::firstWhere('email', 'mariano@network-service.it')->is_admin);
    }

    public function test_rejects_other_domains(): void
    {
        $this->artisan('hub:make-admin', ['email' => 'mario@gmail.com'])->assertFailed();
        $this->assertDatabaseCount('users', 0);
    }
}

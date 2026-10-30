<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\TestCase;

class GoogleLoginTest extends TestCase
{
    use RefreshDatabase;

    private function fakeGoogleUser(string $email, string $id = 'google-123', string $name = 'Mario Rossi'): void
    {
        $googleUser = (new SocialiteUser)->map(['id' => $id, 'name' => $name, 'email' => $email]);
        Socialite::shouldReceive('driver->user')->andReturn($googleUser);
    }

    public function test_guest_is_sent_to_login_page(): void
    {
        $this->get('/')->assertRedirect(route('login'));
        $this->get(route('login'))->assertOk()->assertSee('Accedi con Google');
    }

    public function test_redirect_goes_to_google(): void
    {
        $response = $this->get(route('auth.google.redirect'));

        $response->assertRedirect();
        $this->assertStringContainsString('accounts.google.com', $response->headers->get('Location'));
        $this->assertStringContainsString('hd=network-service.it', $response->headers->get('Location'));
    }

    public function test_company_account_logs_in_and_is_created(): void
    {
        $this->fakeGoogleUser('Mariano@network-service.it');

        $this->get(route('auth.google.callback'))->assertRedirect(route('overview'));

        $this->assertAuthenticated();
        $user = User::firstWhere('email', 'mariano@network-service.it');
        $this->assertNotNull($user);
        $this->assertSame('google-123', $user->google_id);
        $this->assertFalse($user->is_admin);
        $this->assertNotNull($user->last_login_at);
    }

    public function test_other_domains_are_rejected(): void
    {
        $this->fakeGoogleUser('someone@gmail.com');

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('login');

        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);
    }

    public function test_lookalike_domain_is_rejected(): void
    {
        $this->fakeGoogleUser('mario@fake-network-service.it');

        $this->get(route('auth.google.callback'))->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_log_in(): void
    {
        User::factory()->inactive()->create(['email' => 'ex@network-service.it']);
        $this->fakeGoogleUser('ex@network-service.it');

        $this->get(route('auth.google.callback'))->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_expired_state_shows_a_message(): void
    {
        Socialite::shouldReceive('driver->user')->andThrow(new InvalidStateException);

        $this->get(route('auth.google.callback'))->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_user_can_log_out(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_deactivated_user_loses_open_session(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $user->update(['is_active' => false]);

        $this->get('/')->assertRedirect(route('login'));
        $this->assertGuest();
    }
}

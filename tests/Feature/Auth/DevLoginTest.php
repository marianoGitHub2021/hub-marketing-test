<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DevLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_dev_login_is_off_by_default(): void
    {
        $this->post(route('auth.dev'), ['email' => 'mario@network-service.it'])->assertNotFound();
        $this->get(route('login'))->assertDontSee('Accesso di sviluppo');
    }

    public function test_dev_login_works_when_enabled_outside_production(): void
    {
        config(['hub.dev_login' => true]);

        $this->get(route('login'))->assertSee('Accesso di sviluppo');
        $this->post(route('auth.dev'), ['email' => 'mario@network-service.it'])->assertRedirect(route('overview'));
        $this->assertAuthenticated();
    }

    public function test_dev_login_rejects_other_domains(): void
    {
        config(['hub.dev_login' => true]);

        $this->post(route('auth.dev'), ['email' => 'mario@example.com'])->assertSessionHasErrors('login');
        $this->assertGuest();
    }

    public function test_dev_login_never_works_in_production(): void
    {
        config(['hub.dev_login' => true]);
        $this->app->detectEnvironment(fn () => 'production');
        $this->withoutMiddleware(PreventRequestForgery::class);

        $this->post(route('auth.dev'), ['email' => 'mario@network-service.it'])->assertNotFound();
        $this->assertGuest();
    }
}

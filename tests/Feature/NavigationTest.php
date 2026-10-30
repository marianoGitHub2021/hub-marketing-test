<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavigationTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_member_sees_portfolio_pages(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('overview'))->assertOk()->assertSee('Overview di portafoglio');
        $this->get(route('clients'))->assertOk()->assertSee('Clienti');
        $this->get(route('alerts'))->assertOk()->assertSee('Centro allerte');
    }

    public function test_settings_are_admin_only(): void
    {
        $this->actingAs(User::factory()->create());
        $this->get(route('settings'))->assertForbidden();
        $this->get(route('overview'))->assertDontSee('Impostazioni');

        $this->actingAs(User::factory()->admin()->create());
        $this->get(route('settings'))->assertOk()->assertSee('Impostazioni');
        $this->get(route('overview'))->assertSee('Impostazioni');
    }

    public function test_logged_in_user_skips_login_page(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get(route('login'))->assertRedirect(route('overview'));
    }
}

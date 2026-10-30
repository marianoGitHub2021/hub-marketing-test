<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Impostazioni, anagrafica, utenti: solo admin (docs/decisioni-fase-0.md §6).
        Gate::define('admin', fn (User $user) => $user->is_admin);
    }
}

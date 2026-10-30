<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login', ['devLogin' => self::devLoginEnabled()]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Accesso senza Google, solo in sviluppo locale e nei test.
     */
    public function devLogin(Request $request): RedirectResponse
    {
        abort_unless(self::devLoginEnabled(), 404);

        $data = $request->validate(['email' => ['required', 'email']]);
        $email = Str::lower($data['email']);

        if (! Str::endsWith($email, '@'.config('hub.login_domain'))) {
            return back()->withErrors(['login' => 'Usa un indirizzo @'.config('hub.login_domain').'.']);
        }

        $user = User::firstOrCreate(['email' => $email], ['name' => Str::before($email, '@')]);
        abort_unless($user->is_active, 403);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('overview');
    }

    public static function devLoginEnabled(): bool
    {
        return config('hub.dev_login') && app()->environment(['local', 'testing']);
    }
}

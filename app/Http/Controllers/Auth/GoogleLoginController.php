<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class GoogleLoginController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')
            ->with(['hd' => config('hub.login_domain'), 'prompt' => 'select_account'])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException) {
            return $this->fail('La sessione di accesso è scaduta. Riprova.');
        }

        $email = Str::lower((string) $googleUser->getEmail());

        // Il parametro hd della richiesta si può manomettere: il dominio si verifica sull'email restituita da Google.
        if (! Str::endsWith($email, '@'.config('hub.login_domain'))) {
            return $this->fail('Accesso consentito solo agli account @'.config('hub.login_domain').'.');
        }

        $user = User::firstOrNew(['email' => $email]);

        if ($user->exists && ! $user->is_active) {
            return $this->fail('Il tuo utente è disattivato. Chiedi a un amministratore di riattivarlo.');
        }

        $user->fill([
            'name' => $googleUser->getName() ?: $email,
            'google_id' => $googleUser->getId(),
            'last_login_at' => now(),
        ])->save();

        Auth::login($user, remember: true);
        request()->session()->regenerate();

        return redirect()->intended(route('overview'));
    }

    private function fail(string $message): RedirectResponse
    {
        return redirect()->route('login')->withErrors(['login' => $message]);
    }
}

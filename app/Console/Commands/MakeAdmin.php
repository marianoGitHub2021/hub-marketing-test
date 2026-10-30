<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

#[Signature('hub:make-admin {email : Email aziendale dell\'utente} {--revoke : Toglie il ruolo di admin}')]
#[Description('Assegna o toglie il ruolo di admin a un utente nominativo')]
class MakeAdmin extends Command
{
    public function handle(): int
    {
        $email = Str::lower($this->argument('email'));

        if (! Str::endsWith($email, '@'.config('hub.login_domain'))) {
            $this->error('Serve un indirizzo @'.config('hub.login_domain').'.');

            return self::FAILURE;
        }

        $user = User::firstOrCreate(['email' => $email], ['name' => Str::before($email, '@')]);
        $user->update(['is_admin' => ! $this->option('revoke')]);

        $this->info($user->is_admin ? "{$email} ora è admin." : "{$email} non è più admin.");

        return self::SUCCESS;
    }
}

<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Utente nominativo del team. Accesso solo con Google aziendale: nessuna password locale.
 */
#[Fillable(['name', 'email', 'google_id', 'is_admin', 'is_active', 'absent_until', 'last_login_at'])]
#[Hidden(['remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /** Valori predefiniti, uguali a quelli della migrazione. */
    protected $attributes = [
        'is_admin' => false,
        'is_active' => true,
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
            'absent_until' => 'date',
            'last_login_at' => 'datetime',
        ];
    }

    public function isAbsent(): bool
    {
        return $this->absent_until !== null && $this->absent_until->isFuture();
    }
}

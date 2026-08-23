<?php

namespace App\Models;

use App\Models\Mahasiswa;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'role',
    'status',
    'must_change_password',
])]#[Hidden(['password', 'remember_token'])]

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the mentor profile associated with the user.
     */
    public function mentor(): HasOne
    {
        return $this->hasOne(Mentor::class);
    }

    public function isAdministrator(): bool
    {
        return $this->role === 'administrator';
    }

    public function isMentor(): bool
    {
        return $this->role === 'mentor';
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * User memiliki satu profil Mahasiswa.
     */
    public function mahasiswa(): HasOne
    {
        return $this->hasOne(Mahasiswa::class);
    }
}
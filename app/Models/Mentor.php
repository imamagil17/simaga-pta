<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'nip',
    'jabatan',
    'bagian',
    'jenis_kelamin',
    'agama',
    'no_hp',
    'foto',
    'status',
    'keterangan',
])]
class Mentor extends Model
{
    /** @use HasFactory<\Database\Factories\MentorFactory> */
    use HasFactory;

    /**
     * Default status profil mentor.
     */
    protected static function booted(): void
    {
        static::creating(function (Mentor $mentor): void {
            if ($mentor->status === null) {
                $mentor->status = 'active';
            }
        });
    }

    /**
     * Mentor belongs to a User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke penugasan periode magang.
     */
    public function mentorPeriodes(): HasMany
    {
        return $this->hasMany(MentorPeriode::class);
    }

    /**
     * Relasi penempatan mahasiswa.
     */
    public function penempatans(): HasMany
    {
        return $this->hasMany(Penempatan::class);
    }

    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'nim',
    'perguruan_tinggi',
    'program_studi',
    'jenis_kelamin',
    'agama',
    'no_hp',
    'alamat',
    'foto',
    'status',
    'keterangan',
])]
class Mahasiswa extends Model
{
    /** @use HasFactory<\Database\Factories\MahasiswaFactory> */
    use HasFactory;

    /**
     * Default status profil mahasiswa.
     */
    protected static function booted(): void
    {
        static::creating(function (Mahasiswa $mahasiswa): void {
            if ($mahasiswa->status === null) {
                $mahasiswa->status = 'active';
            }
        });
    }

    /**
     * Mahasiswa belongs to a User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mahasiswa memiliki banyak penempatan.
     */
    public function penempatans(): HasMany
    {
        return $this->hasMany(Penempatan::class);
    }

    public function pengumpulanTugas(): HasMany
    {
        return $this->hasMany(PengumpulanTugas::class);
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(Dokumen::class);
    }

    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class);
    }
}
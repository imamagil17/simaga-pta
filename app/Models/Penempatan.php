<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'mahasiswa_id',
    'periode_magang_id',
    'mentor_id',
    'status',
    'tanggal_mulai',
    'tanggal_selesai',
    'keterangan',
])]
class Penempatan extends Model
{
    /** @use HasFactory<\Database\Factories\PenempatanFactory> */
    use HasFactory;

    /**
     * Default status penempatan.
     */
    protected static function booted(): void
    {
        static::creating(function (Penempatan $penempatan): void {
            if ($penempatan->status === null) {
                $penempatan->status = 'active';
            }
        });
    }

    /**
     * Penempatan belongs to a Mahasiswa.
     */
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    /**
     * Penempatan belongs to a Periode Magang.
     */
    public function periodeMagang(): BelongsTo
    {
        return $this->belongsTo(PeriodeMagang::class);
    }

    /**
     * Penempatan belongs to a Mentor.
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    /**
     * Cast tanggal.
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    /**
     * Relasi penempatan mahasiswa.
     */
    public function penempatans(): HasMany
    {
        return $this->hasMany(Penempatan::class);
    }

    /**
     * Penempatan memiliki banyak absensi.
     */
    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class);
    }

    /**
     * Penempatan memiliki banyak logbook.
     */
    public function logbooks(): HasMany
    {
        return $this->hasMany(Logbook::class);
    }
}
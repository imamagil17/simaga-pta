<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'penempatan_id',
    'tanggal',
    'jam_masuk',
    'jam_pulang',
    'status_kehadiran',
    'menit_terlambat',
    'status_verifikasi',
    'keterangan',
    'alasan_penolakan',
    'paraf_mahasiswa',
    'paraf_mahasiswa_at',
    'paraf_mentor',
    'paraf_mentor_at',
])]
class Absensi extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function (Absensi $absensi): void {
            if ($absensi->status_kehadiran === null) {
                $absensi->status_kehadiran = 'hadir';
            }

            if ($absensi->status_verifikasi === null) {
                $absensi->status_verifikasi = 'pending';
            }
        });
    }

    public function penempatan(): BelongsTo
    {
        return $this->belongsTo(Penempatan::class);
    }

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'menit_terlambat' => 'integer',
            'paraf_mahasiswa_at' => 'datetime',
            'paraf_mentor_at' => 'datetime',
        ];
    }
}
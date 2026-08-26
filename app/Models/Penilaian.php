<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';

    protected $fillable = [
        'mahasiswa_id',
        'penempatan_id',
        'mentor_id',
        'nilai_kedisiplinan',
        'nilai_kehadiran',
        'nilai_kinerja',
        'nilai_kompetensi',
        'nilai_sikap',
        'nilai_akhir',
        'catatan',
        'status',
        'finalized_by',
        'finalized_at',
    ];

    protected $attributes = [
        'status' => 'draft',
    ];

    protected function casts(): array
    {
        return [
            'nilai_kedisiplinan' => 'decimal:2',
            'nilai_kehadiran' => 'decimal:2',
            'nilai_kinerja' => 'decimal:2',
            'nilai_kompetensi' => 'decimal:2',
            'nilai_sikap' => 'decimal:2',
            'nilai_akhir' => 'decimal:2',
            'finalized_at' => 'datetime',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function penempatan(): BelongsTo
    {
        return $this->belongsTo(Penempatan::class);
    }

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }

    public function finalizer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'finalized_by'
        );
    }

    /**
     * Hitung nilai akhir berdasarkan bobot.
     */
    public function hitungNilaiAkhir(): float
    {
        $nilai = [
            $this->nilai_kedisiplinan,
            $this->nilai_kehadiran,
            $this->nilai_kinerja,
            $this->nilai_kompetensi,
            $this->nilai_sikap,
        ];

        if (collect($nilai)->contains(null)) {
            return 0;
        }

        return round(
            (
                ((float) $this->nilai_kedisiplinan * 0.20) +
                ((float) $this->nilai_kehadiran * 0.20) +
                ((float) $this->nilai_kinerja * 0.20) +
                ((float) $this->nilai_kompetensi * 0.20) +
                ((float) $this->nilai_sikap * 0.20)
            ),
            2
        );
    }
}

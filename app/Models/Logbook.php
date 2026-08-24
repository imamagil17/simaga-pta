<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Logbook extends Model
{
    use HasFactory;

    protected $fillable = [
        'penempatan_id',
        'tanggal',
        'judul_kegiatan',
        'uraian_kegiatan',
        'hasil_kegiatan',
        'kendala',
        'rencana_tindak_lanjut',
        'bukti_kegiatan',
        'status',
        'catatan_mentor',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $attributes = [
        'status' => 'draft',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function penempatan(): BelongsTo
    {
        return $this->belongsTo(Penempatan::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}

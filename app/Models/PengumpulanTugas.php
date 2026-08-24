<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengumpulanTugas extends Model
{
    use HasFactory;

    protected $table = 'pengumpulan_tugas';

    protected $fillable = [
        'tugas_id',
        'mahasiswa_id',
        'jawaban',
        'file_jawaban',
        'dikumpulkan_at',
        'status',
        'nilai',
        'catatan_mentor',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $attributes = [
        'status' => 'draft',
    ];

    protected function casts(): array
    {
        return [
            'dikumpulkan_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'nilai' => 'decimal:2',
        ];
    }

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(Tugas::class);
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}

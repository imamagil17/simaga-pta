<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';

    protected $fillable = [
        'mahasiswa_id',
        'penempatan_id',
        'jenis_dokumen',
        'nama_dokumen',
        'nama_file',
        'path_file',
        'mime_type',
        'ukuran_file',
        'status',
        'catatan',
        'verified_by',
        'verified_at',
    ];

    protected $attributes = [
        'status' => 'uploaded',
    ];

    protected function casts(): array
    {
        return [
            'ukuran_file' => 'integer',
            'verified_at' => 'datetime',
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

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}

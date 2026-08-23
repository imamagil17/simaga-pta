<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'nama_periode',
    'kode_periode',
    'tanggal_mulai',
    'tanggal_selesai',
    'status',
    'keterangan',
])]
class PeriodeMagang extends Model
{
    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    /**
     * Relasi ke penugasan mentor.
     */
    public function mentorPeriodes(): HasMany
    {
        return $this->hasMany(MentorPeriode::class);
    }
}
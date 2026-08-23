<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'periode_magang_id',
    'mentor_id',
    'status',
    'keterangan',
])]
class MentorPeriode extends Model
{
    /**
     * Relasi ke periode magang.
     */
    public function periodeMagang(): BelongsTo
    {
        return $this->belongsTo(PeriodeMagang::class);
    }

    /**
     * Relasi ke mentor.
     */
    public function mentor(): BelongsTo
    {
        return $this->belongsTo(Mentor::class);
    }
}
<?php

namespace App\Services;

use Carbon\Carbon;

class WorkScheduleService
{
    /**
     * Jam masuk normal.
     */
    public function jamMasuk(Carbon $date): string
    {
        return '08:00';
    }

    /**
     * Jam pulang normal berdasarkan hari.
     *
     * Senin-Kamis = 16:30
     * Jumat       = 16:50
     * Sabtu-Minggu = null
     */
    public function jamPulang(Carbon $date): ?string
    {
        return match ($date->dayOfWeek) {
            Carbon::MONDAY,
            Carbon::TUESDAY,
            Carbon::WEDNESDAY,
            Carbon::THURSDAY => '16:30',

            Carbon::FRIDAY => '16:50',

            Carbon::SATURDAY,
            Carbon::SUNDAY => null,
        };
    }

    /**
     * Apakah hari tersebut merupakan hari kerja?
     */
    public function isHariKerja(Carbon $date): bool
    {
        return $this->jamPulang($date) !== null;
    }
}
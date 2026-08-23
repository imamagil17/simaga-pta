<?php

namespace App\Services;

use Carbon\Carbon;

class WorkScheduleService
{
    /**
     * Apakah mode testing absensi sedang aktif?
     *
     * Mode ini dikontrol melalui .env:
     *
     * ABSENSI_TEST_MODE=true
     *
     * Untuk kondisi normal:
     *
     * ABSENSI_TEST_MODE=false
     */
    protected function isTestMode(): bool
    {
        return filter_var(
            env('ABSENSI_TEST_MODE', false),
            FILTER_VALIDATE_BOOLEAN
        );
    }

    /**
     * Jam pulang testing.
     */
    protected function testJamPulang(): ?string
    {
        return env('ABSENSI_TEST_JAM_PULANG');
    }

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
     * Sabtu-Minggu = tidak tersedia
     */
    public function jamPulang(Carbon $date): ?string
    {
        /*
        |--------------------------------------------------------------------------
        | Mode Testing
        |--------------------------------------------------------------------------
        |
        | Saat ABSENSI_TEST_MODE=true, semua hari dianggap dapat digunakan
        | untuk testing dan jam pulang mengikuti ABSENSI_TEST_JAM_PULANG.
        |
        */
        if ($this->isTestMode()) {
            return $this->testJamPulang();
        }

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
        /*
        |--------------------------------------------------------------------------
        | Mode Testing
        |--------------------------------------------------------------------------
        */
        if ($this->isTestMode()) {
            return true;
        }

        return $this->jamPulang($date) !== null;
    }
}
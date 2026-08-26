<?php

namespace App\Services;

use App\Models\HariLibur;
use App\Models\Setting;
use Carbon\Carbon;

class WorkScheduleService
{
    /**
     * Jam masuk normal.
     *
     * Method ini dipertahankan dengan nama jamMasuk()
     * karena digunakan oleh AbsensiController dan test lama.
     */
    public function jamMasuk(
        ?Carbon $tanggal = null
    ): string {
        return Setting::getValue(
            'jam_masuk',
            '08:00'
        );
    }

    /**
     * Alias untuk mendapatkan jam masuk normal.
     */
    public function jamMasukNormal(): string
    {
        return $this->jamMasuk();
    }

    /**
     * Jam pulang berdasarkan tanggal.
     *
     * Senin-Kamis:
     * 16:30
     *
     * Jumat:
     * 16:50
     *
     * Hari libur:
     * null
     */
    public function jamPulang(
        Carbon $tanggal
    ): ?string {
        if (! $this->isHariKerja($tanggal)) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Jumat
        |--------------------------------------------------------------------------
        */
        if ($tanggal->dayOfWeek === Carbon::FRIDAY) {
            return Setting::getValue(
                'jam_pulang_jumat',
                '16:50'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Hari kerja selain Jumat
        |--------------------------------------------------------------------------
        */
        return Setting::getValue(
            'jam_pulang_senin_kamis',
            '16:30'
        );
    }

    /**
     * Menentukan apakah tanggal merupakan hari kerja.
     *
     * Urutan:
     *
     * 1. Cek setting hari dalam minggu.
     * 2. Jika hari tersebut tidak aktif -> libur.
     * 3. Jika ada hari libur khusus -> libur.
     * 4. Selain itu -> hari kerja.
     */
    public function isHariKerja(
        Carbon $tanggal
    ): bool {
        $map = [
            Carbon::MONDAY =>
            'hari_kerja_senin',

            Carbon::TUESDAY =>
            'hari_kerja_selasa',

            Carbon::WEDNESDAY =>
            'hari_kerja_rabu',

            Carbon::THURSDAY =>
            'hari_kerja_kamis',

            Carbon::FRIDAY =>
            'hari_kerja_jumat',

            Carbon::SATURDAY =>
            'hari_kerja_sabtu',

            Carbon::SUNDAY =>
            'hari_kerja_minggu',
        ];

        $key = $map[$tanggal->dayOfWeek] ?? null;

        if (! $key) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Default:
        | Senin-Jumat masuk
        | Sabtu-Minggu libur
        |--------------------------------------------------------------------------
        */
        $hariAktif = (bool) (
            (int) Setting::getValue(
                $key,
                in_array(
                    $tanggal->dayOfWeek,
                    [
                        Carbon::MONDAY,
                        Carbon::TUESDAY,
                        Carbon::WEDNESDAY,
                        Carbon::THURSDAY,
                        Carbon::FRIDAY,
                    ],
                    true
                )
                    ? '1'
                    : '0'
            )
        );

        if (! $hariAktif) {
            return false;
        }

        /*
        |--------------------------------------------------------------------------
        | Cek hari libur khusus / tanggal merah
        |--------------------------------------------------------------------------
        */
        $libur = HariLibur::query()
            ->whereDate(
                'tanggal',
                $tanggal->toDateString()
            )
            ->where(
                'aktif',
                true
            )
            ->exists();

        if ($libur) {
            return false;
        }

        return true;
    }

    /**
     * Mendapatkan alasan hari libur khusus.
     */
    public function alasanLibur(
        Carbon $tanggal
    ): ?string {
        $hariLibur = HariLibur::query()
            ->whereDate(
                'tanggal',
                $tanggal->toDateString()
            )
            ->where(
                'aktif',
                true
            )
            ->first();

        if (! $hariLibur) {
            return null;
        }

        return $hariLibur->nama;
    }
}

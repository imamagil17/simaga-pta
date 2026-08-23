<?php

use App\Services\WorkScheduleService;
use Carbon\Carbon;

test('jam masuk normal adalah jam 08:00', function () {
    $service = new WorkScheduleService();

    expect(
        $service->jamMasuk(
            Carbon::parse('2026-08-24')
        )
    )->toBe('08:00');
});

test('senin sampai kamis memiliki jam pulang 16:30', function () {
    $service = new WorkScheduleService();

    foreach ([
        '2026-08-24', // Senin
        '2026-08-25', // Selasa
        '2026-08-26', // Rabu
        '2026-08-27', // Kamis
    ] as $tanggal) {
        expect(
            $service->jamPulang(
                Carbon::parse($tanggal)
            )
        )->toBe('16:30');
    }
});

test('jumat memiliki jam pulang 16:50', function () {
    $service = new WorkScheduleService();

    expect(
        $service->jamPulang(
            Carbon::parse('2026-08-28')
        )
    )->toBe('16:50');
});

test('sabtu dan minggu bukan hari kerja', function () {
    $service = new WorkScheduleService();

    expect(
        $service->jamPulang(
            Carbon::parse('2026-08-29')
        )
    )->toBeNull();

    expect(
        $service->jamPulang(
            Carbon::parse('2026-08-30')
        )
    )->toBeNull();
});

test('sabtu dan minggu tidak dianggap hari kerja', function () {
    $service = new WorkScheduleService();

    expect(
        $service->isHariKerja(
            Carbon::parse('2026-08-29')
        )
    )->toBeFalse();

    expect(
        $service->isHariKerja(
            Carbon::parse('2026-08-30')
        )
    )->toBeFalse();

    expect(
        $service->isHariKerja(
            Carbon::parse('2026-08-28')
        )
    )->toBeTrue();
});
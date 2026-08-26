<?php

use App\Models\HariLibur;
use App\Models\Setting;
use App\Services\WorkScheduleService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(
    TestCase::class,
    RefreshDatabase::class
);

beforeEach(function () {
    Setting::setValue(
        'jam_masuk',
        '08:00',
        'jam_kerja'
    );

    Setting::setValue(
        'jam_pulang_senin_kamis',
        '16:30',
        'jam_kerja'
    );

    Setting::setValue(
        'jam_pulang_jumat',
        '16:50',
        'jam_kerja'
    );

    Setting::setValue(
        'hari_kerja_senin',
        '1',
        'hari_kerja'
    );

    Setting::setValue(
        'hari_kerja_selasa',
        '1',
        'hari_kerja'
    );

    Setting::setValue(
        'hari_kerja_rabu',
        '1',
        'hari_kerja'
    );

    Setting::setValue(
        'hari_kerja_kamis',
        '1',
        'hari_kerja'
    );

    Setting::setValue(
        'hari_kerja_jumat',
        '1',
        'hari_kerja'
    );

    Setting::setValue(
        'hari_kerja_sabtu',
        '0',
        'hari_kerja'
    );

    Setting::setValue(
        'hari_kerja_minggu',
        '0',
        'hari_kerja'
    );
});

test('jam masuk normal adalah jam 08:00', function () {
    $service = app(
        WorkScheduleService::class
    );

    expect(
        $service->jamMasuk(
            Carbon::parse('2026-08-24')
        )
    )->toBe('08:00');
});

test('senin sampai kamis memiliki jam pulang 16:30', function () {
    $service = app(
        WorkScheduleService::class
    );

    $senin = Carbon::parse(
        '2026-08-24'
    );

    $selasa = Carbon::parse(
        '2026-08-25'
    );

    $rabu = Carbon::parse(
        '2026-08-26'
    );

    $kamis = Carbon::parse(
        '2026-08-27'
    );

    expect(
        $service->jamPulang($senin)
    )->toBe('16:30');

    expect(
        $service->jamPulang($selasa)
    )->toBe('16:30');

    expect(
        $service->jamPulang($rabu)
    )->toBe('16:30');

    expect(
        $service->jamPulang($kamis)
    )->toBe('16:30');
});

test('jumat memiliki jam pulang 16:50', function () {
    $service = app(
        WorkScheduleService::class
    );

    $jumat = Carbon::parse(
        '2026-08-28'
    );

    expect(
        $service->jamPulang($jumat)
    )->toBe('16:50');
});

test('sabtu dan minggu bukan hari kerja', function () {
    $service = app(
        WorkScheduleService::class
    );

    $sabtu = Carbon::parse(
        '2026-08-29'
    );

    $minggu = Carbon::parse(
        '2026-08-30'
    );

    expect(
        $service->isHariKerja($sabtu)
    )->toBeFalse();

    expect(
        $service->isHariKerja($minggu)
    )->toBeFalse();
});

test('sabtu dan minggu tidak dianggap hari kerja', function () {
    $service = app(
        WorkScheduleService::class
    );

    $sabtu = Carbon::parse(
        '2026-08-29'
    );

    $minggu = Carbon::parse(
        '2026-08-30'
    );

    expect(
        $service->jamPulang($sabtu)
    )->toBeNull();

    expect(
        $service->jamPulang($minggu)
    )->toBeNull();
});

test('sabtu bisa menjadi hari kerja jika diaktifkan admin', function () {
    Setting::setValue(
        'hari_kerja_sabtu',
        '1',
        'hari_kerja'
    );

    $service = app(
        WorkScheduleService::class
    );

    $sabtu = Carbon::parse(
        '2026-08-29'
    );

    expect(
        $service->isHariKerja($sabtu)
    )->toBeTrue();

    expect(
        $service->jamPulang($sabtu)
    )->toBe('16:30');
});

test('tanggal libur khusus tetap libur walaupun hari tersebut aktif', function () {
    HariLibur::create([
        'tanggal' => '2026-08-24',
        'nama' => 'Hari Libur Khusus',
        'aktif' => true,
    ]);

    $service = app(
        WorkScheduleService::class
    );

    $senin = Carbon::parse(
        '2026-08-24'
    );

    expect(
        $service->isHariKerja($senin)
    )->toBeFalse();

    expect(
        $service->jamPulang($senin)
    )->toBeNull();

    expect(
        $service->alasanLibur($senin)
    )->toBe('Hari Libur Khusus');
});

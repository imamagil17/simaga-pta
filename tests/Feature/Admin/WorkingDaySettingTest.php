<?php

use App\Models\HariLibur;
use App\Models\Setting;
use App\Models\User;
use App\Services\WorkScheduleService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createWorkingDayAdmin(): User
{
    return User::factory()->create([
        'role' => 'administrator',
        'status' => 'active',
        'must_change_password' => false,
    ]);
}

test('default working days are monday to friday', function () {
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

    $service = app(
        WorkScheduleService::class
    );

    expect(
        $service->isHariKerja(
            Carbon::create(
                2026,
                8,
                24
            )
        )
    )->toBeTrue();

    expect(
        $service->isHariKerja(
            Carbon::create(
                2026,
                8,
                29
            )
        )
    )->toBeFalse();

    expect(
        $service->isHariKerja(
            Carbon::create(
                2026,
                8,
                30
            )
        )
    )->toBeFalse();
});

test('administrator can change saturday to working day', function () {
    $admin = createWorkingDayAdmin();

    $this
        ->actingAs($admin)
        ->put(
            route('admin.settings.update'),
            [
                'nama_instansi' =>
                'Pengadilan Tinggi Agama Palu',

                'alamat_instansi' =>
                'Palu, Sulawesi Tengah',

                'jam_masuk' =>
                '08:00',

                'jam_pulang_senin_kamis' =>
                '16:30',

                'jam_pulang_jumat' =>
                '16:50',

                'hari_kerja_senin' => '1',
                'hari_kerja_selasa' => '1',
                'hari_kerja_rabu' => '1',
                'hari_kerja_kamis' => '1',
                'hari_kerja_jumat' => '1',
                'hari_kerja_sabtu' => '1',

                'bobot_kedisiplinan' => 20,
                'bobot_kehadiran' => 20,
                'bobot_kinerja' => 20,
                'bobot_kompetensi' => 20,
                'bobot_sikap' => 20,
            ]
        )
        ->assertRedirect(
            route('admin.settings.index')
        );

    $service = app(
        WorkScheduleService::class
    );

    expect(
        $service->isHariKerja(
            Carbon::create(
                2026,
                8,
                29
            )
        )
    )->toBeTrue();
});

test('special holiday makes a normally working day become a holiday', function () {
    Setting::setValue(
        'hari_kerja_monday',
        '1',
        'hari_kerja'
    );

    Setting::setValue(
        'hari_kerja_senin',
        '1',
        'hari_kerja'
    );

    HariLibur::create([
        'tanggal' => '2026-08-24',
        'nama' => 'Hari Libur Khusus',
        'aktif' => true,
    ]);

    $service = app(
        WorkScheduleService::class
    );

    expect(
        $service->isHariKerja(
            Carbon::create(
                2026,
                8,
                24
            )
        )
    )->toBeFalse();

    expect(
        $service->alasanLibur(
            Carbon::create(
                2026,
                8,
                24
            )
        )
    )->toBe('Hari Libur Khusus');
});

test('administrator can add special holiday', function () {
    $admin = createWorkingDayAdmin();

    $this
        ->actingAs($admin)
        ->post(
            route(
                'admin.settings.hari-libur.store'
            ),
            [
                'tanggal' =>
                '2026-12-25',

                'nama' =>
                'Hari Raya',
            ]
        )
        ->assertRedirect(
            route('admin.settings.index')
        )
        ->assertSessionHas(
            'success',
            'Hari libur berhasil ditambahkan.'
        );

    expect(
        HariLibur::query()
            ->whereDate(
                'tanggal',
                '2026-12-25'
            )
            ->exists()
    )->toBeTrue();
});

test('administrator can delete special holiday', function () {
    $admin = createWorkingDayAdmin();

    $hariLibur = HariLibur::create([
        'tanggal' =>
        '2026-12-25',

        'nama' =>
        'Hari Raya',

        'aktif' =>
        true,
    ]);

    $this
        ->actingAs($admin)
        ->delete(
            route(
                'admin.settings.hari-libur.destroy',
                $hariLibur
            )
        )
        ->assertRedirect(
            route('admin.settings.index')
        )
        ->assertSessionHas(
            'success',
            'Hari libur berhasil dihapus.'
        );

    expect(
        HariLibur::find($hariLibur->id)
    )->toBeNull();
});

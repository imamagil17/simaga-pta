<?php

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createAdminSettingUser(): array
{
    $admin = User::factory()->create([
        'role' => 'administrator',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mahasiswa = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    return compact(
        'admin',
        'mentor',
        'mahasiswa'
    );
}

test('administrator can access setting page', function () {
    $data = createAdminSettingUser();

    Setting::setValue(
        'nama_instansi',
        'Pengadilan Tinggi Agama Palu',
        'instansi'
    );

    Setting::setValue(
        'jam_masuk',
        '08:00',
        'jam_kerja'
    );

    $this
        ->actingAs($data['admin'])
        ->get(route('admin.settings.index'))
        ->assertOk()
        ->assertViewIs('admin.settings.index')
        ->assertViewHas('settings');
});

test('administrator can update system settings', function () {
    $data = createAdminSettingUser();

    $this
        ->actingAs($data['admin'])
        ->put(
            route('admin.settings.update'),
            [
                'nama_instansi' =>
                'Pengadilan Tinggi Agama Palu',

                'alamat_instansi' =>
                'Jl. Diponegoro, Palu',

                'jam_masuk' =>
                '08:00',

                'jam_pulang_senin_kamis' =>
                '16:30',

                'jam_pulang_jumat' =>
                '16:50',

                'bobot_kedisiplinan' =>
                20,

                'bobot_kehadiran' =>
                20,

                'bobot_kinerja' =>
                20,

                'bobot_kompetensi' =>
                20,

                'bobot_sikap' =>
                20,
            ]
        )
        ->assertRedirect(
            route('admin.settings.index')
        )
        ->assertSessionHas(
            'success',
            'Pengaturan berhasil diperbarui.'
        );

    expect(
        Setting::getValue('nama_instansi')
    )
        ->toBe('Pengadilan Tinggi Agama Palu');

    expect(
        Setting::getValue('alamat_instansi')
    )
        ->toBe('Jl. Diponegoro, Palu');

    expect(
        Setting::getValue('jam_masuk')
    )
        ->toBe('08:00');

    expect(
        Setting::getValue('bobot_sikap')
    )
        ->toBe('20');
});

test('administrator cannot save assessment weights that do not total 100', function () {
    $data = createAdminSettingUser();

    $this
        ->actingAs($data['admin'])
        ->put(
            route('admin.settings.update'),
            [
                'nama_instansi' =>
                'Pengadilan Tinggi Agama Palu',

                'alamat_instansi' =>
                'Palu',

                'jam_masuk' =>
                '08:00',

                'jam_pulang_senin_kamis' =>
                '16:30',

                'jam_pulang_jumat' =>
                '16:50',

                'bobot_kedisiplinan' =>
                10,

                'bobot_kehadiran' =>
                10,

                'bobot_kinerja' =>
                10,

                'bobot_kompetensi' =>
                10,

                'bobot_sikap' =>
                10,
            ]
        )
        ->assertSessionHasErrors(
            'bobot'
        );
});

test('mentor cannot access admin settings', function () {
    $data = createAdminSettingUser();

    $this
        ->actingAs($data['mentor'])
        ->get(route('admin.settings.index'))
        ->assertForbidden();
});

test('mahasiswa cannot access admin settings', function () {
    $data = createAdminSettingUser();

    $this
        ->actingAs($data['mahasiswa'])
        ->get(route('admin.settings.index'))
        ->assertForbidden();
});

test('setting helper can create and update values', function () {
    Setting::setValue(
        'test_setting',
        'nilai awal',
        'general'
    );

    expect(
        Setting::getValue('test_setting')
    )->toBe('nilai awal');

    Setting::setValue(
        'test_setting',
        'nilai baru',
        'general'
    );

    expect(
        Setting::getValue('test_setting')
    )->toBe('nilai baru');
});

<?php

use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createAdminDashboardData(): array
{
    $admin = User::factory()->create([
        'role' => 'administrator',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'DASH-' . fake()->unique()->numberBetween(100000, 999999),
        'jabatan' => 'Hakim Tinggi',
        'bagian' => 'Kepaniteraan',
        'status' => 'active',
    ]);

    $mahasiswaUser = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mahasiswa = Mahasiswa::create([
        'user_id' => $mahasiswaUser->id,
        'nim' => 'DASH-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Dashboard',
        'kode_periode' => 'DASH-' . fake()->unique()->numberBetween(1000, 9999),
        'tanggal_mulai' => '2026-08-01',
        'tanggal_selesai' => '2026-11-30',
        'status' => 'active',
    ]);

    $penempatan = Penempatan::create([
        'mahasiswa_id' => $mahasiswa->id,
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
        'status' => 'active',
    ]);

    return compact(
        'admin',
        'mentorUser',
        'mentor',
        'mahasiswaUser',
        'mahasiswa',
        'periode',
        'penempatan'
    );
}

test('administrator can access dashboard', function () {
    $data = createAdminDashboardData();

    $this
        ->actingAs($data['admin'])
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertViewIs('admin.dashboard')
        ->assertViewHas('mahasiswaAktif')
        ->assertViewHas('mentorAktif')
        ->assertViewHas('periodeAktif')
        ->assertViewHas('penempatanAktif')
        ->assertViewHas('absensiRekap')
        ->assertViewHas('absensiPending')
        ->assertViewHas('mahasiswaTerbaru');
});

test('administrator dashboard displays correct active data counts', function () {
    $data = createAdminDashboardData();

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.dashboard'));

    expect($response->viewData('mahasiswaAktif'))
        ->toBe(1);

    expect($response->viewData('mentorAktif'))
        ->toBe(1);

    expect($response->viewData('periodeAktif'))
        ->toBe(1);

    expect($response->viewData('penempatanAktif'))
        ->toBe(1);
});

test('administrator dashboard displays todays attendance recap', function () {
    $data = createAdminDashboardData();

    Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => today(),
        'jam_masuk' => '08:03',
        'jam_pulang' => '16:30',
        'status_kehadiran' => 'hadir',
        'menit_terlambat' => 3,
        'status_verifikasi' => 'pending',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.dashboard'));

    $rekap = $response->viewData('absensiRekap');

    expect($rekap['total'])->toBe(1);
    expect($rekap['hadir'])->toBe(1);
    expect($rekap['terlambat'])->toBe(1);
    expect($rekap['pending'])->toBe(1);
    expect($rekap['approved'])->toBe(0);
    expect($rekap['total_menit_terlambat'])->toBe(3);
});

test('administrator dashboard shows pending attendance', function () {
    $data = createAdminDashboardData();

    $absensi = Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => today(),
        'jam_masuk' => '08:03',
        'jam_pulang' => '16:30',
        'status_kehadiran' => 'hadir',
        'menit_terlambat' => 3,
        'status_verifikasi' => 'pending',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.dashboard'));

    $pending = $response->viewData('absensiPending');

    expect($pending)->toHaveCount(1);
    expect($pending->first()->id)->toBe($absensi->id);
});

test('mentor cannot access administrator dashboard', function () {
    $data = createAdminDashboardData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test('mahasiswa cannot access administrator dashboard', function () {
    $data = createAdminDashboardData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

<?php

use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createAdminAbsensiData(): array
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
        'nip' => 'ADM-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'ADM-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Admin Monitoring',
        'kode_periode' => 'ADM-' . fake()->unique()->numberBetween(1000, 9999),
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

test('administrator can access attendance monitoring', function () {
    $data = createAdminAbsensiData();

    $this
        ->actingAs($data['admin'])
        ->get(route('admin.absensi.index'))
        ->assertOk()
        ->assertViewIs('admin.absensi.index')
        ->assertViewHas('absensis')
        ->assertViewHas('rekap')
        ->assertViewHas('filters');
});

test('administrator can filter attendance by verification status', function () {
    $data = createAdminAbsensiData();

    Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'jam_masuk' => '08:00',
        'jam_pulang' => '16:30',
        'status_kehadiran' => 'hadir',
        'status_verifikasi' => 'approved',
    ]);

    Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-25',
        'jam_masuk' => '08:03',
        'status_kehadiran' => 'hadir',
        'menit_terlambat' => 3,
        'status_verifikasi' => 'pending',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.absensi.index', [
            'status_verifikasi' => 'pending',
        ]));

    $response->assertOk();

    $absensis = $response->viewData('absensis');

    expect($absensis)->toHaveCount(1);
    expect($absensis->first()->status_verifikasi)->toBe('pending');
});

test('administrator can filter attendance by mentor', function () {
    $data = createAdminAbsensiData();

    Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'jam_masuk' => '08:00',
        'jam_pulang' => '16:30',
        'status_kehadiran' => 'hadir',
        'status_verifikasi' => 'approved',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.absensi.index', [
            'mentor_id' => $data['mentor']->id,
        ]));

    $response->assertOk();

    $absensis = $response->viewData('absensis');

    expect($absensis)->toHaveCount(1);
    expect(
        $absensis->first()->penempatan->mentor_id
    )->toBe($data['mentor']->id);
});

test('administrator can view attendance detail', function () {
    $data = createAdminAbsensiData();

    $absensi = Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'jam_masuk' => '08:03',
        'jam_pulang' => '16:30',
        'status_kehadiran' => 'hadir',
        'menit_terlambat' => 3,
        'status_verifikasi' => 'approved',
    ]);

    $this
        ->actingAs($data['admin'])
        ->get(route('admin.absensi.show', $absensi))
        ->assertOk()
        ->assertViewIs('admin.absensi.show')
        ->assertSee($data['mahasiswaUser']->name)
        ->assertSee('08:03')
        ->assertSee('16:30');
});

test('mentor cannot access administrator attendance monitoring', function () {
    $data = createAdminAbsensiData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('admin.absensi.index'))
        ->assertForbidden();
});

test('mahasiswa cannot access administrator attendance monitoring', function () {
    $data = createAdminAbsensiData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('admin.absensi.index'))
        ->assertForbidden();
});

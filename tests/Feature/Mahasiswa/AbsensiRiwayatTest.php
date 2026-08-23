<?php

use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/*
|--------------------------------------------------------------------------
| Helper
|--------------------------------------------------------------------------
*/

function createRiwayatAbsensiData(): array
{
    $user = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mahasiswa = Mahasiswa::create([
        'user_id' => $user->id,
        'nim' => 'RIA-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'RIA-' . fake()->unique()->numberBetween(100000, 999999),
        'jabatan' => 'Hakim Tinggi',
        'bagian' => 'Kepaniteraan',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Riwayat Absensi',
        'kode_periode' => 'RIA-' . fake()->unique()->numberBetween(1000, 9999),
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

    return [
        'user' => $user,
        'mahasiswa' => $mahasiswa,
        'mentorUser' => $mentorUser,
        'mentor' => $mentor,
        'periode' => $periode,
        'penempatan' => $penempatan,
    ];
}

/*
|--------------------------------------------------------------------------
| Access
|--------------------------------------------------------------------------
*/

test('mahasiswa can access attendance history', function () {
    $data = createRiwayatAbsensiData();

    $this
        ->actingAs($data['user'])
        ->get(route('mahasiswa.absensi.riwayat'))
        ->assertOk()
        ->assertViewIs('mahasiswa.absensi.riwayat')
        ->assertViewHas('penempatan')
        ->assertViewHas('absensis');
});

/*
|--------------------------------------------------------------------------
| Own Attendance History
|--------------------------------------------------------------------------
*/

test('mahasiswa sees only own attendance history', function () {
    $data = createRiwayatAbsensiData();

    $absensi = Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'jam_masuk' => '08:03',
        'jam_pulang' => '16:30',
        'status_kehadiran' => 'hadir',
        'menit_terlambat' => 3,
        'status_verifikasi' => 'approved',
    ]);

    $response = $this
        ->actingAs($data['user'])
        ->get(route('mahasiswa.absensi.riwayat'));

    $response
        ->assertOk()
        ->assertViewIs('mahasiswa.absensi.riwayat')
        ->assertSee('24 Agustus 2026')
        ->assertSee('08:03')
        ->assertSee('16:30')
        ->assertSee('3 menit')
        ->assertSee('Disetujui');

    $absensis = $response->viewData('absensis');

    expect($absensis)->toHaveCount(1);
    expect($absensis->first()->id)->toBe($absensi->id);
});

/*
|--------------------------------------------------------------------------
| Isolation
|--------------------------------------------------------------------------
*/

test('mahasiswa does not see another students attendance history', function () {
    $data = createRiwayatAbsensiData();

    /*
    |--------------------------------------------------------------------------
    | Mahasiswa lain
    |--------------------------------------------------------------------------
    */
    $otherUser = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $otherMahasiswa = Mahasiswa::create([
        'user_id' => $otherUser->id,
        'nim' => 'OTHER-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $otherPenempatan = Penempatan::create([
        'mahasiswa_id' => $otherMahasiswa->id,
        'periode_magang_id' => $data['periode']->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'active',
    ]);

    $otherAbsensi = Absensi::create([
        'penempatan_id' => $otherPenempatan->id,
        'tanggal' => '2026-08-25',
        'jam_masuk' => '08:00',
        'jam_pulang' => '16:30',
        'status_kehadiran' => 'hadir',
        'status_verifikasi' => 'approved',
    ]);

    $response = $this
        ->actingAs($data['user'])
        ->get(route('mahasiswa.absensi.riwayat'));

    $response->assertOk();

    $absensis = $response->viewData('absensis');

    expect($absensis)->toHaveCount(0);
    expect(
        $absensis->pluck('id')->contains($otherAbsensi->id)
    )->toBeFalse();
});

/*
|--------------------------------------------------------------------------
| Authorization
|--------------------------------------------------------------------------
*/

test('mentor cannot access mahasiswa attendance history', function () {
    $data = createRiwayatAbsensiData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mahasiswa.absensi.riwayat'))
        ->assertForbidden();
});

test('administrator cannot access mahasiswa attendance history', function () {
    $data = createRiwayatAbsensiData();

    $administrator = User::factory()->create([
        'role' => 'administrator',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $this
        ->actingAs($administrator)
        ->get(route('mahasiswa.absensi.riwayat'))
        ->assertForbidden();
});

test('mahasiswa attendance history displays correct recap', function () {
    $data = createRiwayatAbsensiData();

    Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'jam_masuk' => '08:03',
        'jam_pulang' => '16:30',
        'status_kehadiran' => 'hadir',
        'menit_terlambat' => 3,
        'status_verifikasi' => 'approved',
    ]);

    Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-25',
        'jam_masuk' => '08:00',
        'jam_pulang' => '16:30',
        'status_kehadiran' => 'hadir',
        'menit_terlambat' => null,
        'status_verifikasi' => 'pending',
    ]);

    Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-26',
        'status_kehadiran' => 'izin',
        'status_verifikasi' => 'approved',
    ]);

    $response = $this
        ->actingAs($data['user'])
        ->get(route('mahasiswa.absensi.riwayat'));

    $response->assertOk();

    $rekap = $response->viewData('rekap');

    expect($rekap['total'])->toBe(3);
    expect($rekap['hadir'])->toBe(2);
    expect($rekap['izin'])->toBe(1);
    expect($rekap['sakit'])->toBe(0);
    expect($rekap['alpa'])->toBe(0);

    expect($rekap['terlambat'])->toBe(1);
    expect($rekap['total_menit_terlambat'])->toBe(3);

    expect($rekap['approved'])->toBe(2);
    expect($rekap['pending'])->toBe(1);
    expect($rekap['rejected'])->toBe(0);
});
<?php

use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/*
|--------------------------------------------------------------------------
| Helper
|--------------------------------------------------------------------------
*/

function createAbsensiMahasiswa(): Mahasiswa
{
    $user = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    return Mahasiswa::create([
        'user_id' => $user->id,
        'nim' => 'MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'jenis_kelamin' => 'Laki-laki',
        'agama' => 'Islam',
        'no_hp' => '081234567890',
        'alamat' => 'Palu',
        'status' => 'active',
    ]);
}

function createAbsensiMentor(): Mentor
{
    $user = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    return Mentor::create([
        'user_id' => $user->id,
        'nip' => 'NIP-' . fake()->unique()->numberBetween(100000, 999999),
        'jabatan' => 'Hakim Tinggi',
        'bagian' => 'Kepaniteraan',
        'jenis_kelamin' => 'Laki-laki',
        'agama' => 'Islam',
        'no_hp' => '081234567890',
        'status' => 'active',
    ]);
}

function createAbsensiPeriode(): PeriodeMagang
{
    return PeriodeMagang::create([
        'nama_periode' => 'Periode Absensi Testing',
        'kode_periode' => 'ABS-' . fake()->unique()->numberBetween(1000, 9999),
        'tanggal_mulai' => '2026-08-01',
        'tanggal_selesai' => '2026-11-30',
        'status' => 'active',
        'keterangan' => 'Data testing.',
    ]);
}

function createAbsensiPenempatan(): Penempatan
{
    $mahasiswa = createAbsensiMahasiswa();
    $mentor = createAbsensiMentor();
    $periode = createAbsensiPeriode();

    return Penempatan::create([
        'mahasiswa_id' => $mahasiswa->id,
        'periode_magang_id' => $periode->id,
        'mentor_id' => $mentor->id,
        'status' => 'active',
    ]);
}

/*
|--------------------------------------------------------------------------
| Relationship
|--------------------------------------------------------------------------
*/

test('absensi belongsTo penempatan relationship works', function () {
    $penempatan = createAbsensiPenempatan();

    $absensi = Absensi::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-24',
        'jam_masuk' => '08:00',
    ]);

    expect($absensi->penempatan)->not->toBeNull();
    expect($absensi->penempatan->id)->toBe($penempatan->id);
});

test('penempatan hasMany absensis relationship works', function () {
    $penempatan = createAbsensiPenempatan();

    $absensi = Absensi::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-24',
        'jam_masuk' => '08:00',
    ]);

    expect($penempatan->absensis)->toHaveCount(1);
    expect($penempatan->absensis->first()->id)->toBe($absensi->id);
});

/*
|--------------------------------------------------------------------------
| Default Status
|--------------------------------------------------------------------------
*/

test('absensi default attendance status is hadir', function () {
    $penempatan = createAbsensiPenempatan();

    $absensi = Absensi::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-24',
    ]);

    expect($absensi->status_kehadiran)->toBe('hadir');
});

test('absensi default verification status is pending', function () {
    $penempatan = createAbsensiPenempatan();

    $absensi = Absensi::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-24',
    ]);

    expect($absensi->status_verifikasi)->toBe('pending');
});

/*
|--------------------------------------------------------------------------
| Attendance Status
|--------------------------------------------------------------------------
*/

test('absensi can store terlambat attendance status', function () {
    $penempatan = createAbsensiPenempatan();

    $absensi = Absensi::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-24',
        'jam_masuk' => '08:17',
        'status_kehadiran' => 'terlambat',
    ]);

    expect($absensi->status_kehadiran)->toBe('terlambat');
});

/*
|--------------------------------------------------------------------------
| Unique Date
|--------------------------------------------------------------------------
*/

test('same penempatan cannot have two absensi records on same date', function () {
    $penempatan = createAbsensiPenempatan();

    Absensi::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-24',
    ]);

    expect(function () use ($penempatan) {
        Absensi::create([
            'penempatan_id' => $penempatan->id,
            'tanggal' => '2026-08-24',
        ]);
    })->toThrow(QueryException::class);
});

test('same penempatan can have absensi on different dates', function () {
    $penempatan = createAbsensiPenempatan();

    Absensi::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-24',
    ]);

    Absensi::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-25',
    ]);

    expect(
        Absensi::where('penempatan_id', $penempatan->id)->count()
    )->toBe(2);
});

/*
|--------------------------------------------------------------------------
| Jam Masuk & Jam Pulang
|--------------------------------------------------------------------------
*/

test('absensi can store jam masuk and jam pulang', function () {
    $penempatan = createAbsensiPenempatan();

    $absensi = Absensi::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-24',
        'jam_masuk' => '08:00',
        'jam_pulang' => '16:00',
        'status_kehadiran' => 'hadir',
    ]);

    expect($absensi->jam_masuk)->toBe('08:00');
    expect($absensi->jam_pulang)->toBe('16:00');
});

/*
|--------------------------------------------------------------------------
| Paraf Snapshot
|--------------------------------------------------------------------------
*/

test('absensi can store mahasiswa and mentor signature snapshots', function () {
    $penempatan = createAbsensiPenempatan();

    $absensi = Absensi::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-24',
        'jam_masuk' => '08:00',
        'jam_pulang' => '16:00',
        'status_kehadiran' => 'hadir',
        'status_verifikasi' => 'approved',
        'paraf_mahasiswa' => 'absensi/paraf/mahasiswa-test.png',
        'paraf_mahasiswa_at' => now(),
        'paraf_mentor' => 'absensi/paraf/mentor-test.png',
        'paraf_mentor_at' => now(),
    ]);

    expect($absensi->paraf_mahasiswa)
        ->toBe('absensi/paraf/mahasiswa-test.png');

    expect($absensi->paraf_mentor)
        ->toBe('absensi/paraf/mentor-test.png');

    expect($absensi->paraf_mahasiswa_at)->not->toBeNull();
    expect($absensi->paraf_mentor_at)->not->toBeNull();
});
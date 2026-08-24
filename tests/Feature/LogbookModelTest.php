<?php

use App\Models\Logbook;
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

function createLogbookPenempatan(): Penempatan
{
    $mahasiswaUser = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mahasiswa = Mahasiswa::create([
        'user_id' => $mahasiswaUser->id,
        'nim' => 'LOG-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nip' => 'LOG-' . fake()->unique()->numberBetween(100000, 999999),
        'jabatan' => 'Hakim Tinggi',
        'bagian' => 'Kepaniteraan',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Logbook Testing',
        'kode_periode' => 'LOG-' . fake()->unique()->numberBetween(1000, 9999),
        'tanggal_mulai' => '2026-08-01',
        'tanggal_selesai' => '2026-11-30',
        'status' => 'active',
    ]);

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

test('logbook belongsTo penempatan', function () {
    $penempatan = createLogbookPenempatan();

    $logbook = Logbook::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Koordinasi Awal',
        'uraian_kegiatan' => 'Melakukan koordinasi kegiatan magang dengan mentor.',
    ]);

    expect($logbook->penempatan)->not->toBeNull();
    expect($logbook->penempatan->id)->toBe($penempatan->id);
});

test('penempatan hasMany logbooks', function () {
    $penempatan = createLogbookPenempatan();

    $logbook = Logbook::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Koordinasi Awal',
        'uraian_kegiatan' => 'Melakukan koordinasi kegiatan magang dengan mentor.',
    ]);

    expect($penempatan->logbooks)->toHaveCount(1);
    expect($penempatan->logbooks->first()->id)->toBe($logbook->id);
});

/*
|--------------------------------------------------------------------------
| Default
|--------------------------------------------------------------------------
*/

test('logbook default status is draft', function () {
    $penempatan = createLogbookPenempatan();

    $logbook = Logbook::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Koordinasi Awal',
        'uraian_kegiatan' => 'Melakukan koordinasi kegiatan magang dengan mentor.',
    ]);

    expect($logbook->status)->toBe('draft');
});

/*
|--------------------------------------------------------------------------
| Status
|--------------------------------------------------------------------------
*/

test('logbook can store submitted status', function () {
    $penempatan = createLogbookPenempatan();

    $logbook = Logbook::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Pengembangan Sistem',
        'uraian_kegiatan' => 'Mengembangkan modul sistem informasi magang.',
        'status' => 'submitted',
        'submitted_at' => now(),
    ]);

    expect($logbook->status)->toBe('submitted');
    expect($logbook->submitted_at)->not->toBeNull();
});

test('logbook can store approved status', function () {
    $penempatan = createLogbookPenempatan();

    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $logbook = Logbook::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Pengembangan Sistem',
        'uraian_kegiatan' => 'Mengembangkan modul sistem informasi magang.',
        'status' => 'approved',
        'reviewed_by' => $mentorUser->id,
        'reviewed_at' => now(),
    ]);

    expect($logbook->status)->toBe('approved');
    expect($logbook->reviewer->id)->toBe($mentorUser->id);
});

test('logbook can store revision status', function () {
    $penempatan = createLogbookPenempatan();

    $logbook = Logbook::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Pengembangan Sistem',
        'uraian_kegiatan' => 'Mengembangkan modul sistem informasi magang.',
        'status' => 'revision',
        'catatan_mentor' => 'Mohon uraian kegiatan dibuat lebih detail.',
    ]);

    expect($logbook->status)->toBe('revision');

    expect($logbook->catatan_mentor)
        ->toBe('Mohon uraian kegiatan dibuat lebih detail.');
});

/*
|--------------------------------------------------------------------------
| Unique Date
|--------------------------------------------------------------------------
*/

test('same penempatan cannot have two logbooks on same date', function () {
    $penempatan = createLogbookPenempatan();

    Logbook::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Kegiatan Pertama',
        'uraian_kegiatan' => 'Kegiatan pertama.',
    ]);

    expect(function () use ($penempatan) {
        Logbook::create([
            'penempatan_id' => $penempatan->id,
            'tanggal' => '2026-08-24',
            'judul_kegiatan' => 'Kegiatan Kedua',
            'uraian_kegiatan' => 'Kegiatan kedua.',
        ]);
    })->toThrow(QueryException::class);
});

test('same penempatan can have logbooks on different dates', function () {
    $penempatan = createLogbookPenempatan();

    Logbook::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-24',
        'judul_kegiatan' => 'Kegiatan Pertama',
        'uraian_kegiatan' => 'Kegiatan pertama.',
    ]);

    Logbook::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-25',
        'judul_kegiatan' => 'Kegiatan Kedua',
        'uraian_kegiatan' => 'Kegiatan kedua.',
    ]);

    expect(
        Logbook::where('penempatan_id', $penempatan->id)->count()
    )->toBe(2);
});

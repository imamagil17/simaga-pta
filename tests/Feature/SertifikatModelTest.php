<?php

use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\Sertifikat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createSertifikatData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'SRT-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'SRT-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Sertifikat',
        'kode_periode' => 'SRT-' . fake()->unique()->numberBetween(1000, 9999),
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
        'mentorUser',
        'mentor',
        'mahasiswaUser',
        'mahasiswa',
        'periode',
        'penempatan'
    );
}

test('sertifikat belongsTo mahasiswa', function () {
    $data = createSertifikatData();

    $sertifikat = Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
    ]);

    expect($sertifikat->mahasiswa)
        ->not->toBeNull();

    expect($sertifikat->mahasiswa->id)
        ->toBe($data['mahasiswa']->id);
});

test('sertifikat belongsTo mentor', function () {
    $data = createSertifikatData();

    $sertifikat = Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
    ]);

    expect($sertifikat->mentor)
        ->not->toBeNull();

    expect($sertifikat->mentor->id)
        ->toBe($data['mentor']->id);
});

test('sertifikat belongsTo penempatan', function () {
    $data = createSertifikatData();

    $sertifikat = Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
    ]);

    expect($sertifikat->penempatan)
        ->not->toBeNull();

    expect($sertifikat->penempatan->id)
        ->toBe($data['penempatan']->id);
});

test('sertifikat default status is pending', function () {
    $data = createSertifikatData();

    $sertifikat = Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
    ]);

    expect($sertifikat->status)
        ->toBe('pending');
});

test('mahasiswa hasMany sertifikat', function () {
    $data = createSertifikatData();

    Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
    ]);

    expect($data['mahasiswa']->sertifikat)
        ->toHaveCount(1);
});

test('mentor hasMany sertifikat', function () {
    $data = createSertifikatData();

    Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
    ]);

    expect($data['mentor']->sertifikat)
        ->toHaveCount(1);
});

test('penempatan hasOne sertifikat', function () {
    $data = createSertifikatData();

    Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
    ]);

    expect($data['penempatan']->sertifikat)
        ->not->toBeNull();
});

test('sertifikat can store approved status', function () {
    $data = createSertifikatData();

    $sertifikat = Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'approved',
        'nomor_sertifikat' => '001/SIMAGA/PTA-PALU/VIII/2026',
        'approved_by' => $data['mentorUser']->id,
        'approved_at' => now(),
    ]);

    expect($sertifikat->status)
        ->toBe('approved');

    expect($sertifikat->nomor_sertifikat)
        ->toBe('001/SIMAGA/PTA-PALU/VIII/2026');

    expect($sertifikat->approved_at)
        ->not->toBeNull();
});

test('sertifikat can store rejected status with note', function () {
    $data = createSertifikatData();

    $sertifikat = Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'rejected',
        'catatan' => 'Dokumen mahasiswa belum lengkap.',
    ]);

    expect($sertifikat->status)
        ->toBe('rejected');

    expect($sertifikat->catatan)
        ->toBe('Dokumen mahasiswa belum lengkap.');
});

test('one placement cannot have duplicate sertifikat', function () {
    $data = createSertifikatData();

    Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
    ]);

    expect(
        fn() => Sertifikat::create([
            'mahasiswa_id' => $data['mahasiswa']->id,
            'penempatan_id' => $data['penempatan']->id,
            'mentor_id' => $data['mentor']->id,
        ])
    )->toThrow(
        \Illuminate\Database\QueryException::class
    );
});

<?php

use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\Penilaian;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createPenilaianData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'PNL-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'PNL-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Penilaian',
        'kode_periode' => 'PNL-' . fake()->unique()->numberBetween(1000, 9999),
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

test('penilaian belongsTo mahasiswa', function () {
    $data = createPenilaianData();

    $penilaian = Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
    ]);

    expect($penilaian->mahasiswa)
        ->not->toBeNull();

    expect($penilaian->mahasiswa->id)
        ->toBe($data['mahasiswa']->id);
});

test('penilaian belongsTo mentor', function () {
    $data = createPenilaianData();

    $penilaian = Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
    ]);

    expect($penilaian->mentor)
        ->not->toBeNull();

    expect($penilaian->mentor->id)
        ->toBe($data['mentor']->id);
});

test('penilaian belongsTo penempatan', function () {
    $data = createPenilaianData();

    $penilaian = Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
    ]);

    expect($penilaian->penempatan)
        ->not->toBeNull();

    expect($penilaian->penempatan->id)
        ->toBe($data['penempatan']->id);
});

test('penilaian default status is draft', function () {
    $data = createPenilaianData();

    $penilaian = Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
    ]);

    expect($penilaian->status)
        ->toBe('draft');
});

test('penempatan hasOne penilaian', function () {
    $data = createPenilaianData();

    Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
    ]);

    expect($data['penempatan']->penilaian)
        ->not->toBeNull();

    expect($data['penempatan']->penilaian->mahasiswa_id)
        ->toBe($data['mahasiswa']->id);
});

test('mahasiswa hasMany penilaian', function () {
    $data = createPenilaianData();

    Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
    ]);

    expect($data['mahasiswa']->penilaian)
        ->toHaveCount(1);
});

test('penilaian can store all scores', function () {
    $data = createPenilaianData();

    $penilaian = Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'nilai_kedisiplinan' => 90,
        'nilai_kehadiran' => 95,
        'nilai_kinerja' => 85,
        'nilai_kompetensi' => 88,
        'nilai_sikap' => 92,
    ]);

    expect((float) $penilaian->nilai_kedisiplinan)
        ->toBe(90.0);

    expect((float) $penilaian->nilai_kehadiran)
        ->toBe(95.0);

    expect((float) $penilaian->nilai_kinerja)
        ->toBe(85.0);

    expect((float) $penilaian->nilai_kompetensi)
        ->toBe(88.0);

    expect((float) $penilaian->nilai_sikap)
        ->toBe(92.0);
});

test('penilaian calculates final score correctly', function () {
    $data = createPenilaianData();

    $penilaian = Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'nilai_kedisiplinan' => 90,
        'nilai_kehadiran' => 95,
        'nilai_kinerja' => 85,
        'nilai_kompetensi' => 88,
        'nilai_sikap' => 92,
    ]);

    expect($penilaian->hitungNilaiAkhir())
        ->toBe(90.0);
});

test('penilaian can be finalized', function () {
    $data = createPenilaianData();

    $penilaian = Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'nilai_kedisiplinan' => 90,
        'nilai_kehadiran' => 95,
        'nilai_kinerja' => 85,
        'nilai_kompetensi' => 88,
        'nilai_sikap' => 92,
        'nilai_akhir' => 90,
        'status' => 'final',
        'finalized_by' => $data['mentorUser']->id,
        'finalized_at' => now(),
    ]);

    expect($penilaian->status)
        ->toBe('final');

    expect($penilaian->finalized_by)
        ->toBe($data['mentorUser']->id);

    expect($penilaian->finalized_at)
        ->not->toBeNull();
});

test('one placement cannot have duplicate assessment', function () {
    $data = createPenilaianData();

    Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
    ]);

    expect(fn() => Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
    ]))->toThrow(\Illuminate\Database\QueryException::class);
});

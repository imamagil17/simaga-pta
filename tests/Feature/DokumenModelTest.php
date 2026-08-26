<?php

use App\Models\Dokumen;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createDokumenData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'DKM-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'DKM-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Dokumen',
        'kode_periode' => 'DKM-' . fake()->unique()->numberBetween(1000, 9999),
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

test('dokumen belongsTo mahasiswa', function () {
    $data = createDokumenData();

    $dokumen = Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'ktm',
        'nama_dokumen' => 'Kartu Tanda Mahasiswa',
        'nama_file' => 'ktm.pdf',
        'path_file' => 'dokumen/mahasiswa/ktm.pdf',
        'mime_type' => 'application/pdf',
        'ukuran_file' => 120000,
    ]);

    expect($dokumen->mahasiswa)->not->toBeNull();

    expect($dokumen->mahasiswa->id)
        ->toBe($data['mahasiswa']->id);
});

test('dokumen belongsTo penempatan', function () {
    $data = createDokumenData();

    $dokumen = Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'surat_penempatan',
        'nama_dokumen' => 'Surat Penempatan Magang',
        'nama_file' => 'surat-penempatan.pdf',
        'path_file' => 'dokumen/mahasiswa/surat-penempatan.pdf',
        'mime_type' => 'application/pdf',
        'ukuran_file' => 200000,
    ]);

    expect($dokumen->penempatan)->not->toBeNull();

    expect($dokumen->penempatan->id)
        ->toBe($data['penempatan']->id);
});

test('mahasiswa hasMany dokumen', function () {
    $data = createDokumenData();

    Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'ktm',
        'nama_dokumen' => 'KTM',
        'nama_file' => 'ktm.pdf',
        'path_file' => 'dokumen/ktm.pdf',
    ]);

    Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'cv',
        'nama_dokumen' => 'CV',
        'nama_file' => 'cv.pdf',
        'path_file' => 'dokumen/cv.pdf',
    ]);

    expect($data['mahasiswa']->dokumen)
        ->toHaveCount(2);
});

test('penempatan hasMany dokumen', function () {
    $data = createDokumenData();

    Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'surat_pengantar',
        'nama_dokumen' => 'Surat Pengantar',
        'nama_file' => 'surat.pdf',
        'path_file' => 'dokumen/surat.pdf',
    ]);

    expect($data['penempatan']->dokumen)
        ->toHaveCount(1);
});

test('dokumen default status is uploaded', function () {
    $data = createDokumenData();

    $dokumen = Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'cv',
        'nama_dokumen' => 'Curriculum Vitae',
        'nama_file' => 'cv.pdf',
        'path_file' => 'dokumen/cv.pdf',
    ]);

    expect($dokumen->status)
        ->toBe('uploaded');
});

test('dokumen can store verified status', function () {
    $data = createDokumenData();

    $dokumen = Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'ktm',
        'nama_dokumen' => 'KTM',
        'nama_file' => 'ktm.pdf',
        'path_file' => 'dokumen/ktm.pdf',
        'status' => 'verified',
        'verified_by' => $data['mentorUser']->id,
        'verified_at' => now(),
    ]);

    expect($dokumen->status)
        ->toBe('verified');

    expect($dokumen->verifier->id)
        ->toBe($data['mentorUser']->id);
});

test('dokumen can store revision status', function () {
    $data = createDokumenData();

    $dokumen = Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'laporan_magang',
        'nama_dokumen' => 'Laporan Magang',
        'nama_file' => 'laporan.pdf',
        'path_file' => 'dokumen/laporan.pdf',
        'status' => 'revision',
        'catatan' => 'Mohon perbaiki halaman pengesahan.',
        'verified_by' => $data['mentorUser']->id,
        'verified_at' => now(),
    ]);

    expect($dokumen->status)
        ->toBe('revision');

    expect($dokumen->catatan)
        ->toBe('Mohon perbaiki halaman pengesahan.');
});

test('dokumen can exist without penempatan', function () {
    $data = createDokumenData();

    $dokumen = Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => null,
        'jenis_dokumen' => 'ktp',
        'nama_dokumen' => 'Kartu Tanda Penduduk',
        'nama_file' => 'ktp.pdf',
        'path_file' => 'dokumen/ktp.pdf',
    ]);

    expect($dokumen->penempatan)
        ->toBeNull();
});

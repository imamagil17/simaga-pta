<?php

use App\Models\Dokumen;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function createAdminDokumenData(): array
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
        'nip' => 'ADM-DOC-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'ADM-DOC-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Admin Dokumen',
        'kode_periode' => 'ADM-DOC-' . fake()->unique()->numberBetween(1000, 9999),
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

test('administrator can access document monitoring', function () {
    $data = createAdminDokumenData();

    $this
        ->actingAs($data['admin'])
        ->get(route('admin.dokumen.index'))
        ->assertOk()
        ->assertViewIs('admin.dokumen.index')
        ->assertViewHas('dokumen')
        ->assertViewHas('rekap')
        ->assertViewHas('mahasiswas')
        ->assertViewHas('periodeMagangs')
        ->assertViewHas('filters');
});

test('administrator sees all documents', function () {
    $data = createAdminDokumenData();

    Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'ktm',
        'nama_dokumen' => 'KTM',
        'nama_file' => 'ktm.pdf',
        'path_file' => 'dokumen/ktm.pdf',
        'status' => 'uploaded',
    ]);

    Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'cv',
        'nama_dokumen' => 'CV',
        'nama_file' => 'cv.pdf',
        'path_file' => 'dokumen/cv.pdf',
        'status' => 'verified',
    ]);

    Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'laporan_magang',
        'nama_dokumen' => 'Laporan Magang',
        'nama_file' => 'laporan.pdf',
        'path_file' => 'dokumen/laporan.pdf',
        'status' => 'revision',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.dokumen.index'));

    $dokumen = $response->viewData('dokumen');

    expect($dokumen)->toHaveCount(3);
});

test('administrator can filter document by status', function () {
    $data = createAdminDokumenData();

    $verified = Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'ktm',
        'nama_dokumen' => 'KTM',
        'nama_file' => 'ktm.pdf',
        'path_file' => 'dokumen/ktm.pdf',
        'status' => 'verified',
    ]);

    Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'cv',
        'nama_dokumen' => 'CV',
        'nama_file' => 'cv.pdf',
        'path_file' => 'dokumen/cv.pdf',
        'status' => 'revision',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.dokumen.index', [
            'status' => 'verified',
        ]));

    $dokumen = $response->viewData('dokumen');

    expect($dokumen)->toHaveCount(1);

    expect($dokumen->first()->id)
        ->toBe($verified->id);
});

test('administrator can filter document by type', function () {
    $data = createAdminDokumenData();

    $ktm = Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'ktm',
        'nama_dokumen' => 'KTM',
        'nama_file' => 'ktm.pdf',
        'path_file' => 'dokumen/ktm.pdf',
        'status' => 'uploaded',
    ]);

    Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'cv',
        'nama_dokumen' => 'CV',
        'nama_file' => 'cv.pdf',
        'path_file' => 'dokumen/cv.pdf',
        'status' => 'uploaded',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.dokumen.index', [
            'jenis_dokumen' => 'ktm',
        ]));

    $dokumen = $response->viewData('dokumen');

    expect($dokumen)->toHaveCount(1);

    expect($dokumen->first()->id)
        ->toBe($ktm->id);
});

test('administrator can filter document by mahasiswa', function () {
    $data = createAdminDokumenData();

    $otherUser = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $otherMahasiswa = Mahasiswa::create([
        'user_id' => $otherUser->id,
        'nim' => 'ADM-DOC-OTHER-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $ownDocument = Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'ktm',
        'nama_dokumen' => 'KTM Saya',
        'nama_file' => 'ktm.pdf',
        'path_file' => 'dokumen/ktm.pdf',
        'status' => 'uploaded',
    ]);

    Dokumen::create([
        'mahasiswa_id' => $otherMahasiswa->id,
        'penempatan_id' => null,
        'jenis_dokumen' => 'ktm',
        'nama_dokumen' => 'KTM Lain',
        'nama_file' => 'ktm.pdf',
        'path_file' => 'dokumen/other.pdf',
        'status' => 'uploaded',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.dokumen.index', [
            'mahasiswa_id' => $data['mahasiswa']->id,
        ]));

    $dokumen = $response->viewData('dokumen');

    expect($dokumen)->toHaveCount(1);

    expect($dokumen->first()->id)
        ->toBe($ownDocument->id);
});

test('administrator can filter document by period', function () {
    $data = createAdminDokumenData();

    $otherPeriode = PeriodeMagang::create([
        'nama_periode' => 'Periode Kedua Dokumen',
        'kode_periode' => 'ADM-DOC-P2-' . fake()->unique()->numberBetween(1000, 9999),
        'tanggal_mulai' => '2027-01-01',
        'tanggal_selesai' => '2027-03-31',
        'status' => 'active',
    ]);

    $otherPlacement = Penempatan::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'periode_magang_id' => $otherPeriode->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'active',
    ]);

    $ownDocument = Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'ktm',
        'nama_dokumen' => 'Dokumen Periode Pertama',
        'nama_file' => 'ktm.pdf',
        'path_file' => 'dokumen/p1.pdf',
        'status' => 'uploaded',
    ]);

    Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $otherPlacement->id,
        'jenis_dokumen' => 'cv',
        'nama_dokumen' => 'Dokumen Periode Kedua',
        'nama_file' => 'cv.pdf',
        'path_file' => 'dokumen/p2.pdf',
        'status' => 'uploaded',
    ]);

    $response = $this
        ->actingAs($data['admin'])
        ->get(route('admin.dokumen.index', [
            'periode_id' => $data['periode']->id,
        ]));

    $dokumen = $response->viewData('dokumen');

    expect($dokumen)->toHaveCount(1);

    expect($dokumen->first()->id)
        ->toBe($ownDocument->id);
});

test('administrator can view document detail', function () {
    $data = createAdminDokumenData();

    $dokumen = Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'ktm',
        'nama_dokumen' => 'KTM',
        'nama_file' => 'ktm.pdf',
        'path_file' => 'dokumen/ktm.pdf',
        'status' => 'uploaded',
    ]);

    $this
        ->actingAs($data['admin'])
        ->get(route('admin.dokumen.show', $dokumen))
        ->assertOk()
        ->assertViewIs('admin.dokumen.show')
        ->assertViewHas('dokumen');
});

test('administrator can download private document', function () {
    Storage::fake('local');

    $data = createAdminDokumenData();

    $path = 'dokumen/mahasiswa/1/ktm.pdf';

    Storage::disk('local')->put(
        $path,
        'private document'
    );

    $dokumen = Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'ktm',
        'nama_dokumen' => 'KTM',
        'nama_file' => 'ktm.pdf',
        'path_file' => $path,
        'mime_type' => 'application/pdf',
        'ukuran_file' => 100,
        'status' => 'uploaded',
    ]);

    $this
        ->actingAs($data['admin'])
        ->get(route('admin.dokumen.download', $dokumen))
        ->assertOk();
});

test('mentor cannot access admin document monitoring', function () {
    $data = createAdminDokumenData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('admin.dokumen.index'))
        ->assertForbidden();
});

test('mahasiswa cannot access admin document monitoring', function () {
    $data = createAdminDokumenData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('admin.dokumen.index'))
        ->assertForbidden();
});

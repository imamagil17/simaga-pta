<?php

use App\Models\Dokumen;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function createMahasiswaDokumenData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'DMH-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'DMH-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Dokumen Mahasiswa',
        'kode_periode' => 'DMH-' . fake()->unique()->numberBetween(1000, 9999),
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

test('mahasiswa can access document page', function () {
    $data = createMahasiswaDokumenData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.dokumen.index'))
        ->assertOk()
        ->assertViewIs('mahasiswa.dokumen.index')
        ->assertViewHas('dokumen');
});

test('mahasiswa can access document create page', function () {
    $data = createMahasiswaDokumenData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.dokumen.create'))
        ->assertOk()
        ->assertViewIs('mahasiswa.dokumen.create');
});

test('mahasiswa can upload document', function () {
    Storage::fake('local');

    $data = createMahasiswaDokumenData();

    $file = UploadedFile::fake()->create(
        'surat-pengantar.pdf',
        200,
        'application/pdf'
    );

    $this
        ->actingAs($data['mahasiswaUser'])
        ->post(route('mahasiswa.dokumen.store'), [
            'jenis_dokumen' => 'surat_pengantar',
            'nama_dokumen' => 'Surat Pengantar Magang',
            'file' => $file,
        ])
        ->assertRedirect(route('mahasiswa.dokumen.index'))
        ->assertSessionHas('success');

    $dokumen = Dokumen::first();

    expect($dokumen)->not->toBeNull();

    expect($dokumen->mahasiswa_id)
        ->toBe($data['mahasiswa']->id);

    expect($dokumen->penempatan_id)
        ->toBe($data['penempatan']->id);

    expect($dokumen->status)
        ->toBe('uploaded');

    expect($dokumen->nama_file)
        ->toBe('surat-pengantar.pdf');

    Storage::disk('local')
        ->assertExists($dokumen->path_file);
});

test('mahasiswa cannot upload duplicate document type unless revision', function () {
    Storage::fake('local');

    $data = createMahasiswaDokumenData();

    Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'ktm',
        'nama_dokumen' => 'KTM',
        'nama_file' => 'ktm.pdf',
        'path_file' => 'dokumen/mahasiswa/1/ktm.pdf',
        'mime_type' => 'application/pdf',
        'ukuran_file' => 1000,
        'status' => 'uploaded',
    ]);

    $file = UploadedFile::fake()->create(
        'ktm-baru.pdf',
        100,
        'application/pdf'
    );

    $this
        ->actingAs($data['mahasiswaUser'])
        ->post(route('mahasiswa.dokumen.store'), [
            'jenis_dokumen' => 'ktm',
            'nama_dokumen' => 'KTM Baru',
            'file' => $file,
        ])
        ->assertSessionHasErrors('jenis_dokumen');

    expect(Dokumen::count())->toBe(1);
});

test('mahasiswa can replace revision document', function () {
    Storage::fake('local');

    $data = createMahasiswaDokumenData();

    $oldPath = 'dokumen/mahasiswa/1/ktm-lama.pdf';

    Storage::disk('local')->put(
        $oldPath,
        'old content'
    );

    $dokumen = Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'ktm',
        'nama_dokumen' => 'KTM Lama',
        'nama_file' => 'ktm-lama.pdf',
        'path_file' => $oldPath,
        'mime_type' => 'application/pdf',
        'ukuran_file' => 1000,
        'status' => 'revision',
        'catatan' => 'Mohon unggah ulang.',
    ]);

    $newFile = UploadedFile::fake()->create(
        'ktm-baru.pdf',
        200,
        'application/pdf'
    );

    $this
        ->actingAs($data['mahasiswaUser'])
        ->post(route('mahasiswa.dokumen.store'), [
            'jenis_dokumen' => 'ktm',
            'nama_dokumen' => 'KTM Baru',
            'file' => $newFile,
        ])
        ->assertRedirect(route('mahasiswa.dokumen.index'))
        ->assertSessionHas('success');

    $dokumen->refresh();

    expect($dokumen->status)
        ->toBe('uploaded');

    expect($dokumen->nama_file)
        ->toBe('ktm-baru.pdf');

    expect($dokumen->catatan)
        ->toBeNull();

    Storage::disk('local')
        ->assertMissing($oldPath);

    Storage::disk('local')
        ->assertExists($dokumen->path_file);
});

test('mahasiswa can download own document', function () {
    Storage::fake('local');

    $data = createMahasiswaDokumenData();

    $path = 'dokumen/mahasiswa/1/ktm.pdf';

    Storage::disk('local')->put(
        $path,
        'document content'
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
    ]);

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.dokumen.download', $dokumen))
        ->assertOk();
});

test('mahasiswa cannot download another students document', function () {
    Storage::fake('local');

    $data = createMahasiswaDokumenData();

    $otherUser = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $otherMahasiswa = Mahasiswa::create([
        'user_id' => $otherUser->id,
        'nim' => 'OTHER-DOC-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $path = 'dokumen/mahasiswa/other/ktp.pdf';

    Storage::disk('local')->put(
        $path,
        'private'
    );

    $dokumen = Dokumen::create([
        'mahasiswa_id' => $otherMahasiswa->id,
        'penempatan_id' => null,
        'jenis_dokumen' => 'ktp',
        'nama_dokumen' => 'KTP',
        'nama_file' => 'ktp.pdf',
        'path_file' => $path,
        'mime_type' => 'application/pdf',
        'ukuran_file' => 100,
    ]);

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.dokumen.download', $dokumen))
        ->assertForbidden();
});

test('verified document cannot be deleted by mahasiswa', function () {
    Storage::fake('local');

    $data = createMahasiswaDokumenData();

    $path = 'dokumen/mahasiswa/1/ktm.pdf';

    Storage::disk('local')->put(
        $path,
        'verified'
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
        'status' => 'verified',
    ]);

    $this
        ->actingAs($data['mahasiswaUser'])
        ->delete(route('mahasiswa.dokumen.destroy', $dokumen))
        ->assertSessionHasErrors('dokumen');

    expect(Dokumen::find($dokumen->id))
        ->not->toBeNull();
});

test('mahasiswa cannot access document routes as mentor', function () {
    $data = createMahasiswaDokumenData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mahasiswa.dokumen.index'))
        ->assertForbidden();
});

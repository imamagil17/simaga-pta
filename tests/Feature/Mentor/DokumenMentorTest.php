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

function createMentorDokumenData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'MDM-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'MDM-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Mentor Dokumen',
        'kode_periode' => 'MDM-' . fake()->unique()->numberBetween(1000, 9999),
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

function createOtherMentorDokumenData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'OMD-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'OMD-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Mentor Lain',
        'kode_periode' => 'OMD-' . fake()->unique()->numberBetween(1000, 9999),
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

test('mentor can access document page', function () {
    $data = createMentorDokumenData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.dokumen.index'))
        ->assertOk()
        ->assertViewIs('mentor.dokumen.index')
        ->assertViewHas('dokumen')
        ->assertViewHas('rekap');
});

test('mentor sees only documents from own students', function () {
    $data = createMentorDokumenData();
    $other = createOtherMentorDokumenData();

    $ownDocument = Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'ktm',
        'nama_dokumen' => 'KTM Mahasiswa Saya',
        'nama_file' => 'ktm.pdf',
        'path_file' => 'dokumen/own/ktm.pdf',
        'status' => 'uploaded',
    ]);

    Dokumen::create([
        'mahasiswa_id' => $other['mahasiswa']->id,
        'penempatan_id' => $other['penempatan']->id,
        'jenis_dokumen' => 'ktm',
        'nama_dokumen' => 'KTM Mahasiswa Lain',
        'nama_file' => 'ktm.pdf',
        'path_file' => 'dokumen/other/ktm.pdf',
        'status' => 'uploaded',
    ]);

    $response = $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.dokumen.index'));

    $dokumen = $response->viewData('dokumen');

    expect($dokumen)->toHaveCount(1);

    expect($dokumen->first()->id)
        ->toBe($ownDocument->id);
});

test('mentor can view own student document', function () {
    $data = createMentorDokumenData();

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
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.dokumen.show', $dokumen))
        ->assertOk()
        ->assertViewIs('mentor.dokumen.show')
        ->assertViewHas('dokumen');
});

test('mentor cannot view another mentors document', function () {
    $data = createMentorDokumenData();
    $other = createOtherMentorDokumenData();

    $dokumen = Dokumen::create([
        'mahasiswa_id' => $other['mahasiswa']->id,
        'penempatan_id' => $other['penempatan']->id,
        'jenis_dokumen' => 'ktm',
        'nama_dokumen' => 'Dokumen Mentor Lain',
        'nama_file' => 'ktm.pdf',
        'path_file' => 'dokumen/other/ktm.pdf',
        'status' => 'uploaded',
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.dokumen.show', $dokumen))
        ->assertForbidden();
});

test('mentor can verify uploaded document', function () {
    $data = createMentorDokumenData();

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
        ->actingAs($data['mentorUser'])
        ->post(
            route('mentor.dokumen.verify', $dokumen),
            [
                'catatan' => 'Dokumen sudah sesuai.',
            ]
        )
        ->assertRedirect(
            route('mentor.dokumen.show', $dokumen)
        )
        ->assertSessionHas('success');

    $dokumen->refresh();

    expect($dokumen->status)
        ->toBe('verified');

    expect($dokumen->catatan)
        ->toBe('Dokumen sudah sesuai.');

    expect($dokumen->verified_by)
        ->toBe($data['mentorUser']->id);

    expect($dokumen->verified_at)
        ->not->toBeNull();
});

test('mentor can request document revision', function () {
    $data = createMentorDokumenData();

    $dokumen = Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'ktp',
        'nama_dokumen' => 'KTP',
        'nama_file' => 'ktp.pdf',
        'path_file' => 'dokumen/ktp.pdf',
        'status' => 'uploaded',
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(
            route('mentor.dokumen.revision', $dokumen),
            [
                'catatan' =>
                'File kurang jelas, mohon unggah ulang.',
            ]
        )
        ->assertRedirect(
            route('mentor.dokumen.show', $dokumen)
        )
        ->assertSessionHas('success');

    $dokumen->refresh();

    expect($dokumen->status)
        ->toBe('revision');

    expect($dokumen->catatan)
        ->toBe(
            'File kurang jelas, mohon unggah ulang.'
        );
});

test('revision requires mentor note', function () {
    $data = createMentorDokumenData();

    $dokumen = Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'ktp',
        'nama_dokumen' => 'KTP',
        'nama_file' => 'ktp.pdf',
        'path_file' => 'dokumen/ktp.pdf',
        'status' => 'uploaded',
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(
            route('mentor.dokumen.revision', $dokumen),
            [
                'catatan' => '',
            ]
        )
        ->assertSessionHasErrors('catatan');

    $dokumen->refresh();

    expect($dokumen->status)
        ->toBe('uploaded');
});

test('mentor cannot verify already verified document', function () {
    $data = createMentorDokumenData();

    $dokumen = Dokumen::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'jenis_dokumen' => 'cv',
        'nama_dokumen' => 'CV',
        'nama_file' => 'cv.pdf',
        'path_file' => 'dokumen/cv.pdf',
        'status' => 'verified',
        'verified_by' => $data['mentorUser']->id,
        'verified_at' => now(),
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(
            route('mentor.dokumen.verify', $dokumen),
            [
                'catatan' => 'Mencoba verifikasi ulang.',
            ]
        )
        ->assertSessionHasErrors('dokumen');

    $dokumen->refresh();

    expect($dokumen->status)
        ->toBe('verified');
});

test('mahasiswa cannot access mentor document management', function () {
    $data = createMentorDokumenData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mentor.dokumen.index'))
        ->assertForbidden();
});

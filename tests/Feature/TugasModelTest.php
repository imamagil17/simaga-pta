<?php

use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createTugasData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'TGS-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'TGS-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Tugas',
        'kode_periode' => 'TGS-' . fake()->unique()->numberBetween(1000, 9999),
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

test('tugas belongsTo penempatan', function () {
    $data = createTugasData();

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Membuat Modul Absensi',
        'deskripsi' => 'Membuat modul absensi mahasiswa.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'created_by' => $data['mentorUser']->id,
    ]);

    expect($tugas->penempatan)->not->toBeNull();
    expect($tugas->penempatan->id)
        ->toBe($data['penempatan']->id);
});

test('penempatan hasMany tugas', function () {
    $data = createTugasData();

    Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Pertama',
        'deskripsi' => 'Deskripsi tugas pertama.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'created_by' => $data['mentorUser']->id,
    ]);

    Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Kedua',
        'deskripsi' => 'Deskripsi tugas kedua.',
        'tanggal_mulai' => '2026-08-25 08:00:00',
        'tanggal_deadline' => '2026-08-29 16:00:00',
        'created_by' => $data['mentorUser']->id,
    ]);

    expect($data['penempatan']->tugas)
        ->toHaveCount(2);
});

test('tugas default status is draft', function () {
    $data = createTugasData();

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Draft',
        'deskripsi' => 'Deskripsi tugas draft.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'created_by' => $data['mentorUser']->id,
    ]);

    expect($tugas->status)
        ->toBe('draft');
});

test('tugas can store published status', function () {
    $data = createTugasData();

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Published',
        'deskripsi' => 'Deskripsi tugas published.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    expect($tugas->status)
        ->toBe('published');
});

test('pengumpulan tugas belongsTo tugas', function () {
    $data = createTugasData();

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Pengumpulan',
        'deskripsi' => 'Deskripsi tugas pengumpulan.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    $pengumpulan = PengumpulanTugas::create([
        'tugas_id' => $tugas->id,
        'mahasiswa_id' => $data['mahasiswa']->id,
        'jawaban' => 'Jawaban tugas.',
    ]);

    expect($pengumpulan->tugas)->not->toBeNull();

    expect($pengumpulan->tugas->id)
        ->toBe($tugas->id);
});

test('pengumpulan tugas belongsTo mahasiswa', function () {
    $data = createTugasData();

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Mahasiswa',
        'deskripsi' => 'Deskripsi tugas mahasiswa.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    $pengumpulan = PengumpulanTugas::create([
        'tugas_id' => $tugas->id,
        'mahasiswa_id' => $data['mahasiswa']->id,
    ]);

    expect($pengumpulan->mahasiswa)->not->toBeNull();

    expect($pengumpulan->mahasiswa->id)
        ->toBe($data['mahasiswa']->id);
});

test('pengumpulan tugas default status is draft', function () {
    $data = createTugasData();

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Status',
        'deskripsi' => 'Deskripsi tugas status.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    $pengumpulan = PengumpulanTugas::create([
        'tugas_id' => $tugas->id,
        'mahasiswa_id' => $data['mahasiswa']->id,
    ]);

    expect($pengumpulan->status)
        ->toBe('draft');
});

test('same mahasiswa cannot have two submissions for same task', function () {
    $data = createTugasData();

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Unik',
        'deskripsi' => 'Deskripsi tugas unik.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    PengumpulanTugas::create([
        'tugas_id' => $tugas->id,
        'mahasiswa_id' => $data['mahasiswa']->id,
    ]);

    expect(function () use ($data, $tugas) {
        PengumpulanTugas::create([
            'tugas_id' => $tugas->id,
            'mahasiswa_id' => $data['mahasiswa']->id,
        ]);
    })->toThrow(QueryException::class);
});

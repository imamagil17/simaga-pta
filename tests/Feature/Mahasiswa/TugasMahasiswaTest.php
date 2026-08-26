<?php

use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createMahasiswaTugasData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'MTS-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'MTS-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Mahasiswa Tugas',
        'kode_periode' => 'MTS-' . fake()->unique()->numberBetween(1000, 9999),
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

test('mahasiswa can access tugas page', function () {
    $data = createMahasiswaTugasData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.tugas.index'))
        ->assertOk()
        ->assertViewIs('mahasiswa.tugas.index')
        ->assertViewHas('tugas');
});

test('mahasiswa only sees published and closed own tasks', function () {
    $data = createMahasiswaTugasData();

    $published = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Published',
        'deskripsi' => 'Tugas published.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Draft',
        'deskripsi' => 'Tugas draft.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'draft',
        'created_by' => $data['mentorUser']->id,
    ]);

    $response = $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.tugas.index'));

    $tugas = $response->viewData('tugas');

    expect($tugas)->toHaveCount(1);
    expect($tugas->first()->id)->toBe($published->id);
});

test('mahasiswa can view own published task detail', function () {
    $data = createMahasiswaTugasData();

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Detail Tugas',
        'deskripsi' => 'Deskripsi detail tugas.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.tugas.show', $tugas))
        ->assertOk()
        ->assertViewIs('mahasiswa.tugas.show')
        ->assertViewHas('tugas')
        ->assertViewHas('pengumpulan');
});

test('mahasiswa cannot view another students task', function () {
    $data = createMahasiswaTugasData();

    $otherUser = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $otherMahasiswa = Mahasiswa::create([
        'user_id' => $otherUser->id,
        'nim' => 'OTHER-MTS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $otherPlacement = Penempatan::create([
        'mahasiswa_id' => $otherMahasiswa->id,
        'periode_magang_id' => $data['periode']->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'active',
    ]);

    $tugas = Tugas::create([
        'penempatan_id' => $otherPlacement->id,
        'judul' => 'Tugas Orang Lain',
        'deskripsi' => 'Tidak boleh dilihat.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mahasiswa.tugas.show', $tugas))
        ->assertForbidden();
});

test('mahasiswa can save task answer as draft', function () {
    $data = createMahasiswaTugasData();

    Carbon::setTestNow('2026-08-25 10:00:00');

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Draft Jawaban',
        'deskripsi' => 'Tugas untuk menguji draft jawaban.',
        'tanggal_mulai' => '2026-08-25 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    $this
        ->actingAs($data['mahasiswaUser'])
        ->post(route('mahasiswa.tugas.store', $tugas), [
            'jawaban' => 'Ini adalah jawaban draft.',
        ])
        ->assertRedirect(route('mahasiswa.tugas.show', $tugas))
        ->assertSessionHas('success');

    $pengumpulan = PengumpulanTugas::first();

    expect($pengumpulan)->not->toBeNull();
    expect($pengumpulan->status)->toBe('draft');
    expect($pengumpulan->jawaban)->toBe('Ini adalah jawaban draft.');

    Carbon::setTestNow();
});

test('mahasiswa can submit task', function () {
    $data = createMahasiswaTugasData();

    Carbon::setTestNow('2026-08-25 10:00:00');

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Submit',
        'deskripsi' => 'Tugas untuk submit.',
        'tanggal_mulai' => '2026-08-25 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    $pengumpulan = PengumpulanTugas::create([
        'tugas_id' => $tugas->id,
        'mahasiswa_id' => $data['mahasiswa']->id,
        'jawaban' => 'Jawaban siap dikumpulkan.',
        'status' => 'draft',
    ]);

    $this
        ->actingAs($data['mahasiswaUser'])
        ->post(route('mahasiswa.tugas.submit', $tugas))
        ->assertRedirect(route('mahasiswa.tugas.show', $tugas))
        ->assertSessionHas('success');

    $pengumpulan->refresh();

    expect($pengumpulan->status)->toBe('submitted');
    expect($pengumpulan->dikumpulkan_at)->not->toBeNull();

    Carbon::setTestNow();
});

test('mahasiswa cannot submit after deadline', function () {
    $data = createMahasiswaTugasData();

    Carbon::setTestNow('2026-08-29 10:00:00');

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Terlambat',
        'deskripsi' => 'Tugas dengan deadline lewat.',
        'tanggal_mulai' => '2026-08-25 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    $this
        ->actingAs($data['mahasiswaUser'])
        ->post(route('mahasiswa.tugas.store', $tugas), [
            'jawaban' => 'Tidak boleh masuk.',
        ])
        ->assertSessionHasErrors('tugas');

    expect(PengumpulanTugas::count())->toBe(0);

    Carbon::setTestNow();
});

test('mahasiswa cannot submit before task starts', function () {
    $data = createMahasiswaTugasData();

    Carbon::setTestNow('2026-08-24 08:00:00');

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Belum Mulai',
        'deskripsi' => 'Tugas belum dimulai.',
        'tanggal_mulai' => '2026-08-25 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    $this
        ->actingAs($data['mahasiswaUser'])
        ->post(route('mahasiswa.tugas.store', $tugas), [
            'jawaban' => 'Belum boleh.',
        ])
        ->assertSessionHasErrors('tugas');

    expect(PengumpulanTugas::count())->toBe(0);

    Carbon::setTestNow();
});

test('submitted task cannot be edited by mahasiswa', function () {
    $data = createMahasiswaTugasData();

    Carbon::setTestNow('2026-08-25 10:00:00');

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Sudah Submit',
        'deskripsi' => 'Sudah dikumpulkan.',
        'tanggal_mulai' => '2026-08-25 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    $pengumpulan = PengumpulanTugas::create([
        'tugas_id' => $tugas->id,
        'mahasiswa_id' => $data['mahasiswa']->id,
        'jawaban' => 'Jawaban lama.',
        'status' => 'submitted',
        'dikumpulkan_at' => now(),
    ]);

    $this
        ->actingAs($data['mahasiswaUser'])
        ->post(route('mahasiswa.tugas.store', $tugas), [
            'jawaban' => 'Jawaban baru.',
        ])
        ->assertSessionHasErrors('tugas');

    $pengumpulan->refresh();

    expect($pengumpulan->jawaban)
        ->toBe('Jawaban lama.');

    Carbon::setTestNow();
});

test('mahasiswa can edit revision task', function () {
    $data = createMahasiswaTugasData();

    Carbon::setTestNow('2026-08-25 10:00:00');

    $tugas = Tugas::create([
        'penempatan_id' => $data['penempatan']->id,
        'judul' => 'Tugas Revisi',
        'deskripsi' => 'Tugas perlu direvisi.',
        'tanggal_mulai' => '2026-08-25 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $data['mentorUser']->id,
    ]);

    $pengumpulan = PengumpulanTugas::create([
        'tugas_id' => $tugas->id,
        'mahasiswa_id' => $data['mahasiswa']->id,
        'jawaban' => 'Jawaban lama.',
        'status' => 'revision',
        'catatan_mentor' => 'Mohon diperbaiki.',
    ]);

    $this
        ->actingAs($data['mahasiswaUser'])
        ->post(route('mahasiswa.tugas.store', $tugas), [
            'jawaban' => 'Jawaban yang sudah diperbaiki.',
        ])
        ->assertRedirect(route('mahasiswa.tugas.show', $tugas));

    $pengumpulan->refresh();

    expect($pengumpulan->jawaban)
        ->toBe('Jawaban yang sudah diperbaiki.');

    expect($pengumpulan->status)
        ->toBe('revision');

    Carbon::setTestNow();
});

test('mentor cannot access mahasiswa task page', function () {
    $data = createMahasiswaTugasData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mahasiswa.tugas.index'))
        ->assertForbidden();
});

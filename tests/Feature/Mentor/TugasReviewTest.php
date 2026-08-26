<?php

use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\PengumpulanTugas;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createTugasReviewData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'TRV-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'TRV-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Review Tugas',
        'kode_periode' => 'TRV-' . fake()->unique()->numberBetween(1000, 9999),
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

    $tugas = Tugas::create([
        'penempatan_id' => $penempatan->id,
        'judul' => 'Tugas Review',
        'deskripsi' => 'Tugas untuk pengujian review mentor.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $mentorUser->id,
    ]);

    return compact(
        'mentorUser',
        'mentor',
        'mahasiswaUser',
        'mahasiswa',
        'periode',
        'penempatan',
        'tugas'
    );
}

function createOtherMentorReviewData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'OTR-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'OTR-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Mentor Lain',
        'kode_periode' => 'OTR-' . fake()->unique()->numberBetween(1000, 9999),
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

    $tugas = Tugas::create([
        'penempatan_id' => $penempatan->id,
        'judul' => 'Tugas Mentor Lain',
        'deskripsi' => 'Tugas yang bukan milik mentor login.',
        'tanggal_mulai' => '2026-08-24 08:00:00',
        'tanggal_deadline' => '2026-08-28 16:00:00',
        'status' => 'published',
        'created_by' => $mentorUser->id,
    ]);

    return compact(
        'mentorUser',
        'mentor',
        'mahasiswaUser',
        'mahasiswa',
        'periode',
        'penempatan',
        'tugas'
    );
}

test('mentor can view submitted task review', function () {
    $data = createTugasReviewData();

    $pengumpulan = PengumpulanTugas::create([
        'tugas_id' => $data['tugas']->id,
        'mahasiswa_id' => $data['mahasiswa']->id,
        'jawaban' => 'Jawaban mahasiswa.',
        'status' => 'submitted',
        'dikumpulkan_at' => now(),
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->get(route(
            'mentor.tugas.review.show',
            [$data['tugas'], $pengumpulan]
        ))
        ->assertOk()
        ->assertViewIs('mentor.tugas.review')
        ->assertViewHas('tugas')
        ->assertViewHas('pengumpulan');
});

test('mentor can review submitted task', function () {
    $data = createTugasReviewData();

    $pengumpulan = PengumpulanTugas::create([
        'tugas_id' => $data['tugas']->id,
        'mahasiswa_id' => $data['mahasiswa']->id,
        'jawaban' => 'Jawaban mahasiswa.',
        'status' => 'submitted',
        'dikumpulkan_at' => now(),
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(
            route(
                'mentor.tugas.review',
                [$data['tugas'], $pengumpulan]
            ),
            [
                'nilai' => 85,
                'catatan_mentor' => 'Jawaban sudah baik.',
            ]
        )
        ->assertRedirect(
            route(
                'mentor.tugas.review.show',
                [$data['tugas'], $pengumpulan]
            )
        )
        ->assertSessionHas('success');

    $pengumpulan->refresh();

    expect($pengumpulan->status)
        ->toBe('reviewed');

    expect((float) $pengumpulan->nilai)
        ->toBe(85.0);

    expect($pengumpulan->catatan_mentor)
        ->toBe('Jawaban sudah baik.');

    expect($pengumpulan->reviewed_by)
        ->toBe($data['mentorUser']->id);

    expect($pengumpulan->reviewed_at)
        ->not->toBeNull();
});

test('mentor can request task revision', function () {
    $data = createTugasReviewData();

    $pengumpulan = PengumpulanTugas::create([
        'tugas_id' => $data['tugas']->id,
        'mahasiswa_id' => $data['mahasiswa']->id,
        'jawaban' => 'Jawaban yang belum tepat.',
        'status' => 'submitted',
        'dikumpulkan_at' => now(),
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(
            route(
                'mentor.tugas.revision',
                [$data['tugas'], $pengumpulan]
            ),
            [
                'catatan_mentor' =>
                'Mohon perbaiki bagian analisis.',
            ]
        )
        ->assertRedirect(
            route(
                'mentor.tugas.review.show',
                [$data['tugas'], $pengumpulan]
            )
        )
        ->assertSessionHas('success');

    $pengumpulan->refresh();

    expect($pengumpulan->status)
        ->toBe('revision');

    expect($pengumpulan->catatan_mentor)
        ->toBe('Mohon perbaiki bagian analisis.');

    expect($pengumpulan->reviewed_by)
        ->toBe($data['mentorUser']->id);

    expect($pengumpulan->reviewed_at)
        ->not->toBeNull();
});

test('revision requires mentor note', function () {
    $data = createTugasReviewData();

    $pengumpulan = PengumpulanTugas::create([
        'tugas_id' => $data['tugas']->id,
        'mahasiswa_id' => $data['mahasiswa']->id,
        'jawaban' => 'Jawaban mahasiswa.',
        'status' => 'submitted',
        'dikumpulkan_at' => now(),
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(
            route(
                'mentor.tugas.revision',
                [$data['tugas'], $pengumpulan]
            ),
            [
                'catatan_mentor' => '',
            ]
        )
        ->assertSessionHasErrors('catatan_mentor');

    $pengumpulan->refresh();

    expect($pengumpulan->status)
        ->toBe('submitted');
});

test('review requires valid score', function () {
    $data = createTugasReviewData();

    $pengumpulan = PengumpulanTugas::create([
        'tugas_id' => $data['tugas']->id,
        'mahasiswa_id' => $data['mahasiswa']->id,
        'jawaban' => 'Jawaban mahasiswa.',
        'status' => 'submitted',
        'dikumpulkan_at' => now(),
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(
            route(
                'mentor.tugas.review',
                [$data['tugas'], $pengumpulan]
            ),
            [
                'nilai' => 101,
                'catatan_mentor' => 'Tidak valid.',
            ]
        )
        ->assertSessionHasErrors('nilai');

    $pengumpulan->refresh();

    expect($pengumpulan->status)
        ->toBe('submitted');
});

test('mentor cannot review another mentors submission', function () {
    $data = createTugasReviewData();
    $other = createOtherMentorReviewData();

    $pengumpulan = PengumpulanTugas::create([
        'tugas_id' => $other['tugas']->id,
        'mahasiswa_id' => $other['mahasiswa']->id,
        'jawaban' => 'Jawaban mentor lain.',
        'status' => 'submitted',
        'dikumpulkan_at' => now(),
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->get(
            route(
                'mentor.tugas.review.show',
                [$other['tugas'], $pengumpulan]
            )
        )
        ->assertForbidden();
});

test('mentor cannot review already reviewed submission', function () {
    $data = createTugasReviewData();

    $pengumpulan = PengumpulanTugas::create([
        'tugas_id' => $data['tugas']->id,
        'mahasiswa_id' => $data['mahasiswa']->id,
        'jawaban' => 'Sudah dinilai.',
        'status' => 'reviewed',
        'nilai' => 80,
        'reviewed_by' => $data['mentorUser']->id,
        'reviewed_at' => now(),
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(
            route(
                'mentor.tugas.review',
                [$data['tugas'], $pengumpulan]
            ),
            [
                'nilai' => 90,
                'catatan_mentor' => 'Mencoba mengubah nilai.',
            ]
        )
        ->assertSessionHasErrors('tugas');

    $pengumpulan->refresh();

    expect((float) $pengumpulan->nilai)
        ->toBe(80.0);
});

test('mahasiswa cannot access mentor task review', function () {
    $data = createTugasReviewData();

    $pengumpulan = PengumpulanTugas::create([
        'tugas_id' => $data['tugas']->id,
        'mahasiswa_id' => $data['mahasiswa']->id,
        'jawaban' => 'Jawaban mahasiswa.',
        'status' => 'submitted',
        'dikumpulkan_at' => now(),
    ]);

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(
            route(
                'mentor.tugas.review.show',
                [$data['tugas'], $pengumpulan]
            )
        )
        ->assertForbidden();
});

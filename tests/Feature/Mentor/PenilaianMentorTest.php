<?php

use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\Penilaian;
use App\Models\PeriodeMagang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createMentorPenilaianData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'PMN-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'PMN-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Penilaian Mentor',
        'kode_periode' => 'PMN-' . fake()->unique()->numberBetween(1000, 9999),
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

function createOtherMentorPenilaianData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'PMN-OTHER-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'PMN-OTHER-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Mentor Lain',
        'kode_periode' => 'PMN-OTHER-' . fake()->unique()->numberBetween(1000, 9999),
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

test('mentor can access assessment page', function () {
    $data = createMentorPenilaianData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.penilaian.index'))
        ->assertOk()
        ->assertViewIs('mentor.penilaian.index')
        ->assertViewHas('penempatans')
        ->assertViewHas('rekap');
});

test('mentor sees only own students on assessment page', function () {
    $data = createMentorPenilaianData();
    $other = createOtherMentorPenilaianData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.penilaian.index'))
        ->assertOk()
        ->assertSee(
            $data['mahasiswaUser']->name
        )
        ->assertDontSee(
            $other['mahasiswaUser']->name
        );
});

test('mentor can access create assessment page for own student', function () {
    $data = createMentorPenilaianData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(
            route(
                'mentor.penilaian.create',
                $data['mahasiswa']
            )
        )
        ->assertOk()
        ->assertViewIs('mentor.penilaian.create')
        ->assertViewHas('penempatan');
});

test('mentor cannot access another mentors student assessment', function () {
    $data = createMentorPenilaianData();
    $other = createOtherMentorPenilaianData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(
            route(
                'mentor.penilaian.create',
                $other['mahasiswa']
            )
        )
        ->assertForbidden();
});

test('mentor can save assessment as draft', function () {
    $data = createMentorPenilaianData();

    $this
        ->actingAs($data['mentorUser'])
        ->post(
            route(
                'mentor.penilaian.store',
                $data['mahasiswa']
            ),
            [
                'nilai_kedisiplinan' => 90,
                'nilai_kehadiran' => 95,
                'nilai_kinerja' => 85,
                'nilai_kompetensi' => 88,
                'nilai_sikap' => 92,
                'catatan' => 'Kinerja sangat baik.',
            ]
        )
        ->assertRedirect(
            route(
                'mentor.penilaian.show',
                $data['mahasiswa']
            )
        )
        ->assertSessionHas(
            'success',
            'Penilaian berhasil disimpan sebagai draft.'
        );

    $penilaian = Penilaian::first();

    expect($penilaian)->not->toBeNull();

    expect($penilaian->status)
        ->toBe('draft');

    expect((float) $penilaian->nilai_akhir)
        ->toBe(90.0);
});

test('mentor can update draft assessment', function () {
    $data = createMentorPenilaianData();

    $penilaian = Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'nilai_kedisiplinan' => 80,
        'nilai_kehadiran' => 80,
        'nilai_kinerja' => 80,
        'nilai_kompetensi' => 80,
        'nilai_sikap' => 80,
        'nilai_akhir' => 80,
        'status' => 'draft',
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(
            route(
                'mentor.penilaian.store',
                $data['mahasiswa']
            ),
            [
                'nilai_kedisiplinan' => 90,
                'nilai_kehadiran' => 90,
                'nilai_kinerja' => 90,
                'nilai_kompetensi' => 90,
                'nilai_sikap' => 90,
                'catatan' => 'Update nilai.',
            ]
        )
        ->assertRedirect();

    $penilaian->refresh();

    expect((float) $penilaian->nilai_akhir)
        ->toBe(90.0);

    expect($penilaian->catatan)
        ->toBe('Update nilai.');
});

test('mentor cannot change final assessment', function () {
    $data = createMentorPenilaianData();

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

    $this
        ->actingAs($data['mentorUser'])
        ->post(
            route(
                'mentor.penilaian.store',
                $data['mahasiswa']
            ),
            [
                'nilai_kedisiplinan' => 70,
                'nilai_kehadiran' => 70,
                'nilai_kinerja' => 70,
                'nilai_kompetensi' => 70,
                'nilai_sikap' => 70,
            ]
        )
        ->assertSessionHasErrors('penilaian');

    $penilaian->refresh();

    expect((float) $penilaian->nilai_akhir)
        ->toBe(90.0);
});

test('mentor can finalize assessment', function () {
    $data = createMentorPenilaianData();

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
        'status' => 'draft',
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(
            route(
                'mentor.penilaian.finalize',
                $data['mahasiswa']
            )
        )
        ->assertRedirect(
            route(
                'mentor.penilaian.show',
                $data['mahasiswa']
            )
        )
        ->assertSessionHas(
            'success',
            'Penilaian berhasil difinalisasi.'
        );

    $penilaian->refresh();

    expect($penilaian->status)
        ->toBe('final');

    expect($penilaian->finalized_by)
        ->toBe($data['mentorUser']->id);

    expect($penilaian->finalized_at)
        ->not->toBeNull();
});

test('mentor cannot finalize incomplete assessment', function () {
    $data = createMentorPenilaianData();

    $penilaian = Penilaian::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'nilai_kedisiplinan' => 90,
        'nilai_kehadiran' => null,
        'nilai_kinerja' => 85,
        'nilai_kompetensi' => 88,
        'nilai_sikap' => 92,
        'nilai_akhir' => null,
        'status' => 'draft',
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(
            route(
                'mentor.penilaian.finalize',
                $data['mahasiswa']
            )
        )
        ->assertSessionHasErrors('penilaian');

    $penilaian->refresh();

    expect($penilaian->status)
        ->toBe('draft');
});

test('mahasiswa cannot access mentor assessment management', function () {
    $data = createMentorPenilaianData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mentor.penilaian.index'))
        ->assertForbidden();
});

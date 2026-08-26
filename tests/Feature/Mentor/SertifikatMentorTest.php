<?php

use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\Sertifikat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createMentorSertifikatData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'SMN-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'SMN-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Mentor Sertifikat',
        'kode_periode' => 'SMN-' . fake()->unique()->numberBetween(1000, 9999),
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

function createOtherMentorSertifikatData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'SMN-OTHER-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'SMN-OTHER-MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Mentor Lain',
        'kode_periode' => 'SMN-OTHER-P-' . fake()->unique()->numberBetween(1000, 9999),
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

test('mentor can access certificate page', function () {
    $data = createMentorSertifikatData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.sertifikat.index'))
        ->assertOk()
        ->assertViewIs('mentor.sertifikat.index')
        ->assertViewHas('penempatans')
        ->assertViewHas('rekap');
});

test('mentor sees only own students on certificate page', function () {
    $data = createMentorSertifikatData();
    $other = createOtherMentorSertifikatData();

    $response = $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.sertifikat.index'));

    $response
        ->assertSee(
            $data['mahasiswaUser']->name
        )
        ->assertDontSee(
            $other['mahasiswaUser']->name
        );
});

test('mentor can view own student certificate request detail', function () {
    $data = createMentorSertifikatData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(
            route(
                'mentor.sertifikat.show',
                $data['mahasiswa']
            )
        )
        ->assertOk()
        ->assertViewIs('mentor.sertifikat.show')
        ->assertViewHas('penempatan')
        ->assertViewHas('sertifikat');
});

test('mentor cannot view another mentors certificate request', function () {
    $data = createMentorSertifikatData();
    $other = createOtherMentorSertifikatData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(
            route(
                'mentor.sertifikat.show',
                $other['mahasiswa']
            )
        )
        ->assertForbidden();
});

test('mentor can submit certificate request', function () {
    $data = createMentorSertifikatData();

    $this
        ->actingAs($data['mentorUser'])
        ->post(
            route(
                'mentor.sertifikat.store',
                $data['mahasiswa']
            )
        )
        ->assertRedirect(
            route(
                'mentor.sertifikat.show',
                $data['mahasiswa']
            )
        )
        ->assertSessionHas(
            'success',
            'Pengajuan sertifikat berhasil dikirim ke admin.'
        );

    $sertifikat = Sertifikat::query()->first();

    expect($sertifikat)
        ->not
        ->toBeNull();

    expect($sertifikat->mahasiswa_id)
        ->toBe($data['mahasiswa']->id);

    expect($sertifikat->penempatan_id)
        ->toBe($data['penempatan']->id);

    expect($sertifikat->mentor_id)
        ->toBe($data['mentor']->id);

    expect($sertifikat->status)
        ->toBe('pending');
});

test('mentor cannot submit certificate request twice while pending', function () {
    $data = createMentorSertifikatData();

    Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'pending',
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(
            route(
                'mentor.sertifikat.store',
                $data['mahasiswa']
            )
        )
        ->assertSessionHasErrors(
            'sertifikat'
        );

    expect(
        Sertifikat::count()
    )->toBe(1);
});

test('mentor cannot submit certificate request after approved', function () {
    $data = createMentorSertifikatData();

    Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'approved',
        'nomor_sertifikat' =>
        '001/SIMAGA/PTA-PALU/VIII/2026',
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(
            route(
                'mentor.sertifikat.store',
                $data['mahasiswa']
            )
        )
        ->assertSessionHasErrors(
            'sertifikat'
        );
});

test('mentor can submit certificate request again after rejection', function () {
    $data = createMentorSertifikatData();

    $sertifikat = Sertifikat::create([
        'mahasiswa_id' => $data['mahasiswa']->id,
        'penempatan_id' => $data['penempatan']->id,
        'mentor_id' => $data['mentor']->id,
        'status' => 'rejected',
        'catatan' => 'Mohon periksa kembali data.',
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->post(
            route(
                'mentor.sertifikat.store',
                $data['mahasiswa']
            )
        )
        ->assertRedirect(
            route(
                'mentor.sertifikat.show',
                $data['mahasiswa']
            )
        )
        ->assertSessionHas(
            'success',
            'Pengajuan sertifikat berhasil dikirim ke admin.'
        );

    $sertifikat->refresh();

    expect($sertifikat->status)
        ->toBe('pending');

    expect($sertifikat->catatan)
        ->toBeNull();

    expect($sertifikat->approved_by)
        ->toBeNull();

    expect($sertifikat->approved_at)
        ->toBeNull();
});

test('mahasiswa cannot access mentor certificate management', function () {
    $data = createMentorSertifikatData();

    $this
        ->actingAs($data['mahasiswaUser'])
        ->get(route('mentor.sertifikat.index'))
        ->assertForbidden();
});

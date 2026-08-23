<?php

use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function createVerificationData(): array
{
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'VER-' . fake()->unique()->numberBetween(100000, 999999),
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
        'nim' => 'MHS-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Verifikasi',
        'kode_periode' => 'V-' . fake()->unique()->numberBetween(1000, 9999),
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

    $absensi = Absensi::create([
        'penempatan_id' => $penempatan->id,
        'tanggal' => '2026-08-24',
        'jam_masuk' => '08:03',
        'jam_pulang' => '16:30',
        'status_kehadiran' => 'hadir',
        'menit_terlambat' => 3,
        'status_verifikasi' => 'pending',
        'paraf_mahasiswa' => 'absensi/paraf/mahasiswa/test.png',
        'paraf_mahasiswa_at' => Carbon::parse('2026-08-24 16:30'),
    ]);

    return compact(
        'mentorUser',
        'mentor',
        'mahasiswaUser',
        'mahasiswa',
        'periode',
        'penempatan',
        'absensi'
    );
}

function verificationSignature(): string
{
    return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';
}

test('mentor can view assigned attendance detail', function () {
    $data = createVerificationData();

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.absensi.show', $data['absensi']))
        ->assertOk()
        ->assertViewIs('mentor.absensi.show')
        ->assertSee($data['mahasiswaUser']->name)
        ->assertSee('08:03')
        ->assertSee('16:30')
        ->assertSee('3 menit');
});

test('mentor cannot view another mentors attendance detail', function () {
    $data = createVerificationData();

    $otherMentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $otherMentor = Mentor::create([
        'user_id' => $otherMentorUser->id,
        'nip' => 'OTHER-' . fake()->unique()->numberBetween(100000, 999999),
        'jabatan' => 'Hakim Tinggi',
        'bagian' => 'Kepaniteraan',
        'status' => 'active',
    ]);

    $data['penempatan']->update([
        'mentor_id' => $otherMentor->id,
    ]);

    $this
        ->actingAs($data['mentorUser'])
        ->get(route('mentor.absensi.show', $data['absensi']))
        ->assertNotFound();
});

test('mentor can approve attendance with signature', function () {
    Storage::fake('public');

    $data = createVerificationData();

    $response = $this
        ->actingAs($data['mentorUser'])
        ->post(
            route('mentor.absensi.approve', $data['absensi']),
            [
                'paraf_mentor' => verificationSignature(),
            ]
        );

    $response
        ->assertRedirect(route('mentor.absensi.show', $data['absensi']))
        ->assertSessionHas(
            'success',
            'Absensi berhasil disetujui dan paraf mentor telah disimpan.'
        );

    $absensi = $data['absensi']->fresh();

    expect($absensi->status_verifikasi)->toBe('approved');
    expect($absensi->paraf_mentor)->not->toBeNull();
    expect($absensi->paraf_mentor_at)->not->toBeNull();

    Storage::disk('public')
        ->assertExists($absensi->paraf_mentor);
});

test('mentor cannot approve attendance without signature', function () {
    $data = createVerificationData();

    $response = $this
        ->actingAs($data['mentorUser'])
        ->post(
            route('mentor.absensi.approve', $data['absensi']),
            [
                'paraf_mentor' => '',
            ]
        );

    $response
        ->assertRedirect()
        ->assertSessionHasErrors('paraf_mentor');

    expect(
        $data['absensi']->fresh()->status_verifikasi
    )->toBe('pending');
});

test('mentor can reject attendance with reason', function () {
    $data = createVerificationData();

    $response = $this
        ->actingAs($data['mentorUser'])
        ->post(
            route('mentor.absensi.reject', $data['absensi']),
            [
                'alasan_penolakan' => 'Jam pulang tidak sesuai dengan catatan kegiatan.',
            ]
        );

    $response
        ->assertRedirect(route('mentor.absensi.show', $data['absensi']))
        ->assertSessionHas(
            'success',
            'Absensi telah ditolak dan alasan penolakan berhasil disimpan.'
        );

    $absensi = $data['absensi']->fresh();

    expect($absensi->status_verifikasi)->toBe('rejected');
    expect($absensi->alasan_penolakan)
        ->toBe('Jam pulang tidak sesuai dengan catatan kegiatan.');
});

test('mentor cannot reject already approved attendance', function () {
    $data = createVerificationData();

    $data['absensi']->update([
        'status_verifikasi' => 'approved',
        'paraf_mentor' => 'absensi/paraf/mentor/test.png',
        'paraf_mentor_at' => now(),
    ]);

    $response = $this
        ->actingAs($data['mentorUser'])
        ->post(
            route('mentor.absensi.reject', $data['absensi']),
            [
                'alasan_penolakan' => 'Percobaan penolakan.',
            ]
        );

    $response
        ->assertRedirect()
        ->assertSessionHasErrors('absensi');

    expect(
        $data['absensi']->fresh()->status_verifikasi
    )->toBe('approved');
});

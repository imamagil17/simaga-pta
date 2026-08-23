<?php

use App\Models\Absensi;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\PeriodeMagang;
use App\Models\User;
use App\Services\SignatureService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function createPulangTestData(): array
{
    $user = User::factory()->create([
        'role' => 'mahasiswa',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mahasiswa = Mahasiswa::create([
        'user_id' => $user->id,
        'nim' => 'PUL-' . fake()->unique()->numberBetween(100000, 999999),
        'perguruan_tinggi' => 'Universitas Tadulako',
        'program_studi' => 'Teknik Informatika',
        'status' => 'active',
    ]);

    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'status' => 'active',
        'must_change_password' => false,
    ]);

    $mentor = Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => 'NIP-' . fake()->unique()->numberBetween(100000, 999999),
        'jabatan' => 'Hakim Tinggi',
        'bagian' => 'Kepaniteraan',
        'status' => 'active',
    ]);

    $periode = PeriodeMagang::create([
        'nama_periode' => 'Periode Pulang Testing',
        'kode_periode' => 'PUL-' . fake()->unique()->numberBetween(1000, 9999),
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
        'user',
        'mahasiswa',
        'mentor',
        'periode',
        'penempatan'
    );
}

function fakeSignatureDataUrl(): string
{
    $pngBase64 =
        'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

    return 'data:image/png;base64,' . $pngBase64;
}

test('mahasiswa cannot absen pulang before absen masuk', function () {
    $data = createPulangTestData();

    Carbon::setTestNow(
        Carbon::create(2026, 8, 24, 16, 30)
    );

    Storage::fake('public');

    $response = $this
        ->actingAs($data['user'])
        ->post(route('mahasiswa.absensi.pulang'), [
            'paraf_mahasiswa' => fakeSignatureDataUrl(),
        ]);

    $response
        ->assertRedirect()
        ->assertSessionHasErrors('absensi');

    expect(Absensi::count())->toBe(0);
});

test('mahasiswa cannot absen pulang before official time', function () {
    $data = createPulangTestData();

    Carbon::setTestNow(
        Carbon::create(2026, 8, 24, 16, 29)
    );

    Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'jam_masuk' => '08:00',
        'status_kehadiran' => 'hadir',
    ]);

    Storage::fake('public');

    $response = $this
        ->actingAs($data['user'])
        ->post(route('mahasiswa.absensi.pulang'), [
            'paraf_mahasiswa' => fakeSignatureDataUrl(),
        ]);

    $response
        ->assertRedirect()
        ->assertSessionHasErrors('absensi');

    expect(
        Absensi::first()->jam_pulang
    )->toBeNull();
});

test('mahasiswa can absen pulang on monday at 16 30', function () {
    $data = createPulangTestData();

    Carbon::setTestNow(
        Carbon::create(2026, 8, 24, 16, 30)
    );

    Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'jam_masuk' => '08:00',
        'status_kehadiran' => 'hadir',
    ]);

    Storage::fake('public');

    $response = $this
        ->actingAs($data['user'])
        ->post(route('mahasiswa.absensi.pulang'), [
            'paraf_mahasiswa' => fakeSignatureDataUrl(),
        ]);

    $response
        ->assertRedirect(route('mahasiswa.absensi.index'))
        ->assertSessionHas(
            'success',
            'Absen pulang berhasil dicatat dan dikirim untuk verifikasi mentor.'
        );

    $absensi = Absensi::first();

    expect($absensi->jam_pulang)->toBe('16:30');
    expect($absensi->status_verifikasi)->toBe('pending');
    expect($absensi->paraf_mahasiswa)->not->toBeNull();
    expect($absensi->paraf_mahasiswa_at)->not->toBeNull();

    Storage::disk('public')
        ->assertExists($absensi->paraf_mahasiswa);
});

test('mahasiswa can absen pulang on friday at 16 50', function () {
    $data = createPulangTestData();

    Carbon::setTestNow(
        Carbon::create(2026, 8, 28, 16, 50)
    );

    Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-28',
        'jam_masuk' => '08:00',
        'status_kehadiran' => 'hadir',
    ]);

    Storage::fake('public');

    $response = $this
        ->actingAs($data['user'])
        ->post(route('mahasiswa.absensi.pulang'), [
            'paraf_mahasiswa' => fakeSignatureDataUrl(),
        ]);

    $response
        ->assertRedirect(route('mahasiswa.absensi.index'));

    expect(Absensi::first()->jam_pulang)
        ->toBe('16:50');
});

test('mahasiswa cannot absen pulang twice on same day', function () {
    $data = createPulangTestData();

    Carbon::setTestNow(
        Carbon::create(2026, 8, 24, 16, 30)
    );

    $absensi = Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'jam_masuk' => '08:00',
        'jam_pulang' => '16:30',
        'status_kehadiran' => 'hadir',
    ]);

    Storage::fake('public');

    $response = $this
        ->actingAs($data['user'])
        ->post(route('mahasiswa.absensi.pulang'), [
            'paraf_mahasiswa' => fakeSignatureDataUrl(),
        ]);

    $response
        ->assertRedirect()
        ->assertSessionHasErrors('absensi');

    expect(
        Absensi::whereKey($absensi->id)->value('jam_pulang')
    )->toBe('16:30');
});

test('mahasiswa cannot absen on saturday', function () {
    $data = createPulangTestData();

    Carbon::setTestNow(
        Carbon::create(2026, 8, 29, 16, 30)
    );

    Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-29',
        'jam_masuk' => '08:00',
        'status_kehadiran' => 'hadir',
    ]);

    Storage::fake('public');

    $response = $this
        ->actingAs($data['user'])
        ->post(route('mahasiswa.absensi.pulang'), [
            'paraf_mahasiswa' => fakeSignatureDataUrl(),
        ]);

    $response
        ->assertRedirect()
        ->assertSessionHasErrors('absensi');
});

test('mahasiswa cannot absen pulang without signature', function () {
    $data = createPulangTestData();

    Carbon::setTestNow(
        Carbon::create(2026, 8, 24, 16, 30)
    );

    Absensi::create([
        'penempatan_id' => $data['penempatan']->id,
        'tanggal' => '2026-08-24',
        'jam_masuk' => '08:00',
        'status_kehadiran' => 'hadir',
    ]);

    $response = $this
        ->actingAs($data['user'])
        ->post(route('mahasiswa.absensi.pulang'), [
            'paraf_mahasiswa' => '',
        ]);

    $response
        ->assertRedirect()
        ->assertSessionHasErrors('paraf_mahasiswa');

    expect(
        Absensi::first()->jam_pulang
    )->toBeNull();
});
<?php

use App\Models\Mentor;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('administrator can access admin mentors index page', function () {
    $admin = User::factory()->create([
        'role' => 'administrator',
        'must_change_password' => false,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.mentors.index'));

    $response->assertStatus(200);
    $response->assertSee('Data Mentor');
});

test('mentor cannot access admin mentors index page', function () {
    $mentor = User::factory()->create([
        'role' => 'mentor',
        'must_change_password' => false,
    ]);

    $response = $this->actingAs($mentor)->get(route('admin.mentors.index'));

    $response->assertStatus(403);
});

test('mahasiswa cannot access admin mentors index page', function () {
    $mahasiswa = User::factory()->create([
        'role' => 'mahasiswa',
        'must_change_password' => false,
    ]);

    $response = $this->actingAs($mahasiswa)->get(route('admin.mentors.index'));

    $response->assertStatus(403);
});

test('mentors index displays only users with mentor role', function () {
    $admin = User::factory()->create([
        'role' => 'administrator',
        'must_change_password' => false,
    ]);

    $mentorUser = User::factory()->create([
        'name' => 'Mentor Akun Uji',
        'email' => 'mentor.uji@test.com',
        'role' => 'mentor',
        'must_change_password' => false,
    ]);

    $mahasiswaUser = User::factory()->create([
        'name' => 'Mahasiswa Akun Uji',
        'email' => 'mahasiswa.uji@test.com',
        'role' => 'mahasiswa',
        'must_change_password' => false,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.mentors.index'));

    $response->assertStatus(200);
    $response->assertSee('Mentor Akun Uji');
    $response->assertSee('mentor.uji@test.com');
    $response->assertDontSee('Mahasiswa Akun Uji');
});

test('mentor without profile displays Belum dilengkapi state', function () {
    $admin = User::factory()->create([
        'role' => 'administrator',
        'must_change_password' => false,
    ]);

    $mentorUser = User::factory()->create([
        'name' => 'Mentor Tanpa Profil',
        'email' => 'tanpa.profil@test.com',
        'role' => 'mentor',
        'must_change_password' => false,
    ]);

    expect($mentorUser->mentor)->toBeNull();

    $response = $this->actingAs($admin)->get(route('admin.mentors.index'));

    $response->assertStatus(200);
    $response->assertSee('Mentor Tanpa Profil');
    $response->assertSee('tanpa.profil@test.com');
    $response->assertSee('Belum dilengkapi');
});

test('mentor with profile displays profile details', function () {
    $admin = User::factory()->create([
        'role' => 'administrator',
        'must_change_password' => false,
    ]);

    $mentorUser = User::factory()->create([
        'name' => 'Mentor Lengkap',
        'email' => 'mentor.lengkap@test.com',
        'role' => 'mentor',
        'must_change_password' => false,
    ]);

    Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => '1988112233445566',
        'jabatan' => 'Hakim Utama',
        'bagian' => 'Perdata Agama',
        'jenis_kelamin' => 'Laki-laki',
        'agama' => 'Islam',
        'no_hp' => '081122334455',
        'status' => 'active',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.mentors.index'));

    $response->assertStatus(200);
    $response->assertSee('Mentor Lengkap');
    $response->assertSee('1988112233445566');
    $response->assertSee('Hakim Utama');
    $response->assertSee('Perdata Agama');
    $response->assertSee('081122334455');
    $response->assertSee('Aktif');
});

test('administrator can access mentor profile create page for mentor without profile', function () {
    $admin = User::factory()->create([
        'role' => 'administrator',
        'must_change_password' => false,
    ]);

    $mentorUser = User::factory()->create([
        'name' => 'Budi Mentor',
        'email' => 'budi.mentor@test.com',
        'role' => 'mentor',
        'must_change_password' => false,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.mentors.profile.create', $mentorUser));

    $response->assertStatus(200);
    $response->assertSee('Lengkapi Profil Mentor');
    $response->assertSee('Budi Mentor');
    $response->assertSee('budi.mentor@test.com');
});

test('accessing mentor profile create page returns 404 for non mentor user role', function () {
    $admin = User::factory()->create([
        'role' => 'administrator',
        'must_change_password' => false,
    ]);

    $mahasiswaUser = User::factory()->create([
        'role' => 'mahasiswa',
        'must_change_password' => false,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.mentors.profile.create', $mahasiswaUser));

    $response->assertStatus(404);
});

test('accessing mentor profile create page returns 404 if mentor already has profile', function () {
    $admin = User::factory()->create([
        'role' => 'administrator',
        'must_change_password' => false,
    ]);

    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'must_change_password' => false,
    ]);

    Mentor::create([
        'user_id' => $mentorUser->id,
        'nip' => '198501012010011001',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.mentors.profile.create', $mentorUser));

    $response->assertStatus(404);
});

test('non administrator users cannot access mentor profile create page', function () {
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'must_change_password' => false,
    ]);

    $response = $this->actingAs($mentorUser)->get(route('admin.mentors.profile.create', $mentorUser));

    $response->assertStatus(403);
});

test('administrator can store mentor profile without photo', function () {
    $admin = User::factory()->create([
        'role' => 'administrator',
        'must_change_password' => false,
    ]);

    $mentorUser = User::factory()->create([
        'name' => 'Ahmad Mentor',
        'email' => 'ahmad.mentor@test.com',
        'role' => 'mentor',
        'must_change_password' => false,
    ]);

    $response = $this->actingAs($admin)->post(route('admin.mentors.profile.store', $mentorUser), [
        'nip' => '199501012020011005',
        'jabatan' => 'Panitera Muda',
        'bagian' => 'Kepaniteraan Hukum',
        'jenis_kelamin' => 'Laki-laki',
        'agama' => 'Islam',
        'no_hp' => '081299887766',
        'status' => 'active',
        'keterangan' => 'Mentor pembimbing lapangan.',
    ]);

    $response->assertRedirect(route('admin.mentors.index'));
    $response->assertSessionHas('success', 'Profil mentor berhasil disimpan.');

    $this->assertDatabaseHas('mentors', [
        'user_id' => $mentorUser->id,
        'nip' => '199501012020011005',
        'jabatan' => 'Panitera Muda',
        'bagian' => 'Kepaniteraan Hukum',
        'jenis_kelamin' => 'Laki-laki',
        'agama' => 'Islam',
        'no_hp' => '081299887766',
        'status' => 'active',
        'keterangan' => 'Mentor pembimbing lapangan.',
        'foto' => null,
    ]);

    // Check that admin.mentors.index now displays real data instead of Belum dilengkapi
    $indexResponse = $this->actingAs($admin)->get(route('admin.mentors.index'));
    $indexResponse->assertSee('Ahmad Mentor');
    $indexResponse->assertSee('199501012020011005');
    $indexResponse->assertSee('Panitera Muda');
});

test('administrator can store mentor profile with photo upload', function () {
    Storage::fake('public');

    $admin = User::factory()->create([
        'role' => 'administrator',
        'must_change_password' => false,
    ]);

    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'must_change_password' => false,
    ]);

    $file = UploadedFile::fake()->image('pasfoto.jpg', 400, 400);

    $response = $this->actingAs($admin)->post(route('admin.mentors.profile.store', $mentorUser), [
        'nip' => '199202022021022002',
        'jabatan' => 'Hakim',
        'bagian' => 'Kepaniteraan Permohonan',
        'jenis_kelamin' => 'Perempuan',
        'agama' => 'Islam',
        'no_hp' => '085544332211',
        'foto' => $file,
        'status' => 'active',
    ]);

    $response->assertRedirect(route('admin.mentors.index'));

    $mentor = Mentor::where('user_id', $mentorUser->id)->first();
    expect($mentor)->not->toBeNull();
    expect($mentor->foto)->not->toBeNull();

    Storage::disk('public')->assertExists($mentor->foto);
});

test('mentor profile store fails validation on invalid inputs', function () {
    $admin = User::factory()->create([
        'role' => 'administrator',
        'must_change_password' => false,
    ]);

    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'must_change_password' => false,
    ]);

    $invalidFile = UploadedFile::fake()->create('document.pdf', 500, 'application/pdf');

    $response = $this->actingAs($admin)->post(route('admin.mentors.profile.store', $mentorUser), [
        'jenis_kelamin' => 'InvalidGender',
        'agama' => 'InvalidAgama',
        'status' => 'InvalidStatus',
        'foto' => $invalidFile,
    ]);

    $response->assertSessionHasErrors(['jenis_kelamin', 'agama', 'status', 'foto']);
    $this->assertDatabaseMissing('mentors', ['user_id' => $mentorUser->id]);
});

test('mentor profile store returns 404 for non mentor user role or existing profile', function () {
    $admin = User::factory()->create([
        'role' => 'administrator',
        'must_change_password' => false,
    ]);

    $mahasiswaUser = User::factory()->create([
        'role' => 'mahasiswa',
        'must_change_password' => false,
    ]);

    $existingMentorUser = User::factory()->create([
        'role' => 'mentor',
        'must_change_password' => false,
    ]);
    Mentor::create(['user_id' => $existingMentorUser->id]);

    // Store for mahasiswa -> 404
    $this->actingAs($admin)->post(route('admin.mentors.profile.store', $mahasiswaUser), [
        'status' => 'active',
    ])->assertStatus(404);

    // Store for existing profile -> 404
    $this->actingAs($admin)->post(route('admin.mentors.profile.store', $existingMentorUser), [
        'status' => 'active',
    ])->assertStatus(404);
});

test('non administrator users cannot store mentor profile', function () {
    $mentorUser = User::factory()->create([
        'role' => 'mentor',
        'must_change_password' => false,
    ]);

    $response = $this->actingAs($mentorUser)->post(route('admin.mentors.profile.store', $mentorUser), [
        'status' => 'active',
    ]);

    $response->assertStatus(403);
});

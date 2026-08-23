<?php

use App\Services\SignatureService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

/*
|--------------------------------------------------------------------------
| Store Signature
|--------------------------------------------------------------------------
*/

test('signature service can store png signature from data url', function () {
    Storage::fake('public');

    /*
    |--------------------------------------------------------------------------
    | PNG 1x1 sederhana untuk testing
    |--------------------------------------------------------------------------
    */
    $pngBase64 =
        'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

    $dataUrl = 'data:image/png;base64,' . $pngBase64;

    $service = new SignatureService();

    $path = $service->store(
        $dataUrl,
        'absensi/paraf/mahasiswa'
    );

    expect($path)
        ->toStartWith('absensi/paraf/mahasiswa/');

    expect($path)
        ->toEndWith('.png');

    Storage::disk('public')
        ->assertExists($path);
});

/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

test('signature service rejects invalid format', function () {
    Storage::fake('public');

    $service = new SignatureService();

    expect(function () use ($service) {
        $service->store(
            'data:image/jpeg;base64,invalid',
            'absensi/paraf/mahasiswa'
        );
    })->toThrow(\InvalidArgumentException::class);
});

/*
|--------------------------------------------------------------------------
| Delete Signature
|--------------------------------------------------------------------------
*/

test('signature service can delete stored signature', function () {
    Storage::fake('public');

    $path = 'absensi/paraf/mahasiswa/test.png';

    Storage::disk('public')->put(
        $path,
        'test'
    );

    $service = new SignatureService();

    $service->delete($path);

    Storage::disk('public')
        ->assertMissing($path);
});
<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;

class SignatureService
{
    /**
     * Simpan hasil signature canvas ke storage public.
     *
     * @param string $dataUrl
     * @param string $directory
     * @return string
     */
    public function store(string $dataUrl, string $directory = 'absensi/paraf'): string
    {
        if (! str_starts_with($dataUrl, 'data:image/png;base64,')) {
            throw new InvalidArgumentException('Format paraf tidak valid.');
        }

        $base64 = substr(
            $dataUrl,
            strlen('data:image/png;base64,')
        );

        $base64 = str_replace(' ', '+', $base64);

        $binary = base64_decode($base64, true);

        if ($binary === false) {
            throw new InvalidArgumentException('Data paraf tidak dapat diproses.');
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi ukuran file
        |--------------------------------------------------------------------------
        |
        | Maksimal sekitar 1 MB.
        |
        */
        if (strlen($binary) > 1024 * 1024) {
            throw new InvalidArgumentException(
                'Ukuran paraf terlalu besar. Maksimal 1 MB.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi MIME menggunakan isi file
        |--------------------------------------------------------------------------
        */
        $tmpPath = tempnam(sys_get_temp_dir(), 'signature_');

        if ($tmpPath === false) {
            throw new InvalidArgumentException(
                'Gagal menyiapkan file paraf.'
            );
        }

        try {
            file_put_contents($tmpPath, $binary);

            $mimeType = mime_content_type($tmpPath);

            if ($mimeType !== 'image/png') {
                throw new InvalidArgumentException(
                    'Paraf harus berupa gambar PNG.'
                );
            }

            $fileName = 'paraf_' . Str::uuid() . '.png';

            $path = $directory . '/' . $fileName;

            Storage::disk('public')->put(
                $path,
                $binary
            );

            return $path;
        } finally {
            if (file_exists($tmpPath)) {
                unlink($tmpPath);
            }
        }
    }

    /**
     * Hapus file paraf.
     */
    public function delete(?string $path): void
    {
        if (
            $path &&
            Storage::disk('public')->exists($path)
        ) {
            Storage::disk('public')->delete($path);
        }
    }
}
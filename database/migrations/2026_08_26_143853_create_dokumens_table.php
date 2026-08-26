<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mahasiswa_id')
                ->constrained('mahasiswas')
                ->cascadeOnDelete();

            $table->foreignId('penempatan_id')
                ->nullable()
                ->constrained('penempatans')
                ->nullOnDelete();

            $table->enum('jenis_dokumen', [
                'ktm',
                'ktp',
                'cv',
                'surat_pengantar',
                'surat_pernyataan',
                'surat_penempatan',
                'surat_selesai_magang',
                'laporan_magang',
                'lampiran_laporan',
                'dokumen_pendukung',
                'dokumen_lainnya',
            ]);

            $table->string('nama_dokumen', 150);

            $table->string('nama_file', 255);

            /*
            |--------------------------------------------------------------------------
            | Path file dibuat private.
            |--------------------------------------------------------------------------
            */
            $table->string('path_file', 500);

            $table->string('mime_type', 100)->nullable();

            $table->unsignedBigInteger('ukuran_file')->nullable();

            $table->enum('status', [
                'uploaded',
                'verified',
                'revision',
            ])->default('uploaded');

            $table->text('catatan')->nullable();

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            $table->index([
                'mahasiswa_id',
                'jenis_dokumen',
            ]);

            $table->index([
                'penempatan_id',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};

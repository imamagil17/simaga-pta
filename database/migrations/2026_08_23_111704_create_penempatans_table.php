<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penempatans', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mahasiswa_id')
                ->constrained('mahasiswas')
                ->cascadeOnDelete();

            $table->foreignId('periode_magang_id')
                ->constrained('periode_magangs')
                ->cascadeOnDelete();

            $table->foreignId('mentor_id')
                ->constrained('mentors')
                ->cascadeOnDelete();

            $table->string('status')
                ->default('active');

            $table->date('tanggal_mulai')
                ->nullable();

            $table->date('tanggal_selesai')
                ->nullable();

            $table->text('keterangan')
                ->nullable();

            $table->timestamps();

            $table->unique([
                'mahasiswa_id',
                'periode_magang_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penempatans');
    }
};
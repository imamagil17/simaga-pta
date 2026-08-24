<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('tugas_id')
                ->constrained('tugas')
                ->cascadeOnDelete();

            $table->foreignId('mahasiswa_id')
                ->constrained('mahasiswas')
                ->cascadeOnDelete();

            $table->text('jawaban')->nullable();

            $table->string('file_jawaban')->nullable();

            $table->timestamp('dikumpulkan_at')->nullable();

            $table->enum('status', [
                'draft',
                'submitted',
                'reviewed',
                'revision',
            ])->default('draft');

            $table->decimal('nilai', 5, 2)->nullable();

            $table->text('catatan_mentor')->nullable();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            /*
            |----------------------------------------------------------------------
            | Satu mahasiswa hanya memiliki satu pengumpulan untuk satu tugas.
            |----------------------------------------------------------------------
            */
            $table->unique([
                'tugas_id',
                'mahasiswa_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};

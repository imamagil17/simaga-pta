<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('penempatan_id')
                ->constrained('penempatans')
                ->cascadeOnDelete();

            $table->string('judul', 150);

            $table->text('deskripsi');

            $table->dateTime('tanggal_mulai');

            $table->dateTime('tanggal_deadline');

            $table->enum('status', [
                'draft',
                'published',
                'closed',
            ])->default('draft');

            $table->string('file_tugas')->nullable();

            /*
            |----------------------------------------------------------------------
            | User yang membuat tugas.
            | Untuk tugas yang sebenarnya nanti adalah akun Mentor.
            |----------------------------------------------------------------------
            */
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->index([
                'penempatan_id',
                'status',
            ]);

            $table->index('tanggal_deadline');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};

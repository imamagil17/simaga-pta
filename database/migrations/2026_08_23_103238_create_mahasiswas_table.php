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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('nim')->unique();

            $table->string('perguruan_tinggi');

            $table->string('program_studi');

            $table->string('jenis_kelamin')->nullable();

            $table->string('agama')->nullable();

            $table->string('no_hp')->nullable();

            $table->text('alamat')->nullable();

            $table->string('foto')->nullable();

            $table->string('status')
                ->default('active');

            $table->text('keterangan')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
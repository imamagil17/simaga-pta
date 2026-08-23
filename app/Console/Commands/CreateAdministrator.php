<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdministrator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-administrator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat akun Administrator PTA pertama secara interaktif';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('====================================');
        $this->info('  BUAT AKUN ADMINISTRATOR PTA');
        $this->info('====================================');

        $name = $this->ask('Masukkan Nama Lengkap Administrator');
        while (empty(trim($name))) {
            $this->error('Nama tidak boleh kosong.');
            $name = $this->ask('Masukkan Nama Lengkap Administrator');
        }

        $email = $this->ask('Masukkan Alamat Email');
        $validator = Validator::make(['email' => $email], [
            'email' => ['required', 'email', 'unique:users,email'],
        ]);

        while ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            $email = $this->ask('Masukkan Alamat Email');
            $validator = Validator::make(['email' => $email], [
                'email' => ['required', 'email', 'unique:users,email'],
            ]);
        }

        $password = $this->secret('Masukkan Password (minimal 8 karakter)');
        while (strlen($password) < 8) {
            $this->error('Password minimal harus 8 karakter.');
            $password = $this->secret('Masukkan Password (minimal 8 karakter)');
        }

        $confirmPassword = $this->secret('Konfirmasi Password');
        while ($password !== $confirmPassword) {
            $this->error('Konfirmasi password tidak cocok.');
            $confirmPassword = $this->secret('Konfirmasi Password');
        }

        $user = User::create([
            'name' => trim($name),
            'email' => strtolower(trim($email)),
            'password' => Hash::make($password),
            'role' => 'administrator',
        ]);

        $this->newLine();
        $this->info("✓ Akun Administrator PTA berhasil dibuat!");
        $this->table(
            ['ID', 'Nama', 'Email', 'Role'],
            [[$user->id, $user->name, $user->email, $user->role]]
        );

        return Command::SUCCESS;
    }
}

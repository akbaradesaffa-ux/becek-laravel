<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $email = Str::lower(trim((string) config('becek.admin_email')));
        $configuredPassword = trim((string) config('becek.admin_password'));
        $existingAdmin = User::where('email', $email)->first();

        $payload = [
            'nama_lengkap' => 'Administrator BECEK',
            'email' => $email,
            'status_role' => 'Administrator',
        ];

        if (Schema::hasColumn('tb_user', 'username')) {
            $payload['username'] = $email;
        }

        if ($existingAdmin) {
            if ($configuredPassword !== '') {
                $payload['password'] = Hash::make($configuredPassword);
            }

            $existingAdmin->update($payload);
            $this->command?->info("Akun administrator diperbarui: {$email}");

            return;
        }

        $initialPassword = $configuredPassword !== ''
            ? $configuredPassword
            : Str::password(16);

        $payload['password'] = Hash::make($initialPassword);
        User::create($payload);

        $this->command?->info("Akun administrator dibuat: {$email}");

        if ($configuredPassword === '') {
            $this->command?->warn("Password administrator awal: {$initialPassword}");
            $this->command?->warn('Simpan password tersebut dan segera ganti setelah login.');
        }
    }
}

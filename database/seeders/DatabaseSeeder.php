<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@becek.local'],
            [
                'nama_lengkap' => 'Administrator BECEK',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'status_role' => 'Administrator',
            ]
        );
    }
}

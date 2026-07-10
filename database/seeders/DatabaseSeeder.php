<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $payload = [
            'nama_lengkap' => 'Administrator BECEK',
            'email' => 'admin@becek.local',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'status_role' => 'Administrator',
        ];

        if (Schema::hasColumn('tb_user', 'username')) {
            $payload['username'] = 'admin@becek.local';
        }

        User::firstOrCreate(
            ['email' => 'admin@becek.local'],
            $payload
        );
    }
}
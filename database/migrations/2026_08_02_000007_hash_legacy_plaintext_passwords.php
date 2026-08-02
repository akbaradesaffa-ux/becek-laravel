<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tb_user') || ! Schema::hasColumn('tb_user', 'password')) {
            return;
        }

        DB::table('tb_user')
            ->select('id', 'password')
            ->whereNotNull('password')
            ->orderBy('id')
            ->chunkById(100, function ($users): void {
                foreach ($users as $user) {
                    $password = (string) $user->password;

                    if ($password === '' || password_get_info($password)['algoName'] !== 'unknown') {
                        continue;
                    }

                    DB::table('tb_user')
                        ->where('id', $user->id)
                        ->update(['password' => Hash::make($password)]);
                }
            });
    }

    public function down(): void
    {
        // Password hash tidak dapat dan tidak boleh dikembalikan menjadi plaintext.
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tb_user')) {
            return;
        }

        if (! Schema::hasColumn('tb_user', 'email')) {
            Schema::table('tb_user', function (Blueprint $table) {
                $table->string('email', 150)->nullable()->after('nama_lengkap');
            });
        }

        // Backfill akun lama yang sebelumnya memakai username.
        if (Schema::hasColumn('tb_user', 'username')) {
            $users = DB::table('tb_user')->select('id', 'username', 'email')->get();

            foreach ($users as $user) {
                if (! empty($user->email)) {
                    continue;
                }

                $username = trim((string) $user->username);
                $email = filter_var($username, FILTER_VALIDATE_EMAIL)
                    ? strtolower($username)
                    : 'user' . $user->id . '@becek.local';

                DB::table('tb_user')->where('id', $user->id)->update(['email' => $email]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tb_user') && Schema::hasColumn('tb_user', 'email')) {
            Schema::table('tb_user', function (Blueprint $table) {
                $table->dropColumn('email');
            });
        }
    }
};

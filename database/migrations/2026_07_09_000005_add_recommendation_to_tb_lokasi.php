<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tb_lokasi')) {
            return;
        }

        Schema::table('tb_lokasi', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_lokasi', 'is_recommended')) {
                $table->boolean('is_recommended')->default(false)->after('jam_tutup');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('tb_lokasi')) {
            return;
        }

        Schema::table('tb_lokasi', function (Blueprint $table) {
            if (Schema::hasColumn('tb_lokasi', 'is_recommended')) {
                $table->dropColumn('is_recommended');
            }
        });
    }
};

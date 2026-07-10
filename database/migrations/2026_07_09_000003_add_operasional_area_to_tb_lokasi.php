<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_lokasi', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_lokasi', 'area')) {
                $table->string('area', 120)->nullable();
            }

            if (!Schema::hasColumn('tb_lokasi', 'hari_operasional')) {
                $table->string('hari_operasional', 120)->nullable();
            }

            if (!Schema::hasColumn('tb_lokasi', 'jam_buka')) {
                $table->time('jam_buka')->nullable();
            }

            if (!Schema::hasColumn('tb_lokasi', 'jam_tutup')) {
                $table->time('jam_tutup')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tb_lokasi', function (Blueprint $table) {
            foreach (['area', 'hari_operasional', 'jam_buka', 'jam_tutup'] as $column) {
                if (Schema::hasColumn('tb_lokasi', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tb_lokasi', function (Blueprint $table) {
            if (!Schema::hasColumn('tb_lokasi', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('link_google_maps');
            }

            if (!Schema::hasColumn('tb_lokasi', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tb_lokasi', function (Blueprint $table) {
            if (Schema::hasColumn('tb_lokasi', 'longitude')) {
                $table->dropColumn('longitude');
            }

            if (Schema::hasColumn('tb_lokasi', 'latitude')) {
                $table->dropColumn('latitude');
            }
        });
    }
};

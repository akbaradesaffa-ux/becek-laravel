<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tb_jadwal_operasional')) {
            return;
        }

        Schema::create('tb_jadwal_operasional', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lokasi_id')->constrained('tb_lokasi')->cascadeOnDelete();
            $table->string('hari', 12);
            $table->unsignedTinyInteger('urutan');
            $table->boolean('is_buka')->default(true);
            $table->boolean('is_24_jam')->default(false);
            $table->time('jam_buka')->nullable();
            $table->time('jam_tutup')->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();

            $table->unique(['lokasi_id', 'hari']);
            $table->index(['lokasi_id', 'urutan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_jadwal_operasional');
    }
};

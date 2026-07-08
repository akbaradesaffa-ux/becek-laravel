<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_lokasi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_lokasi', 10)->unique();
            $table->string('nama', 150);
            $table->enum('kategori', ['Cafe', 'Warkop']);
            $table->string('rentang_harga', 100);
            $table->text('link_google_maps');
            $table->string('jalur_foto', 255);
            $table->timestamp('dibuat_pada')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_lokasi');
    }
};
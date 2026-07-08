<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_lokasi_fasilitas', function (Blueprint $table) {
            $table->foreignId('lokasi_id')->constrained('tb_lokasi')->onDelete('cascade');
            $table->foreignId('fasilitas_id')->constrained('tb_fasilitas')->onUpdate('cascade');
            $table->primary(['lokasi_id', 'fasilitas_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_lokasi_fasilitas');
    }
};
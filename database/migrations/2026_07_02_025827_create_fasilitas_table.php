<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_fasilitas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_fasilitas', 50)->unique();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_fasilitas');
    }
};
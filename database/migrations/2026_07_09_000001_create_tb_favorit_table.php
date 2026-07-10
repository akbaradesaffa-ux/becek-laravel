<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tb_favorit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('tb_user')->onDelete('cascade');
            $table->foreignId('lokasi_id')->constrained('tb_lokasi')->onDelete('cascade');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->unique(['user_id', 'lokasi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_favorit');
    }
};

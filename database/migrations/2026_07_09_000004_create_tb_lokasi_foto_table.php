<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tb_lokasi_foto')) {
            return;
        }

        Schema::create('tb_lokasi_foto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lokasi_id')->constrained('tb_lokasi')->onDelete('cascade');
            $table->string('jalur_foto', 255);
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamp('dibuat_pada')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tb_lokasi_foto');
    }
};

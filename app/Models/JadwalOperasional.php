<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalOperasional extends Model
{
    protected $table = 'tb_jadwal_operasional';
    public $timestamps = false;

    protected $fillable = [
        'lokasi_id',
        'hari',
        'urutan',
        'is_buka',
        'is_24_jam',
        'jam_buka',
        'jam_tutup',
    ];

    protected $casts = [
        'is_buka' => 'boolean',
        'is_24_jam' => 'boolean',
    ];

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }
}

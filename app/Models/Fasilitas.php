<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    protected $table = 'tb_fasilitas';
    public $timestamps = false;

    protected $fillable = ['nama_fasilitas'];

    public function lokasi()
    {
        return $this->belongsToMany(Lokasi::class, 'tb_lokasi_fasilitas', 'fasilitas_id', 'lokasi_id');
    }
}
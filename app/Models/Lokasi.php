<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $table = 'tb_lokasi';
    public $timestamps = false;

    protected $fillable = [
        'kode_lokasi',
        'nama',
        'kategori',
        'rentang_harga',
        'link_google_maps',
        'jalur_foto',
    ];

    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'tb_lokasi_fasilitas', 'lokasi_id', 'fasilitas_id');
    }

    public function getFasilitasStringAttribute(): string
    {
        return $this->fasilitas->pluck('nama_fasilitas')->implode(', ');
    }
}
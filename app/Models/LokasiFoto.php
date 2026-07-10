<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiFoto extends Model
{
    protected $table = 'tb_lokasi_foto';
    public $timestamps = false;

    protected $fillable = [
        'lokasi_id',
        'jalur_foto',
        'urutan',
    ];

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }
}

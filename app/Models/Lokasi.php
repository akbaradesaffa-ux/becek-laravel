<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $table = 'tb_lokasi';
    public $timestamps = false;

    protected $fillable = [
        'kode_lokasi',
        'nama',
        'kategori',
        'area',
        'rentang_harga',
        'link_google_maps',
        'jalur_foto',
        'hari_operasional',
        'jam_buka',
        'jam_tutup',
        'is_recommended',
    ];

    protected $casts = [
        'is_recommended' => 'boolean',
    ];

    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'tb_lokasi_fasilitas', 'lokasi_id', 'fasilitas_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'lokasi_id');
    }

    public function fotos()
    {
        return $this->hasMany(LokasiFoto::class, 'lokasi_id')->orderBy('urutan')->orderBy('id');
    }

    public function getFasilitasStringAttribute(): string
    {
        return $this->fasilitas->pluck('nama_fasilitas')->implode(', ');
    }

    public function getJamOperasionalLabelAttribute(): string
    {
        if (!$this->jam_buka || !$this->jam_tutup) {
            return 'Jam belum diatur';
        }

        return substr((string) $this->jam_buka, 0, 5) . ' - ' . substr((string) $this->jam_tutup, 0, 5);
    }

    public function getStatusOperasionalAttribute(): string
    {
        if (!$this->jam_buka || !$this->jam_tutup) {
            return 'Jam belum diatur';
        }

        $now = Carbon::now('Asia/Jakarta');
        $open = Carbon::createFromFormat('H:i:s', strlen($this->jam_buka) === 5 ? $this->jam_buka . ':00' : $this->jam_buka, 'Asia/Jakarta')
            ->setDate($now->year, $now->month, $now->day);
        $close = Carbon::createFromFormat('H:i:s', strlen($this->jam_tutup) === 5 ? $this->jam_tutup . ':00' : $this->jam_tutup, 'Asia/Jakarta')
            ->setDate($now->year, $now->month, $now->day);

        if ($close->lessThanOrEqualTo($open)) {
            return ($now->greaterThanOrEqualTo($open) || $now->lessThanOrEqualTo($close)) ? 'Buka sekarang' : 'Tutup';
        }

        return $now->betweenIncluded($open, $close) ? 'Buka sekarang' : 'Tutup';
    }
}

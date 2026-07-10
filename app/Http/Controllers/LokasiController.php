<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Favorite;
use App\Models\Lokasi;
use Illuminate\Support\Facades\Schema;

class LokasiController extends Controller
{
    public function explore()
    {
        $lokasi = Lokasi::with('fasilitas')->orderBy('id', 'desc')->get();
        $categories = $lokasi->pluck('kategori')->filter()->unique()->values();
        $areas = $lokasi->pluck('area')->filter()->unique()->sort()->values();
        $fasilitasMaster = Fasilitas::orderBy('nama_fasilitas')->get();
        $namaLengkap = session('nama_lengkap', 'Pengguna');

        return view('user.explore', [
            'lokasi' => $lokasi,
            'categories' => $categories,
            'areas' => $areas,
            'fasilitasMaster' => $fasilitasMaster,
            'favoriteIds' => $this->favoriteIds(),
            'namaLengkap' => $namaLengkap,
            'activePage' => 'explore',
        ]);
    }

    public function detail($id)
    {
        $lokasi = Lokasi::with(['fasilitas', 'fotos'])->find($id);

        if (!$lokasi) {
            return redirect()->route('dashboard');
        }

        return view('user.detail', [
            'lokasi' => $lokasi,
            'fasilitasStr' => $lokasi->fasilitas_string,
            'favoriteIds' => $this->favoriteIds(),
            'namaLengkap' => session('nama_lengkap', 'Pengguna'),
        ]);
    }

    private function favoriteIds(): array
    {
        if (!Schema::hasTable('tb_favorit')) {
            return [];
        }

        return Favorite::where('user_id', session('id_user'))
            ->pluck('lokasi_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }
}

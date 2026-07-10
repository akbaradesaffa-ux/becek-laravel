<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Lokasi;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        if (!session('id_user')) {
            return redirect()->route('login');
        }

        $recommended = Lokasi::with('fasilitas')
            ->where('is_recommended', true)
            ->orderBy('id', 'desc')
            ->get();

        $lokasi = $recommended->isNotEmpty()
            ? $recommended
            : Lokasi::with('fasilitas')->orderBy('id', 'desc')->limit(6)->get();

        $namaLengkap = session('nama_lengkap', 'Pengguna');

        return view('user.dashboard', [
            'lokasi'      => $lokasi,
            'totalCafe'   => Lokasi::where('kategori', 'Cafe')->count(),
            'totalWarkop' => Lokasi::where('kategori', 'Warkop')->count(),
            'favoriteIds' => $this->favoriteIds(),
            'namaLengkap' => $namaLengkap,
            'hasRecommendedPlaces' => $recommended->isNotEmpty(),
            'activePage'  => 'dashboard',
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

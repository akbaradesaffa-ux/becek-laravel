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

        $hasRecommendedPlaces = Lokasi::where('is_recommended', true)->exists();

        $lokasiQuery = Lokasi::with(['fasilitas', 'jadwalOperasional'])
            ->orderByDesc('id');

        if ($hasRecommendedPlaces) {
            $lokasiQuery->where('is_recommended', true);
        }

        $lokasi = $lokasiQuery->paginate(5)->withQueryString();

        $namaLengkap = session('nama_lengkap', 'Pengguna');

        return view('user.dashboard', [
            'lokasi'      => $lokasi,
            'totalCafe'   => Lokasi::where('kategori', 'Cafe')->count(),
            'totalWarkop' => Lokasi::where('kategori', 'Warkop')->count(),
            'favoriteIds' => $this->favoriteIds(),
            'namaLengkap' => $namaLengkap,
            'hasRecommendedPlaces' => $hasRecommendedPlaces,
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

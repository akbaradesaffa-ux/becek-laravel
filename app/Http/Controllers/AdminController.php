<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Favorite;
use App\Models\Lokasi;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    public function index()
    {
        $countLokasi = Lokasi::count();
        $countUser = User::count();
        $countCafe = Lokasi::where('kategori', 'Cafe')->count();
        $countWarkop = Lokasi::where('kategori', 'Warkop')->count();
        $countFasilitas = Fasilitas::count();
        $countFavorit = Schema::hasTable('tb_favorit') ? Favorite::count() : 0;

        $latestLocations = Lokasi::with(['fasilitas', 'jadwalOperasional'])
            ->orderByDesc('id')
            ->paginate(5, ['*'], 'latest_page')
            ->withQueryString();

        $topLocations = Schema::hasTable('tb_favorit')
            ? Lokasi::with('jadwalOperasional')
                ->withCount('favorites')
                ->orderByDesc('favorites_count')
                ->orderByDesc('id')
                ->paginate(5, ['*'], 'top_page')
                ->withQueryString()
            : collect();

        $namaLogin = session('nama_lengkap', 'Admin');
        $initial = strtoupper(substr($namaLogin, 0, 1));

        return view('admin.dashboard', [
            'countLokasi' => $countLokasi,
            'countUser' => $countUser,
            'countCafe' => $countCafe,
            'countWarkop' => $countWarkop,
            'countFasilitas' => $countFasilitas,
            'countFavorit' => $countFavorit,
            'latestLocations' => $latestLocations,
            'topLocations' => $topLocations,
            'namaLogin' => $namaLogin,
            'initial' => $initial,
            'activePage' => 'admin_dashboard',
        ]);
    }
}

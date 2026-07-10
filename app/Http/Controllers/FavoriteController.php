<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Lokasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;

class FavoriteController extends Controller
{
    public function index()
    {
        if (!session('id_user')) {
            return redirect()->route('login');
        }

        $namaLengkap = session('nama_lengkap', 'Pengguna');
        $favoriteIds = $this->favoriteIds();

        $lokasi = Lokasi::with('fasilitas')
            ->whereIn('id', $favoriteIds)
            ->orderBy('id', 'desc')
            ->get();

        return view('user.favorites', [
            'lokasi'      => $lokasi,
            'favoriteIds' => $favoriteIds,
            'namaLengkap' => $namaLengkap,
            'activePage'  => 'favorites',
        ]);
    }

    public function toggle(int $id): JsonResponse
    {
        if (!session('id_user')) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi login habis. Silakan login ulang.',
            ], 401);
        }

        if (!Schema::hasTable('tb_favorit')) {
            return response()->json([
                'success' => false,
                'message' => 'Tabel favorit belum tersedia. Jalankan migration terlebih dahulu.',
            ], 500);
        }

        $lokasi = Lokasi::find($id);
        if (!$lokasi) {
            return response()->json([
                'success' => false,
                'message' => 'Tempat tidak ditemukan.',
            ], 404);
        }

        $userId = (int) session('id_user');
        $favorite = Favorite::where('user_id', $userId)
            ->where('lokasi_id', $id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'success' => true,
                'favorited' => false,
                'message' => 'Tempat dihapus dari favorit.',
            ]);
        }

        Favorite::create([
            'user_id' => $userId,
            'lokasi_id' => $id,
        ]);

        return response()->json([
            'success' => true,
            'favorited' => true,
            'message' => 'Tempat ditambahkan ke favorit.',
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

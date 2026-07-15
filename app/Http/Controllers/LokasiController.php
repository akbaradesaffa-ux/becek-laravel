<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Favorite;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class LokasiController extends Controller
{
    public function explore(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $selectedCategory = trim((string) $request->query('kategori', ''));
        $selectedArea = trim((string) $request->query('area', ''));
        $selectedFacilities = array_values(array_filter(array_map(
            static fn ($item) => trim((string) $item),
            (array) $request->query('fasilitas', [])
        )));

        $lokasiQuery = Lokasi::with(['fasilitas', 'jadwalOperasional']);

        if ($search !== '') {
            $lokasiQuery->where(function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%")
                    ->orWhere('area', 'like', "%{$search}%")
                    ->orWhere('rentang_harga', 'like', "%{$search}%")
                    ->orWhereHas('fasilitas', function ($facilityQuery) use ($search) {
                        $facilityQuery->where('nama_fasilitas', 'like', "%{$search}%");
                    });
            });
        }

        if ($selectedCategory !== '') {
            $lokasiQuery->where('kategori', $selectedCategory);
        }

        if ($selectedArea !== '') {
            $lokasiQuery->where('area', $selectedArea);
        }

        foreach ($selectedFacilities as $facility) {
            $lokasiQuery->whereHas('fasilitas', function ($query) use ($facility) {
                $query->where('nama_fasilitas', $facility);
            });
        }

        $lokasi = $lokasiQuery
            ->orderByDesc('is_recommended')
            ->orderByDesc('id')
            ->paginate(5)
            ->withQueryString();

        $categories = Lokasi::query()
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        $areas = Lokasi::query()
            ->whereNotNull('area')
            ->where('area', '!=', '')
            ->distinct()
            ->orderBy('area')
            ->pluck('area');

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
            'search' => $search,
            'selectedCategory' => $selectedCategory,
            'selectedArea' => $selectedArea,
            'selectedFacilities' => $selectedFacilities,
        ]);
    }

    public function detail($id)
    {
        $lokasi = Lokasi::with(['fasilitas', 'fotos', 'jadwalOperasional'])->find($id);

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

<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Favorite;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

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

    public function nearby(Request $request)
    {
        $validated = $request->validate([
            'latitude' => ['nullable', 'numeric', 'between:-90,90', 'required_with:longitude'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180', 'required_with:latitude'],
            'radius' => ['nullable', Rule::in(['1', '3', '5', '10', '25'])],
            'kategori' => ['nullable', Rule::in(['Cafe', 'Warkop'])],
        ]);

        $latitude = array_key_exists('latitude', $validated) ? (float) $validated['latitude'] : null;
        $longitude = array_key_exists('longitude', $validated) ? (float) $validated['longitude'] : null;
        $radius = (int) ($validated['radius'] ?? 5);
        $selectedCategory = (string) ($validated['kategori'] ?? '');
        $hasUserLocation = $latitude !== null && $longitude !== null;
        $perPage = 9;
        $page = max(1, (int) $request->query('page', 1));

        $items = collect();

        if ($hasUserLocation) {
            $query = Lokasi::with(['fasilitas', 'jadwalOperasional'])
                ->whereNotNull('latitude')
                ->whereNotNull('longitude');

            if ($selectedCategory !== '') {
                $query->where('kategori', $selectedCategory);
            }

            $items = $query->get()
                ->map(function (Lokasi $item) use ($latitude, $longitude) {
                    $distance = $this->haversineDistance(
                        $latitude,
                        $longitude,
                        (float) $item->latitude,
                        (float) $item->longitude
                    );

                    $item->setAttribute('distance_km', round($distance, 2));

                    return $item;
                })
                ->filter(fn (Lokasi $item) => $item->distance_km <= $radius)
                ->sortBy('distance_km')
                ->values();
        }

        $lokasi = new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('user.nearby', [
            'lokasi' => $lokasi,
            'favoriteIds' => $this->favoriteIds(),
            'namaLengkap' => session('nama_lengkap', 'Pengguna'),
            'activePage' => 'nearby',
            'latitude' => $latitude,
            'longitude' => $longitude,
            'radius' => $radius,
            'selectedCategory' => $selectedCategory,
            'hasUserLocation' => $hasUserLocation,
            'locationsWithCoordinates' => Lokasi::whereNotNull('latitude')->whereNotNull('longitude')->count(),
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

    private function haversineDistance(
        float $latitudeFrom,
        float $longitudeFrom,
        float $latitudeTo,
        float $longitudeTo
    ): float {
        $earthRadiusKm = 6371.0088;
        $latitudeDelta = deg2rad($latitudeTo - $latitudeFrom);
        $longitudeDelta = deg2rad($longitudeTo - $longitudeFrom);

        $a = sin($latitudeDelta / 2) ** 2
            + cos(deg2rad($latitudeFrom))
            * cos(deg2rad($latitudeTo))
            * sin($longitudeDelta / 2) ** 2;

        return $earthRadiusKm * 2 * asin(min(1, sqrt($a)));
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

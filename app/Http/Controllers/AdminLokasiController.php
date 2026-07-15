<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Lokasi;
use App\Models\LokasiFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class AdminLokasiController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $lokasiQuery = Lokasi::with(['fasilitas', 'fotos', 'jadwalOperasional'])
            ->withCount('favorites')
            ->orderByDesc('is_recommended')
            ->orderByDesc('id');

        if ($search !== '') {
            $lokasiQuery->where(function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode_lokasi', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%")
                    ->orWhere('area', 'like', "%{$search}%")
                    ->orWhere('rentang_harga', 'like', "%{$search}%")
                    ->orWhere('hari_operasional', 'like', "%{$search}%")
                    ->orWhereHas('jadwalOperasional', function ($scheduleQuery) use ($search) {
                        $scheduleQuery->where('hari', 'like', "%{$search}%")
                            ->orWhere('jam_buka', 'like', "%{$search}%")
                            ->orWhere('jam_tutup', 'like', "%{$search}%");
                    })
                    ->orWhereHas('fasilitas', function ($facilityQuery) use ($search) {
                        $facilityQuery->where('nama_fasilitas', 'like', "%{$search}%");
                    });
            });
        }

        $lokasiList = $lokasiQuery
            ->paginate(5, ['*'], 'lokasi_page')
            ->withQueryString();

        $fasilitasList = Fasilitas::orderBy('id', 'desc')
            ->paginate(5, ['*'], 'fasilitas_page')
            ->withQueryString();
        $fasilitasCheckbox = Fasilitas::orderBy('nama_fasilitas')->get();
        $namaLogin = session('nama_lengkap', 'Admin');

        return view('admin.lokasi', [
            'lokasiList' => $lokasiList,
            'fasilitasList' => $fasilitasList,
            'fasilitasCheckbox' => $fasilitasCheckbox,
            'namaLogin' => $namaLogin,
            'initial' => strtoupper(substr($namaLogin, 0, 1)),
            'status' => $request->query('status'),
            'search' => $search,
            'activePage' => 'admin_lokasi',
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request, true);
        $namaFoto = $this->storeUploadedFile($request->file('foto'));

        $lokasi = Lokasi::create([
            'kode_lokasi' => $this->generateUniqueCode(),
            'nama' => $data['nama'],
            'kategori' => $data['kategori'],
            'area' => $data['area'] ?? null,
            'rentang_harga' => $data['harga'],
            'link_google_maps' => $data['link_maps'],
            'jalur_foto' => $namaFoto,
            'hari_operasional' => 'Jadwal harian',
            'jam_buka' => null,
            'jam_tutup' => null,
            'is_recommended' => $request->boolean('is_recommended'),
        ]);

        $this->syncJadwalOperasional($lokasi, $data['jadwal']);
        $this->syncFasilitas($lokasi, $request);
        $this->storeExtraPhotos($lokasi, $request);

        return redirect()->route('admin.lokasi')->with('success', 'Lokasi baru berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $lokasi = Lokasi::with(['fotos', 'jadwalOperasional'])->findOrFail($id);
        $data = $this->validatedData($request, false);

        $payload = [
            'nama' => $data['nama'],
            'kategori' => $data['kategori'],
            'area' => $data['area'] ?? null,
            'rentang_harga' => $data['harga'],
            'link_google_maps' => $data['link_maps'],
            'hari_operasional' => 'Jadwal harian',
            'jam_buka' => null,
            'jam_tutup' => null,
            'is_recommended' => $request->boolean('is_recommended'),
        ];

        if ($request->hasFile('foto')) {
            $this->deleteUploadFile($lokasi->jalur_foto);
            $payload['jalur_foto'] = $this->storeUploadedFile($request->file('foto'));
        }

        $lokasi->update($payload);
        $this->syncJadwalOperasional($lokasi, $data['jadwal']);
        $this->syncFasilitas($lokasi, $request);
        $this->storeExtraPhotos($lokasi, $request);

        return redirect()->route('admin.lokasi')->with('success', 'Data lokasi berhasil diperbarui.');
    }

    public function toggleRecommendation(int $id)
    {
        $lokasi = Lokasi::findOrFail($id);
        $lokasi->update([
            'is_recommended' => !$lokasi->is_recommended,
        ]);

        $message = $lokasi->is_recommended
            ? 'Lokasi ditandai sebagai rekomendasi.'
            : 'Lokasi dihapus dari rekomendasi.';

        return redirect()->back()->with('success', $message);
    }

    public function destroy($id)
    {
        $lokasi = Lokasi::with('fotos')->find($id);

        if ($lokasi) {
            $this->deleteUploadFile($lokasi->jalur_foto);

            foreach ($lokasi->fotos as $foto) {
                $this->deleteUploadFile($foto->jalur_foto);
            }

            $lokasi->delete();
        }

        return redirect()->route('admin.lokasi')->with('success', 'Lokasi berhasil dihapus.');
    }

    public function deleteFoto(int $id)
    {
        $foto = LokasiFoto::findOrFail($id);
        $this->deleteUploadFile($foto->jalur_foto);
        $foto->delete();

        return redirect()->route('admin.lokasi')->with('success', 'Foto tambahan berhasil dihapus.');
    }

    public function storeFasilitas(Request $request)
    {
        $nama = trim($request->input('nama_fasilitas_baru', ''));

        if (empty($nama)) {
            return redirect()->route('admin.lokasi');
        }

        if (Fasilitas::where('nama_fasilitas', $nama)->exists()) {
            return redirect()->route('admin.lokasi', ['status' => 'exists']);
        }

        Fasilitas::create(['nama_fasilitas' => $nama]);

        return redirect()->route('admin.lokasi', ['status' => 'success']);
    }

    public function deleteFasilitas($id)
    {
        Fasilitas::destroy($id);

        return redirect()->route('admin.lokasi')->with('success', 'Fasilitas berhasil dihapus.');
    }

    private function validatedData(Request $request, bool $isCreate): array
    {
        $rules = [
            'nama' => 'required|string|max:150',
            'kategori' => ['required', Rule::in(['Cafe', 'Warkop'])],
            'area' => 'nullable|string|max:120',
            'harga' => 'required|string|max:100',
            'jadwal' => 'required|array',
            'is_recommended' => 'nullable|boolean',
            'link_maps' => 'required|url',
            'foto' => ($isCreate ? 'required' : 'nullable') . '|image|max:4096',
            'foto_tambahan.*' => 'nullable|image|max:4096',
            'fasilitas_ids' => 'nullable|array',
            'fasilitas_ids.*' => 'integer|exists:tb_fasilitas,id',
        ];

        foreach (Lokasi::HARI_OPERASIONAL as $dayKey => $dayLabel) {
            $rules["jadwal.{$dayKey}.status"] = ['required', Rule::in(['buka', '24_jam', 'tutup'])];
            $rules["jadwal.{$dayKey}.jam_buka"] = [
                Rule::requiredIf(fn () => $request->input("jadwal.{$dayKey}.status") === 'buka'),
                'nullable',
                'date_format:H:i',
            ];
            $rules["jadwal.{$dayKey}.jam_tutup"] = [
                Rule::requiredIf(fn () => $request->input("jadwal.{$dayKey}.status") === 'buka'),
                'nullable',
                'date_format:H:i',
            ];
        }

        return $request->validate($rules, [
            'jadwal.*.jam_buka.required' => 'Jam buka wajib diisi untuk hari yang berstatus buka.',
            'jadwal.*.jam_tutup.required' => 'Jam tutup wajib diisi untuk hari yang berstatus buka.',
        ]);
    }

    private function syncJadwalOperasional(Lokasi $lokasi, array $jadwal): void
    {
        foreach (array_keys(Lokasi::HARI_OPERASIONAL) as $index => $dayKey) {
            $daySchedule = $jadwal[$dayKey] ?? ['status' => 'tutup'];
            $status = $daySchedule['status'] ?? 'tutup';

            $lokasi->jadwalOperasional()->updateOrCreate(
                ['hari' => $dayKey],
                [
                    'urutan' => $index + 1,
                    'is_buka' => $status !== 'tutup',
                    'is_24_jam' => $status === '24_jam',
                    'jam_buka' => $status === 'buka' ? ($daySchedule['jam_buka'] ?? null) : null,
                    'jam_tutup' => $status === 'buka' ? ($daySchedule['jam_tutup'] ?? null) : null,
                ]
            );
        }
    }

    private function syncFasilitas(Lokasi $lokasi, Request $request): void
    {
        $lokasi->fasilitas()->sync($request->input('fasilitas_ids', []));
    }

    private function storeUploadedFile($file): string
    {
        $uploadPath = public_path('uploads');

        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }

        $safeName = preg_replace('/[^A-Za-z0-9_.-]/', '_', $file->getClientOriginalName());
        $fileName = time() . '_' . uniqid() . '_' . $safeName;
        $file->move($uploadPath, $fileName);

        return $fileName;
    }

    private function storeExtraPhotos(Lokasi $lokasi, Request $request): void
    {
        if (!$request->hasFile('foto_tambahan')) {
            return;
        }

        $lastOrder = (int) $lokasi->fotos()->max('urutan');

        foreach ($request->file('foto_tambahan') as $file) {
            if (!$file) {
                continue;
            }

            $lastOrder++;
            LokasiFoto::create([
                'lokasi_id' => $lokasi->id,
                'jalur_foto' => $this->storeUploadedFile($file),
                'urutan' => $lastOrder,
            ]);
        }
    }

    private function deleteUploadFile(?string $fileName): void
    {
        if (!$fileName) {
            return;
        }

        $filePath = public_path('uploads/' . $fileName);

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    private function generateUniqueCode(): string
    {
        do {
            $kode = '#' . random_int(100, 999);
        } while (Lokasi::where('kode_lokasi', $kode)->exists());

        return $kode;
    }
}

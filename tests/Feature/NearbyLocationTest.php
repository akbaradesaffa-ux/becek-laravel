<?php

namespace Tests\Feature;

use App\Models\Lokasi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NearbyLocationTest extends TestCase
{
    use RefreshDatabase;

    public function test_nearby_page_requires_an_authenticated_session(): void
    {
        $this->get('/terdekat')->assertRedirect(route('login'));
    }

    public function test_locations_are_sorted_by_distance_and_filtered_by_radius(): void
    {
        Lokasi::create([
            'kode_lokasi' => 'LKS001',
            'nama' => 'Kopi Paling Dekat',
            'kategori' => 'Cafe',
            'area' => 'Bekasi',
            'rentang_harga' => 'Rp10.000 - Rp30.000',
            'link_google_maps' => 'https://maps.google.com/?q=-6.2005,106.8166',
            'latitude' => -6.2005,
            'longitude' => 106.8166,
            'jalur_foto' => 'dekat.jpg',
        ]);

        Lokasi::create([
            'kode_lokasi' => 'LKS002',
            'nama' => 'Warkop Lebih Jauh',
            'kategori' => 'Warkop',
            'area' => 'Bekasi',
            'rentang_harga' => 'Rp5.000 - Rp20.000',
            'link_google_maps' => 'https://maps.google.com/?q=-6.2200,106.8166',
            'latitude' => -6.2200,
            'longitude' => 106.8166,
            'jalur_foto' => 'jauh.jpg',
        ]);

        Lokasi::create([
            'kode_lokasi' => 'LKS003',
            'nama' => 'Di Luar Radius',
            'kategori' => 'Cafe',
            'area' => 'Bogor',
            'rentang_harga' => 'Rp10.000 - Rp30.000',
            'link_google_maps' => 'https://maps.google.com/?q=-6.7000,106.8166',
            'latitude' => -6.7000,
            'longitude' => 106.8166,
            'jalur_foto' => 'luar.jpg',
        ]);

        $response = $this->withSession([
            'id_user' => 99,
            'role' => 'User',
            'nama_lengkap' => 'Pengguna Test',
        ])->get('/terdekat?latitude=-6.2000&longitude=106.8166&radius=5');

        $response
            ->assertOk()
            ->assertSeeInOrder(['Kopi Paling Dekat', 'Warkop Lebih Jauh'])
            ->assertDontSee('Di Luar Radius')
            ->assertSee('Buka Rute');
    }

    public function test_category_filter_only_returns_the_selected_category(): void
    {
        Lokasi::create([
            'kode_lokasi' => 'LKS004',
            'nama' => 'Cafe Pilihan',
            'kategori' => 'Cafe',
            'area' => 'Bekasi',
            'rentang_harga' => 'Rp10.000 - Rp30.000',
            'link_google_maps' => 'https://maps.google.com/?q=-6.2005,106.8166',
            'latitude' => -6.2005,
            'longitude' => 106.8166,
            'jalur_foto' => 'cafe.jpg',
        ]);

        Lokasi::create([
            'kode_lokasi' => 'LKS005',
            'nama' => 'Warkop Pilihan',
            'kategori' => 'Warkop',
            'area' => 'Bekasi',
            'rentang_harga' => 'Rp5.000 - Rp20.000',
            'link_google_maps' => 'https://maps.google.com/?q=-6.2006,106.8166',
            'latitude' => -6.2006,
            'longitude' => 106.8166,
            'jalur_foto' => 'warkop.jpg',
        ]);

        $this->withSession([
            'id_user' => 99,
            'role' => 'User',
        ])->get('/terdekat?latitude=-6.2000&longitude=106.8166&radius=5&kategori=Cafe')
            ->assertOk()
            ->assertSee('Cafe Pilihan')
            ->assertDontSee('Warkop Pilihan');
    }
}

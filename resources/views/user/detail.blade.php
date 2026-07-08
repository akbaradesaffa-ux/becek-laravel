<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Tempat | BECEK</title>
    <link rel="stylesheet" href="{{ asset('css/stylepage4.css') }}">
</head>
<body>
    <div class="detail-container">
        <a href="{{ route('dashboard') }}" class="btn-back">← Kembali ke Dashboard</a>

        <img src="{{ asset('uploads/' . $lokasi->jalur_foto) }}" alt="{{ $lokasi->nama }}">

        <h1>{{ $lokasi->nama }}</h1>

        <div class="info-row">
            <strong>Kategori:</strong> {{ $lokasi->kategori }}
        </div>

        <div class="info-row">
            <strong>Rentang Harga:</strong> {{ $lokasi->rentang_harga }}
        </div>

        <div class="info-row">
            <strong>Fasilitas:</strong> {{ $fasilitasStr }}
        </div>

        <div class="info-row" style="border-bottom: none; margin-bottom: 25px;">
            <strong>Google Maps:</strong>
            <a href="{{ $lokasi->link_google_maps }}" target="_blank" class="btn-maps">Lihat Lokasi</a>
        </div>
    </div>
</body>
</html>
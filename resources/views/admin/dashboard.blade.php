<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - BECEK</title>
    <link rel="stylesheet" href="{{ asset('css/stylepage6.css') }}">
</head>
<body>
@include('partials.navbar_admin', [
    'activePage'   => $activePage,
    'namaLogin'    => $namaLogin,
    'namaLengkap'  => $namaLogin,
    'initial'      => $initial
])
    <main class="main-content">
        <section class="welcome-banner">
            <h1>Selamat Datang Admin! 👋</h1>
            <p>Manajemen data komunitas kopi Bekasi hari ini.</p>
        </section>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📍</div>
                <div class="stat-info">
                    <h3>{{ $countLokasi }}</h3>
                    <p>Total Lokasi Cafe</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-info">
                    <h3>{{ $countUser }}</h3>
                    <p>Total Pengguna Sistem</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">☕</div>
                <div class="stat-info">
                    <h3>Aktif</h3>
                    <p>Status Sistem Terpantau</p>
                </div>
            </div>
        </div>

        <h2 class="section-title">Navigasi Manajemen Data</h2>
        <div class="action-grid">
            <div class="action-card" onclick="window.location.href='/admin/lokasi'">
                <div class="action-icon">📍</div>
                <div class="action-info">
                    <h3>Manage Locations</h3>
                    <p>Tambah, edit, hapus, dan kelola lokasi kafe di Bekasi beserta daftar kelengkapan fasilitasnya.</p>
                    <span class="action-btn">Kelola Lokasi &rarr;</span>
                </div>
            </div>

            <div class="action-card" onclick="window.location.href='/admin/users'">
                <div class="action-icon">👥</div>
                <div class="action-info">
                    <h3>Manage Users</h3>
                    <p>Kontrol akun, tambah pengguna baru, hapus akses user, serta tinjau hak level peran login.</p>
                    <span class="action-btn">Kelola Pengguna &rarr;</span>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/jspage6.js') }}"></script>
</body>
</html>
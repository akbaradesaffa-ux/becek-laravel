<footer class="admin-footer" aria-label="Footer Admin BECEK">
    <div class="admin-footer-shell">
        <div class="admin-footer-grid">
            <div class="admin-footer-brand">
                <div class="admin-footer-brand-row">
                    <div class="admin-footer-icon" aria-hidden="true">☕</div>
                    <div>
                        <span>BECEK</span>
                        <small>ADMIN PANEL</small>
                    </div>
                </div>
                <p>Panel pengelolaan direktori digital coffee shop dan warkop di Kota Bekasi.</p>
            </div>

            <div class="admin-footer-nav">
                <h4>Navigasi Admin</h4>
                <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a href="{{ route('admin.lokasi') }}">Kelola Lokasi</a>
                <a href="{{ route('admin.users') }}">Kelola Pengguna</a>
                <a href="{{ route('admin.reports') }}">Laporan</a>
            </div>

            <div class="admin-footer-contact">
                <h4>Informasi</h4>
                <div class="admin-footer-contact-list">
                    <div class="admin-footer-contact-item">
                        <span aria-hidden="true">📍</span>
                        <p>Kota Bekasi, Jawa Barat</p>
                    </div>
                    <a class="admin-footer-contact-item" href="{{ route('about') }}">
                        <span aria-hidden="true">ⓘ</span>
                        <p>Tentang BECEK</p>
                    </a>
                    <a class="admin-footer-contact-item" href="{{ route('logout') }}">
                        <span aria-hidden="true">↗</span>
                        <p>Keluar dari Admin</p>
                    </a>
                </div>
            </div>
        </div>

        <div class="admin-footer-bottom">
            <p>© 2026 BECEK. Hak Cipta Dilindungi.</p>
            <p>Panel administrasi direktori kopi Bekasi.</p>
        </div>
    </div>
</footer>

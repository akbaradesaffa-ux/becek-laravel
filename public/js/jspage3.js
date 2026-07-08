document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const cardLinks = document.querySelectorAll('.cafe-card-link');

    let currentSearch = '';
    let currentCategory = 'all';
    let currentFacility = ['all']; // Diubah jadi Array untuk menampung multi-select

    // Fungsi Evaluasi Filter Multi-Kriteria
    function filterCafes() {
        cardLinks.forEach(link => {
            const card = link.querySelector('.cafe-card');
            const name = card.getAttribute('data-nama') || '';
            const category = link.getAttribute('data-kategori') || '';
            
            // Ambil string fasilitas dari card, bersihkan space, dan jadikan array kecil
            const facilitiesListRaw = link.getAttribute('data-fasilitas') || '';
            const cardFacilitiesArr = facilitiesListRaw.split(',').map(item => item.trim().toLowerCase());
            
            // 1. Cocokkan teks pencarian nama
            const matchesSearch = name.includes(currentSearch);
            
            // 2. Cocokkan kategori
            const matchesCategory = (currentCategory === 'all' || category === currentCategory);
            
            // 3. Cocokkan multi-fasilitas (Menggunakan .every)
            // Artinya: Cafe HARUS memiliki SEMUA fasilitas yang ada di dalam array currentFacility
            const matchesFacility = (
                currentFacility.includes('all') || 
                currentFacility.every(fac => cardFacilitiesArr.includes(fac))
            );

            // Kartu ditampilkan hanya jika memenuhi ketiga kondisi sekaligus
            if (matchesSearch && matchesCategory && matchesFacility) {
                link.style.display = 'block';
            } else {
                link.style.display = 'none';
            }
        });
    }

    // Input Realtime di Kolom Pencarian
    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            currentSearch = e.target.value.toLowerCase();
            filterCafes();
        });
    }

    // Event handler klik untuk tombol filter kelompok
    filterButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const type = this.getAttribute('data-filter-type');
            const value = this.getAttribute('data-filter');

            if (type === 'kategori') {
                // Kategori tetap single-select (pilih salah satu)
                const siblingButtons = document.querySelectorAll(`.filter-btn[data-filter-type="${type}"]`);
                siblingButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentCategory = value;

            } else if (type === 'fasilitas') {
                // Cari tombol "Semua Fasilitas" sebagai patokan reset
                const allFacilityBtn = document.querySelector('.filter-btn[data-filter-type="fasilitas"][data-filter="all"]');

                if (value === 'all') {
                    // Jika klik "Semua Fasilitas", matikan semua tombol fasilitas lain
                    const facilityButtons = document.querySelectorAll('.filter-btn[data-filter-type="fasilitas"]');
                    facilityButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentFacility = ['all'];
                } else {
                    // Jika klik fasilitas spesifik (Wifi, AC, dll)
                    if (allFacilityBtn) allFacilityBtn.classList.remove('active');

                    if (this.classList.contains('active')) {
                        // Jika sebelumnya sudah aktif lalu diklik lagi, matikan (uncheck)
                        this.classList.remove('active');
                        currentFacility = currentFacility.filter(item => item !== value);
                    } else {
                        // Jika belum aktif, aktifkan (check)
                        this.classList.add('active');
                        currentFacility.push(value);
                    }

                    // Jika setelah bongkar pasang ternyata ga ada fasilitas yang dipilih, balikin ke 'all'
                    if (currentFacility.length === 0) {
                        if (allFacilityBtn) allFacilityBtn.classList.add('active');
                        currentFacility = ['all'];
                    } else {
                        // Buang value 'all' dari array jika ada fasilitas spesifik yang sedang aktif
                        currentFacility = currentFacility.filter(item => item !== 'all');
                    }
                }
            }

            // Jalankan filter setelah state array berubah
            filterCafes();
        });
    });
});
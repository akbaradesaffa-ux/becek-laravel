document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    setupMobileMenu();
    setupProfileDropdown();
    setupAccountDeletion();
    setupFavoriteButtons(csrfToken);
    setupExploreFilter();
});

function setupMobileMenu() {
    const menuButton = document.querySelector('[data-mobile-menu-button]');
    const menu = document.querySelector('[data-mobile-menu]');

    if (!menuButton || !menu) return;

    menuButton.addEventListener('click', function () {
        menu.classList.toggle('open');
    });
}

function setupProfileDropdown() {
    const dropdown = document.querySelector('[data-profile-dropdown]');
    const trigger = document.querySelector('[data-profile-trigger]');

    if (!dropdown || !trigger) return;

    trigger.addEventListener('click', function (event) {
        event.stopPropagation();
        const isOpen = dropdown.classList.toggle('open');
        trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    document.addEventListener('click', function (event) {
        if (!dropdown.contains(event.target)) {
            dropdown.classList.remove('open');
            trigger.setAttribute('aria-expanded', 'false');
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            dropdown.classList.remove('open');
            trigger.setAttribute('aria-expanded', 'false');
        }
    });
}

function setupAccountDeletion() {
    const deleteButton = document.querySelector('[data-delete-account]');
    const deleteForm = document.getElementById('deleteAccountForm');
    const passwordInput = document.getElementById('deleteAccountPassword');

    if (!deleteButton || !deleteForm || !passwordInput) return;

    deleteButton.addEventListener('click', function () {
        const confirmed = window.confirm('Yakin mau hapus akun? Semua data login dan favorit akun ini akan hilang.');
        if (!confirmed) return;

        const password = window.prompt('Masukkan password akun untuk konfirmasi hapus akun:');
        if (!password) {
            showToast('Hapus akun dibatalkan. Password wajib diisi.');
            return;
        }

        passwordInput.value = password;
        deleteForm.submit();
    });
}

function setupFavoriteButtons(csrfToken) {
    const buttons = document.querySelectorAll('[data-favorite-button]');

    buttons.forEach((button) => {
        button.addEventListener('click', async function (event) {
            event.preventDefault();
            event.stopPropagation();

            const url = button.getAttribute('data-url');
            if (!url) return;

            button.disabled = true;

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                });

                const data = await response.json();
                if (!response.ok || !data.success) {
                    showToast(data.message || 'Favorit gagal diperbarui.');
                    return;
                }

                button.classList.toggle('active', Boolean(data.favorited));
                button.setAttribute(
                    'aria-label',
                    data.favorited ? 'Hapus dari favorit' : 'Tambah ke favorit'
                );

                if (!data.favorited && window.location.pathname.includes('/favorit')) {
                    const card = button.closest('.cafe-card-wrap');
                    card?.remove();
                    renderEmptyFavoritesState();
                }

                showToast(data.message);
            } catch (error) {
                showToast('Terjadi kesalahan sistem. Coba lagi.');
            } finally {
                button.disabled = false;
            }
        });
    });
}

function setupExploreFilter() {
    const searchInput = document.getElementById('searchInput');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const cardWraps = document.querySelectorAll('.cafe-card-wrap');
    const resultCount = document.getElementById('resultCount');
    const noResultState = document.getElementById('noResultState');

    if (!searchInput || !filterButtons.length || !cardWraps.length) return;

    let currentSearch = '';
    let currentCategory = 'all';
    let currentArea = 'all';
    let currentFacility = ['all'];

    function filterCafes() {
        let visibleCount = 0;

        cardWraps.forEach((wrap) => {
            const card = wrap.querySelector('.cafe-card');
            const name = card?.getAttribute('data-nama') || '';
            const category = wrap.getAttribute('data-kategori') || '';
            const area = wrap.getAttribute('data-area') || '';
            const price = wrap.getAttribute('data-harga') || '';
            const facilitiesRaw = wrap.getAttribute('data-fasilitas') || '';
            const facilities = facilitiesRaw.split(',').map(item => item.trim().toLowerCase()).filter(Boolean);

            const searchableText = `${name} ${category} ${area} ${price} ${facilitiesRaw}`;
            const matchesSearch = searchableText.includes(currentSearch);
            const matchesCategory = currentCategory === 'all' || category === currentCategory;
            const matchesArea = currentArea === 'all' || area === currentArea;
            const matchesFacility = currentFacility.includes('all') || currentFacility.every(facility => facilities.includes(facility));
            const isVisible = matchesSearch && matchesCategory && matchesArea && matchesFacility;

            wrap.style.display = isVisible ? 'block' : 'none';
            if (isVisible) visibleCount += 1;
        });

        if (resultCount) {
            resultCount.textContent = `Menampilkan ${visibleCount} dari ${cardWraps.length} tempat`;
        }

        if (noResultState) {
            noResultState.classList.toggle('hidden', visibleCount > 0);
        }
    }

    searchInput.addEventListener('input', function (event) {
        currentSearch = event.target.value.trim().toLowerCase();
        filterCafes();
    });

    filterButtons.forEach((button) => {
        button.addEventListener('click', function () {
            const type = button.getAttribute('data-filter-type');
            const value = button.getAttribute('data-filter');

            if (type === 'kategori') {
                document.querySelectorAll('.filter-btn[data-filter-type="kategori"]').forEach((item) => item.classList.remove('active'));
                button.classList.add('active');
                currentCategory = value || 'all';
            }

            if (type === 'area') {
                document.querySelectorAll('.filter-btn[data-filter-type="area"]').forEach((item) => item.classList.remove('active'));
                button.classList.add('active');
                currentArea = value || 'all';
            }

            if (type === 'fasilitas') {
                const allFacilityButton = document.querySelector('.filter-btn[data-filter-type="fasilitas"][data-filter="all"]');

                if (value === 'all') {
                    document.querySelectorAll('.filter-btn[data-filter-type="fasilitas"]').forEach((item) => item.classList.remove('active'));
                    button.classList.add('active');
                    currentFacility = ['all'];
                } else {
                    allFacilityButton?.classList.remove('active');

                    if (button.classList.contains('active')) {
                        button.classList.remove('active');
                        currentFacility = currentFacility.filter(item => item !== value);
                    } else {
                        button.classList.add('active');
                        currentFacility.push(value || '');
                    }

                    currentFacility = currentFacility.filter(item => item && item !== 'all');

                    if (currentFacility.length === 0) {
                        currentFacility = ['all'];
                        allFacilityButton?.classList.add('active');
                    }
                }
            }

            filterCafes();
        });
    });
}

function showToast(message) {
    if (!message) return;

    const existingToast = document.querySelector('.toast');
    if (existingToast) existingToast.remove();

    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;
    document.body.appendChild(toast);

    window.requestAnimationFrame(() => toast.classList.add('show'));

    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 260);
    }, 2300);
}

function renderEmptyFavoritesState() {
    const grid = document.getElementById('cafeGrid');
    if (!grid || grid.querySelector('.cafe-card-wrap')) return;

    grid.innerHTML = `
        <div class="empty-state">
            <div>★</div>
            <h3>Belum ada tempat favorit</h3>
            <p>Tekan ikon bintang di kartu tempat untuk menyimpannya ke daftar favorit.</p>
            <a href="/explore" class="btn-empty">Cari Tempat</a>
        </div>
    `;
}

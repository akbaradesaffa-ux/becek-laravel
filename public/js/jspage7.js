const locationModal = document.getElementById('locationModal');
const locationForm = document.getElementById('locationForm');
const locationMethod = document.getElementById('locationMethod');
const locationTitle = document.getElementById('locationModalTitle');
const locationSubmitButton = document.getElementById('locationSubmitButton');
const mainPhotoInput = document.getElementById('lokasiFoto');
const extraPhotoInput = document.getElementById('lokasiFotoTambahan');
const mainPreview = document.getElementById('mainPhotoPreview');
const extraPreview = document.getElementById('extraPhotoPreview');
const recommendedInput = document.getElementById('lokasiRekomendasi');
const locationDetailModal = document.getElementById('locationDetailModal');

function setDetailText(id, value) {
    const element = document.getElementById(id);
    if (element) element.textContent = value || '-';
}

function openLocationDetailModal(button) {
    if (!locationDetailModal || !button) return;

    setDetailText('detailNama', button.dataset.nama || 'Detail Lokasi');
    setDetailText('detailKode', button.dataset.kode || '-');
    setDetailText('detailKategori', button.dataset.kategori || '-');
    setDetailText('detailArea', button.dataset.area || '-');
    setDetailText('detailHarga', button.dataset.harga || '-');
    setDetailText('detailStatus', button.dataset.status || '-');
    setDetailText('detailHari', button.dataset.hari || '-');
    setDetailText('detailJam', button.dataset.jam || '-');
    setDetailText('detailRekomendasi', button.dataset.rekomendasi || '-');
    setDetailText('detailFavorit', button.dataset.favorit || '0');
    setDetailText('detailFotoTambahan', `${button.dataset.fotoTambahan || '0'} foto`);
    setDetailText('detailFasilitas', button.dataset.fasilitas || '-');

    const detailPhoto = document.getElementById('detailFoto');
    if (detailPhoto) {
        detailPhoto.src = button.dataset.foto || '';
        detailPhoto.alt = `Foto ${button.dataset.nama || 'lokasi'}`;
    }

    const mapsLink = document.getElementById('detailMapsLink');
    if (mapsLink) {
        mapsLink.href = button.dataset.maps || '#';
    }

    locationDetailModal.style.display = 'flex';
}

function closeLocationDetailModal() {
    if (locationDetailModal) {
        locationDetailModal.style.display = 'none';
    }
}

function openCreateLocationModal() {
    locationForm.reset();
    locationForm.action = locationForm.dataset.storeUrl || '/admin/lokasi/store';
    locationTitle.textContent = 'Tambah Lokasi Baru';
    locationSubmitButton.textContent = 'Simpan Lokasi';
    locationMethod.disabled = true;
    locationMethod.value = 'POST';
    mainPhotoInput.required = true;
    clearPreview();
    if (recommendedInput) {
        recommendedInput.checked = false;
    }
    document.querySelectorAll('[data-facility-checkbox]').forEach((checkbox) => {
        checkbox.checked = false;
    });
    locationModal.style.display = 'flex';
}

function openEditLocationModal(button) {
    locationForm.reset();
    locationForm.action = button.dataset.updateUrl;
    locationTitle.textContent = 'Edit Lokasi';
    locationSubmitButton.textContent = 'Update Lokasi';
    locationMethod.disabled = false;
    locationMethod.value = 'PUT';
    mainPhotoInput.required = false;
    clearPreview();

    document.getElementById('lokasiNama').value = button.dataset.nama || '';
    document.getElementById('lokasiKategori').value = button.dataset.kategori || '';
    document.getElementById('lokasiArea').value = button.dataset.area || '';
    document.getElementById('lokasiHarga').value = button.dataset.harga || '';
    document.getElementById('lokasiHari').value = button.dataset.hari || '';
    document.getElementById('lokasiJamBuka').value = button.dataset.jamBuka || '';
    document.getElementById('lokasiJamTutup').value = button.dataset.jamTutup || '';
    document.getElementById('lokasiMaps').value = button.dataset.maps || '';
    if (recommendedInput) {
        recommendedInput.checked = button.dataset.rekomendasi === '1';
    }

    const selectedFacilities = (button.dataset.fasilitas || '').split(',').filter(Boolean);
    document.querySelectorAll('[data-facility-checkbox]').forEach((checkbox) => {
        checkbox.checked = selectedFacilities.includes(checkbox.value);
    });

    locationModal.style.display = 'flex';
}

function closeLocationModal() {
    locationModal.style.display = 'none';
    locationForm.reset();
    clearPreview();
}

function clearPreview() {
    if (mainPreview) mainPreview.innerHTML = '';
    if (extraPreview) extraPreview.innerHTML = '';
}

function renderPreview(input, target, multiple = false) {
    if (!input || !target) return;
    target.innerHTML = '';

    const files = Array.from(input.files || []);
    if (!files.length) return;

    files.slice(0, multiple ? 8 : 1).forEach((file) => {
        if (!file.type.startsWith('image/')) return;

        const img = document.createElement('img');
        img.className = 'upload-preview-img';
        img.src = URL.createObjectURL(file);
        img.alt = file.name;
        target.appendChild(img);
    });
}

function confirmAdminDelete(message) {
    return window.confirm(message || 'Yakin ingin menghapus data ini?');
}

if (locationForm) {
    locationForm.dataset.storeUrl = locationForm.action;
}

if (mainPhotoInput) {
    mainPhotoInput.addEventListener('change', () => renderPreview(mainPhotoInput, mainPreview, false));
}

if (extraPhotoInput) {
    extraPhotoInput.addEventListener('change', () => renderPreview(extraPhotoInput, extraPreview, true));
}

if (locationModal) {
    locationModal.addEventListener('click', function (event) {
        if (event.target === locationModal) {
            closeLocationModal();
        }
    });
}

if (locationDetailModal) {
    locationDetailModal.addEventListener('click', function (event) {
        if (event.target === locationDetailModal) {
            closeLocationDetailModal();
        }
    });
}

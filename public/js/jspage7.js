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
const copyMondayScheduleButton = document.getElementById('copyMondaySchedule');

const operationalDays = [
    ['senin', 'Senin'],
    ['selasa', 'Selasa'],
    ['rabu', 'Rabu'],
    ['kamis', 'Kamis'],
    ['jumat', 'Jumat'],
    ['sabtu', 'Sabtu'],
    ['minggu', 'Minggu'],
];

function setDetailText(id, value) {
    const element = document.getElementById(id);
    if (element) element.textContent = value || '-';
}

function parseSchedule(value) {
    if (!value) return {};

    try {
        return JSON.parse(value);
    } catch (error) {
        console.error('Jadwal operasional tidak dapat dibaca.', error);
        return {};
    }
}

function scheduleTimeLabel(schedule) {
    if (!schedule) return 'Jadwal belum diatur';
    if (schedule.status === '24_jam') return '24 Jam';
    if (schedule.status === 'tutup') return 'Tutup';

    if (schedule.status === 'buka' && schedule.jam_buka && schedule.jam_tutup) {
        return `${schedule.jam_buka} - ${schedule.jam_tutup}`;
    }

    return 'Jadwal belum diatur';
}

function formatScheduleSummary(scheduleData) {
    const groups = [];

    operationalDays.forEach(([dayKey, dayLabel]) => {
        const timeLabel = scheduleTimeLabel(scheduleData[dayKey]);
        const lastGroup = groups[groups.length - 1];

        if (lastGroup && lastGroup.timeLabel === timeLabel) {
            lastGroup.endLabel = dayLabel;
            return;
        }

        groups.push({
            startLabel: dayLabel,
            endLabel: dayLabel,
            timeLabel,
        });
    });

    return groups.map((group) => {
        const dayRange = group.startLabel === group.endLabel
            ? group.startLabel
            : `${group.startLabel} - ${group.endLabel}`;

        return `${dayRange}: ${group.timeLabel}`;
    }).join('\n');
}

function openLocationDetailModal(button) {
    if (!locationDetailModal || !button) return;

    setDetailText('detailNama', button.dataset.nama || 'Detail Lokasi');
    setDetailText('detailKode', button.dataset.kode || '-');
    setDetailText('detailKategori', button.dataset.kategori || '-');
    setDetailText('detailArea', button.dataset.area || '-');
    setDetailText('detailHarga', button.dataset.harga || '-');
    setDetailText('detailStatus', button.dataset.status || '-');
    setDetailText('detailJadwal', formatScheduleSummary(parseSchedule(button.dataset.jadwal)));
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

function scheduleRow(dayKey) {
    return document.querySelector(`[data-schedule-row][data-day="${dayKey}"]`);
}

function updateScheduleRow(row) {
    if (!row) return;

    const status = row.querySelector('[data-schedule-status]');
    const openInput = row.querySelector('[data-schedule-open]');
    const closeInput = row.querySelector('[data-schedule-close]');
    const usesTime = status && status.value === 'buka';

    if (openInput) {
        openInput.disabled = !usesTime;
        if (usesTime && !openInput.value) openInput.value = '07:00';
    }

    if (closeInput) {
        closeInput.disabled = !usesTime;
        if (usesTime && !closeInput.value) closeInput.value = '23:00';
    }

    row.classList.toggle('schedule-row-disabled', !usesTime);
}

function setScheduleForm(scheduleData = {}) {
    operationalDays.forEach(([dayKey]) => {
        const row = scheduleRow(dayKey);
        if (!row) return;

        const data = scheduleData[dayKey] || {
            status: 'buka',
            jam_buka: '07:00',
            jam_tutup: '23:00',
        };
        const status = row.querySelector('[data-schedule-status]');
        const openInput = row.querySelector('[data-schedule-open]');
        const closeInput = row.querySelector('[data-schedule-close]');

        if (status) status.value = data.status || 'tutup';
        if (openInput) openInput.value = data.jam_buka || '';
        if (closeInput) closeInput.value = data.jam_tutup || '';
        updateScheduleRow(row);
    });
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
    setScheduleForm();

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
    document.getElementById('lokasiMaps').value = button.dataset.maps || '';
    setScheduleForm(parseSchedule(button.dataset.jadwal));

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

function copyMondaySchedule() {
    const mondayRow = scheduleRow('senin');
    if (!mondayRow) return;

    const mondayStatus = mondayRow.querySelector('[data-schedule-status]')?.value || 'tutup';
    const mondayOpen = mondayRow.querySelector('[data-schedule-open]')?.value || '';
    const mondayClose = mondayRow.querySelector('[data-schedule-close]')?.value || '';

    operationalDays.forEach(([dayKey]) => {
        if (dayKey === 'senin') return;

        const row = scheduleRow(dayKey);
        if (!row) return;

        const status = row.querySelector('[data-schedule-status]');
        const openInput = row.querySelector('[data-schedule-open]');
        const closeInput = row.querySelector('[data-schedule-close]');

        if (status) status.value = mondayStatus;
        if (openInput) openInput.value = mondayOpen;
        if (closeInput) closeInput.value = mondayClose;
        updateScheduleRow(row);
    });
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

document.querySelectorAll('[data-schedule-status]').forEach((statusSelect) => {
    statusSelect.addEventListener('change', () => updateScheduleRow(statusSelect.closest('[data-schedule-row]')));
});

if (copyMondayScheduleButton) {
    copyMondayScheduleButton.addEventListener('click', copyMondaySchedule);
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

setScheduleForm();

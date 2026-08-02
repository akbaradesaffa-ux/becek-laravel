(function () {
    'use strict';

    const locationButton = document.getElementById('useCurrentLocation');
    const locationForm = document.getElementById('nearbyFilterForm');
    const latitudeInput = document.getElementById('nearbyLatitude');
    const longitudeInput = document.getElementById('nearbyLongitude');
    const statusElement = document.getElementById('nearbyStatus');

    if (!locationButton || !locationForm || !latitudeInput || !longitudeInput || !statusElement) {
        return;
    }

    const originalButtonText = locationButton.innerHTML;

    function setStatus(message, type = '') {
        statusElement.textContent = message;
        statusElement.classList.remove('success', 'error');
        if (type) statusElement.classList.add(type);
    }

    function setLoading(isLoading) {
        locationButton.disabled = isLoading;
        locationButton.classList.toggle('loading', isLoading);
        locationButton.innerHTML = isLoading
            ? '<span class="nearby-spinner" aria-hidden="true"></span><span>Mendeteksi lokasi...</span>'
            : originalButtonText;
    }

    function geolocationErrorMessage(error) {
        if (!error) return 'Lokasi tidak dapat dibaca. Coba kembali.';

        switch (error.code) {
            case error.PERMISSION_DENIED:
                return 'Izin lokasi ditolak. Aktifkan izin lokasi pada pengaturan browser, lalu coba kembali.';
            case error.POSITION_UNAVAILABLE:
                return 'Lokasi perangkat belum tersedia. Pastikan GPS atau layanan lokasi aktif.';
            case error.TIMEOUT:
                return 'Pencarian lokasi terlalu lama. Coba kembali di area dengan sinyal yang lebih baik.';
            default:
                return 'Lokasi tidak dapat dibaca. Coba kembali.';
        }
    }

    locationButton.addEventListener('click', function () {
        if (!window.isSecureContext && !['localhost', '127.0.0.1'].includes(window.location.hostname)) {
            setStatus('Akses lokasi membutuhkan HTTPS. Aktifkan SSL pada hosting terlebih dahulu.', 'error');
            return;
        }

        if (!navigator.geolocation) {
            setStatus('Browser ini tidak mendukung fitur lokasi.', 'error');
            return;
        }

        setLoading(true);
        setStatus('Sedang meminta lokasi perangkat...');

        navigator.geolocation.getCurrentPosition(
            function (position) {
                latitudeInput.value = position.coords.latitude.toFixed(7);
                longitudeInput.value = position.coords.longitude.toFixed(7);
                setStatus('Lokasi ditemukan. Mengurutkan cafe dan warkop terdekat...', 'success');
                locationForm.submit();
            },
            function (error) {
                setLoading(false);
                setStatus(geolocationErrorMessage(error), 'error');
            },
            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 60000,
            }
        );
    });
})();

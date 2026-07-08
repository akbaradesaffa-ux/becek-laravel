// Filter pencarian
const searchInput = document.getElementById('searchInput');
const cards = document.querySelectorAll('.cafe-card');

searchInput.addEventListener('keyup', function() {
    const keyword = this.value.toLowerCase();
    cards.forEach(card => {
        const nama = card.getAttribute('data-nama');
        if (nama && nama.includes(keyword)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Modal detail
const modal = document.getElementById('detailModal');
cards.forEach(card => {
    card.addEventListener('click', function(e) {
        const detailDiv = this.querySelector('.detail-data');
        if (!detailDiv) return;
        document.getElementById('modalImg').src = detailDiv.dataset.gambar;
        document.getElementById('modalNama').innerText = detailDiv.dataset.nama;
        document.getElementById('modalLokasi').innerHTML = '📍 ' + detailDiv.dataset.lokasi;
        document.getElementById('modalHarga').innerHTML = '💰 ' + detailDiv.dataset.harga;
        document.getElementById('modalFasilitas').innerHTML = '✨ Fasilitas: ' + detailDiv.dataset.fasilitas;
        document.getElementById('modalDeskripsi').innerText = detailDiv.dataset.deskripsi;
        modal.style.display = 'flex';
    });
});

function closeModal() {
    modal.style.display = 'none';
}
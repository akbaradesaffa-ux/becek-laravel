// Script untuk Dashboard (page6)
// Bisa digunakan untuk interaktivitas seperti chart atau notifikasi real-time nantinya.
console.log('Dashboard Loaded');

// Contoh: Animasi angka statistik
const stats = document.querySelectorAll('.stat-info h3');
stats.forEach(stat => {
    const value = parseInt(stat.innerText);
    if(!isNaN(value)) {
        let start = 0;
        const duration = 1000;
        const increment = Math.ceil(value / (duration / 16));
        const timer = setInterval(() => {
            start += increment;
            if(start >= value) {
                stat.innerText = value;
                clearInterval(timer);
            } else {
                stat.innerText = start;
            }
        }, 16);
    }
});
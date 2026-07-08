<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - BECEK</title>
    <link rel="stylesheet" href="{{ asset('css/stylepage9.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
</head>
<body>
    @include('partials.navbar_admin', ['activePage' => $activePage, 'namaLogin' => $namaLogin, 'initial' => $initial])

    <main class="main-content">
        <div class="header-title">
            <h1>Reports</h1>
            <p>Ringkasan dan visualisasi data lokasi kuliner Bekasi.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📍</div>
                <div class="stat-info">
                    <h3>{{ $totalLokasi }}</h3>
                    <p>Total Semua Lokasi</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon cafe-icon">☕</div>
                <div class="stat-info">
                    <h3>{{ $totalCafe }}</h3>
                    <p>Total Cafe</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon warkop-icon">🫖</div>
                <div class="stat-info">
                    <h3>{{ $totalWarkop }}</h3>
                    <p>Total Warkop</p>
                </div>
            </div>
        </div>

        <div class="chart-section">
            <div class="chart-card">
                <div class="chart-header">
                    <div>
                        <h2>Distribusi Kategori Lokasi</h2>
                        <p>Perbandingan jumlah Cafe dan Warkop yang terdaftar.</p>
                    </div>
                </div>
                <div class="chart-body">
                    <div class="donut-wrapper">
                        <canvas id="donutChart"></canvas>
                        <div class="donut-center">
                            <span class="donut-total">{{ $totalLokasi }}</span>
                            <span class="donut-label">Total</span>
                        </div>
                    </div>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <div class="legend-dot cafe-dot"></div>
                            <div class="legend-info">
                                <span class="legend-name">Cafe</span>
                                <span class="legend-count">{{ $totalCafe }} lokasi</span>
                                <div class="legend-bar-track">
                                    <div class="legend-bar cafe-bar" style="width: {{ $pctCafe }}%"></div>
                                </div>
                                <span class="legend-pct">{{ $pctCafe }}%</span>
                            </div>
                        </div>
                        <div class="legend-item">
                            <div class="legend-dot warkop-dot"></div>
                            <div class="legend-info">
                                <span class="legend-name">Warkop</span>
                                <span class="legend-count">{{ $totalWarkop }} lokasi</span>
                                <div class="legend-bar-track">
                                    <div class="legend-bar warkop-bar" style="width: {{ $pctWarkop }}%"></div>
                                </div>
                                <span class="legend-pct">{{ $pctWarkop }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const ctx = document.getElementById('donutChart').getContext('2d');

        const data = {
            labels: ['Cafe', 'Warkop'],
            datasets: [{
                data: [{{ $totalCafe }}, {{ $totalWarkop }}],
                backgroundColor: ['#6F4E37', '#C8A882'],
                hoverBackgroundColor: ['#3d2b1f', '#a8845e'],
                borderWidth: 0,
                hoverOffset: 8
            }]
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: {
                cutout: '72%',
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const pct = total > 0 ? Math.round((context.parsed / total) * 100) : 0;
                                return ` ${context.label}: ${context.parsed} lokasi (${pct}%)`;
                            }
                        },
                        backgroundColor: '#0f172a',
                        titleColor: '#fff',
                        bodyColor: '#cbd5e1',
                        padding: 12,
                        cornerRadius: 10
                    }
                },
                animation: {
                    animateRotate: true,
                    duration: 900,
                    easing: 'easeInOutQuart'
                }
            }
        });
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>
        (function () {
            try {
                var savedTheme = localStorage.getItem('becek-theme') || 'dark';
                document.documentElement.setAttribute('data-theme', savedTheme === 'light' ? 'light' : 'dark');
            } catch (error) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
            document.documentElement.classList.add('page-transition-active');
        })();
    </script>
    <title>Reports - BECEK</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=becek-logo-clean-20260709">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}?v=becek-logo-clean-20260709">
    <link rel="stylesheet" href="{{ asset('css/becek-admin.css') }}?v=theme-motion-20260709">
    <link rel="stylesheet" href="{{ asset('css/page-transition.css') }}?v=theme-toggle-20260709">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
</head>
<body>
    @include('partials.navbar_admin', ['activePage' => $activePage, 'namaLogin' => $namaLogin, 'initial' => $initial])

    <main class="main-content page-enter">
        <div class="header-title">
            <p class="eyebrow">Data & Statistik</p>
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
                <div class="stat-icon purple">☕</div>
                <div class="stat-info">
                    <h3>{{ $totalCafe }}</h3>
                    <p>Total Cafe</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green">🫖</div>
                <div class="stat-info">
                    <h3>{{ $totalWarkop }}</h3>
                    <p>Total Warkop</p>
                </div>
            </div>
        </div>

        <section class="chart-card">
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
        </section>

    </main>

    <script>
        const chartCanvas = document.getElementById('donutChart');
        let becekReportChart = null;

        function getAdminCssVar(name, fallback) {
            return getComputedStyle(document.documentElement).getPropertyValue(name).trim() || fallback;
        }

        function getChartThemeOptions() {
            const isLight = document.documentElement.getAttribute('data-theme') === 'light';
            return {
                cafe: getAdminCssVar('--admin-primary-2', '#c87c2a'),
                warkop: isLight ? '#a6783e' : '#8a6a3a',
                tooltipBg: isLight ? '#fffaf3' : '#23170f',
                tooltipTitle: getAdminCssVar('--admin-text', isLight ? '#2b1b10' : '#ffffff'),
                tooltipBody: getAdminCssVar('--admin-muted', isLight ? '#7f674d' : '#f0e4cf')
            };
        }

        function renderReportChart() {
            if (!chartCanvas || typeof Chart === 'undefined') return;
            const ctx = chartCanvas.getContext('2d');
            const theme = getChartThemeOptions();

            if (becekReportChart) {
                becekReportChart.destroy();
            }

            becekReportChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Cafe', 'Warkop'],
                    datasets: [{
                        data: [{{ $totalCafe }}, {{ $totalWarkop }}],
                        backgroundColor: [theme.cafe, theme.warkop],
                        hoverBackgroundColor: ['#e8a44a', '#d4a870'],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
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
                            backgroundColor: theme.tooltipBg,
                            titleColor: theme.tooltipTitle,
                            bodyColor: theme.tooltipBody,
                            padding: 12,
                            cornerRadius: 12
                        }
                    },
                    animation: { animateRotate: true, duration: 900, easing: 'easeInOutQuart' }
                }
            });
        }

        renderReportChart();
        window.addEventListener('becek:theme-change', renderReportChart);
    </script>
    <script src="{{ asset('js/page-transition.js') }}"></script>
    <script src="{{ asset('js/becek-theme-toggle.js') }}?v=no-footer-logo-20260709"></script>
</body>
</html>

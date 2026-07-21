@extends('layouts.admin')

@section('title','Dashboard')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            📊 Dasbor
        </h2>

        <p class="text-muted mb-0">
            Platform Monitoring Risiko Rantai Pasok Global - Sistem Monitoring
        </p>
    </div>

   <!-- KPI -->
<div class="row g-4 mb-4">

    <!-- Negara -->
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted fw-semibold">
                            Total Negara
                        </small>

                        <h2 class="fw-bold mt-2 mb-0">
                            {{ number_format($totalCountries) }}
                        </h2>

                        <small class="text-success">
                            <i class="bi bi-geo-alt"></i> {{ $totalContinents }} Benua
                        </small>

                    </div>

                    <div class="bg-primary bg-opacity-10 rounded-circle p-3">

                        <i class="bi bi-globe2 fs-2 text-primary"></i>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Weather -->
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted fw-semibold">
                            Log Cuaca
                        </small>

                        <h2 class="fw-bold mt-2 mb-0">
                            {{ number_format($totalWeather) }}
                        </h2>

                        <small class="text-success">
                            <i class="bi bi-check-circle"></i> Monitoring Aktif
                        </small>

                    </div>

                    <div class="bg-success bg-opacity-10 rounded-circle p-3">

                        <i class="bi bi-cloud-sun fs-2 text-success"></i>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Exchange -->
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted fw-semibold">
                            Nilai Tukar
                        </small>

                        <h2 class="fw-bold mt-2 mb-0">
                            {{ number_format($totalExchange) }}
                        </h2>

                        <small class="text-warning">
                            <i class="bi bi-currency-exchange"></i> Data Real-time
                        </small>

                    </div>

                    <div class="bg-warning bg-opacity-10 rounded-circle p-3">

                        <i class="bi bi-currency-exchange fs-2 text-warning"></i>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Economy -->
    <div class="col-lg-3 col-md-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted fw-semibold">
                            Rekaman Ekonomi
                        </small>

                        <h2 class="fw-bold mt-2 mb-0">
                            {{ number_format($totalEconomy) }}
                        </h2>

                        <small class="text-info">
                            <i class="bi bi-graph-up"></i> World Bank API
                        </small>

                    </div>

                    <div class="bg-info bg-opacity-10 rounded-circle p-3">

                        <i class="bi bi-bar-chart-line fs-2 text-info"></i>

                    </div>

                </div>

            </div>
        </div>
    </div>

</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    
    <!-- Chart Negara per Benua -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 pb-3">
                <h6 class="fw-bold mb-0">📊 Jumlah Negara per Benua</h6>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="continentChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Risk Level -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 pb-3">
                <h6 class="fw-bold mb-0">⚠️ Kategori Risiko Negara</h6>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="riskChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Map and Statistics -->
<div class="row g-4 mb-4">
    
    <!-- Map -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 pb-3">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-map"></i> Peta Negara Global
                </h6>
            </div>
            <div class="card-body p-0">
                <div id="map" style="height: 400px; border-radius: 0 0 1rem 1rem;"></div>
            </div>
        </div>
    </div>

    <!-- Top Risk Countries -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-0 pt-4 pb-3">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-exclamation-triangle"></i> Negara Risiko Tinggi
                </h6>
            </div>
            <div class="card-body">
                @if($topRiskCountries && $topRiskCountries->count() > 0)
                    @foreach($topRiskCountries->take(5) as $country)
                        <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong class="text-dark d-block">{{ $country->name }}</strong>
                                    <small class="text-muted">
                                        <i class="bi bi-geo-alt"></i> {{ $country->continent ?? 'N/A' }}
                                    </small>
                                </div>
                                <span class="badge bg-danger">
                                    {{ number_format($country->risk_score, 1) }}
                                </span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-danger" 
                                     role="progressbar" 
                                     style="width: {{ ($country->risk_score / 10) * 100 }}%"
                                     aria-valuenow="{{ $country->risk_score }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="10">
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="text-center mt-3">
                        <a href="{{ route('risk.index') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Semua Risiko <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-shield-check fs-3 d-block mb-2"></i>
                        <small>Tidak ada data risiko</small>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

<!-- Recent News & Quick Actions -->
<div class="row g-4 mb-4">
    
    <!-- Recent News (Full Width) -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 pb-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-newspaper"></i> Berita Terbaru
                </h6>
                <a href="{{ route('news.index') }}" class="btn btn-sm btn-outline-primary">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($recentNews && $recentNews->count() > 0)
                    @foreach($recentNews->take(4) as $news)
                        <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="d-flex gap-3">
                                @if($news->image_url)
                                    <img src="{{ $news->image_url }}" 
                                         alt="{{ $news->title }}" 
                                         class="rounded"
                                         style="width: 80px; height: 80px; object-fit: cover;"
                                         onerror="this.style.display='none'">
                                @endif
                                <div class="flex-grow-1">
                                    <a href="{{ $news->url }}" target="_blank" class="text-decoration-none">
                                        <h6 class="fw-bold text-dark mb-1">{{ Str::limit($news->title, 80) }}</h6>
                                    </a>
                                    <p class="text-muted small mb-2">
                                        {{ Str::limit($news->description ?? '', 120) }}
                                    </p>
                                    <div class="d-flex gap-2 align-items-center">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3"></i> 
                                            {{ $news->published_at ? $news->published_at->format('d M Y') : 'N/A' }}
                                        </small>
                                        @if($news->sentiment_score !== null)
                                            <span class="badge {{ $news->sentiment_score > 0 ? 'bg-success' : ($news->sentiment_score < 0 ? 'bg-danger' : 'bg-secondary') }}">
                                                {{ $news->sentiment_score > 0 ? 'Positif' : ($news->sentiment_score < 0 ? 'Negatif' : 'Netral') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        <small>Belum ada berita</small>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

</div>

@endsection

@push('scripts')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Leaflet Map
    const map = L.map('map').setView([20, 0], 2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    const countries = @json($countries);

    const riskColors = { Rendah: '#198754', Sedang: '#ffc107', Tinggi: '#dc3545' };

    countries.forEach(country => {
        if (country.latitude && country.longitude) {
            const color = riskColors[country.risk_level] || '#0d6efd';
            const icon = L.divIcon({
                className: '',
                html: `<div style="background:${color};width:14px;height:14px;border-radius:50%;border:2px solid white;box-shadow:0 0 4px rgba(0,0,0,0.4);"></div>`,
                iconSize: [14, 14],
                iconAnchor: [7, 7]
            });
            L.marker([country.latitude, country.longitude], {icon})
                .addTo(map)
                .bindPopup(
                    `<div style="min-width:160px;">
                        <b style="font-size:0.95rem;">${country.name}</b><br>
                        <small class="text-muted">${country.continent || '-'}</small><br><br>
                        <span>👥 ${country.population ? country.population.toLocaleString() : 'N/A'}</span><br>
                        <span>💱 ${country.currency_code || 'N/A'}</span><br><br>
                        <a href="/countries/${country.id}" class="btn btn-sm btn-primary w-100" style="font-size:0.8rem;">
                            🔍 Lihat Detail Negara
                        </a>
                    </div>`
                );
        }
    });

    // Chart Negara per Benua
    const continentChartCtx = document.getElementById('continentChart');
    if (continentChartCtx) {
        new Chart(continentChartCtx, {
            type: 'bar',
            data: {
                labels: @json($continentChart->pluck('continent')),
                datasets: [{
                    label: 'Jumlah Negara',
                    data: @json($continentChart->pluck('total')),
                    backgroundColor: '#3b82f6',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }

    // Chart Risk Level
    const riskChartCtx = document.getElementById('riskChart');
    if (riskChartCtx) {
        new Chart(riskChartCtx, {
            type: 'doughnut',
            data: {
                labels: @json($riskChart->pluck('risk_level')),
                datasets: [{
                    data: @json($riskChart->pluck('total')),
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#6366f1']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
</script>
@endpush
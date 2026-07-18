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
                <h6 class="fw-bold mb-0">Distribusi Negara per Benua</h6>
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
                <h6 class="fw-bold mb-0">Distribusi Tingkat Risiko</h6>
            </div>
            <div class="card-body">
                <div style="height: 300px;">
                    <canvas id="riskChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Map and Recent Sync -->
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

    <!-- Recent Sync -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 pb-3">
                <h6 class="fw-bold mb-0">
                    <i class="bi bi-clock-history"></i> Log Sinkronisasi Terbaru
                </h6>
            </div>
            <div class="card-body">
                @forelse($recentSync as $sync)
                    <div class="mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <strong class="text-dark">{{ $sync->sync_type }}</strong>
                            @if($sync->status == 'completed')
                                <span class="badge bg-success">Berhasil</span>
                            @elseif($sync->status == 'failed')
                                <span class="badge bg-danger">Gagal</span>
                            @else
                                <span class="badge bg-warning">{{ $sync->status }}</span>
                            @endif
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-calendar3"></i> 
                            {{ optional($sync->finished_at)->format('d M Y H:i') ?? 'N/A' }}
                        </small>
                        @if($sync->records_synced)
                            <br><small class="text-muted">{{ $sync->records_synced }} data tersinkronisasi</small>
                        @endif
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        <small>Belum ada sync log</small>
                    </div>
                @endforelse
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

    countries.forEach(country => {
        if (country.latitude && country.longitude) {
            L.marker([
                country.latitude,
                country.longitude
            ])
            .addTo(map)
            .bindPopup(
                '<b>' + country.name + '</b><br>' + 
                (country.continent || 'T/A') + '<br>' +
                '<small>Populasi: ' + (country.population ? country.population.toLocaleString() : 'T/A') + '</small>'
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
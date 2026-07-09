@extends('layouts.admin')

@section('title','Dashboard')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="mb-4">
        <h2 class="fw-bold mb-1">
            Dashboard
        </h2>

        <p class="text-muted mb-0">
            Global Supply Chain Monitoring System
        </p>
    </div>

    <!-- KPI -->
    <div class="row g-4 mb-4">

        <!-- Negara -->
        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Total Negara
                            </small>

                            <h2 class="fw-bold mt-2">
                                {{ number_format($totalCountries) }}
                            </h2>

                        </div>

                        <div class="display-5 text-primary">

                            🌍

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Benua -->
        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Total Benua
                            </small>

                            <h2 class="fw-bold mt-2">

                                {{ $totalContinents }}

                            </h2>

                        </div>

                        <div class="display-5 text-success">

                            🌎

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Risk -->
        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">
                                Global Risk
                            </small>

                            <h2 class="fw-bold text-success mt-2">

                                {{ strtoupper($globalRisk) }}

                            </h2>

                        </div>

                        <div class="display-5 text-warning">

                            ⚠️

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <small class="text-muted">Current Time</small>
                    <div id="currentTime" class="fs-4 fw-bold text-primary mt-3">--:--:-- WIB</div>
                </div>
            </div>
        </div>

    </div>

    <!-- Map -->

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white">

            <h5 class="fw-bold mb-0">

                🌍 Peta Monitoring Global

            </h5>

        </div>

        <div class="card-body">

            <div id="map"
                 style="height:500px;border-radius:15px;">

            </div>

        </div>

    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white">
                    <h5 class="fw-bold mb-0">Distribusi Negara per Benua</h5>
                </div>
                <div class="card-body">
                    <canvas id="continentChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white">
                    <h5 class="fw-bold mb-0">Risk Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="riskChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
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
                '<b>' + country.name + '</b><br>' + country.continent
            );
        }
    });

    const timeElement = document.getElementById('currentTime');
    const updateTime = () => {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        if (timeElement) {
            timeElement.textContent = `${hours}:${minutes}:${seconds} WIB`;
        }
    };

    updateTime();
    setInterval(updateTime, 1000);

    const continentChartCtx = document.getElementById('continentChart');
    if (continentChartCtx) {
        new Chart(continentChartCtx, {
            type: 'bar',
            data: {
                labels: @json($continentChart->pluck('continent')),
                datasets: [{
                    label: 'Jumlah Negara',
                    data: @json($continentChart->pluck('total')),
                    backgroundColor: '#3b82f6'
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
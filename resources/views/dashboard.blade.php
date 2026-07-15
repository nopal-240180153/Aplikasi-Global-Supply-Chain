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

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">
                            Total Negara
                        </small>

                        <h2 class="fw-bold mt-2 mb-0">
                            {{ number_format($totalCountries) }}
                        </h2>

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

                        <small class="text-muted">
                            Weather Logs
                        </small>

                        <h2 class="fw-bold mt-2 mb-0">
                            {{ number_format($totalWeather) }}
                        </h2>

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

                        <small class="text-muted">
                            Exchange Rate
                        </small>

                        <h2 class="fw-bold mt-2 mb-0">
                            {{ number_format($totalExchange) }}
                        </h2>

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

                        <small class="text-muted">
                            Economy Records
                        </small>

                        <h2 class="fw-bold mt-2 mb-0">
                            {{ number_format($totalEconomy) }}
                        </h2>

                    </div>

                    <div class="bg-info bg-opacity-10 rounded-circle p-3">

                        <i class="bi bi-bar-chart-line fs-2 text-info"></i>

                    </div>

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
@extends('layouts.admin')

@section('title','Nilai Tukar Mata Uang')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                💱 Nilai Tukar Mata Uang Global
            </h2>
            <p class="text-muted mb-0">
                Monitoring kurs mata uang global real-time berdasarkan Exchange Rate API
            </p>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row g-4 mb-4">
        <!-- Total Exchange -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Total Nilai Tukar</small>
                            <h2 class="fw-bold mt-2 mb-1">{{ number_format($totalExchange) }}</h2>
                            <span class="text-success small">
                                <i class="bi bi-arrow-up-right"></i>
                                {{ number_format($totalExchange) }} Data
                            </span>
                        </div>
                        <div class="text-primary fs-2">
                            <i class="bi bi-currency-exchange"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Currency -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Jenis Mata Uang</small>
                            <h2 class="fw-bold mt-2 mb-1">{{ number_format($currencyCount) }}</h2>
                            <span class="text-primary small">Currency Types</span>
                        </div>
                        <div class="text-success fs-2">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Average -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Kurs Rata-rata</small>
                            <h2 class="fw-bold mt-2 mb-1">{{ number_format($averageRate,2) }}</h2>
                            <span class="text-warning small">Average Rate</span>
                        </div>
                        <div class="text-warning fs-2">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Pembaruan Terakhir</small>
                            <h5 class="fw-bold mt-2 mb-1">
                                {{ \Carbon\Carbon::parse($lastUpdate)->format('d M Y') }}
                            </h5>
                            <span class="text-danger small">
                                {{ \Carbon\Carbon::parse($lastUpdate)->format('H:i') }}
                            </span>
                        </div>
                        <div class="text-danger fs-2">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Dashboard -->
    <div class="row g-4 mb-4">
        
        <!-- Bar Chart - Top Exchange Rates -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1">
                                <i class="bi bi-bar-chart-fill text-primary"></i>
                                Top 10 Nilai Tukar Tertinggi
                            </h5>
                            <p class="text-muted small mb-0">Negara dengan nilai tukar mata uang tertinggi terhadap base currency</p>
                        </div>
                        <span class="badge bg-primary">Top 10</span>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="exchangeBarChart" height="80"></canvas>
                </div>
                <div class="card-footer bg-light border-0">
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i>
                        Nilai tukar lebih tinggi menunjukkan mata uang lokal lebih lemah terhadap base currency
                    </small>
                </div>
            </div>
        </div>

        <!-- Pie Chart - Currency Distribution -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div>
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-pie-chart-fill text-success"></i>
                            Distribusi Mata Uang
                        </h5>
                        <p class="text-muted small mb-0">10 mata uang paling banyak digunakan</p>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="currencyPieChart" height="200"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- Grafik Tambahan -->
    <div class="row g-4 mb-4">
        
        <!-- Horizontal Bar Chart - Exchange Rate Ranges -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div>
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-graph-up text-warning"></i>
                            Range Nilai Tukar
                        </h5>
                        <p class="text-muted small mb-0">Distribusi nilai tukar berdasarkan range</p>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="rangeBarChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Line Chart - Top 5 Currencies Comparison -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-3">
                    <div>
                        <h5 class="fw-bold mb-1">
                            <i class="bi bi-graph-up-arrow text-info"></i>
                            Perbandingan Top 5 Negara
                        </h5>
                        <p class="text-muted small mb-0">Nilai tukar 5 negara teratas</p>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="comparisonLineChart" height="100"></canvas>
                </div>
            </div>
        </div>

    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('exchange.index') }}">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Cari Negara</label>
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Ketik nama negara..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Mata Uang</label>
                        <select name="currency" class="form-select">
                            <option value="">Semua Currency</option>
                            @foreach($exchangeRates->unique('target_currency') as $rate)
                                <option value="{{ $rate->target_currency }}" {{ request('currency') == $rate->target_currency ? 'selected' : '' }}>
                                    {{ $rate->target_currency }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </div>
                @if(request()->hasAny(['search', 'currency']))
                    <div class="mt-3">
                        <a href="{{ route('exchange.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Atur Ulang Filter
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Tabel -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 pt-4 pb-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Data Nilai Tukar Mata Uang</h5>
                @if($exchangeRates->total() > 0)
                    <span class="badge bg-primary">{{ $exchangeRates->total() }} Data</span>
                @endif
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>Negara</th>
                        <th>Mata Uang</th>
                        <th>Base</th>
                        <th>Kurs</th>
                        <th>Kekuatan</th>
                        <th>Update</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($exchangeRates as $exchange)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($exchange->country->flag)
                                        <img src="{{ $exchange->country->flag }}"
                                             width="35"
                                             class="rounded me-2">
                                    @endif
                                    <strong>{{ $exchange->country->name }}</strong>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $exchange->target_currency }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $exchange->base_currency }}</span>
                            </td>
                            <td class="fw-bold text-primary">
                                {{ number_format($exchange->exchange_rate,4) }}
                            </td>
                            <td>
                                @if($exchange->exchange_rate < 1)
                                    <span class="badge bg-success">
                                        <i class="bi bi-arrow-up"></i> Kuat
                                    </span>
                                @elseif($exchange->exchange_rate < 10)
                                    <span class="badge bg-info text-dark">
                                        <i class="bi bi-dash"></i> Moderat
                                    </span>
                                @elseif($exchange->exchange_rate < 100)
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-arrow-down"></i> Lemah
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-arrow-down-circle"></i> Sangat Lemah
                                    </span>
                                @endif
                            </td>
                            <td>
                                {{ $exchange->recorded_at->format('d M Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Tidak ada data nilai tukar yang sesuai dengan filter.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($exchangeRates->hasPages())
            <div class="card-footer bg-white border-0">
                {{ $exchangeRates->links() }}
            </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
// Color Palette
const colors = {
    primary: ['#2563eb', '#3b82f6', '#60a5fa', '#93c5fd', '#bfdbfe', '#dbeafe'],
    success: ['#10b981', '#34d399', '#6ee7b7', '#a7f3d0', '#d1fae5'],
    warning: ['#f59e0b', '#fbbf24', '#fcd34d', '#fde68a', '#fef3c7'],
    danger: ['#ef4444', '#f87171', '#fca5a5', '#fecaca', '#fee2e2'],
    info: ['#06b6d4', '#22d3ee', '#67e8f9', '#a5f3fc', '#cffafe']
};

// 1. Bar Chart - Top 10 Exchange Rates
const barCtx = document.getElementById('exchangeBarChart');
const barChart = new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: [
            @foreach($topExchangeRates as $item)
                "{{ $item->country->name }}",
            @endforeach
        ],
        datasets: [{
            label: 'Nilai Tukar ({{ $topExchangeRates->first()->base_currency ?? 'USD' }} ke Local Currency)',
            data: [
                @foreach($topExchangeRates as $item)
                    {{ $item->exchange_rate }},
                @endforeach
            ],
            backgroundColor: colors.primary,
            borderWidth: 0,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ' + context.parsed.y.toFixed(4);
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString();
                    }
                }
            },
            x: {
                ticks: {
                    maxRotation: 45,
                    minRotation: 45
                }
            }
        }
    }
});

// 2. Pie Chart - Currency Distribution
const pieCtx = document.getElementById('currencyPieChart');
const pieChart = new Chart(pieCtx, {
    type: 'doughnut',
    data: {
        labels: [
            @foreach($currencyDistribution as $item)
                "{{ $item->target_currency }}",
            @endforeach
        ],
        datasets: [{
            label: 'Jumlah Negara',
            data: [
                @foreach($currencyDistribution as $item)
                    {{ $item->count }},
                @endforeach
            ],
            backgroundColor: [
                '#2563eb', '#10b981', '#f59e0b', '#ef4444', '#06b6d4',
                '#8b5cf6', '#ec4899', '#f97316', '#14b8a6', '#6366f1'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    boxWidth: 12,
                    padding: 10,
                    font: {
                        size: 11
                    }
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return label + ': ' + value + ' (' + percentage + '%)';
                    }
                }
            }
        }
    }
});

// 3. Horizontal Bar Chart - Exchange Rate Ranges
const rangeCtx = document.getElementById('rangeBarChart');
const rangeChart = new Chart(rangeCtx, {
    type: 'bar',
    data: {
        labels: [
            @foreach($exchangeRateRanges as $item)
                "{{ $item->rate_range }}",
            @endforeach
        ],
        datasets: [{
            label: 'Jumlah Negara',
            data: [
                @foreach($exchangeRateRanges as $item)
                    {{ $item->count }},
                @endforeach
            ],
            backgroundColor: colors.warning,
            borderWidth: 0,
            borderRadius: 6,
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Negara: ' + context.parsed.x;
                    }
                }
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// 4. Line Chart - Top 5 Countries Comparison
const lineCtx = document.getElementById('comparisonLineChart');
const lineChart = new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: [
            @foreach($topExchangeRates->take(5) as $item)
                "{{ $item->country->name }}",
            @endforeach
        ],
        datasets: [{
            label: 'Nilai Tukar',
            data: [
                @foreach($topExchangeRates->take(5) as $item)
                    {{ $item->exchange_rate }},
                @endforeach
            ],
            borderColor: '#06b6d4',
            backgroundColor: 'rgba(6, 182, 212, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointRadius: 5,
            pointBackgroundColor: '#06b6d4',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Rate: ' + context.parsed.y.toFixed(4);
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>
@endpush

@endsection

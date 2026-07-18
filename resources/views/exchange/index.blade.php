@extends('layouts.admin')

@section('title','Nilai Tukar Mata Uang')

@section('content')

<div class="container-fluid">

    <!-- Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                💱 Nilai Tukar Mata Uang
            </h2>

            <p class="text-muted mb-0">
                Monitoring kurs mata uang global berdasarkan Exchange Rate API.
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

                            <small class="text-muted fw-semibold">
                                Total Nilai Tukar
                            </small>

                            <h2 class="fw-bold mt-2 mb-1">
                                {{ number_format($totalExchange) }}
                            </h2>

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

                            <small class="text-muted fw-semibold">

                                Mata Uang

                            </small>

                            <h2 class="fw-bold mt-2 mb-1">

                                {{ number_format($currencyCount) }}

                            </h2>

                            <span class="text-primary small">

                                Mata Uang

                            </span>

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

                            <small class="text-muted fw-semibold">

                                Kurs Rata-rata

                            </small>

                            <h2 class="fw-bold mt-2 mb-1">

                                {{ number_format($averageRate,2) }}

                            </h2>

                            <span class="text-warning small">

                                Nilai Tukar

                            </span>

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

                            <small class="text-muted fw-semibold">

                                Pembaruan Terakhir

                            </small>

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

    <!-- Grafik -->

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white border-0 pt-4 pb-3">

            <h5 class="fw-bold mb-0">

                Visualisasi Nilai Tukar Mata Uang (Top 10)

            </h5>

        </div>

        <div class="card-body">

            <canvas id="exchangeChart" height="110"></canvas>

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

                                <span class="badge bg-primary">

                                    {{ $exchange->target_currency }}

                                </span>

                            </td>

                            <td>

                                <span class="badge bg-secondary">{{ $exchange->base_currency }}</span>

                            </td>

                            <td class="fw-bold text-primary">

                                {{ number_format($exchange->exchange_rate,4) }}

                            </td>

                            <td>

                                {{ $exchange->recorded_at->format('d M Y H:i') }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="text-center py-5">

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

const ctx = document.getElementById('exchangeChart');

new Chart(ctx, {

    type: 'bar',

    data: {

        labels: [

            @foreach($topExchangeRates as $item)

                "{{ $item->country->name }}",

            @endforeach

        ],

        datasets: [{

            label: 'Nilai Tukar',

            data: [

                @foreach($topExchangeRates as $item)

                    {{ $item->exchange_rate }},

                @endforeach

            ],

            borderWidth: 1,

            borderRadius: 8,

            backgroundColor: [

                '#2563eb',

                '#3b82f6',

                '#60a5fa',

                '#93c5fd',

                '#bfdbfe',

                '#1d4ed8',

                '#2563eb',

                '#3b82f6',

                '#60a5fa',

                '#93c5fd'

            ]

        }]

    },

    options: {

        responsive:true,

        plugins:{

            legend:{

                display:false

            }

        },

        scales:{

            y:{

                beginAtZero:true

            }

        }

    }

});

</script>

@endpush

@endsection

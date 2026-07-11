@extends('layouts.admin')

@section('title','Nilai Tukar Mata Uang')

@section('content')

<div class="container-fluid">

    <!-- Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Nilai Tukar Mata Uang
            </h2>

            <p class="text-muted mb-0">
                Monitoring kurs mata uang global berdasarkan Exchange Rate API.
            </p>

        </div>

    </div>

    <!-- Statistik -->

    <!-- ===================== STATISTIC CARD ===================== -->

    <div class="row g-4 mb-4">

        <!-- Total Exchange -->
        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100 stat-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted fw-semibold">
                                Total Exchange
                            </small>

                            <h2 class="fw-bold mt-2 mb-1">
                                {{ number_format($totalExchange) }}
                            </h2>

                            <span class="text-success small">
                                <i class="bi bi-arrow-up-right"></i>
                                {{ number_format($totalExchange) }} Data
                            </span>

                        </div>

                        <div class="stat-icon bg-primary">

                            <i class="bi bi-currency-exchange"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Currency -->

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100 stat-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted fw-semibold">

                                Currency

                            </small>

                            <h2 class="fw-bold mt-2 mb-1">

                                {{ number_format($currencyCount) }}

                            </h2>

                            <span class="text-primary small">

                                Mata Uang

                            </span>

                        </div>

                        <div class="stat-icon bg-success">

                            <i class="bi bi-cash-stack"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Average -->

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100 stat-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted fw-semibold">

                                Average Rate

                            </small>

                            <h2 class="fw-bold mt-2 mb-1">

                                {{ number_format($averageRate,2) }}

                            </h2>

                            <span class="text-warning small">

                                Exchange Rate

                            </span>

                        </div>

                        <div class="stat-icon bg-warning">

                            <i class="bi bi-graph-up-arrow"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Update -->

        <div class="col-xl-3 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100 stat-card">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <small class="text-muted fw-semibold">

                                Last Update

                            </small>

                            <h5 class="fw-bold mt-2 mb-1">

                                {{ \Carbon\Carbon::parse($lastUpdate)->format('d M Y') }}

                            </h5>

                            <span class="text-danger small">

                                {{ \Carbon\Carbon::parse($lastUpdate)->format('H:i') }}

                            </span>

                        </div>

                        <div class="stat-icon bg-danger">

                            <i class="bi bi-clock-history"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Placeholder Grafik -->

    <div class="card border-0 shadow-sm rounded-4 mb-4">

        <div class="card-header bg-white">

            <strong>

                Visualisasi Nilai Tukar Mata Uang

            </strong>

        </div>

        <div class="card-body">

    <canvas id="exchangeChart" height="110"></canvas>

</div>
<!-- ===================== TOOLBAR ===================== -->

<div class="card border-0 shadow-sm rounded-4 mb-4">

    <div class="card-body">

        <div class="row g-3 align-items-center">

            <!-- Search -->

            <div class="col-lg-4">

                <div class="input-group">

                    <span class="input-group-text bg-white">

                        <i class="bi bi-search"></i>

                    </span>

                    <input
                        type="text"
                        id="searchInput"
                        class="form-control"
                        placeholder="Cari Negara...">

                </div>

            </div>

            <!-- Currency -->

            <div class="col-lg-3">

                <select
                    id="currencyFilter"
                    class="form-select">

                    <option value="">

                        Semua Currency

                    </option>

                    @foreach($exchangeRates->unique('target_currency') as $rate)

                        <option>

                            {{ $rate->target_currency }}

                        </option>

                    @endforeach

                </select>

            </div>

            <!-- Refresh -->

            <div class="col-lg-2">

                <a href="{{ route('exchange.index') }}"
                   class="btn btn-primary w-100">

                    <i class="bi bi-arrow-clockwise"></i>

                    Refresh

                </a>

            </div>

            <!-- Export -->

            <div class="col-lg-3">

                <button
                    class="btn btn-success w-100">

                    <i class="bi bi-download"></i>

                    Export Excel

                </button>

            </div>

        </div>

    </div>

</div>
    </div>

    <!-- Tabel -->

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white">

            <strong>

                Data Nilai Tukar Mata Uang

            </strong>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0" id="exchangeTable">

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

                                    {{ $exchange->country->name }}

                                </div>

                            </td>

                            <td>

                                <span class="badge bg-primary">

                                    {{ $exchange->target_currency }}

                                </span>

                            </td>

                            <td>

                                {{ $exchange->base_currency }}

                            </td>

                            <td class="fw-bold">

                                {{ number_format($exchange->exchange_rate,4) }}

                            </td>

                            <td>

                                {{ $exchange->recorded_at->format('d M Y H:i') }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="5" class="text-center py-5">

                                Belum ada data nilai tukar.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="mt-4">

        {{ $exchangeRates->links() }}

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

            label: 'Exchange Rate',

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
<script>

document.getElementById('searchInput').addEventListener('keyup', function(){

    let value = this.value.toLowerCase();

    document.querySelectorAll('#exchangeTable tbody tr').forEach(function(row){

        row.style.display = row.innerText.toLowerCase().includes(value)
            ? ''
            : 'none';

    });

});

</script>
@endpush
@endsection

@extends('layouts.admin')

@section('title','Monitoring Cuaca')

@section('content')

<div class="container-fluid">

    <!-- Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                🌦️ Monitoring Cuaca Global
            </h2>

            <p class="text-muted mb-0">
                Monitoring cuaca real-time dari Open-Meteo API.
            </p>

        </div>

    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('weather.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Cari Negara</label>
                        <input 
                            type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Ketik nama negara..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Benua</label>
                        <select name="continent" class="form-select">
                            <option value="">Semua Benua</option>
                            @foreach($continents as $cont)
                                <option value="{{ $cont }}" {{ request('continent') == $cont ? 'selected' : '' }}>
                                    {{ $cont }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Kondisi Cuaca</label>
                        <select name="condition" class="form-select">
                            <option value="">Semua Kondisi</option>
                            @foreach($conditions as $cond)
                                <option value="{{ $cond }}" {{ request('condition') == $cond ? 'selected' : '' }}>
                                    {{ $cond }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Cari
                        </button>
                    </div>
                </div>
                @if(request()->hasAny(['search', 'continent', 'condition']))
                    <div class="mt-3">
                        <a href="{{ route('weather.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Atur Ulang Filter
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Statistik -->

    <div class="row g-4 mb-4">

        <div class="col-lg-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Total Monitoring</small>
                            <h2 class="fw-bold mt-2 mb-0">{{ $weatherLogs->total() }}</h2>
                        </div>
                        <div class="text-primary fs-2">
                            <i class="bi bi-cloud"></i>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Rata-rata Suhu</small>
                            <h2 class="fw-bold mt-2 mb-0 text-danger">{{ number_format($averageTemperature,1) }} °C</h2>
                        </div>
                        <div class="text-danger fs-2">
                            <i class="bi bi-thermometer-half"></i>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Risiko Tinggi</small>
                            <h2 class="fw-bold mt-2 mb-0 text-warning">{{ $highRiskCountries }}</h2>
                        </div>
                        <div class="text-warning fs-2">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted fw-semibold">Pembaruan Terakhir</small>
                            <h6 class="mt-2 mb-1">{{ optional($lastUpdate)->format('d M Y') ?? 'T/A' }}</h6>
                            <h5 class="fw-bold text-success mb-0">{{ optional($lastUpdate)->format('H:i') ?? '-' }}</h5>
                        </div>
                        <div class="text-success fs-2">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Table -->

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white border-0 pt-4 pb-3">

            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Data Monitoring Cuaca</h5>
                @if($weatherLogs->total() > 0)
                    <span class="badge bg-primary">{{ $weatherLogs->total() }} Data</span>
                @endif
            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                    <tr>

                        <th>Negara</th>

                        <th>Suhu</th>

                        <th>Angin</th>

                        <th>Curah Hujan</th>

                        <th>Kondisi</th>

                        <th>Risiko Badai</th>

                        <th>Update</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($weatherLogs as $weather)

                        <tr>

                            <td>

                                <div class="d-flex align-items-center">

                                    @if($weather->country->flag)
                                        <img src="{{ $weather->country->flag }}"
                                             width="35"
                                             class="me-2 rounded">
                                    @endif

                                    <strong>{{ $weather->country->name }}</strong>

                                </div>

                            </td>

                            <td>

                                <span class="badge bg-danger">{{ $weather->temperature }} °C</span>

                            </td>

                            <td>

                                {{ $weather->wind_speed }} km/h

                            </td>

                            <td>

                                {{ $weather->rainfall }} mm

                            </td>

                            <td>

                                <span class="badge bg-info text-dark">{{ $weather->weather_condition }}</span>

                            </td>

                            <td>

                                @if($weather->storm_risk < 30)

                                    <span class="badge bg-success">

                                        Rendah ({{ $weather->storm_risk }}%)

                                    </span>

                                @elseif($weather->storm_risk < 70)

                                    <span class="badge bg-warning text-dark">

                                        Sedang ({{ $weather->storm_risk }}%)

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        Tinggi ({{ $weather->storm_risk }}%)

                                    </span>

                                @endif

                            </td>

                            <td>

                                {{ $weather->recorded_at->format('d M Y H:i') }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="text-center p-5">

                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Tidak ada data cuaca yang sesuai dengan filter.
                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($weatherLogs->hasPages())
            <div class="card-footer bg-white border-0">
                {{ $weatherLogs->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
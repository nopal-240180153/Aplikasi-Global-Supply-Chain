@extends('layouts.admin')

@section('title', 'Data Negara')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">🌍 Data Negara</h2>
            <p class="text-muted mb-0">Daftar negara yang terdaftar dalam sistem monitoring rantai pasok global.</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('countries.index') }}">
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
                        <label class="form-label fw-semibold">Region</label>
                        <select name="region" class="form-select">
                            <option value="">Semua Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>
                                    {{ $region }}
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
                @if(request()->hasAny(['search', 'region']))
                    <div class="mt-3">
                        <a href="{{ route('countries.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Atur Ulang Filter
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4">

        <div class="card-header bg-white border-0 pt-4 pb-3">

            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Daftar Negara</h5>
                @if($countries->total() > 0)
                    <span class="badge bg-primary">{{ $countries->total() }} Negara</span>
                @endif
            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                    <tr>

                        <th>Bendera</th>
                        <th>Negara</th>
                        <th>Ibukota</th>
                        <th>Region</th>
                        <th>Mata Uang</th>
                        <th>Populasi</th>
                        <th>Tingkat Risiko</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($countries as $country)

                        <tr>

                            <td width="70">

                                @if($country->flag)

                                    <img
                                        src="{{ $country->flag }}"
                                        width="40"
                                        class="rounded">

                                @endif

                            </td>

                            <td>

                                <strong>{{ $country->name }}</strong>

                            </td>

                            <td>

                                {{ $country->capital ?? '-' }}

                            </td>

                            <td>

                                <span class="badge bg-info text-dark">{{ $country->region ?? '-' }}</span>

                            </td>

                            <td>

                                <span class="badge bg-secondary">{{ $country->currency_code ?? '-' }}</span>

                            </td>

                            <td>

                                {{ number_format($country->population) }}

                            </td>

                            <td>

                                @if($country->risk_level == 'Rendah')
                                    <span class="badge bg-success">Rendah</span>
                                @elseif($country->risk_level == 'Sedang')
                                    <span class="badge bg-warning text-dark">Sedang</span>
                                @elseif($country->risk_level == 'Tinggi')
                                    <span class="badge bg-danger">Tinggi</span>
                                @else
                                    <span class="badge bg-secondary">-</span>
                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="text-center p-5">

                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Tidak ada data negara yang sesuai dengan filter.
                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($countries->hasPages())
            <div class="card-footer bg-white border-0">
                {{ $countries->links() }}
            </div>
        @endif

    </div>

</div>

@endsection
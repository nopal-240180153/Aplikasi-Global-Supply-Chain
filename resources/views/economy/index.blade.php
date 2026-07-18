@extends('layouts.admin')

@section('title','Data Ekonomi')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">

                <i class="bi bi-graph-up-arrow text-primary"></i>

                Data Ekonomi Global

            </h2>

            <p class="text-muted mb-0">

                Data ekonomi negara hasil sinkronisasi World Bank API.

            </p>

        </div>

    </div>

    <!-- Filter Section -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('economy.index') }}">
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
                            @foreach($regions as $reg)
                                <option value="{{ $reg }}" {{ request('region') == $reg ? 'selected' : '' }}>
                                    {{ $reg }}
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
                        <a href="{{ route('economy.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x-circle"></i> Atur Ulang Filter
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card shadow border-0 rounded-4">

        <div class="card-header bg-white border-0 pt-4 pb-3">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <h5 class="mb-0 fw-bold">

                        Daftar Data Ekonomi

                    </h5>

                </div>

                <div class="col-md-6 text-end">

                    <span class="badge bg-primary">

                        Total : {{ $economies->total() }} Data

                    </span>

                </div>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-striped mb-0 align-middle">

                    <thead class="table-light">

                    <tr>

                        <th width="60">No</th>

                        <th>Negara</th>

                        <th>GDP (USD)</th>

                        <th>Inflasi (%)</th>

                        <th>Populasi</th>

                        <th>Ekspor</th>

                        <th>Impor</th>

                        <th>Tahun</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($economies as $economy)

                        <tr>

                            <td>

                                {{ $loop->iteration + ($economies->currentPage()-1)*$economies->perPage() }}

                            </td>

                            <td>

                                <div class="d-flex align-items-center">

                                    @if($economy->country->flag)
                                        <img src="{{ $economy->country->flag }}"
                                             width="30"
                                             class="rounded me-2">
                                    @endif

                                    <strong>{{ $economy->country->name }}</strong>

                                </div>

                            </td>

                            <td>

                                ${{ number_format($economy->gdp,2) }}

                            </td>

                            <td>

                                <span class="badge bg-{{ $economy->inflation > 5 ? 'danger' : 'info' }}">

                                    {{ number_format($economy->inflation,2) }} %

                                </span>

                            </td>

                            <td>

                                {{ number_format($economy->population) }}

                            </td>

                            <td>

                                ${{ number_format($economy->exports,2) }}

                            </td>

                            <td>

                                ${{ number_format($economy->imports,2) }}

                            </td>

                            <td>

                                <span class="badge bg-secondary">{{ $economy->year }}</span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="text-center p-5">

                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    Tidak ada data ekonomi yang sesuai dengan filter.
                                </div>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        @if($economies->hasPages())
            <div class="card-footer bg-white border-0">

                {{ $economies->links() }}

            </div>
        @endif

    </div>

</div>

@endsection
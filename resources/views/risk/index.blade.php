@extends('layouts.admin')

@section('title', 'Analisis Risiko')

@section('content')

<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Analisis Risiko Supply Chain
            </h2>

            <p class="text-muted mb-0">
                Monitoring risiko rantai pasok global berdasarkan cuaca, ekonomi, nilai tukar, dan berita.
            </p>
        </div>



    </div>

    <!-- Statistik -->

    <div class="row g-4 mb-4">

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Total Negara

                            </small>

                            <h2 class="fw-bold mt-2">

                                {{ number_format($totalCountry) }}

                            </h2>

                        </div>

                        <div class="display-5 text-primary">

                            <i class="fas fa-globe-asia"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Risiko Rendah

                            </small>

                            <h2 class="fw-bold text-success mt-2">

                                {{ $lowRisk }}

                            </h2>

                        </div>

                        <div class="display-5 text-success">

                            <i class="fas fa-check-circle"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Risiko Sedang

                            </small>

                            <h2 class="fw-bold text-warning mt-2">

                                {{ $mediumRisk }}

                            </h2>

                        </div>

                        <div class="display-5 text-warning">

                            <i class="fas fa-exclamation-circle"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="card border-0 shadow-sm rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Risiko Tinggi

                            </small>

                            <h2 class="fw-bold text-danger mt-2">

                                {{ $highRisk }}

                            </h2>

                        </div>

                        <div class="display-5 text-danger">

                            <i class="fas fa-radiation"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Tabel -->

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white">

            <div class="row align-items-center">

                <div class="col-md-6">

                    <h5 class="fw-bold mb-0">

                        Data Analisis Risiko

                    </h5>

                </div>

                <div class="col-md-6">

                    <input
                        type="text"
                        id="searchRisk"
                        class="form-control"
                        placeholder="Cari Negara...">

                </div>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>No</th>

                            <th>Negara</th>

                            <th>Weather</th>

                            <th>Economy</th>

                            <th>Exchange</th>

                            <th>News</th>

                            <th>Total</th>

                            <th>Level Risiko</th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($risks as $index => $risk)

                        <tr>

                            <td>

                                {{ $risks->firstItem() + $index }}

                            </td>

                            <td>

                                <div class="fw-bold">

                                    {{ $risk->country->name }}

                                </div>

                                <small class="text-muted">

                                    {{ $risk->country->region }}

                                </small>

                            </td>

                            <td>

                                {{ number_format($risk->weather_score,1) }}

                            </td>

                            <td>

                                {{ number_format($risk->economy_score,1) }}

                            </td>

                            <td>

                                {{ number_format($risk->exchange_score,1) }}

                            </td>

                            <td>

                                {{ number_format($risk->news_score,1) }}

                            </td>

                            <td width="220">

                                <div class="progress"
                                     style="height:18px;">

                                    @php

                                        $color = 'bg-success';

                                        if($risk->total_score >= 35){

                                            $color = 'bg-danger';

                                        }elseif($risk->total_score >=20){

                                            $color = 'bg-warning';

                                        }

                                    @endphp

                                    <div
                                        class="progress-bar {{ $color }}"
                                        role="progressbar"
                                        style="width: {{ min($risk->total_score,100) }}%;">

                                        {{ number_format($risk->total_score,1) }}

                                    </div>

                                </div>

                            </td>

                            <td>

                                @if($risk->risk_level=='Rendah')

                                    <span class="badge bg-success">

                                        Rendah

                                    </span>

                                @elseif($risk->risk_level=='Sedang')

                                    <span class="badge bg-warning text-dark">

                                        Sedang

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        Tinggi

                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8"
                                class="text-center py-5">

                                <h5 class="text-muted">

                                    Belum ada data analisis risiko.

                                </h5>

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <div class="card-footer bg-white">

            {{ $risks->links() }}

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

document.getElementById('searchRisk').addEventListener('keyup', function(){

    let keyword = this.value.toLowerCase();

    let rows = document.querySelectorAll('tbody tr');

    rows.forEach(function(row){

        row.style.display = row.innerText.toLowerCase().includes(keyword)

            ? ''

            : 'none';

    });

});



</script>

@endpush
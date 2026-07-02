@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<h2 class="mb-4">
    Dashboard Pemantauan Rantai Pasok Global
</h2>

<div class="row">

    <div class="col-lg-3 col-md-6 mb-3">

        <div class="card card-dashboard border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">
                            Total Negara
                        </small>

                        <h2 class="fw-bold mt-2">
                            {{ number_format($totalCountries) }}
                        </h2>

                    </div>

                    <div class="fs-1 text-primary">
                        🌍
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6 mb-3">

        <div class="card card-dashboard border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">
                            Risiko Tinggi
                        </small>

                        <h2 class="fw-bold mt-2">
                            0
                        </h2>

                    </div>

                    <div class="fs-1 text-danger">
                        ⚠️
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6 mb-3">

        <div class="card card-dashboard border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">
                            Berita Hari Ini
                        </small>

                        <h2 class="fw-bold mt-2">
                            0
                        </h2>

                    </div>

                    <div class="fs-1 text-warning">
                        📰
                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-6 mb-3">

        <div class="card card-dashboard border-0 shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <small class="text-muted">
                            Nilai Tukar
                        </small>

                        <h2 class="fw-bold mt-2">
                            -
                        </h2>

                    </div>

                    <div class="fs-1 text-success">
                        💱
                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="card card-dashboard mt-4 border-0 shadow-sm">

    <div class="card-body">

        <h5 class="mb-3">
            🌎 Peta Monitoring Global
        </h5>

        <div id="map" style="height:500px;border-radius:12px;"></div>

    </div>

</div>

@endsection

@push('scripts')

<script>

const map = L.map('map').setView([20,0],2);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom:19,
}).addTo(map);

</script>

@endpush
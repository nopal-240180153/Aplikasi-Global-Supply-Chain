@extends('layouts.admin')

@section('title', 'Sinkronisasi Data')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Sinkronisasi Data
            </h2>

            <p class="text-muted mb-0">
                Kelola sinkronisasi seluruh data eksternal ke database lokal.
            </p>

        </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="row g-4">

        <!-- ================= COUNTRY ================= -->

        <div class="col-lg-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        🌍 Master Data Negara
                    </h5>

                </div>

                <div class="card-body">

                    <p class="text-muted">
                        Sinkronisasi seluruh data negara dari REST Countries API.
                    </p>

                    <table class="table table-borderless align-middle">

                        <tr>

                            <td width="180">
                                Status
                            </td>

                            <td>

                                @if($countrySync)

                                    @if($countrySync->status == 'Success')

                                        <span class="badge bg-success">

                                            Success

                                        </span>

                                    @elseif($countrySync->status == 'Running')

                                        <span class="badge bg-warning">

                                            Running

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            Failed

                                        </span>

                                    @endif

                                @else

                                    <span class="badge bg-secondary">

                                        Belum Pernah

                                    </span>

                                @endif

                            </td>

                        </tr>

                        <tr>

                            <td>

                                Last Sync

                            </td>

                            <td>

                                {{ optional($countrySync?->finished_at)->format('d M Y H:i') ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <td>

                                Total Data

                            </td>

                            <td>

                                {{ $countrySync->updated_data ?? 0 }}

                            </td>

                        </tr>

                        <tr>

                            <td>

                                Durasi

                            </td>

                            <td>

                                {{ $countrySync->duration ?? '-' }} detik

                            </td>

                        </tr>

                    </table>

                    <form method="POST"
                          action="{{ route('sync.countries') }}">

                        @csrf

                        <button class="btn btn-primary">

                            <i class="bi bi-arrow-repeat"></i>

                            Sinkronisasi Negara

                        </button>

                    </form>

                </div>

            </div>

        </div>

        <!-- ================= WEATHER ================= -->

        <div class="col-lg-6">

            <div class="card shadow-sm border-0 h-100">

                <div class="card-header bg-white">

                    <h5 class="mb-0">
                        🌦 Monitoring Cuaca
                    </h5>

                </div>

                <div class="card-body">

                    <p class="text-muted">
                        Sinkronisasi data cuaca terbaru dari Open-Meteo API.
                    </p>

                    <table class="table table-borderless align-middle">

                        <tr>

                            <td width="180">
                                Status
                            </td>

                            <td>

                                @if($weatherSync)

                                    @if($weatherSync->status == 'Success')

                                        <span class="badge bg-success">

                                            Success

                                        </span>

                                    @elseif($weatherSync->status == 'Running')

                                        <span class="badge bg-warning">

                                            Running

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            Failed

                                        </span>

                                    @endif

                                @else

                                    <span class="badge bg-secondary">

                                        Belum Pernah

                                    </span>

                                @endif

                            </td>

                        </tr>

                        <tr>

                            <td>

                                Last Sync

                            </td>

                            <td>

                                {{ optional($weatherSync?->finished_at)->format('d M Y H:i') ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <td>

                                Total Data

                            </td>

                            <td>

                                {{ $weatherSync->updated_data ?? 0 }}

                            </td>

                        </tr>

                        <tr>

                            <td>

                                Durasi

                            </td>

                            <td>

                                {{ $weatherSync->duration ?? '-' }} detik

                            </td>

                        </tr>

                    </table>

                    <form method="POST"
                          action="{{ route('sync.weather') }}">

                        @csrf

                        <button class="btn btn-success">

                            <i class="bi bi-cloud-arrow-down"></i>

                            Sinkronisasi Cuaca

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
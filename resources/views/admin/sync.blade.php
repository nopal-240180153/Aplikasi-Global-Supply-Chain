@extends('layouts.admin')

@section('title', 'Sinkronisasi Data')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">
                <i class="bi bi-arrow-repeat text-primary"></i>
                Sinkronisasi Data
            </h2>

            <p class="text-muted mb-0">
                Kelola sinkronisasi seluruh data eksternal ke database lokal.
            </p>

        </div>

    </div>

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="bi bi-check-circle-fill"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="bi bi-exclamation-circle-fill"></i>

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif

    <div class="row g-4">

        <!-- ================= COUNTRY ================= -->

        <div class="col-lg-6">

            <div class="card shadow border-0 h-100">

                <div class="card-header bg-primary text-white">

                    <h5 class="mb-0">

                        🌍 Master Data Negara

                    </h5>

                </div>

                <div class="card-body">

                    <p class="text-muted">

                        Sinkronisasi seluruh data negara dari REST Countries API.

                    </p>

                    <table class="table table-borderless">

                        <tr>

                            <td width="160">

                                Status

                            </td>

                            <td>

                                @if($countrySync)

                                    @if($countrySync->status=='Success')

                                        <span class="badge bg-success">

                                            Berhasil

                                        </span>

                                    @elseif($countrySync->status=='Running')

                                        <span class="badge bg-warning text-dark">

                                            Berjalan

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            Gagal

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

                            <td>Sinkronisasi Terakhir</td>

                            <td>

                                {{ optional($countrySync?->finished_at)->format('d M Y H:i') ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <td>Total Data</td>

                            <td>

                                {{ $countrySync->updated_data ?? 0 }}

                            </td>

                        </tr>

                        <tr>

                            <td>Durasi</td>

                            <td>

                                {{ $countrySync->duration ?? '-' }} detik

                            </td>

                        </tr>

                    </table>

                    <form method="POST"

                          action="{{ route('sync.countries') }}">

                        @csrf

                        <button class="btn btn-primary w-100">

                            <i class="bi bi-arrow-repeat"></i>

                            Sinkronisasi Negara

                        </button>

                    </form>

                </div>

            </div>

        </div>

               <!-- ================= WEATHER ================= -->

        <div class="col-lg-6">

            <div class="card shadow border-0 h-100">

                <div class="card-header bg-success text-white">

                    <h5 class="mb-0">

                        🌦 Monitoring Cuaca

                    </h5>

                </div>

                <div class="card-body">

                    <p class="text-muted">

                        Sinkronisasi data cuaca terbaru dari Open-Meteo API.

                    </p>

                    <table class="table table-borderless">

                        <tr>

                            <td width="160">Status</td>

                            <td>

                                @if($weatherSync)

                                    @if($weatherSync->status=='Success')

                                        <span class="badge bg-success">Berhasil</span>

                                    @elseif($weatherSync->status=='Running')

                                        <span class="badge bg-warning text-dark">Berjalan</span>

                                    @else

                                        <span class="badge bg-danger">Gagal</span>

                                    @endif

                                @else

                                    <span class="badge bg-secondary">Belum Pernah</span>

                                @endif

                            </td>

                        </tr>

                        <tr>

                            <td>Sinkronisasi Terakhir</td>

                            <td>

                                {{ optional($weatherSync?->finished_at)->format('d M Y H:i') ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <td>Total Data</td>

                            <td>

                                {{ $weatherSync->updated_data ?? 0 }}

                            </td>

                        </tr>

                        <tr>

                            <td>Durasi</td>

                            <td>

                                {{ $weatherSync->duration ?? '-' }} detik

                            </td>

                        </tr>

                    </table>

                    <form method="POST"

                          action="{{ route('sync.weather') }}">

                        @csrf

                        <button class="btn btn-success w-100">

                            <i class="bi bi-cloud-arrow-down"></i>

                            Sinkronisasi Cuaca

                        </button>

                    </form>

                </div>

            </div>

        </div>

    <!-- Row continues -->

        <!-- ================= EXCHANGE RATE ================= -->

        <div class="col-lg-6">

            <div class="card shadow border-0 h-100">

                <div class="card-header bg-warning">

                    <h5 class="mb-0">

                        💱 Exchange Rate

                    </h5>

                </div>

                <div class="card-body">

                    <p class="text-muted">

                        Sinkronisasi kurs mata uang terbaru dari Exchange Rate API.

                    </p>

                    <table class="table table-borderless">

                        <tr>

                            <td width="160">Status</td>

                            <td>

                                @if($exchangeRateSync)

                                    @if($exchangeRateSync->status=='Success')

                                        <span class="badge bg-success">Berhasil</span>

                                    @elseif($exchangeRateSync->status=='Running')

                                        <span class="badge bg-warning text-dark">Berjalan</span>

                                    @else

                                        <span class="badge bg-danger">Gagal</span>

                                    @endif

                                @else

                                    <span class="badge bg-secondary">Belum Pernah</span>

                                @endif

                            </td>

                        </tr>

                        <tr>

                            <td>Sinkronisasi Terakhir</td>

                            <td>

                                {{ optional($exchangeRateSync?->finished_at)->format('d M Y H:i') ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <td>Total Data</td>

                            <td>

                                {{ $exchangeRateSync->updated_data ?? 0 }}

                            </td>

                        </tr>

                        <tr>

                            <td>Durasi</td>

                            <td>

                                {{ $exchangeRateSync->duration ?? '-' }} detik

                            </td>

                        </tr>

                    </table>

                    <form method="POST"

                          action="{{ route('sync.exchange-rate') }}">

                        @csrf

                        <button class="btn btn-warning w-100">

                            <i class="bi bi-currency-exchange"></i>

                            Sinkronisasi Exchange Rate

                        </button>

                    </form>

                </div>

            </div>

        </div>

        <!-- ================= ECONOMY ================= -->

        <div class="col-lg-6">

            <div class="card shadow border-0 h-100">

                <div class="card-header bg-info text-white">

                    <h5 class="mb-0">

                        📊 Data Ekonomi

                    </h5>

                </div>

                <div class="card-body">

                    <p class="text-muted">

                        Sinkronisasi data ekonomi global dari World Bank API.

                    </p>

                    <table class="table table-borderless">

                        <tr>

                            <td width="160">

                                Status

                            </td>

                            <td>

                                @if($economySync)

                                    @if($economySync->status == 'Success')

                                        <span class="badge bg-success">

                                            Berhasil

                                        </span>

                                    @elseif($economySync->status == 'Running')

                                        <span class="badge bg-warning text-dark">

                                            Berjalan

                                        </span>

                                    @else

                                        <span class="badge bg-danger">

                                            Gagal

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

                            Sinkronisasi Terakhir

                        </td>

                            <td>

                                {{ optional($economySync?->finished_at)->format('d M Y H:i') ?? '-' }}

                            </td>

                        </tr>

                        <tr>

                            <td>

                                Total Data

                            </td>

                            <td>

                                {{ $economySync->updated_data ?? 0 }}

                            </td>

                        </tr>

                        <tr>

                            <td>

                                Durasi

                            </td>

                            <td>

                                {{ $economySync->duration ?? '-' }} detik

                            </td>

                        </tr>

                    </table>

                    <form method="POST"
                          action="{{ route('sync.economy') }}">

                        @csrf

                        <button class="btn btn-info text-white w-100">

                            <i class="bi bi-bar-chart-line-fill"></i>

                            Sinkronisasi Data Ekonomi

                        </button>

                    </form>

                </div>

            </div>

        </div>

    <!-- Row continues -->

    <!-- ================= ANALISIS RISIKO ================= -->

    <div class="col-lg-6">

        <div class="card shadow border-0 h-100">

            <div class="card-header bg-danger text-white">

                <h5 class="mb-0">

                    ⚠️ Analisis Risiko Supply Chain

                </h5>

            </div>

            <div class="card-body">

                <p class="text-muted">

                    Menghitung tingkat risiko rantai pasok berdasarkan
                    data cuaca, ekonomi, nilai tukar, dan berita.

                </p>

                <table class="table table-borderless">

                    <tr>

                        <td width="160">

                            Status

                        </td>

                        <td>

                            @if(isset($riskSync))

                                @if($riskSync->status == 'Success')

                                    <span class="badge bg-success">

                                        Berhasil

                                    </span>

                                @elseif($riskSync->status == 'Processing')

                                    <span class="badge bg-warning text-dark">

                                        Memproses

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        Gagal

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

                        <td>Last Sync</td>

                        <td>

                            {{ optional($riskSync?->finished_at)->format('d M Y H:i') ?? '-' }}

                        </td>

                    </tr>

                    <tr>

                        <td>Total Data</td>

                        <td>

                            {{ $riskSync->updated_data ?? 0 }}

                        </td>

                    </tr>

                    <tr>

                        <td>Durasi</td>

                        <td>

                            {{ $riskSync->duration ?? '-' }} detik

                        </td>

                    </tr>

                </table>

                <form method="POST"
                      action="{{ route('sync.risk') }}">

                    @csrf

                    <button class="btn btn-danger w-100">

                        <i class="bi bi-shield-exclamation"></i>

                        Sinkronisasi Analisis Risiko

                    </button>

                </form>

            </div>

        </div>

    </div>
    <!-- ================= BERITA ================= -->

    <div class="col-lg-6">

        <div class="card shadow border-0 h-100">

            <div class="card-header bg-secondary text-white">

                <h5 class="mb-0">

                    📰 Sinkronisasi Berita

                </h5>

            </div>

            <div class="card-body">

                <p class="text-muted">

                    Mengambil berita global terbaru berdasarkan negara.

                </p>

                <table class="table table-borderless">

                    <tr>

                        <td width="160">

                            Status

                        </td>

                        <td>

                            @if($newsSync)

                                @if($newsSync->status == 'Success')

                                    <span class="badge bg-success">

                                        Berhasil

                                    </span>

                                @elseif($newsSync->status == 'Running')

                                    <span class="badge bg-warning text-dark">

                                        Berjalan

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        Gagal

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

                        <td>Last Sync</td>

                        <td>

                            {{ optional($newsSync?->finished_at)->format('d M Y H:i') ?? '-' }}

                        </td>

                    </tr>

                    <tr>

                        <td>Total Data</td>

                        <td>

                            {{ $newsSync->updated_data ?? 0 }}

                        </td>

                    </tr>

                    <tr>

                        <td>Durasi</td>

                        <td>

                            {{ $newsSync->duration ?? '-' }} detik

                        </td>

                    </tr>

                </table>

                <form method="POST"
                      action="{{ route('sync.news') }}">

                    @csrf

                    <button class="btn btn-secondary w-100">

                        <i class="bi bi-newspaper"></i>

                        Sinkronisasi Berita

                    </button>

                </form>

            </div>

        </div>

    </div>

    <!-- ================= PELABUHAN ================= -->

    <div class="col-lg-6">

        <div class="card shadow border-0 h-100">

            <div class="card-header bg-dark text-white">

                <h5 class="mb-0">

                    🚢 Data Pelabuhan

                </h5>

            </div>

            <div class="card-body">

                <p class="text-muted">

                    Sinkronisasi data pelabuhan global dari World Port Index.

                </p>

                <table class="table table-borderless">

                    <tr>

                        <td width="160">

                            Status

                        </td>

                        <td>

                            @if($portSync)

                                @if($portSync->status == 'Success')

                                    <span class="badge bg-success">

                                        Berhasil

                                    </span>

                                @elseif($portSync->status == 'Running')

                                    <span class="badge bg-warning text-dark">

                                        Berjalan

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        Gagal

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

                        <td>Sinkronisasi Terakhir</td>

                        <td>

                            {{ optional($portSync?->finished_at)->format('d M Y H:i') ?? '-' }}

                        </td>

                    </tr>

                    <tr>

                        <td>Total Data</td>

                        <td>

                            {{ $portSync->updated_data ?? 0 }}

                        </td>

                    </tr>

                    <tr>

                        <td>Durasi</td>

                        <td>

                            {{ $portSync->duration ?? '-' }} detik

                        </td>

                    </tr>

                </table>

                <form method="POST"
                      action="{{ route('sync.ports') }}">

                    @csrf

                    <button class="btn btn-dark w-100">

                        <i class="bi bi-geo-alt-fill"></i>

                        Sinkronisasi Pelabuhan

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

</div>
@endsection
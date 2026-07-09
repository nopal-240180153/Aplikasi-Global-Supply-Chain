@extends('layouts.admin')

@section('title','Monitoring Cuaca')

@section('content')

<div class="container-fluid">

    <!-- Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">
                Monitoring Cuaca Global
            </h2>

            <p class="text-muted mb-0">
                Real-time weather monitoring from Open-Meteo API.
            </p>

        </div>

    </div>

    <!-- Statistik -->

    <div class="row g-4 mb-4">

        <div class="col-lg-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <small class="text-muted">

                        Total Monitoring

                    </small>

                    <h2 class="fw-bold mt-2">

                        {{ $weatherLogs->total() }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <small class="text-muted">

                        Rata-rata Suhu

                    </small>

                    <h2 class="fw-bold mt-2 text-danger">

                        {{ number_format($weatherLogs->avg('temperature'),1) }} °C

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <small class="text-muted">

                        Rata-rata Angin

                    </small>

                    <h2 class="fw-bold mt-2 text-primary">

                        {{ number_format($weatherLogs->avg('wind_speed'),1) }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="card border-0 shadow-sm rounded-4">

                <div class="card-body">

                    <small class="text-muted">

                        Last Update

                    </small>

                    <h6 class="mt-3">

                        {{ now()->format('d M Y') }}

                    </h6>

                    <h4 class="fw-bold text-success">

                        {{ now()->format('H:i') }}

                    </h4>

                </div>

            </div>

        </div>

    </div>

    <!-- Table -->

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-header bg-white">

            <strong>

                Data Monitoring Cuaca

            </strong>

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

                        <th>Storm Risk</th>

                        <th>Update</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($weatherLogs as $weather)

                        <tr>

                            <td>

                                <div class="d-flex align-items-center">

                                    <img src="{{ $weather->country->flag }}"
                                         width="35"
                                         class="me-2 rounded">

                                    {{ $weather->country->name }}

                                </div>

                            </td>

                            <td>

                                {{ $weather->temperature }} °C

                            </td>

                            <td>

                                {{ $weather->wind_speed }} km/h

                            </td>

                            <td>

                                {{ $weather->rainfall }} mm

                            </td>

                            <td>

                                {{ $weather->weather_condition }}

                            </td>

                            <td>

                                @if($weather->storm_risk < 30)

                                    <span class="badge bg-success">

                                        Low

                                    </span>

                                @elseif($weather->storm_risk < 70)

                                    <span class="badge bg-warning">

                                        Medium

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        High

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

                                Belum ada data cuaca.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="mt-4">

        {{ $weatherLogs->links() }}

    </div>

</div>

@endsection
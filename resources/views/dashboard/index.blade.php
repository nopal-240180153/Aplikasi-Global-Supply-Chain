@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="fw-bold mb-1">
            Dashboard
        </h2>

        <p class="text-muted">
            Global Supply Chain Monitoring System
        </p>

    </div>

    <div class="row g-4">

        <div class="col-lg-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Total Negara
                    </small>

                    <h2 class="fw-bold mt-2">

                        {{ number_format($totalCountries) }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Total Populasi
                    </small>

                    <h2 class="fw-bold mt-2">

                        {{ number_format($totalPopulation) }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Total Region
                    </small>

                    <h2 class="fw-bold mt-2">

                        {{ $regions }}

                    </h2>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6">

            <div class="card shadow-sm h-100">

                <div class="card-body">

                    <small class="text-muted">
                        Status Sinkronisasi
                    </small>

                    <h5 class="mt-2">

                        @if($lastSync)

                            <span class="badge bg-success">

                                {{ $lastSync->status }}

                            </span>

                        @else

                            <span class="badge bg-secondary">

                                Belum Ada

                            </span>

                        @endif

                    </h5>

                </div>

            </div>

        </div>

    </div>

    <div class="card shadow-sm mt-4">

        <div class="card-header">

            <strong>

                10 Negara Terakhir

            </strong>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover mb-0">

                    <thead>

                        <tr>

                            <th>Bendera</th>

                            <th>Negara</th>

                            <th>Region</th>

                            <th>Mata Uang</th>

                            <th>Risk</th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach($latestCountries as $country)

                        <tr>

                            <td width="70">

                                @if($country->flag)

                                    <img
                                        src="{{ $country->flag }}"
                                        width="40">

                                @endif

                            </td>

                            <td>

                                {{ $country->name }}

                            </td>

                            <td>

                                {{ $country->region }}

                            </td>

                            <td>

                                {{ $country->currency_code }}

                            </td>

                            <td>

                                <span class="badge bg-success">

                                    {{ $country->risk_level }}

                                </span>

                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
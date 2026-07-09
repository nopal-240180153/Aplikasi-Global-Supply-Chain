@extends('layouts.admin')

@section('title', 'Data Negara')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header bg-white">

            <div class="row align-items-center">

                <div class="col-md-4">
                    <h5 class="mb-0">
                        🌍 Data Negara
                    </h5>
                </div>

                <div class="col-md-8">

                    <form method="GET">

                        <div class="row g-2">

                            <div class="col-md-5">

                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    class="form-control"
                                    placeholder="Cari negara...">

                            </div>

                            <div class="col-md-4">

                                <select
                                    name="region"
                                    class="form-select">

                                    <option value="">
                                        Semua Region
                                    </option>

                                    @foreach($regions as $region)

                                        <option
                                            value="{{ $region }}"
                                            {{ request('region') == $region ? 'selected' : '' }}>

                                            {{ $region }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-3">

                                <button class="btn btn-primary w-100">

                                    Cari

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

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
                        <th>Risk</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($countries as $country)

                        <tr>

                            <td width="70">

                                @if($country->flag)

                                    <img
                                        src="{{ $country->flag }}"
                                        width="45">

                                @endif

                            </td>

                            <td>

                                <strong>{{ $country->name }}</strong>

                            </td>

                            <td>

                                {{ $country->capital }}

                            </td>

                            <td>

                                {{ $country->region }}

                            </td>

                            <td>

                                {{ $country->currency_code }}

                            </td>

                            <td>

                                {{ number_format($country->population) }}

                            </td>

                            <td>

                                <span class="badge bg-success">

                                    {{ $country->risk_level }}

                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="text-center p-5">

                                Tidak ada data.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <div class="card-footer">

            {{ $countries->links() }}

        </div>

    </div>

</div>

@endsection
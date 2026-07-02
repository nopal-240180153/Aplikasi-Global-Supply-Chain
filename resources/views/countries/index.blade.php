@extends('layouts.admin')

@section('title', 'Data Negara')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                🌍 Data Negara Dunia
            </h5>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover table-bordered align-middle">

                    <thead class="table-light">

                        <tr>

                            <th width="70">Bendera</th>
                            <th>Negara</th>
                            <th>Ibukota</th>
                            <th>Wilayah</th>
                            <th>Mata Uang</th>
                            <th class="text-end">Populasi</th>

                        </tr>

                    </thead>

                    <tbody>

                    @foreach($countries as $country)

                        @php

                            $flag = $country['flag']['url_png'] ?? '';

                            $nama = $country['names']['common'] ?? '-';

                            $capital = $country['capitals'][0]['name'] ?? '-';

                            $region = $country['region'] ?? '-';

                            $population = number_format($country['population'] ?? 0);

                            $currency = $country['currencies'][0]['code'] ?? '-';

                        @endphp

                        <tr>

                            <td>

                                @if(!empty($flag))

                                    <img src="{{ $flag }}"
                                         width="45">

                                @else

                                    -

                                @endif

                            </td>

                            <td>

                                {{ $nama }}

                            </td>

                            <td>

                                {{ $capital }}

                            </td>

                            <td>

                                {{ $region }}

                            </td>

                            <td>

                                {{ $currency }}

                            </td>

                            <td class="text-end">

                                {{ $population }}

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
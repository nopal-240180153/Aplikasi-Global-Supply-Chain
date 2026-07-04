@extends('layouts.admin')

@section('title','Monitoring Cuaca')

@section('content')

<div class="container-fluid">

    <h3 class="mb-4">
        🌦 Monitoring Cuaca
    </h3>
<form method="GET" action="{{ route('weather.index') }}" class="mb-4">

    <div class="row">

        <div class="col-md-6">

            <label class="form-label">
                Pilih Negara
            </label>

            <select
    name="country"
    class="form-select"
    onchange="this.form.submit()">

    @foreach($countries as $item)

    <option
        value="{{ $item['uuid'] }}"
        {{ request('country') == $item['uuid'] ? 'selected' : '' }}>

        {{ $item['names']['common'] }}

    </option>

    @endforeach

</select>

        </div>

    </div>

</form>
    <div class="card shadow-sm">

        <div class="card-header bg-primary text-white">
    Cuaca Saat Ini
    @if(isset($country))
        ({{ $country['names']['common'] }})
    @endif
</div>

        <div class="card-body">

            @if($weather)

                <table class="table table-bordered">

                    <tr>
                        <th>Suhu</th>
                        <td>{{ $weather['current']['temperature_2m'] }} °C</td>
                    </tr>

                    <tr>
                        <th>Kelembapan</th>
                        <td>{{ $weather['current']['relative_humidity_2m'] }} %</td>
                    </tr>

                    <tr>
                        <th>Kecepatan Angin</th>
                        <td>{{ $weather['current']['wind_speed_10m'] }} km/jam</td>
                    </tr>

                    <tr>
                        <th>Kode Cuaca</th>
                        <td>{{ $weather['current']['weather_code'] }}</td>
                    </tr>

                </table>

            @else

                <div class="alert alert-danger">
                    Gagal mengambil data cuaca.
                </div>

            @endif

        </div>

    </div>

</div>

@endsection
@extends('layouts.admin')

@section('title','News Intelligence')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="fw-bold">

            News Intelligence

        </h2>

        <p class="text-muted">

            Monitoring berita global berdasarkan negara.

        </p>

    </div>

    <div class="card shadow-sm border-0 rounded-4 mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row">

                    <div class="col-md-5">

                        <label class="form-label">

                            Negara

                        </label>

                        <select
                            name="country"
                            class="form-select select2">

                            <option value="">

                                Semua Negara

                            </option>

                            @foreach($countries as $country)

                                <option
                                    value="{{ $country->id }}"
                                    {{ $countryId == $country->id ? 'selected' : '' }}>

                                    {{ $country->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-2 d-flex align-items-end">

                        <button
                            class="btn btn-primary w-100">

                            Cari

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <div class="card shadow-sm border-0 rounded-4">

        <div class="card-header bg-white">

            <h5 class="fw-bold mb-0">

                Daftar Berita

            </h5>

        </div>

        <div class="card-body table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                <tr>

                    <th>Negara</th>

                    <th>Judul</th>

                    <th>Sumber</th>

                    <th>Sentimen</th>

                    <th>Tanggal</th>

                    <th>Aksi</th>

                </tr>

                </thead>

                <tbody>

                @forelse($news as $item)

                    <tr>

                        <td>

                            {{ $item->country?->name }}

                        </td>

                        <td>

                            {{ $item->title }}

                        </td>

                        <td>

                            {{ $item->source }}

                        </td>

                        <td>

                            @if($item->sentiment=='Positive')

                                <span class="badge bg-success">

                                    Positif

                                </span>

                            @elseif($item->sentiment=='Negative')

                                <span class="badge bg-danger">

                                    Negatif

                                </span>

                            @else

                                <span class="badge bg-warning text-dark">

                                    Netral

                                </span>

                            @endif

                        </td>

                        <td>

                            {{ optional($item->published_at)->format('d-m-Y H:i') }} UTC

                        </td>

                        <td>

                            <a
                                href="{{ $item->url }}"
                                target="_blank"
                                class="btn btn-sm btn-primary">

                                Baca

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center">

                            Belum ada data berita.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

            {{ $news->links() }}

        </div>

    </div>

</div>

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: "Cari negara..."
            });
        });
    </script>
@endpush
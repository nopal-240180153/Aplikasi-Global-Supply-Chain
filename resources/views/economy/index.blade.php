@extends('layouts.admin')

@section('title','Data Ekonomi')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">

                <i class="bi bi-graph-up-arrow text-primary"></i>

                Data Ekonomi Global

            </h2>

            <p class="text-muted mb-0">

                Data ekonomi negara hasil sinkronisasi World Bank API.

            </p>

        </div>

    </div>

    <div class="card shadow border-0">

        <div class="card-header bg-white">

            <div class="row">

                <div class="col-md-6">

                    <h5 class="mb-0">

                        Daftar Data Ekonomi

                    </h5>

                </div>

                <div class="col-md-6 text-end">

                    <span class="badge bg-primary">

                        Total :

                        {{ $economies->total() }}

                        Negara

                    </span>

                </div>

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover table-striped mb-0 align-middle">

                    <thead class="table-dark">

                    <tr>

                        <th width="60">No</th>

                        <th>Negara</th>

                        <th>GDP (USD)</th>

                        <th>Inflasi (%)</th>

                        <th>Populasi</th>

                        <th>Ekspor</th>

                        <th>Impor</th>

                        <th>Tahun</th>

                    </tr>

                    </thead>

                    <tbody>

                    @forelse($economies as $economy)

                        <tr>

                            <td>

                                {{ $loop->iteration + ($economies->currentPage()-1)*$economies->perPage() }}

                            </td>

                            <td>

                                <strong>

                                    {{ $economy->country->name }}

                                </strong>

                            </td>

                            <td>

                                ${{ number_format($economy->gdp,2) }}

                            </td>

                            <td>

                                <span class="badge bg-info">

                                    {{ number_format($economy->inflation,2) }} %

                                </span>

                            </td>

                            <td>

                                {{ number_format($economy->population) }}

                            </td>

                            <td>

                                ${{ number_format($economy->exports,2) }}

                            </td>

                            <td>

                                ${{ number_format($economy->imports,2) }}

                            </td>

                            <td>

                                {{ $economy->year }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="text-center p-5">

                                Tidak ada data.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

        <div class="card-footer bg-white">

            {{ $economies->links() }}

        </div>

    </div>

</div>

@endsection
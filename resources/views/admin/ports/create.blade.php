@extends('layouts.admin-portal')

@section('title', 'Tambah Pelabuhan')

@section('content')
<div class="container-fluid">

    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.ports.index') }}" class="btn btn-outline-secondary me-3">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <div>
            <h2 class="fw-bold mb-0">
                <i class="bi bi-plus-circle text-primary"></i>
                Tambah Pelabuhan
            </h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.ports.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="country_id" class="form-label fw-bold">Negara <span class="text-danger">*</span></label>
                            <select name="country_id" id="country_id" class="form-select" required>
                                <option value="">-- Pilih Negara --</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="port_name" class="form-label fw-bold">Nama Pelabuhan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="port_name" name="port_name" value="{{ old('port_name') }}" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="port_code" class="form-label fw-bold">Kode Pelabuhan</label>
                                <input type="text" class="form-control" id="port_code" name="port_code" value="{{ old('port_code') }}" placeholder="Misal: IDJKT">
                            </div>
                            <div class="col-md-6">
                                <label for="city" class="form-label fw-bold">Kota</label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="latitude" class="form-label fw-bold">Latitude <span class="text-danger">*</span></label>
                                <input type="number" step="any" class="form-control" id="latitude" name="latitude" value="{{ old('latitude') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="longitude" class="form-label fw-bold">Longitude <span class="text-danger">*</span></label>
                                <input type="number" step="any" class="form-control" id="longitude" name="longitude" value="{{ old('longitude') }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="type" class="form-label fw-bold">Tipe Pelabuhan</label>
                            <input type="text" class="form-control" id="type" name="type" value="{{ old('type') }}" placeholder="Misal: Seaport, Dry Port">
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save"></i> Simpan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

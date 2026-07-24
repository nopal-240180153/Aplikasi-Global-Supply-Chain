@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold mb-1">Profil Pengguna</h2>
        <p class="text-muted">Kelola informasi profil dan keamanan akun Anda.</p>
    </div>

    @if(auth()->user()->is_admin)
    <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-4 border-primary">
        <div class="card-body">
            <h5 class="card-title fw-bold">Portal Admin</h5>
            <p class="text-muted mb-3">Anda memiliki akses sebagai Administrator. Klik tombol di bawah untuk masuk ke Portal Admin.</p>
            <a href="{{ route('admin.sync') }}" class="btn btn-primary">
                <i class="bi bi-box-arrow-in-right"></i> Masuk ke Portal Admin
            </a>
        </div>
    </div>
    @endif

    <div class="row g-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
            
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

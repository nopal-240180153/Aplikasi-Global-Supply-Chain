@extends('layouts.admin-portal')

@section('title', 'Tambah User Baru')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">
                <i class="bi bi-person-plus text-primary"></i>
                Tambah User Baru
            </h2>
            <p class="text-muted mb-0">
                Tambahkan user baru ke dalam platform.
            </p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Kembali
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square"></i>
                        Form Tambah User
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}"
                                placeholder="Masukkan nama lengkap"
                                required
                                autofocus
                            >
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                placeholder="user@example.com"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input 
                                    type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password"
                                    placeholder="Minimal 8 karakter"
                                    required
                                >
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye" id="toggleIcon"></i>
                                </button>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Password minimal 8 karakter</small>
                        </div>

                        <!-- Password Confirmation -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">
                                Konfirmasi Password <span class="text-danger">*</span>
                            </label>
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password_confirmation" 
                                name="password_confirmation"
                                placeholder="Ulangi password"
                                required
                            >
                        </div>

                        <!-- Is Admin Checkbox -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input 
                                    class="form-check-input" 
                                    type="checkbox" 
                                    id="is_admin" 
                                    name="is_admin"
                                    value="1"
                                    {{ old('is_admin') ? 'checked' : '' }}
                                >
                                <label class="form-check-label" for="is_admin">
                                    <strong>Set sebagai Administrator</strong>
                                    <br>
                                    <small class="text-muted">
                                        Administrator memiliki akses penuh ke semua fitur admin panel
                                    </small>
                                </label>
                            </div>
                        </div>

                        <hr>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i>
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i>
                                Simpan User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('bi-eye');
            toggleIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('bi-eye-slash');
            toggleIcon.classList.add('bi-eye');
        }
    });
</script>
@endpush

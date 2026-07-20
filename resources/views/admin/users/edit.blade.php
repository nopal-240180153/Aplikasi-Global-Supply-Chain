@extends('layouts.admin-portal')

@section('title', 'Edit User')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">
                <i class="bi bi-pencil-square text-primary"></i>
                Edit User
            </h2>
            <p class="text-muted mb-0">
                Perbarui informasi user: <strong>{{ $user->name }}</strong>
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
                <div class="card-header bg-warning">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square"></i>
                        Form Edit User
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')

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
                                value="{{ old('name', $user->name) }}"
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
                                value="{{ old('email', $user->email) }}"
                                placeholder="user@example.com"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password (Optional) -->
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            <strong>Info:</strong> Kosongkan password jika tidak ingin mengubahnya.
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password Baru (Opsional)
                            </label>
                            <div class="input-group">
                                <input 
                                    type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password"
                                    placeholder="Kosongkan jika tidak ingin mengubah"
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
                                Konfirmasi Password Baru
                            </label>
                            <input 
                                type="password" 
                                class="form-control" 
                                id="password_confirmation" 
                                name="password_confirmation"
                                placeholder="Ulangi password baru"
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
                                    {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                >
                                <label class="form-check-label" for="is_admin">
                                    <strong>Administrator</strong>
                                    <br>
                                    <small class="text-muted">
                                        Administrator memiliki akses penuh ke semua fitur admin panel
                                    </small>
                                    @if($user->id === auth()->id())
                                        <br>
                                        <small class="text-warning">
                                            <i class="bi bi-exclamation-triangle"></i>
                                            Anda tidak dapat mengubah role Anda sendiri
                                        </small>
                                    @endif
                                </label>
                            </div>
                        </div>

                        <!-- User Info -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="mb-2">Informasi User</h6>
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> 
                                    Terdaftar: {{ $user->created_at->format('d M Y H:i') }}
                                </small>
                                <br>
                                <small class="text-muted">
                                    <i class="bi bi-clock-history"></i> 
                                    Terakhir diupdate: {{ $user->updated_at->format('d M Y H:i') }}
                                </small>
                            </div>
                        </div>

                        <hr>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i>
                                Batal
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save"></i>
                                Update User
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

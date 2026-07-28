<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Admin - GSCMS</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0f172a;
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-container {
            max-width: 480px;
            width: 100%;
            padding: 20px;
        }

        .register-card {
            background: #1e293b;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 25px;
        }

        .logo-icon {
            font-size: 3rem;
            color: #3b82f6;
            margin-bottom: 10px;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 5px;
        }

        .logo-subtitle {
            font-size: 0.9rem;
            color: #94a3b8;
        }

        .form-label {
            color: #cbd5e1;
            font-weight: 500;
            margin-bottom: 6px;
            font-size: 0.9rem;
        }

        .form-control {
            background: #0f172a;
            border: 1px solid #334155;
            color: white;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .form-control:focus {
            background: #0f172a;
            border-color: #3b82f6;
            color: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .form-control::placeholder {
            color: #475569;
        }

        .btn-register {
            background: #3b82f6;
            color: white;
            padding: 12px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            background: #2563eb;
            transform: translateY(-1px);
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
        }

        .back-link a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: #3b82f6;
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #fca5a5;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="bi bi-person-plus-fill"></i>
                </div>
                <h1 class="logo-text">Buat Akun Admin</h1>
                <p class="logo-subtitle">Daftarkan akun administrator GSCMS baru</p>
            </div>

            <!-- Error Alerts -->
            @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('admin.register.submit') }}">
                @csrf

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">
                        <i class="bi bi-person-fill me-1"></i> Nama Lengkap
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="name" 
                           name="name" 
                           placeholder="Nama lengkap Anda" 
                           value="{{ old('name') }}" 
                           required 
                           autofocus>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope-fill me-1"></i> Email
                    </label>
                    <input type="email" 
                           class="form-control" 
                           id="email" 
                           name="email" 
                           placeholder="admin@example.com" 
                           value="{{ old('email') }}" 
                           required>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock-fill me-1"></i> Password
                    </label>
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           placeholder="Minimal 8 karakter" 
                           required>
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">
                        <i class="bi bi-shield-lock-fill me-1"></i> Konfirmasi Password
                    </label>
                    <input type="password" 
                           class="form-control" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           placeholder="Ulangi password" 
                           required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-register">
                    <i class="bi bi-check-circle-fill me-1"></i> Daftar Admin
                </button>
            </form>

            <!-- Back Link -->
            <div class="back-link">
                <a href="{{ route('admin.login') }}">
                    Sudah punya akun? <span class="text-primary fw-semibold">Login di sini</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

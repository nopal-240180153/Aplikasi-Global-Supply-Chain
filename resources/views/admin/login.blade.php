<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - GSCMS</title>
    
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

        .login-container {
            max-width: 450px;
            width: 100%;
            padding: 20px;
        }

        .login-card {
            background: #1e293b;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-icon {
            font-size: 3rem;
            color: #2563eb;
            margin-bottom: 15px;
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
            margin-bottom: 8px;
        }

        .form-control {
            background: #0f172a;
            border: 1px solid #334155;
            color: white;
            padding: 12px 15px;
            border-radius: 8px;
        }

        .form-control:focus {
            background: #0f172a;
            border-color: #2563eb;
            color: white;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .btn-login {
            background: #2563eb;
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

        .btn-login:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .form-check-input {
            background: #0f172a;
            border: 1px solid #334155;
        }

        .form-check-input:checked {
            background: #2563eb;
            border-color: #2563eb;
        }

        .form-check-label {
            color: #cbd5e1;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: #2563eb;
        }

        .alert {
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: rgba(220, 38, 38, 0.1);
            border: 1px solid rgba(220, 38, 38, 0.3);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Logo Section -->
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <h1 class="logo-text">Admin Portal</h1>
                <p class="logo-subtitle">Global Supply Chain Monitoring System</p>
            </div>

            <!-- Alerts -->
            @if(session('status'))
                <div class="alert alert-success" role="alert">
                    <i class="bi bi-check-circle"></i> {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> 
                    @foreach($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope"></i> Email
                    </label>
                    <input type="email" 
                           class="form-control" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           placeholder="admin@example.com"
                           required 
                           autofocus>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock"></i> Password
                    </label>
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           placeholder="Masukkan password"
                           required>
                </div>

                <!-- Remember Me -->
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="remember_me" 
                               name="remember">
                        <label class="form-check-label" for="remember_me">
                            Remember me
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-login">
                    <i class="bi bi-box-arrow-in-right"></i> Log in
                </button>
            </form>

            <!-- Back Link -->
            <div class="back-link">
                <a href="{{ route('dashboard') }}">
                    <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

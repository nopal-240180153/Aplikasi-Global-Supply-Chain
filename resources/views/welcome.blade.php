<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSCMS - Global Supply Chain Monitoring System</title>
    
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

        .welcome-container {
            max-width: 1200px;
            padding: 40px 20px;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-text {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 10px;
        }

        .logo-subtitle {
            font-size: 0.9rem;
            color: #94a3b8;
        }

        .hero-section {
            text-align: center;
            margin-bottom: 50px;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: #cbd5e1;
            margin-bottom: 30px;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn-group-custom {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-custom {
            padding: 15px 40px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary-custom {
            background: #2563eb;
            color: white;
        }

        .btn-primary-custom:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        }

        .btn-secondary-custom {
            background: transparent;
            color: white;
            border: 2px solid #475569;
        }

        .btn-secondary-custom:hover {
            background: #1e293b;
            border-color: #64748b;
            transform: translateY(-2px);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 60px;
        }

        .feature-card {
            background: #1e293b;
            padding: 25px;
            border-radius: 12px;
            border: 1px solid rgba(148, 163, 184, 0.1);
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            background: #334155;
            border-color: #2563eb;
            transform: translateY(-5px);
        }

        .feature-icon {
            width: 50px;
            height: 50px;
            background: #2563eb;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .feature-icon i {
            font-size: 1.5rem;
            color: white;
        }

        .feature-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 8px;
            color: white;
        }

        .feature-text {
            font-size: 0.9rem;
            color: #94a3b8;
            line-height: 1.6;
        }

        /* Modal Styling */
        .modal-content {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 12px;
        }

        .modal-header {
            background: #0f172a;
            border-bottom: 1px solid #334155;
            color: white;
        }

        .modal-body {
            background: #1e293b;
        }

        .modal-footer {
            background: #1e293b;
            border-top: 1px solid #334155;
        }

        .btn-close {
            filter: brightness(0) invert(1);
        }

        .form-label {
            color: #e2e8f0;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control {
            background: #0f172a;
            border: 1px solid #334155;
            color: white;
            padding: 12px;
            border-radius: 8px;
        }

        .form-control:focus {
            background: #0f172a;
            border-color: #2563eb;
            color: white;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .form-check-input {
            background: #0f172a;
            border-color: #334155;
        }

        .form-check-input:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .form-check-label {
            color: #cbd5e1;
        }

        .modal-link {
            color: #3b82f6;
            text-decoration: none;
        }

        .modal-link:hover {
            color: #60a5fa;
            text-decoration: underline;
        }

        .btn-modal-primary {
            background: #2563eb;
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-modal-primary:hover {
            background: #1d4ed8;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1rem;
            }
            
            .btn-custom {
                width: 100%;
                justify-content: center;
            }
            
            .features-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="welcome-container">
        <!-- Logo -->
        <div class="logo-section">
            <div class="logo-text">GSCMS</div>
            <div class="logo-subtitle">Global Supply Chain Monitoring System</div>
        </div>

        <!-- Hero Section -->
        <div class="hero-section">
            <h1 class="hero-title">
                Monitoring Supply Chain<br>Real-Time & Terintegrasi
            </h1>
            <p class="hero-subtitle">
                Platform monitoring untuk risk assessment, analisis ekonomi, cuaca, dan berita global dalam satu dashboard.
            </p>
            
            <div class="btn-group-custom">
                <button class="btn-custom btn-primary-custom" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Sign In
                </button>
                <button class="btn-custom btn-secondary-custom" data-bs-toggle="modal" data-bs-target="#registerModal">
                    <i class="bi bi-person-plus"></i>
                    Sign Up
                </button>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div class="feature-title">Risk Assessment</div>
                <div class="feature-text">Analisis risiko real-time berdasarkan data global</div>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-cloud-sun-fill"></i>
                </div>
                <div class="feature-title">Weather Monitoring</div>
                <div class="feature-text">Monitoring cuaca global dengan peta interaktif</div>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-currency-exchange"></i>
                </div>
                <div class="feature-title">Exchange Rate</div>
                <div class="feature-text">Monitoring nilai tukar mata uang</div>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="bi bi-newspaper"></i>
                </div>
                <div class="feature-title">Global News</div>
                <div class="feature-text">Berita global dengan sentiment analysis</div>
            </div>
        </div>
    </div>

    <!-- Sign In Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Sign In
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="login-email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="login-email" 
                                   name="email" 
                                   placeholder="your@email.com" 
                                   required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="login-password" class="form-label">Password</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="login-password" 
                                   name="password" 
                                   placeholder="••••••••" 
                                   required>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="remember" 
                                   id="remember-me">
                            <label class="form-check-label" for="remember-me">
                                Remember me
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-modal-primary w-100">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Sign In
                        </button>
                        
                        <div class="text-center mt-3">
                            <p class="mb-0" style="color: #94a3b8;">
                                Belum punya akun? 
                                <a href="#" 
                                   class="modal-link" 
                                   data-bs-dismiss="modal" 
                                   data-bs-toggle="modal" 
                                   data-bs-target="#registerModal">
                                    Sign Up
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Sign Up Modal -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerModalLabel">
                        <i class="bi bi-person-plus me-2"></i>
                        Sign Up
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="register-name" class="form-label">Full Name</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="register-name" 
                                   name="name" 
                                   placeholder="John Doe" 
                                   required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="register-email" class="form-label">Email</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="register-email" 
                                   name="email" 
                                   placeholder="john@example.com" 
                                   required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="register-password" class="form-label">Password</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="register-password" 
                                   name="password" 
                                   placeholder="••••••••" 
                                   required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="register-password-confirm" class="form-label">Confirm Password</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="register-password-confirm" 
                                   name="password_confirmation" 
                                   placeholder="••••••••" 
                                   required>
                        </div>
                        
                        <div class="form-check mb-3">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   id="agree-terms" 
                                   required>
                            <label class="form-check-label" for="agree-terms">
                                I agree to the Terms & Conditions
                            </label>
                        </div>
                        
                        <button type="submit" class="btn btn-modal-primary w-100">
                            <i class="bi bi-person-plus me-2"></i>
                            Create Account
                        </button>
                        
                        <div class="text-center mt-3">
                            <p class="mb-0" style="color: #94a3b8;">
                                Sudah punya akun? 
                                <a href="#" 
                                   class="modal-link" 
                                   data-bs-dismiss="modal" 
                                   data-bs-toggle="modal" 
                                   data-bs-target="#loginModal">
                                    Sign In
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-show modal jika ada error
        document.addEventListener('DOMContentLoaded', function() {
            @if($errors->any())
                @if(request()->is('login'))
                    new bootstrap.Modal(document.getElementById('loginModal')).show();
                @elseif(request()->is('register'))
                    new bootstrap.Modal(document.getElementById('registerModal')).show();
                @endif
            @endif
        });
    </script>

</body>
</html>

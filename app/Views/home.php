<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= asset('img/favicon.ico') ?>" type="image/x-icon">
    <title>Sookth Mobile Pigmy - Smart Banking Collection Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #64748b;
            --success: #10b981;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .hero {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 6rem 0 5rem;
            position: relative;
            overflow: hidden;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin: 2rem 0;
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: var(--primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .btn-login {
            padding: 1rem 2rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= url('/') ?>">
                <i class="bi bi-piggy-bank-fill me-2"></i>Sookth Mobile Pigmy
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Smart Banking Collection Platform</h1>
                    <p class="lead mb-4">Streamline your pigmy collection process with our modern, efficient, and secure platform.</p>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="login-card text-center">
                                <div class="feature-icon mx-auto">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <h5>Admin Login</h5>
                                <p class="text-muted small">Manage system and users</p>
                                <a href="<?= url('admin/login') ?>" class="btn btn-primary btn-login w-100">
                                    Login as Admin
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="login-card text-center">
                                <div class="feature-icon mx-auto bg-success">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <h5>Agent Login</h5>
                                <p class="text-muted small">Collect pigmy deposits</p>
                                <a href="<?= url('agent/login') ?>" class="btn btn-success btn-login w-100">
                                    Login as Agent
                                </a>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="login-card text-center">
                                <div class="feature-icon mx-auto bg-secondary">
                                    <i class="bi bi-building"></i>
                                </div>
                                <h5>Bank Login</h5>
                                <p class="text-muted small">View reports and analytics</p>
                                <a href="<?= url('bank/login') ?>" class="btn btn-secondary btn-login w-100">
                                    Login as Bank
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Key Features</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="feature-icon mx-auto">
                            <i class="bi bi-phone"></i>
                        </div>
                        <h5>Mobile Friendly</h5>
                        <p class="text-muted">Optimized for mobile devices for on-the-go collection</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="feature-icon mx-auto">
                            <i class="bi bi-bar-chart"></i>
                        </div>
                        <h5>Real-time Reports</h5>
                        <p class="text-muted">Get instant insights with comprehensive reports</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="feature-icon mx-auto">
                            <i class="bi bi-lock"></i>
                        </div>
                        <h5>Secure & Reliable</h5>
                        <p class="text-muted">Bank-grade security for your transactions</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; <?= date('Y') ?> Sookth Mobile Pigmy. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('js/security.js') ?>"></script>
</body>
</html>

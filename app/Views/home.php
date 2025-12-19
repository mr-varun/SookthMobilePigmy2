<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= asset('img/favicon.ico') ?>" type="image/x-icon">
    <title>Sookth Mobile Pigmy - Smart Banking Collection Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --info: #06b6d4;
            --gradient-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --gradient-3: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            overflow-x: hidden;
            background: #f8fafc;
        }

        /* Animated Background */
        .animated-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            opacity: 0.05;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(20px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-link {
            color: #1e293b !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s;
            border-radius: 8px;
        }

        .nav-link:hover {
            background: rgba(102, 126, 234, 0.1);
            color: var(--primary) !important;
        }

        /* Hero Section */
        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 8rem 0 6rem;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            animation: fadeInUp 1s ease;
        }

        .hero p {
            font-size: 1.25rem;
            opacity: 0.95;
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease 0.2s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Floating Animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        /* Login Cards */
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            margin: 1.5rem 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 1s ease 0.4s both;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-primary);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.15);
        }

        .login-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: var(--gradient-primary);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.8rem;
            margin: 0 auto 1.5rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .feature-icon::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 18px;
            background: inherit;
            opacity: 0.3;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.1); opacity: 0; }
        }

        .login-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .login-card.admin-card .feature-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .login-card.agent-card .feature-icon {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .login-card.bank-card .feature-icon {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .login-card h5 {
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }

        .login-card p {
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        .btn-login {
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: left 0.3s ease;
            z-index: -1;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .btn-success {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .btn-info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="animated-bg"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
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
                        <a class="nav-link" href="#features">
                            <i class="bi bi-star me-1"></i> Features
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">
                            <i class="bi bi-info-circle me-1"></i> About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">
                            <i class="bi bi-envelope me-1"></i> Contact
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="display-3 fw-bold mb-4">
                        Smart Banking<br/>Collection Platform
                    </h1>
                    <p class="lead mb-4">
                        Streamline your pigmy collection process with our modern, efficient, and secure platform. 
                        Experience seamless banking operations at your fingertips.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>
                            <span>100% Secure</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>
                            <span>Real-time Updates</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>
                            <span>24/7 Access</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="login-card agent-card text-center">
                                <div class="feature-icon">
                                    <i class="bi bi-person-badge-fill"></i>
                                </div>
                                <h5>Agent Portal</h5>
                                <p class="small">Collect deposits and manage customers</p>
                                <a href="<?= url('agent/login') ?>" class="btn btn-success btn-login w-100">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Agent Login
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="login-card bank-card text-center">
                                <div class="feature-icon">
                                    <i class="bi bi-building"></i>
                                </div>
                                <h5>Bank Portal</h5>
                                <p class="small">Comprehensive reports and analytics dashboard</p>
                                <a href="<?= url('bank/login') ?>" class="btn btn-info btn-login w-100">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Bank Login
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5" style="padding: 6rem 0 !important; background: white;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3" style="color: #1e293b;">
                    Powerful Features for Modern Banking
                </h2>
                <p class="lead text-muted">Everything you need to manage pigmy collections efficiently</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4" style="animation: fadeInUp 1s ease 0.6s both;">
                    <div class="text-center p-4" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%); border-radius: 20px; height: 100%; transition: all 0.3s ease;">
                        <div class="feature-icon mx-auto" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <i class="bi bi-phone-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #1e293b;">Mobile Optimized</h5>
                        <p class="text-muted">Fully responsive design optimized for mobile devices. Collect deposits on-the-go with ease.</p>
                    </div>
                </div>
                <div class="col-md-4" style="animation: fadeInUp 1s ease 0.7s both;">
                    <div class="text-center p-4" style="background: linear-gradient(135deg, rgba(240, 147, 251, 0.05) 0%, rgba(245, 87, 108, 0.05) 100%); border-radius: 20px; height: 100%; transition: all 0.3s ease;">
                        <div class="feature-icon mx-auto" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #1e293b;">Real-time Analytics</h5>
                        <p class="text-muted">Get instant insights with comprehensive reports and live dashboards tracking all collections.</p>
                    </div>
                </div>
                <div class="col-md-4" style="animation: fadeInUp 1s ease 0.8s both;">
                    <div class="text-center p-4" style="background: linear-gradient(135deg, rgba(79, 172, 254, 0.05) 0%, rgba(0, 242, 254, 0.05) 100%); border-radius: 20px; height: 100%; transition: all 0.3s ease;">
                        <div class="feature-icon mx-auto" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #1e293b;">Bank-Grade Security</h5>
                        <p class="text-muted">Industry-leading security measures to protect your transactions and customer data.</p>
                    </div>
                </div>
                <div class="col-md-4" style="animation: fadeInUp 1s ease 0.9s both;">
                    <div class="text-center p-4" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(5, 150, 105, 0.05) 100%); border-radius: 20px; height: 100%; transition: all 0.3s ease;">
                        <div class="feature-icon mx-auto" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                            <i class="bi bi-cloud-upload-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #1e293b;">Auto Backup</h5>
                        <p class="text-muted">Automated daily backups ensure your data is always safe and recoverable.</p>
                    </div>
                </div>
                <div class="col-md-4" style="animation: fadeInUp 1s ease 1s both;">
                    <div class="text-center p-4" style="background: linear-gradient(135deg, rgba(251, 146, 60, 0.05) 0%, rgba(249, 115, 22, 0.05) 100%); border-radius: 20px; height: 100%; transition: all 0.3s ease;">
                        <div class="feature-icon mx-auto" style="background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);">
                            <i class="bi bi-printer-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #1e293b;">Print & Export</h5>
                        <p class="text-muted">Generate professional reports and receipts. Export data in multiple formats.</p>
                    </div>
                </div>
                <div class="col-md-4" style="animation: fadeInUp 1s ease 1.1s both;">
                    <div class="text-center p-4" style="background: linear-gradient(135deg, rgba(168, 85, 247, 0.05) 0%, rgba(147, 51, 234, 0.05) 100%); border-radius: 20px; height: 100%; transition: all 0.3s ease;">
                        <div class="feature-icon mx-auto" style="background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%);">
                            <i class="bi bi-lightning-charge-fill"></i>
                        </div>
                        <h5 class="fw-bold mb-3" style="color: #1e293b;">Lightning Fast</h5>
                        <p class="text-muted">Optimized performance for quick transactions and instant data synchronization.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5" style="padding: 4rem 0 !important; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4 mb-md-0">
                    <div style="font-size: 3rem; font-weight: 800; margin-bottom: 0.5rem;">99.9%</div>
                    <div style="font-size: 1.1rem; opacity: 0.9;">Uptime Guarantee</div>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <div style="font-size: 3rem; font-weight: 800; margin-bottom: 0.5rem;">24/7</div>
                    <div style="font-size: 1.1rem; opacity: 0.9;">Support Available</div>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <div style="font-size: 3rem; font-weight: 800; margin-bottom: 0.5rem;">1000+</div>
                    <div style="font-size: 1.1rem; opacity: 0.9;">Active Users</div>
                </div>
                <div class="col-md-3">
                    <div style="font-size: 3rem; font-weight: 800; margin-bottom: 0.5rem;">100%</div>
                    <div style="font-size: 1.1rem; opacity: 0.9;">Secure Platform</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background: #1e293b; color: white; padding: 3rem 0 1.5rem;">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        <i class="bi bi-piggy-bank-fill me-2" style="-webkit-text-fill-color: white;"></i>Sookth Mobile Pigmy
                    </h5>
                    <p style="opacity: 0.8; line-height: 1.6;">Modern banking collection platform designed for efficiency, security, and ease of use.</p>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h6 class="fw-bold mb-3">Quick Links</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#features" style="color: rgba(255,255,255,0.8); text-decoration: none; transition: all 0.3s;">Features</a></li>
                        <li class="mb-2"><a href="#about" style="color: rgba(255,255,255,0.8); text-decoration: none; transition: all 0.3s;">About Us</a></li>
                        <li class="mb-2"><a href="#contact" style="color: rgba(255,255,255,0.8); text-decoration: none; transition: all 0.3s;">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6 class="fw-bold mb-3">Contact Info</h6>
                    <p style="opacity: 0.8; line-height: 1.8;">
                        <i class="bi bi-envelope me-2"></i>support@sookthmobilepigmy.com<br>
                        <i class="bi bi-telephone me-2"></i>+91 XXXX-XXXXXX<br>
                        <i class="bi bi-geo-alt me-2"></i>India
                    </p>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.1); margin: 2rem 0 1.5rem;">
            <div class="text-center" style="opacity: 0.8;">
                <p class="mb-0">&copy; <?= date('Y') ?> Sookth Mobile Pigmy. All rights reserved. | Designed with <i class="bi bi-heart-fill text-danger"></i> for better banking</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('js/security.js') ?>"></script>
    
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add hover effect to feature cards
        document.querySelectorAll('.col-md-4 > div').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
                this.style.boxShadow = '0 20px 40px rgba(0,0,0,0.1)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            });
        });
    </script>
</body>
</html>

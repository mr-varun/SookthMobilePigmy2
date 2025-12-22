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
            --warning: #f59e0b;
            --light: #f8fafc;
            --dark: #1e293b;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #334155;
            line-height: 1.6;
        }

        .navbar {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #4dabf7;
        }

        .hero {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 6rem 0 5rem;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,202.7C672,203,768,181,864,170.7C960,160,1056,160,1152,170.7C1248,181,1344,203,1392,213.3L1440,224L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            background-position: center bottom;
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .btn-login {
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        /* Hero prominent buttons */
        .btn-hero-primary,
        .btn-hero-secondary {
            color: #fff !important;
            border: 2px solid transparent;
            padding: 0.9rem 1.75rem;
            font-size: 1rem;
            font-weight: 700;
            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.12);
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease, opacity 0.18s ease;
        }

        .btn-hero-primary {
            background: linear-gradient(135deg, #06b6d4 0%, #2563eb 100%);
        }

        .btn-hero-secondary {
            background: linear-gradient(135deg, #10b981 0%, #06b6d4 100%);
        }

        .btn-hero-primary:hover,
        .btn-hero-secondary:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 40px rgba(37, 99, 235, 0.16);
            border-color: rgba(255,255,255,0.12);
        }

        .btn-hero-primary:active,
        .btn-hero-secondary:active {
            transform: translateY(-2px) scale(0.995);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.12);
        }

        .btn-hero-primary:focus,
        .btn-hero-secondary:focus {
            outline: 3px solid rgba(37, 99, 235, 0.12);
            outline-offset: 3px;
        }

        /* Sookth button hover/focus tweaks */
        .btn-outline-primary.btn-login {
            transition: background-color 0.18s ease, border-color 0.18s ease, color 0.18s ease, transform 0.12s ease;
            border: 2px solid rgba(255,255,255,0.06);
        }

        .btn-outline-primary.btn-login:hover {
            background-color: rgba(255,255,255,0.06);
            border-color: rgba(255,255,255,0.18);
            transform: translateY(-3px);
            color: #fff !important;
        }

        .btn-outline-primary.btn-login:focus {
            box-shadow: 0 6px 20px rgba(37,99,235,0.12);
            outline: 2px solid rgba(37,99,235,0.12);
            outline-offset: 2px;
        }

        /* subtle pulsing to draw eye (reduced motion respects) */
        @media (prefers-reduced-motion: no-preference) {

            .btn-hero-primary,
            .btn-hero-secondary {
                animation: pulseHighlight 3.5s ease-in-out infinite;
            }

            @keyframes pulseHighlight {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-2px);
                }
            }
        }

        .btn-primary {
            background: var(--primary);
            border: none;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.4);
        }

        .btn-outline-light:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
        }

        .section-title {
            position: relative;
            margin-bottom: 3rem;
            font-weight: 700;
            color: var(--dark);
        }

        .section-title::after {
            content: '';
            position: absolute;
            width: 60px;
            height: 4px;
            background: var(--primary);
            bottom: -10px;
            left: 0;
            border-radius: 2px;
        }

        .center-title::after {
            left: 50%;
            transform: translateX(-50%);
        }

        .feature-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 100%;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }

        /* Feature badges */
        .feature-badge {
            display: inline-block;
            font-size: 0.65rem;
            padding: 0.25rem 0.5rem;
            border-radius: 999px;
            margin-left: 0.5rem;
            vertical-align: middle;
            font-weight: 600;
        }

        .feature-badge.included {
            background: rgba(16, 185, 129, 0.12);
            color: #059669;
            border: 1px solid rgba(16, 185, 129, 0.18);
        }

        .feature-badge.upcoming {
            background: rgba(245, 158, 11, 0.12);
            color: #b45309;
            border: 1px solid rgba(245, 158, 11, 0.14);
        }

        .stats-section {
            background: var(--light);
            padding: 5rem 0;
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .testimonial-card {
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 2rem;
            height: 100%;
            border-left: 4px solid var(--primary);
        }

        .testimonial-text {
            font-style: italic;
            margin-bottom: 1.5rem;
        }

        .client-info {
            display: flex;
            align-items: center;
        }

        .client-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin-right: 1rem;
        }

        .workflow-step {
            text-align: center;
            padding: 2rem 1rem;
            position: relative;
        }

        .step-number {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin: 0 auto 1.5rem;
        }

        .workflow-connector {
            position: absolute;
            top: 25px;
            right: -30px;
            width: 60px;
            height: 2px;
            background: var(--primary);
            opacity: 0.3;
        }

        .workflow-step:last-child .workflow-connector {
            display: none;
        }

        footer {
            background: var(--dark);
            color: white;
            padding: 4rem 0 2rem;
        }

        .footer-links a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.3s;
            display: block;
            margin-bottom: 0.5rem;
        }

        .footer-links a:hover {
            color: white;
        }

        @media (max-width: 768px) {
            .hero {
                padding: 4rem 0 3rem;
            }

            .workflow-connector {
                display: none;
            }

            .section-title::after {
                left: 50%;
                transform: translateX(-50%);
            }
        }

        .navbar-toggler {
            border: none;
            padding: 4px 8px;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.8%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="https://sookthsolutions.com/">
                <img src="<?= asset('img/icon.png') ?>" alt="logo" style="height: 55px; border-radius: 50%;">
                Sookth Mobile Pigmy
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#workflow">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#testimonials">Testimonials</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a id="sookthBtn" class="btn btn-outline-primary btn-login " href="<?= url('admin/login') ?>"
                            title="Hold Ctrl and click to open">Sookth</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <h1 class="display-4 fw-bold mb-4">Revolutionizing Banking Collections</h1>
                        <p class="lead mb-5">Streamline your daily collection processes with our secure, mobile-first
                            platform designed for cooperative banks and financial institutions.</p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="<?= url('bank/login') ?>" class="btn btn-hero-primary btn-login"
                                aria-label="Bank Login">
                                <i class="bi bi-shield-lock me-2"></i>Bank Login
                            </a>
                            <a href="<?= url('agent/login') ?>" class="btn btn-hero-secondary btn-login"
                                aria-label="Agent Login">
                                <i class="bi bi-person-badge me-2"></i>Agent Login
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 text-center mt-5 mt-lg-0">
                        <div class="position-relative">
                            <div class="bg-white rounded-3 p-4 shadow-lg d-inline-block">
                                <div class="d-flex mb-4">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="width: 50px; height: 50px;">
                                        <i class="bi bi-phone text-white fs-5"></i>
                                    </div>
                                    <div class="text-start">
                                        <h5 class="mb-0">Mobile Collection</h5>
                                        <small class="text-muted">Real-time updates</small>
                                    </div>
                                </div>
                                <div class="d-flex mb-4">
                                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="width: 50px; height: 50px;">
                                        <i class="bi bi-graph-up text-white fs-5"></i>
                                    </div>
                                    <div class="text-start">
                                        <h5 class="mb-0">Daily Reports</h5>
                                        <small class="text-muted">Automated analytics</small>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="width: 50px; height: 50px;">
                                        <i class="bi bi-shield-check text-white fs-5"></i>
                                    </div>
                                    <div class="text-start">
                                        <h5 class="mb-0">Secure OTP Login</h5>
                                        <small class="text-muted">Bank-grade security</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Why Choose Mobile Pigmy -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title center-title text-center">Why Choose Mobile Pigmy?</h2>
            <div class="row mt-5">
                <div class="col-md-4 mb-4">
                    <div class="feature-card p-4 text-center">
                        <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4>Bank-Grade Security</h4>
                        <p class="text-muted">Secure OTP-based authentication and encrypted data transmission ensure
                            your financial data is always protected.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card p-4 text-center">
                        <div class="feature-icon bg-success bg-opacity-10 text-success mx-auto">
                            <i class="bi bi-lightning-charge"></i>
                        </div>
                        <h4>Real-time Processing</h4>
                        <p class="text-muted">Instant transaction processing and updates keep your records accurate and
                            up-to-date at all times.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card p-4 text-center">
                        <div class="feature-icon bg-warning bg-opacity-10 text-warning mx-auto">
                            <i class="bi bi-phone"></i>
                        </div>
                        <h4>Mobile-First Design</h4>
                        <p class="text-muted">Optimized for mobile devices, allowing agents to work efficiently from
                            anywhere with an internet connection.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title center-title text-center">Powerful Features</h2>
            <center>
                <p>Some Features Are included, Some Features are upcomming soon...</p>
            </center>
            <div class="row mt-5">
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="feature-card p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-people text-white"></i>
                            </div>
                            <h5 class="mb-0">Agent Management <span class="feature-badge included">Included</span></h5>
                        </div>
                        <p class="text-muted mb-0">Easily manage field agents, assign territories, and track performance
                            with comprehensive reporting.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="feature-card p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-wallet2 text-white"></i>
                            </div>
                            <h5 class="mb-0">UPI Integration <span class="feature-badge upcoming">Upcoming</span></h5>
                        </div>
                        <p class="text-muted mb-0">Seamless UPI payment integration for quick and secure digital
                            transactions.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="feature-card p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-graph-up text-white"></i>
                            </div>
                            <h5 class="mb-0">Comprehensive Reports <span class="feature-badge included">Included</span>
                            </h5>
                        </div>
                        <p class="text-muted mb-0">Generate daily, weekly, and monthly reports with detailed analytics
                            and insights.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="feature-card p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-wifi text-white"></i>
                            </div>
                            <h5 class="mb-0">Easy Connectivity <span class="feature-badge upcoming">Upcoming</span></h5>
                        </div>
                        <p class="text-muted mb-0">Works seamlessly even with limited internet connectivity with offline
                            data synchronization.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="feature-card p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-chat-dots text-white"></i>
                            </div>
                            <h5 class="mb-0">SMS & WhatsApp Alerts <span class="feature-badge included">Included</span>
                            </h5>
                        </div>
                        <p class="text-muted mb-0">Automated transaction alerts and notifications via SMS and WhatsApp.
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="feature-card p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3"
                                style="width: 50px; height: 50px;">
                                <i class="bi bi-journal-text text-white"></i>
                            </div>
                            <h5 class="mb-0">Digital Passbook <span class="feature-badge included">Included</span></h5>
                        </div>
                        <p class="text-muted mb-0">Customer transaction passbook with complete history and downloadable
                            statements.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <h2 class="section-title center-title text-center">Trusted by Financial Institutions</h2>
            <div class="row mt-5">
                <div class="col-md-3 col-6 stat-item">
                    <div class="stat-number" data-count="750">0</div>
                    <div class="stat-text">Banks & Cooperatives</div>
                </div>
                <div class="col-md-3 col-6 stat-item">
                    <div class="stat-number" data-count="12500">0</div>
                    <div class="stat-text">Active Agents</div>
                </div>
                <div class="col-md-3 col-6 stat-item">
                    <div class="stat-number" data-count="98.7">0</div>
                    <div class="stat-text">Uptime Percentage</div>
                </div>
                <div class="col-md-3 col-6 stat-item">
                    <div class="stat-number" data-count="15">0</div>
                    <div class="stat-text">Years of Experience</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Workflow Section -->
    <section id="workflow" class="py-5">
        <div class="container">
            <h2 class="section-title center-title text-center">How It Works</h2>
            <div class="row mt-5">
                <div class="col-lg-3 col-md-6 workflow-step">
                    <div class="step-number">1</div>
                    <h5>Agent Login</h5>
                    <p class="text-muted">Agents securely log in using OTP-based authentication</p>
                    <div class="workflow-connector d-none d-lg-block"></div>
                </div>
                <div class="col-lg-3 col-md-6 workflow-step">
                    <div class="step-number">2</div>
                    <h5>Collection Process</h5>
                    <p class="text-muted">Record daily collections with UPI or cash payments</p>
                    <div class="workflow-connector d-none d-lg-block"></div>
                </div>
                <div class="col-lg-3 col-md-6 workflow-step">
                    <div class="step-number">3</div>
                    <h5>Real-time Sync</h5>
                    <p class="text-muted">Data synchronizes with central banking system</p>
                    <div class="workflow-connector d-none d-lg-block"></div>
                </div>
                <div class="col-lg-3 col-md-6 workflow-step">
                    <div class="step-number">4</div>
                    <h5>Reporting</h5>
                    <p class="text-muted">Generate comprehensive reports for management</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title center-title text-center">What Our Clients Say</h2>
            <div class="row mt-5">
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"Mobile Pigmy has transformed our collection process. Our agents are
                            now 40% more efficient, and we have real-time visibility into all transactions."</p>
                        <div class="client-info">
                            <div class="client-avatar">RK</div>
                            <div>
                                <h6 class="mb-0">Rajesh Kumar</h6>
                                <small class="text-muted">Manager, Urban Cooperative Bank</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"The UPI integration and digital passbook features have
                            significantly improved customer satisfaction. Our collection efficiency has increased by
                            60%."</p>
                        <div class="client-info">
                            <div class="client-avatar">PS</div>
                            <div>
                                <h6 class="mb-0">Priya Sharma</h6>
                                <small class="text-muted">CEO, Microfinance Institution</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="testimonial-card">
                        <p class="testimonial-text">"As a field agent, the mobile app makes my job so much easier. I can
                            process collections quickly and the offline capability is a lifesaver in remote areas."</p>
                        <div class="client-info">
                            <div class="client-avatar">AS</div>
                            <div>
                                <h6 class="mb-0">Amit Singh</h6>
                                <small class="text-muted">Field Agent, 3 years experience</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: white;">
        <div class="container text-center">
            <h2 class="mb-4">Ready to Transform Your Collection Process?</h2>
            <p class="lead mb-5">Join hundreds of financial institutions already using Mobile Pigmy</p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <!--<a href="login-sookth.php" class="btn btn-light btn-login">-->
                <!--    <i class="bi bi-shield-lock me-2"></i>Sookth Login-->
                <!--</a>-->
                <a href="bank-login.php" class="btn btn-outline-light btn-login">
                    <i class="bi bi-shield-lock me-2"></i>Bank Login
                </a>
                <a href="agent-login.php" class="btn btn-outline-light btn-login">
                    <i class="bi bi-person-badge me-2"></i>Agent Login
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h4 class="mb-3"><i class="bi bi-cash-stack me-2"></i>Sookth Mobile Pigmy</h4>
                    <p>Revolutionizing banking collections with secure, mobile-first technology for cooperative banks
                        and financial institutions.</p>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h5 class="mb-3">Quick Links</h5>
                    <div class="footer-links">
                        <a href="#features">Features</a>
                        <a href="#workflow">How It Works</a>
                        <a href="#testimonials">Testimonials</a>
                        <a href="#">Pricing</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="mb-3">Login Portals</h5>
                    <div class="footer-links">
                        <!--<a href="login-sookth.php">Sookth Login</a>-->
                        <a href="bank-login.php">Bank Login</a>
                        <a href="agent-login.php">Agent Login</a>
                    </div>
                </div>
                <div class="col-lg-3 mb-4">
                    <h5 class="mb-3">Contact Us</h5>
                    <div class="footer-links">
                        <a href="mailto:mail@sookthsolutions.com"><i
                                class="bi bi-envelope me-2"></i>mail@sookthsolutions.com</a>
                        <a href="tel:+919880817335"><i class="bi bi-telephone me-2"></i>+91 98808 17335</a>
                        <a href="#"><i class="bi bi-geo-alt me-2"></i>Jhyothibha Pule Nagare </br>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Near New Police Station</br>&nbsp;&nbsp;&nbsp;&nbsp; Terdal,
                            Bagalkot - 587315</br> &nbsp;&nbsp;&nbsp;&nbsp; KA India</a>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: #475569;">
            <div class="text-center">
                <p class="mb-0">&copy; <?= date('Y') ?> Sookth Mobile Pigmy. All rights reserved. | A product of Sookth
                    Solutions</p>
                <small class="text-white">Manager</small>
                <small class="fw-bold" style="font-size:0.95rem; color:#ffd43b">
                    Shrikant Kori
                </small>
                <span class="mx-2">|</span>
                <small class="text-white">Designed by</small>
                <a href="https://gravatar.com/varunhosakoti" target="_blank" rel="noopener noreferrer"
                    class="ms-2 text-decoration-none" style="color:#ffd43b; font-weight:600;">
                    mr-varun
                </a>
            </div>
        </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('js/security.js') ?>"></script>
    <script>
        // Animated counter for stats
        function animateCounter() {
            const counters = document.querySelectorAll('.stat-number');
            const speed = 200;

            counters.forEach(counter => {
                const target = +counter.getAttribute('data-count');
                const count = +counter.innerText;
                const increment = target / speed;

                if (count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(animateCounter, 1);
                } else {
                    counter.innerText = target;
                }
            });
        }

        // Initialize counter when stats section is in view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter();
                    observer.unobserve(entry.target);
                }
            });
        });

        const statsSection = document.querySelector('.stats-section');
        if (statsSection) {
            observer.observe(statsSection);
        }

        // Navbar background on scroll
        window.addEventListener('scroll', function () {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 2px 15px rgba(0, 0, 0, 0.1)';
            } else {
                navbar.style.boxShadow = '0 2px 15px rgba(0, 0, 0, 0.1)';
            }
        });

        //short cuts
        document.addEventListener("keydown", function (event) {
            if (event.altKey && event.code === "KeyS") {
                window.location.href = "<?= url('admin/login') ?>";
            }
            if (event.altKey && event.code === "KeyA") {
                window.location.href = "<?= url('agent/login') ?>";
            }
            if (event.altKey && event.code === "KeyB") {
                window.location.href = "<?= url('bank/login') ?>";
            }
        });

        // Make Sookth button only work with Ctrl+Click
        (function () {
            const sookthBtn = document.getElementById('sookthBtn');
            if (!sookthBtn) return;
            sookthBtn.addEventListener('click', function (e) {
                if (!e.ctrlKey) {
                    e.preventDefault();
                    e.stopPropagation();
                    // Optional: visual hint
                    sookthBtn.blur();
                }
                // If Ctrl is held, allow the normal navigation to proceed
            });
        })();
    </script>
</body>

</html>
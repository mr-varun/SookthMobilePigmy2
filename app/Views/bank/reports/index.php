<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'All Reports' ?> - Sookth Mobile Pigmy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
        }
        .report-card {
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
            height: 100%;
        }
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        .report-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 1rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= url('bank/dashboard') ?>">
                <i class="bi bi-building me-2"></i>Bank Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('bank/dashboard') ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('bank/agents') ?>">
                            <i class="bi bi-people"></i> Agents
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= url('bank/reports') ?>">
                            <i class="bi bi-bar-chart"></i> Reports
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?= e(user('name')) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= url('bank/profile') ?>"><i class="bi bi-person"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="<?= url('bank/backup') ?>"><i class="bi bi-database"></i> Backup</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= url('bank/logout') ?>"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid mt-4">
        <!-- Flash Messages -->
        <?= flashMessages() ?>

        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col">
                <h2><i class="bi bi-bar-chart"></i> Reports & Analytics</h2>
                <p class="text-muted">Generate and view various reports for your branch</p>
            </div>
        </div>

        <!-- Report Categories -->
        <div class="row g-4">
            <!-- Detailed Transactions Report -->
            <div class="col-md-6 col-lg-4">
                <a href="<?= url('bank/reports/detailed') ?>" class="text-decoration-none">
                    <div class="card report-card">
                        <div class="card-body text-center p-4">
                            <div class="report-icon bg-primary text-white mx-auto">
                                <i class="bi bi-list-check"></i>
                            </div>
                            <h5 class="card-title">Detailed Transactions</h5>
                            <p class="card-text text-muted">
                                View all transactions with complete details including agent information and customer data
                            </p>
                            <span class="badge bg-primary">View Report</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Agent Transactions Report -->
            <div class="col-md-6 col-lg-4">
                <div class="card report-card" onclick="showAgentSelector()">
                    <div class="card-body text-center p-4">
                        <div class="report-icon bg-success text-white mx-auto">
                            <i class="bi bi-person-badge"></i>
                        </div>
                        <h5 class="card-title">Agent-wise Transactions</h5>
                        <p class="card-text text-muted">
                            View transactions by specific agent with summary and detailed breakdown
                        </p>
                        <span class="badge bg-success">Select Agent</span>
                    </div>
                </div>
            </div>

            <!-- Daily Summary Report -->
            <div class="col-md-6 col-lg-4">
                <a href="<?= url('bank/reports/print-summary?date=' . date('Y-m-d')) ?>" class="text-decoration-none">
                    <div class="card report-card">
                        <div class="card-body text-center p-4">
                            <div class="report-icon bg-info text-white mx-auto">
                                <i class="bi bi-calendar-day"></i>
                            </div>
                            <h5 class="card-title">Daily Summary</h5>
                            <p class="card-text text-muted">
                                Print today's collection summary by all agents
                            </p>
                            <span class="badge bg-info">Print Summary</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Agent Performance Report -->
            <div class="col-md-6 col-lg-4">
                <a href="<?= url('bank/agents') ?>" class="text-decoration-none">
                    <div class="card report-card">
                        <div class="card-body text-center p-4">
                            <div class="report-icon bg-warning text-white mx-auto">
                                <i class="bi bi-trophy"></i>
                            </div>
                            <h5 class="card-title">Agent Performance</h5>
                            <p class="card-text text-muted">
                                View all agents with their collection statistics and performance metrics
                            </p>
                            <span class="badge bg-warning">View Agents</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Monthly Report -->
            <div class="col-md-6 col-lg-4">
                <a href="<?= url('bank/reports/detailed?start_date=' . date('Y-m-01') . '&end_date=' . date('Y-m-t')) ?>" class="text-decoration-none">
                    <div class="card report-card">
                        <div class="card-body text-center p-4">
                            <div class="report-icon bg-secondary text-white mx-auto">
                                <i class="bi bi-calendar-month"></i>
                            </div>
                            <h5 class="card-title">Monthly Report</h5>
                            <p class="card-text text-muted">
                                View this month's complete transaction report
                            </p>
                            <span class="badge bg-secondary">This Month</span>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Custom Date Range -->
            <div class="col-md-6 col-lg-4">
                <div class="card report-card" onclick="showDateSelector()">
                    <div class="card-body text-center p-4">
                        <div class="report-icon bg-dark text-white mx-auto">
                            <i class="bi bi-calendar-range"></i>
                        </div>
                        <h5 class="card-title">Custom Date Range</h5>
                        <p class="card-text text-muted">
                            Generate report for any custom date range
                        </p>
                        <span class="badge bg-dark">Select Dates</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Agent Selector Modal -->
    <div class="modal fade" id="agentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Agent</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">Please visit the <a href="<?= url('bank/agents') ?>">Agents page</a> and click "View Transactions" for any agent.</p>
                    <a href="<?= url('bank/agents') ?>" class="btn btn-primary w-100">
                        <i class="bi bi-people"></i> Go to Agents
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Date Selector Modal -->
    <div class="modal fade" id="dateModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Date Range</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= url('bank/reports/detailed') ?>" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="<?= date('Y-m-01') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Generate Report
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
    <script src="<?= asset('js/security.js') ?>"></script>
    <script>
        function showAgentSelector() {
            new bootstrap.Modal(document.getElementById('agentModal')).show();
        }

        function showDateSelector() {
            new bootstrap.Modal(document.getElementById('dateModal')).show();
        }
    </script>
</body>
</html>

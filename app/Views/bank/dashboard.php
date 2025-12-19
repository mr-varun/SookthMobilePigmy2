<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Bank Dashboard' ?> - Sookth Mobile Pigmy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
        }
        .stat-card {
            border-radius: 10px;
            padding: 1.5rem;
            height: 100%;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
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
                        <a class="nav-link" href="<?= url('bank/reports') ?>">
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
                <h2>Welcome, <?= e(user('name')) ?>!</h2>
                <p class="text-muted">Branch Code: <?= e(user('branch_code')) ?></p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Agents</h6>
                            <h3 class="mb-0"><?= $stats['total_agents'] ?></h3>
                        </div>
                        <div class="stat-icon bg-primary text-white">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Today's Collection</h6>
                            <h3 class="mb-0"><?= currency($stats['total_collections_today']) ?></h3>
                        </div>
                        <div class="stat-icon bg-success text-white">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">This Month</h6>
                            <h3 class="mb-0"><?= currency($stats['total_collections_month']) ?></h3>
                        </div>
                        <div class="stat-icon bg-info text-white">
                            <i class="bi bi-calendar-month"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Customers</h6>
                            <h3 class="mb-0"><?= $stats['total_customers'] ?></h3>
                        </div>
                        <div class="stat-icon bg-warning text-white">
                            <i class="bi bi-person-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Agents & Recent Collections -->
        <div class="row g-3">
            <!-- Top Agents -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-trophy"></i> Top Performing Agents</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($topAgents)): ?>
                            <p class="text-muted text-center py-3">No data available</p>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($topAgents as $index => $agent): ?>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>#<?= $index + 1 ?> <?= e($agent['name']) ?></strong>
                                            <br>
                                            <small class="text-muted"><?= e($agent['agent_code']) ?></small>
                                        </div>
                                        <span class="badge bg-success"><?= currency($agent['total_amount'] ?? 0) ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Collections -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Collections</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recentCollections)): ?>
                            <p class="text-muted text-center py-3">No recent collections</p>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($recentCollections as $collection): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <strong><?= e($collection['customer_name']) ?></strong>
                                                <br>
                                                <small class="text-muted">
                                                    Agent: <?= e($collection['agent_name']) ?>
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <strong class="text-success"><?= currency($collection['amount']) ?></strong>
                                                <br>
                                                <small class="text-muted"><?= formatDate($collection['date']) ?> <?= e($collection['time']) ?></small>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mt-4 mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <a href="<?= url('bank/agents') ?>" class="btn btn-outline-primary w-100 py-3">
                                    <i class="bi bi-people fs-4 d-block mb-2"></i>
                                    View Agents
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= url('bank/reports') ?>" class="btn btn-outline-success w-100 py-3">
                                    <i class="bi bi-file-earmark-text fs-4 d-block mb-2"></i>
                                    Generate Reports
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= url('bank/reports/detailed') ?>" class="btn btn-outline-info w-100 py-3">
                                    <i class="bi bi-list-check fs-4 d-block mb-2"></i>
                                    Detailed Transactions
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= url('bank/backup') ?>" class="btn btn-outline-secondary w-100 py-3">
                                    <i class="bi bi-database fs-4 d-block mb-2"></i>
                                    Database Backup
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
    <script src="<?= asset('js/security.js') ?>"></script>
</body>
</html>

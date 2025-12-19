<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Manage Agents' ?> - Sookth Mobile Pigmy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
        }
        .agent-card {
            transition: transform 0.2s;
        }
        .agent-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
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
                        <a class="nav-link active" href="<?= url('bank/agents') ?>">
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
                <h2><i class="bi bi-people"></i> Manage Agents</h2>
                <p class="text-muted">View and manage all agents in your branch</p>
            </div>
        </div>

        <!-- Agents List -->
        <?php if (empty($agents)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> No agents found in your branch.
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($agents as $agent): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card agent-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-1"><?= e($agent['agent_name']) ?></h5>
                                        <p class="text-muted small mb-0">
                                            <i class="bi bi-qr-code"></i> <?= e($agent['agent_code']) ?>
                                        </p>
                                    </div>
                                    <?php if (isset($agent['status']) && $agent['status'] == 1): ?>
                                        <span class="badge bg-success status-badge">
                                            <i class="bi bi-check-circle"></i> Active
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger status-badge">
                                            <i class="bi bi-x-circle"></i> Disabled
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-2">
                                    <small class="text-muted"><i class="bi bi-telephone"></i></small>
                                    <span><?= e($agent['agent_mobile'] ?? 'N/A') ?></span>
                                </div>

                                <?php if (!empty($agent['agent_email'])): ?>
                                    <div class="mb-2">
                                        <small class="text-muted"><i class="bi bi-envelope"></i></small>
                                        <span><?= e($agent['agent_email']) ?></span>
                                    </div>
                                <?php endif; ?>

                                <div class="mb-3">
                                    <small class="text-muted"><i class="bi bi-geo-alt"></i></small>
                                    <span><?= e($agent['address'] ?? 'N/A') ?></span>
                                </div>

                                <div class="border-top pt-3">
                                    <div class="row text-center">
                                        <div class="col">
                                            <small class="text-muted d-block">Today</small>
                                            <strong class="text-success"><?= currency($agent['today_collection'] ?? 0) ?></strong>
                                        </div>
                                        <div class="col">
                                            <small class="text-muted d-block">This Month</small>
                                            <strong class="text-primary"><?= currency($agent['month_collection'] ?? 0) ?></strong>
                                        </div>
                                        <div class="col">
                                            <small class="text-muted d-block">Customers</small>
                                            <strong><?= $agent['customer_count'] ?? 0 ?></strong>
                                        </div>
                                    </div>
                                </div>
    
                                    <!-- Enable/Disable Agent -->
                                    <form action="<?= url('bank/agents/toggle-status') ?>" method="POST" style="margin: 0;">
                                        <input type="hidden" name="agent_code" value="<?= e($agent['agent_code']) ?>">
                                        <input type="hidden" name="current_status" value="<?= e($agent['status'] ?? 1) ?>">
                                        <?php if (isset($agent['status']) && $agent['status'] == 1): ?>
                                            <button type="submit" class="btn btn-sm btn-warning w-100" 
                                                    onclick="return confirm('Are you sure you want to DISABLE this agent? They will not be able to login.')">
                                                <i class="bi bi-pause-circle"></i> Disable Agent
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-sm btn-success w-100"
                                                    onclick="return confirm('Are you sure you want to ENABLE this agent? They will be able to login.')">
                                                <i class="bi bi-play-circle"></i> Enable Agent
                                            </button>
                                        <?php endif; ?>
                                    </form>
                                
                                <div class="mt-3 d-grid gap-2">
                                    <a href="<?= url('bank/reports/agent-transactions?agent_code=' . $agent['agent_code']) ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-text"></i> View Transactions
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
    <script src="<?= asset('js/security.js') ?>"></script>
</body>
</html>

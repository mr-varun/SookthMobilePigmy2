<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Profile' ?> - SMP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= url('bank/dashboard') ?>">
                <i class="bi bi-bank"></i> SMP Bank
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
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
                            <i class="bi bi-file-text"></i> Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= url('bank/profile') ?>">
                            <i class="bi bi-person-circle"></i> Profile
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?= e(user('name')) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= url('bank/profile') ?>">Profile</a></li>
                            <li><a class="dropdown-item" href="<?= url('bank/reset-password') ?>">Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= url('bank/logout') ?>">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Flash Messages -->
        <?= flashMessages() ?>

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-person-circle"></i> Branch Profile</h2>
            <a href="<?= url('bank/reset-password') ?>" class="btn btn-primary">
                <i class="bi bi-key"></i> Change Password
            </a>
        </div>

        <div class="row">
            <!-- Profile Information -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-building"></i> Branch Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th width="30%">Branch Code:</th>
                                    <td><span class="badge bg-primary fs-6"><?= e($branch['branch_code']) ?></span></td>
                                </tr>
                                <tr>
                                    <th>Branch Name:</th>
                                    <td><strong class="fs-5"><?= e($branch['branch_name']) ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Manager Name:</th>
                                    <td><?= e($branch['manager_name']) ?></td>
                                </tr>
                                <tr>
                                    <th>Mobile Number:</th>
                                    <td>
                                        <i class="bi bi-phone"></i> <?= e($branch['manager_mobile']) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email Address:</th>
                                    <td>
                                        <i class="bi bi-envelope"></i> <?= e($branch['manager_email']) ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <?php if (($branch['status'] ?? 'active') == 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Activity -->
                <?php 
                    $recentActivity = Database::fetchAll("
                        SELECT t.*, a.agent_name 
                        FROM transactions t
                        LEFT JOIN agent a ON t.agent_code = a.agent_code
                        WHERE t.branch_code = ?
                        ORDER BY t.date DESC, t.time DESC
                        LIMIT 10
                    ", [$branch['branch_code']]);
                ?>
                
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Activity</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($recentActivity)): ?>
                            <p class="text-center text-muted p-4 mb-0">No recent activity</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th>Agent</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recentActivity as $activity): ?>
                                            <tr>
                                                <td>
                                                    <small><?= formatDate($activity['date']) ?></small><br>
                                                    <small class="text-muted"><?= date('h:i A', strtotime($activity['time'])) ?></small>
                                                </td>
                                                <td>
                                                    <?= e($activity['agent_name']) ?><br>
                                                    <small class="text-muted"><?= e($activity['agent_code']) ?></small>
                                                </td>
                                                <td>
                                                    <?= e($activity['account_name']) ?><br>
                                                    <small class="text-muted"><?= e($activity['account_number']) ?></small>
                                                </td>
                                                <td>
                                                    <strong class="text-success"><?= currency($activity['amount']) ?></strong>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($recentActivity)): ?>
                        <div class="card-footer text-center">
                            <a href="<?= url('bank/reports/detailed') ?>" class="text-decoration-none">
                                View All Transactions <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Statistics Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Stats -->
                <?php 
                    $branchStats = [
                        'total_agents' => Database::fetchOne("SELECT COUNT(*) as count FROM agent WHERE branch_code = ?", [$branch['branch_code']])['count'] ?? 0,
                        'total_customers' => Database::fetchOne("SELECT COUNT(*) as count FROM accounts WHERE branch_code = ?", [$branch['branch_code']])['count'] ?? 0,
                        'today_collections' => Database::fetchOne("SELECT COUNT(*) as count FROM transactions WHERE branch_code = ? AND DATE(date) = CURDATE()", [$branch['branch_code']])['count'] ?? 0,
                        'today_amount' => Database::fetchOne("SELECT SUM(amount) as total FROM transactions WHERE branch_code = ? AND DATE(date) = CURDATE()", [$branch['branch_code']])['total'] ?? 0,
                    ];
                ?>
                
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-graph-up"></i> Quick Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Total Agents</span>
                                <h4 class="mb-0"><?= $branchStats['total_agents'] ?></h4>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Total Customers</span>
                                <h4 class="mb-0"><?= $branchStats['total_customers'] ?></h4>
                            </div>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Today's Collections</span>
                                <h4 class="mb-0"><?= $branchStats['today_collections'] ?></h4>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Today's Amount</span>
                                <h4 class="mb-0 text-success"><?= currency($branchStats['today_amount']) ?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="<?= url('bank/dashboard') ?>" class="text-decoration-none">
                            View Dashboard <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card shadow-sm mt-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="<?= url('bank/agents') ?>" class="list-group-item list-group-item-action">
                            <i class="bi bi-people"></i> View All Agents
                        </a>
                        <a href="<?= url('bank/reports') ?>" class="list-group-item list-group-item-action">
                            <i class="bi bi-file-text"></i> Generate Reports
                        </a>
                        <a href="<?= url('bank/reports/detailed') ?>" class="list-group-item list-group-item-action">
                            <i class="bi bi-receipt"></i> View Transactions
                        </a>
                        <a href="<?= url('bank/reset-password') ?>" class="list-group-item list-group-item-action">
                            <i class="bi bi-key"></i> Change Password
                        </a>
                        <a href="<?= url('bank/backup') ?>" class="list-group-item list-group-item-action">
                            <i class="bi bi-download"></i> Backup Data
                        </a>
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

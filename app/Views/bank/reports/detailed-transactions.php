<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Detailed Transactions' ?> - Sookth Mobile Pigmy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
        }
        @media print {
            .no-print {
                display: none;
            }
        }
        .table-sm td, .table-sm th {
            padding: 0.5rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark no-print">
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
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('bank/logout') ?>">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid mt-4">
        <!-- Flash Messages -->
        <div class="no-print">
            <?= flashMessages() ?>
        </div>

        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col">
                <h2><i class="bi bi-list-check"></i> Detailed Transaction Report</h2>
                <p class="text-muted">Branch: <?= e(user('name')) ?> (<?= e(user('branch_code')) ?>)</p>
                <p class="text-muted">Period: <?= formatDate($startDate) ?> to <?= formatDate($endDate) ?></p>
            </div>
            <div class="col-auto no-print">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bi bi-printer"></i> Print
                </button>
                <a href="<?= url('bank/dashboard') ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="card mb-4 no-print">
            <div class="card-body">
                <form method="GET" action="<?= url('bank/reports/detailed') ?>">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="<?= e($startDate) ?>">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="<?= e($endDate) ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-search"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Card -->
        <?php 
            $totalAmount = 0;
            $totalCount = count($collections);
            $agentStats = [];
            
            foreach ($collections as $collection) {
                $totalAmount += $collection['amount'];
                
                // Group by agent
                $agentCode = $collection['agent_code'];
                if (!isset($agentStats[$agentCode])) {
                    $agentStats[$agentCode] = [
                        'name' => $collection['agent_name'],
                        'count' => 0,
                        'amount' => 0
                    ];
                }
                $agentStats[$agentCode]['count']++;
                $agentStats[$agentCode]['amount'] += $collection['amount'];
            }
        ?>
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Transactions</h6>
                        <h3 class="mb-0"><?= $totalCount ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Amount</h6>
                        <h3 class="mb-0 text-success"><?= currency($totalAmount) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Active Agents</h6>
                        <h3 class="mb-0 text-primary"><?= count($agentStats) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Avg Transaction</h6>
                        <h3 class="mb-0 text-info"><?= $totalCount > 0 ? currency($totalAmount / $totalCount) : currency(0) ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Agent-wise Summary -->
        <?php if (!empty($agentStats)): ?>
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Agent-wise Summary</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Agent Name</th>
                                <th>Agent Code</th>
                                <th class="text-center">Transactions</th>
                                <th class="text-end">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $index = 1; ?>
                            <?php foreach ($agentStats as $code => $stat): ?>
                                <tr>
                                    <td><?= $index++ ?></td>
                                    <td><?= e($stat['name']) ?></td>
                                    <td><?= e($code) ?></td>
                                    <td class="text-center"><?= $stat['count'] ?></td>
                                    <td class="text-end"><strong class="text-success"><?= currency($stat['amount']) ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Detailed Transactions Table -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">All Transactions</h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($collections)): ?>
                    <div class="alert alert-info m-3">
                        <i class="bi bi-info-circle"></i> No transactions found for the selected period.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Transaction ID</th>
                                    <th>Agent</th>
                                    <th>Account No</th>
                                    <th>Customer Name</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($collections as $index => $collection): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= formatDate($collection['date']) ?></td>
                                        <td><?= e($collection['time']) ?></td>
                                        <td><small><?= e($collection['transaction_id']) ?></small></td>
                                        <td>
                                            <strong><?= e($collection['agent_name']) ?></strong>
                                            <br>
                                            <small class="text-muted"><?= e($collection['agent_code']) ?></small>
                                        </td>
                                        <td><?= e($collection['account_number']) ?></td>
                                        <td><?= e($collection['customer_name']) ?></td>
                                        <td class="text-end"><strong class="text-success"><?= currency($collection['amount']) ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="7" class="text-end">Total:</th>
                                    <th class="text-end"><strong class="text-success"><?= currency($totalAmount) ?></strong></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
    <script src="<?= asset('js/security.js') ?>"></script>
</body>
</html>

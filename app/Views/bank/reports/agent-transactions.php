<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Agent Transactions' ?> - Sookth Mobile Pigmy</title>
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
                <h2><i class="bi bi-file-text"></i> Agent Transaction Report</h2>
                <?php if ($agent): ?>
                    <p class="text-muted mb-1">Agent: <?= e($agent['agent_name']) ?> (<?= e($agent['agent_code']) ?>)</p>
                <?php endif; ?>
                <p class="text-muted">Period: <?= formatDate($startDate) ?> to <?= formatDate($endDate) ?></p>
            </div>
            <div class="col-auto no-print">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bi bi-printer"></i> Print
                </button>
                <a href="<?= url('bank/agents') ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="card mb-4 no-print">
            <div class="card-body">
                <form method="GET" action="<?= url('bank/reports/agent-transactions') ?>">
                    <div class="row g-3">
                        <input type="hidden" name="agent_code" value="<?= e($_GET['agent_code'] ?? '') ?>">
                        <div class="col-md-4">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="<?= e($startDate) ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="<?= e($endDate) ?>">
                        </div>
                        <div class="col-md-4">
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
            foreach ($collections as $collection) {
                $totalAmount += $collection['amount'];
            }
        ?>
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Transactions</h6>
                        <h3 class="mb-0"><?= $totalCount ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Total Amount</h6>
                        <h3 class="mb-0 text-success"><?= currency($totalAmount) ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Average Transaction</h6>
                        <h3 class="mb-0 text-info"><?= $totalCount > 0 ? currency($totalAmount / $totalCount) : currency(0) ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Transaction Details</h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($collections)): ?>
                    <div class="alert alert-info m-3">
                        <i class="bi bi-info-circle"></i> No transactions found for the selected period.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Transaction ID</th>
                                    <th>Account Number</th>
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
                                        <td><?= e($collection['account_number']) ?></td>
                                        <td><?= e($collection['customer_name']) ?></td>
                                        <td class="text-end"><strong class="text-success"><?= currency($collection['amount']) ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="6" class="text-end">Total:</th>
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

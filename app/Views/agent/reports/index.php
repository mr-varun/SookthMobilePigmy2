<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Reports - Agent Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f0f4f8;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            min-height: 100vh;
            padding-bottom: 20px;
        }
        nav {
            background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            padding: 12px 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.12);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        nav .navbar-brand {
            font-size: 1.2rem;
            font-weight: 700;
            color: white !important;
        }
        nav .btn {
            border-radius: 10px;
            padding: 8px 14px;
            font-weight: 600;
            font-size: 13px;
        }
        .page-header {
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin: 12px 0;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        .page-header h2 {
            color: #1e293b;
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
        }
        .page-header .subtitle {
            color: #64748b;
            font-size: 0.9rem;
            margin-top: 4px;
        }
        @media print {
            body {
                background: white !important;
                font-size: 12pt;
            }
            nav, .filter-card, .btn, button {
                display: none !important;
            }
            .container {
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .page-header {
                box-shadow: none !important;
                border: 1px solid #ddd;
                margin-bottom: 20px !important;
            }
            .stats-cards {
                page-break-inside: avoid;
                border: 1px solid #ddd;
                padding: 15px;
                margin-bottom: 20px;
            }
            .stat-card {
                border: 1px solid #ddd !important;
                box-shadow: none !important;
            }
            .transactions-card {
                box-shadow: none !important;
                border: 1px solid #ddd;
            }
            .transaction-group {
                page-break-inside: avoid;
            }
            .transaction-group-header {
                background: #f8f9fa !important;
                border: 1px solid #ddd !important;
                color: #000 !important;
            }
            .transactions-table {
                border: 1px solid #ddd !important;
            }
            .transactions-table thead {
                background: #f8f9fa !important;
                color: #000 !important;
            }
            .transactions-table tbody tr {
                border-bottom: 1px solid #ddd !important;
            }
        }
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin: 12px 0;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 14px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
        }
        .stat-card .icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 8px;
        }
        .stat-card.primary .icon {
            background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            color: white;
        }
        .stat-card.success .icon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }
        .stat-card .label {
            color: #64748b;
            font-size: 0.75rem;
            margin-bottom: 4px;
            font-weight: 600;
        }
        .stat-card .value {
            color: #1e293b;
            font-size: 1.3rem;
            font-weight: 700;
        }
        .filter-card {
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin: 12px 0;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        .filter-card h5 {
            color: #1e293b;
            font-weight: 700;
            margin-bottom: 16px;
            font-size: 1rem;
        }
        .form-label {
            font-weight: 600;
            color: #64748b;
            margin-bottom: 6px;
            font-size: 0.85rem;
        }
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 12px;
            transition: all 0.3s;
            font-size: 0.9rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #06b6d4;
            box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.15);
        }
        .btn-search {
            background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            border: none;
            border-radius: 10px;
            color: white;
            padding: 10px 20px;
            font-weight: 600;
            transition: transform 0.2s, box-shadow 0.2s;
            font-size: 0.9rem;
        }
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(6, 182, 212, 0.4);
        }
        .transactions-card {
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin: 12px 0;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        .transactions-card h5 {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
        }
        .transaction-group {
            margin-bottom: 20px;
        }
        .transaction-group-header {
            background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            color: white;
            padding: 10px 14px;
            border-radius: 10px;
            font-weight: 600;
            margin-bottom: 12px;
            font-size: 0.9rem;
        }
        .table-responsive {
            border-radius: 10px;
            overflow-x: auto;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            -webkit-overflow-scrolling: touch;
        }
        .transactions-table {
            width: 100%;
            margin-bottom: 0;
            background: white;
            font-size: 0.85rem;
        }
        .transactions-table thead {
            background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            color: white;
        }
        .transactions-table thead th {
            border: none;
            padding: 10px 8px;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            white-space: nowrap;
        }
        .transactions-table tbody tr {
            transition: background-color 0.2s;
            border-bottom: 1px solid #e9ecef;
        }
        .transactions-table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .transactions-table tbody td {
            padding: 10px 8px;
            vertical-align: middle;
            color: #64748b;
            font-size: 0.85rem;
        }
        .transactions-table .account-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.9rem;
        }
        .transactions-table .account-number {
            color: #06b6d4;
            font-weight: 600;
        }
        .transactions-table .trans-id {
            color: #94a3b8;
            font-size: 0.8rem;
        }
        .transactions-table .amount {
            color: #10b981;
            font-weight: 700;
            font-size: 1rem;
        }
        .no-data {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
        }
        .no-data i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 16px;
        }
        .container {
            max-width: 600px;
            padding: 0 12px;
        }
        @media (max-width: 576px) {
            nav .navbar-brand {
                font-size: 1rem;
            }
            nav .btn {
                padding: 6px 10px;
                font-size: 12px;
            }
            .page-header h2 {
                font-size: 1.1rem;
            }
            .stats-cards {
                gap: 8px;
            }
            .stat-card {
                padding: 10px;
            }
            .stat-card .icon {
                width: 32px;
                height: 32px;
                font-size: 1rem;
            }
            .stat-card .label {
                font-size: 0.7rem;
            }
            .stat-card .value {
                font-size: 1.1rem;
            }
            .filter-card {
                padding: 12px;
            }
            .btn-search {
                width: 100%;
                margin-top: 8px;
            }
            .transactions-table {
                font-size: 0.75rem;
            }
            .transactions-table thead th {
                padding: 8px 4px;
                font-size: 0.7rem;
            }
            .transactions-table tbody td {
                padding: 8px 4px;
                font-size: 0.75rem;
            }
            .transactions-table .amount {
                font-size: 0.9rem;
            }
            .transaction-group-header {
                font-size: 0.85rem;
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Header -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= url('agent/dashboard') ?>">
                <i class="fas fa-user-tie me-2"></i>
                <?= htmlspecialchars($agent_name) ?>
            </a>
            <div class="d-flex">
                <a href="<?= url('agent/dashboard') ?>" class="btn btn-light btn-sm me-2">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="<?= url('agent/logout') ?>" class="btn btn-danger btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <!-- Page Header -->
        <div class="page-header">
            <h2><i class="fas fa-chart-bar me-2"></i>Transaction Reports</h2>
            <p class="subtitle mb-0"><?= htmlspecialchars($branch_name) ?></p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-cards">
            <div class="stat-card primary">
                <div class="icon">
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="label">Total Transactions</div>
                <div class="value"><?= number_format($total_count) ?></div>
            </div>
            <div class="stat-card success">
                <div class="icon">
                    <i class="fas fa-rupee-sign"></i>
                </div>
                <div class="label">Total Amount</div>
                <div class="value">₹<?= number_format($total_amount, 2) ?></div>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="filter-card">
            <h5><i class="fas fa-filter me-2"></i>Filter Transactions</h5>
            <form method="POST" action="<?= url('agent/reports') ?>">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">From Date</label>
                        <input type="date" name="from_date" class="form-control" 
                               value="<?= htmlspecialchars($from_date) ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">To Date</label>
                        <input type="date" name="to_date" class="form-control" 
                               value="<?= htmlspecialchars($to_date) ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Sort By</label>
                        <select name="sort_by" class="form-select">
                            <option value="date" <?= $sort_by === 'date' ? 'selected' : '' ?>>Date</option>
                            <option value="account_number" <?= $sort_by === 'account_number' ? 'selected' : '' ?>>Account Number</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-search">
                        <i class="fas fa-search me-2"></i>Search Transactions
                    </button>
                    <a href="<?= url('agent/reports/daywise') ?>" class="btn btn-outline-primary" style="border-radius: 10px;">
                        <i class="fas fa-calendar-alt me-2"></i>Daywise Summary
                    </a>
                    <button type="button" class="btn btn-outline-success" style="border-radius: 10px;" onclick="printReport()" <?= empty($transactions) ? 'disabled' : '' ?>>
                        <i class="fas fa-print me-2"></i>Print Report
                    </button>
                </div>
            </form>
        </div>

        <!-- Transactions Display -->
        <div class="transactions-card">
            <h5 class="mb-4">
                <i class="fas fa-list me-2"></i>
                Transaction History 
                <?php if ($from_date && $to_date): ?>
                    <small class="text-muted">(<?= date('d M Y', strtotime($from_date)) ?> - <?= date('d M Y', strtotime($to_date)) ?>)</small>
                <?php endif; ?>
            </h5>

            <?php if (!empty($transactions)): ?>
                <?php foreach ($transactions as $group_key => $group_transactions): ?>
                    <div class="transaction-group">
                        <div class="transaction-group-header">
                            <?php if ($sort_by === 'date'): ?>
                                <i class="far fa-calendar me-2"></i>
                                <?= date('l, d F Y', strtotime($group_key)) ?>
                            <?php else: ?>
                                <i class="fas fa-user me-2"></i>
                                Account: <?= htmlspecialchars($group_key) ?>
                            <?php endif; ?>
                            <span class="float-end">
                                <?= count($group_transactions) ?> transaction(s) | 
                                ₹<?= number_format(array_sum(array_column($group_transactions, 'amount')), 2) ?>
                            </span>
                        </div>

                        <div class="table-responsive">
                            <table class="table transactions-table">
                                <thead>
                                    <tr>
                                        <th>Transaction ID</th>
                                        <th>Account Name</th>
                                        <th>Account Number</th>
                                        <th>Time</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($group_transactions as $transaction): ?>
                                        <tr>
                                            <td class="trans-id">
                                                <i class="fas fa-hashtag me-1"></i>
                                                <?= htmlspecialchars($transaction['transaction_id']) ?>
                                            </td>
                                            <td class="account-name">
                                                <i class="fas fa-user-circle me-2"></i>
                                                <?= htmlspecialchars($transaction['account_name']) ?>
                                            </td>
                                            <td class="account-number">
                                                <i class="fas fa-credit-card me-1"></i>
                                                <?= htmlspecialchars($transaction['account_number']) ?>
                                            </td>
                                            <td>
                                                <i class="far fa-clock me-1"></i>
                                                <?= date('h:i A', strtotime($transaction['time'])) ?>
                                            </td>
                                            <td class="text-end amount">
                                                <i class="fas fa-rupee-sign me-1"></i><?= number_format($transaction['amount'], 2) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-data">
                    <i class="fas fa-inbox"></i>
                    <h5>No Transactions Found</h5>
                    <p>No transactions available for the selected date range.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
    <script>
        // Set max date to today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.querySelectorAll('input[type="date"]').forEach(input => {
                input.max = today;
            });
        });

        // Print report function
        function printReport() {
            window.print();
        }
    </script>
</body>
</html>

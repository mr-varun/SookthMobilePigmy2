<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daywise Collection Summary - Agent Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 1rem;
            padding-bottom: 5rem;
        }

        .header-section {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .header-subtitle {
            color: #6b7280;
            font-size: 0.95rem;
        }

        .summary-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 1.25rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .summary-label {
            color: #6b7280;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .summary-value {
            font-size: 1.75rem;
            font-weight: 700;
        }

        .summary-card:nth-child(1) .summary-value {
            color: #667eea;
        }

        .summary-card:nth-child(2) .summary-value {
            color: #10b981;
        }

        .report-section {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .report-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .table-wrapper {
            overflow-x: auto;
            margin-bottom: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        thead th {
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.9rem;
        }

        tbody tr {
            border-bottom: 1px solid #e5e7eb;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        tbody td {
            padding: 1rem;
            color: #374151;
            font-size: 0.9rem;
        }

        .date-column {
            font-weight: 600;
            color: #667eea;
        }

        .day-column {
            color: #6b7280;
            font-style: italic;
        }

        .count-column {
            font-weight: 600;
            color: #374151;
        }

        .amount-column {
            font-weight: 700;
            color: #10b981;
        }

        tfoot {
            background: #f9fafb;
            font-weight: 700;
        }

        tfoot td {
            padding: 1rem;
            border-top: 2px solid #667eea;
        }

        .grand-total-label {
            color: #374151;
            font-size: 1rem;
        }

        .grand-total-value {
            color: #10b981;
            font-size: 1.1rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
        }

        .empty-icon {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 1rem;
        }

        .empty-title {
            font-weight: 600;
            color: #6b7280;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .empty-text {
            color: #9ca3af;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            display: flex;
            gap: 1rem;
        }

        .nav-btn {
            flex: 1;
            padding: 0.875rem;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .nav-btn-primary {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .nav-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(239, 68, 68, 0.3);
            color: white;
        }

        .nav-btn-secondary {
            background: #f3f4f6;
            color: #374151;
        }

        .nav-btn-secondary:hover {
            background: #e5e7eb;
            color: #374151;
        }

        @media (max-width: 576px) {
            body {
                padding: 0.75rem;
                padding-bottom: 5rem;
            }

            .header-title {
                font-size: 1.25rem;
            }

            .summary-cards {
                grid-template-columns: 1fr;
            }

            .summary-value {
                font-size: 1.5rem;
            }

            thead th,
            tbody td,
            tfoot td {
                padding: 0.75rem 0.5rem;
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header-section">
        <div class="header-title">
            <i class="fas fa-chart-line"></i>
            Daywise Collection Summary
        </div>
        <div class="header-subtitle">Your daily collection overview</div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="summary-label">
                <i class="fas fa-calendar-check"></i>
                Total Days
            </div>
            <div class="summary-value"><?= $total_days ?></div>
        </div>
        <div class="summary-card">
            <div class="summary-label">
                <i class="fas fa-rupee-sign"></i>
                Total Amount
            </div>
            <div class="summary-value">₹<?= number_format($grand_total, 2) ?></div>
        </div>
    </div>

    <!-- Report -->
    <div class="report-section">
        <div class="report-title">
            <i class="fas fa-table"></i>
            Daily Breakdown
        </div>

        <?php if (!empty($daywise_data)): ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Count</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($daywise_data as $row): ?>
                            <tr>
                                <td class="date-column">
                                    <?= date('d M Y', strtotime($row['transaction_date'])) ?>
                                </td>
                                <td class="day-column">
                                    <?= date('l', strtotime($row['transaction_date'])) ?>
                                </td>
                                <td class="count-column">
                                    <?= $row['transaction_count'] ?>
                                </td>
                                <td class="amount-column">
                                    ₹<?= number_format($row['daily_total'], 2) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="grand-total-label">GRAND TOTAL</td>
                            <td class="grand-total-label"><?= $total_transactions ?></td>
                            <td class="grand-total-value">₹<?= number_format($grand_total, 2) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <div class="empty-title">No Data Available</div>
                <div class="empty-text">No transactions found for this period</div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <?php if (!empty($daywise_data)): ?>
            <a href="<?= url('agent/reports/print-daywise') ?>" 
               class="nav-btn nav-btn-primary"
               target="_blank">
                <i class="fas fa-file-pdf"></i>
                <span>Print PDF</span>
            </a>
        <?php endif; ?>
        <a href="<?= url('agent/reports') ?>" class="nav-btn nav-btn-secondary">
            <i class="fas fa-arrow-left"></i>
            <span>Back</span>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
</body>
</html>

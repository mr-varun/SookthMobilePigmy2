<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Collection Summary - <?= formatDate($date) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                font-size: 12px;
            }
        }
        
        body {
            background: white;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
        }
        
        .company-name {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .report-title {
            font-size: 1.5rem;
            color: #666;
            margin-bottom: 10px;
        }
        
        .info-section {
            margin-bottom: 20px;
        }
        
        table {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .summary-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            padding: 12px;
            border: 1px solid #dee2e6;
        }
        
        .summary-table td {
            padding: 10px;
            border: 1px solid #dee2e6;
        }
        
        .total-row {
            font-weight: bold;
            background-color: #e9ecef;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #333;
        }
        
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
        }
        
        .signature-box {
            text-align: center;
            width: 200px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <div class="no-print mb-3">
        <button onclick="window.print()" class="btn btn-primary">
            <i class="bi bi-printer"></i> Print Report
        </button>
        <a href="<?= url('bank/reports') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Reports
        </a>
    </div>

    <!-- Report Header -->
    <div class="header">
        <div class="company-name">Sookth Mobile Pigmy</div>
        <div class="report-title">Daily Collection Summary Report</div>
        <div class="text-muted">
            <strong>Branch:</strong> <?= e(user('name')) ?> (<?= e(user('branch_code')) ?>)
        </div>
    </div>

    <!-- Report Info -->
    <div class="info-section">
        <div class="row">
            <div class="col-md-6">
                <strong>Date:</strong> <?= formatDate($date) ?>
            </div>
            <div class="col-md-6 text-end">
                <strong>Generated On:</strong> <?= date('d-m-Y h:i A') ?>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <?php 
        $totalTransactions = 0;
        $totalAmount = 0;
        foreach ($summary as $agent) {
            $totalTransactions += $agent['collection_count'] ?? 0;
            $totalAmount += $agent['total_amount'] ?? 0;
        }
    ?>
    
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="text-muted">Active Agents</h6>
                    <h3><?= count($summary) ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="text-muted">Total Collections</h6>
                    <h3><?= $totalTransactions ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h6 class="text-muted">Total Amount</h6>
                    <h3 class="text-success"><?= currency($totalAmount) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Agent-wise Summary Table -->
    <table class="summary-table table table-bordered">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="25%">Agent Name</th>
                <th width="15%">Agent Code</th>
                <th width="15%" class="text-center">Collections</th>
                <th width="20%" class="text-end">Total Amount</th>
                <th width="20%" class="text-end">Average</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($summary)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">No collections found for this date</td>
                </tr>
            <?php else: ?>
                <?php foreach ($summary as $index => $agent): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= e($agent['name']) ?></td>
                        <td><?= e($agent['agent_code']) ?></td>
                        <td class="text-center"><?= $agent['collection_count'] ?? 0 ?></td>
                        <td class="text-end"><?= currency($agent['total_amount'] ?? 0) ?></td>
                        <td class="text-end">
                            <?php 
                                $count = $agent['collection_count'] ?? 0;
                                $amount = $agent['total_amount'] ?? 0;
                                echo $count > 0 ? currency($amount / $count) : currency(0);
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                
                <!-- Total Row -->
                <tr class="total-row">
                    <td colspan="3" class="text-end">TOTAL:</td>
                    <td class="text-center"><?= $totalTransactions ?></td>
                    <td class="text-end"><?= currency($totalAmount) ?></td>
                    <td class="text-end">
                        <?= $totalTransactions > 0 ? currency($totalAmount / $totalTransactions) : currency(0) ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Footer with Signatures -->
    <div class="footer">
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">
                    Prepared By
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    Verified By
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    Authorized By
                </div>
            </div>
        </div>
        
        <div class="text-center mt-4 text-muted">
            <small>This is a computer-generated report and does not require a signature</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
    <script src="<?= asset('js/security.js') ?>"></script>
    <script>
        // Auto-print on load (optional)
        // window.onload = function() { window.print(); };
    </script>
</body>
</html>

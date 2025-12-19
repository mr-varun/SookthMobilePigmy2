<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Resend Messages' ?> - Agent Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 1000px;
        }
        .header-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .header-title {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .icon-box {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .icon-box i {
            font-size: 1.8rem;
            color: white;
        }
        h2 {
            margin: 0;
            color: #333;
            font-size: 1.75rem;
        }
        .subtitle {
            color: #666;
            margin: 0;
        }
        .btn-back {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            transition: transform 0.2s;
        }
        .btn-back:hover {
            transform: translateY(-2px);
            color: white;
        }
        .collections-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }
        .collection-item {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid #667eea;
            transition: transform 0.2s;
        }
        .collection-item:hover {
            transform: translateX(5px);
        }
        .collection-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        .collection-account {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
        }
        .collection-amount {
            font-size: 1.3rem;
            font-weight: 700;
            color: #28a745;
        }
        .collection-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        .detail-item {
            font-size: 0.9rem;
            color: #666;
        }
        .detail-item strong {
            color: #333;
        }
        .btn-resend {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 600;
            transition: transform 0.2s;
            cursor: pointer;
        }
        .btn-resend:hover {
            transform: translateY(-2px);
            color: white;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1rem;
        }
        .stats-bar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }
        .stat-item h4 {
            margin: 0;
            font-size: 1.5rem;
        }
        .stat-item small {
            opacity: 0.9;
        }
        @media (max-width: 768px) {
            .header-section {
                flex-direction: column;
                gap: 1rem;
            }
            .collection-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            .stats-bar {
                flex-direction: column;
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header-card">
            <div class="header-section">
                <div class="header-title">
                    <div class="icon-box">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <div>
                        <h2>Resend Messages</h2>
                        <p class="subtitle">Today's Collections</p>
                    </div>
                </div>
                <a href="<?= url('agent/dashboard') ?>" class="btn-back">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>
            
            <div class="stats-bar">
                <div class="stat-item">
                    <h4><?= count($collections) ?></h4>
                    <small>Total Collections</small>
                </div>
                <div class="stat-item">
                    <h4>₹<?= number_format(array_sum(array_column($collections, 'credit_amount')), 2) ?></h4>
                    <small>Total Amount</small>
                </div>
            </div>
        </div>

        <!-- Collections List -->
        <div class="collections-card">
            <?php if (empty($collections)): ?>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h4>No Collections Today</h4>
                    <p>You haven't made any collections today yet.</p>
                    <a href="<?= url('agent/dashboard') ?>" class="btn btn-primary mt-3">
                        <i class="fas fa-plus-circle me-2"></i>Make Collection
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($collections as $collection): ?>
                    <div class="collection-item">
                        <div class="collection-header">
                            <div>
                                <div class="collection-account">
                                    <i class="fas fa-user me-2"></i><?= htmlspecialchars($collection['account_name']) ?>
                                </div>
                                <small class="text-muted">A/C: <?= htmlspecialchars($collection['account_number']) ?></small>
                            </div>
                            <div class="collection-amount">
                                ₹<?= number_format($collection['credit_amount'], 2) ?>
                            </div>
                        </div>

                        <div class="collection-details">
                            <div class="detail-item">
                                <i class="fas fa-clock me-1"></i>
                                <strong>Time:</strong> <?= date('h:i A', strtotime($collection['date_time'])) ?>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-mobile-alt me-1"></i>
                                <strong>Mobile:</strong> <?= htmlspecialchars($collection['account_mobile']) ?>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-wallet me-1"></i>
                                <strong>Balance:</strong> ₹<?= number_format($collection['account_new_balance'], 2) ?>
                            </div>
                        </div>

                        <form method="POST" action="<?= url('agent/resend/send') ?>" style="display: inline;">
                            <input type="hidden" name="account_number" value="<?= htmlspecialchars($collection['account_number']) ?>">
                            <input type="hidden" name="amount" value="<?= htmlspecialchars($collection['credit_amount']) ?>">
                            <input type="hidden" name="date" value="<?= date('d-m-Y h:i A', strtotime($collection['date_time'])) ?>">
                            <button type="submit" class="btn-resend">
                                <i class="fas fa-paper-plane me-2"></i>Resend Message
                            </button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
</body>
</html>

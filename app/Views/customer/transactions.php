<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History - <?= htmlspecialchars($customer['account_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #06b6d4;
            --primary-dark: #0891b2;
            --secondary: #3b82f6;
            --success: #10b981;
            --success-light: #d1fae5;
            --text-dark: #0f172a;
            --text-medium: #334155;
            --text-muted: #64748b;
            --bg-page: #f1f5f9;
            --bg-card: #ffffff;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', sans-serif;
            background: var(--bg-page);
            color: var(--text-dark);
            padding: 0;
            margin: 0;
            overflow-x: hidden;
        }

        .top-bar {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 16px 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: var(--shadow-md);
            backdrop-filter: blur(10px);
        }

        .top-bar h1 {
            font-size: 1.15rem;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.02em;
        }

        .top-icon {
            font-size: 1.5rem;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }

        .container-mobile {
            max-width: 600px;
            margin: 0 auto;
            padding: 16px;
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .customer-card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 16px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .customer-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 16px 16px 0 0;
        }

        .customer-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 12px;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .customer-name::before {
            content: '👤';
            font-size: 1.4rem;
        }

        .customer-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-top: 12px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            color: var(--text-medium);
            background: var(--bg-page);
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .info-item:hover {
            background: var(--success-light);
            transform: translateX(2px);
        }

        .info-icon {
            font-size: 1.1rem;
            opacity: 0.8;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 16px;
        }

        .stat-card {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 18px;
            text-align: center;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .stat-card:hover::before {
            opacity: 0.05;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-icon {
            font-size: 2rem;
            margin-bottom: 8px;
            filter: grayscale(0.2);
        }

        .stat-label {
            font-size: 0.7rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.03em;
        }

        .transactions-section {
            background: var(--bg-card);
            border-radius: 16px;
            padding: 0;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .section-header {
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.1), rgba(59, 130, 246, 0.1));
            padding: 16px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .section-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.02em;
        }

        .section-title-icon {
            font-size: 1.3rem;
        }

        .transactions-list {
            padding: 12px;
        }

        .transaction-item {
            background: var(--bg-page);
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
            position: relative;
        }

        .transaction-item:last-child {
            margin-bottom: 0;
        }

        .transaction-item:hover {
            transform: translateX(4px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary);
            background: white;
        }

        .transaction-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 0;
            background: linear-gradient(180deg, var(--primary), var(--secondary));
            border-radius: 0 3px 3px 0;
            transition: height 0.3s ease;
        }

        .transaction-item:hover::before {
            height: 60%;
        }

        .transaction-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .transaction-date {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .date-icon {
            font-size: 0.9rem;
            opacity: 0.7;
        }

        .transaction-time {
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.8rem;
            margin-left: 4px;
        }

        .transaction-amount {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--success);
            background: var(--success-light);
            padding: 4px 12px;
            border-radius: 8px;
            letter-spacing: -0.02em;
        }

        .transaction-details {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            font-size: 0.8rem;
            color: var(--text-muted);
            gap: 12px;
        }

        .agent-info {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            background: white;
            border-radius: 6px;
            font-weight: 500;
        }

        .agent-icon {
            font-size: 0.9rem;
        }

        .transaction-id {
            font-family: 'Courier New', monospace;
            font-size: 0.7rem;
            color: var(--text-muted);
            background: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }

        .empty-icon {
            font-size: 4rem;
            opacity: 0.2;
            margin-bottom: 16px;
            filter: grayscale(1);
        }

        .empty-text {
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-muted);
        }

        @media (max-width: 576px) {
            .container-mobile {
                padding: 12px;
            }

            .customer-info {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .info-item {
                font-size: 0.8rem;
                padding: 6px 10px;
            }

            .transaction-amount {
                font-size: 1rem;
                padding: 3px 10px;
            }

            .stat-value {
                font-size: 1.25rem;
            }

            .stat-icon {
                font-size: 1.6rem;
            }

            .transaction-details {
                flex-direction: column;
                align-items: flex-start;
                gap: 6px;
            }
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <h1>
            <span class="top-icon">📊</span>
            <span>Transaction History</span>
        </h1>
    </div>

    <div class="container-mobile">
        <!-- Customer Info Card -->
        <div class="customer-card">
            <div class="customer-name"><?= htmlspecialchars($customer['account_name']) ?></div>
            <div class="customer-info">
                <div class="info-item">
                    <span class="info-icon">📋</span>
                    <span>A/C: <?= htmlspecialchars($customer['account_number']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-icon">📱</span>
                    <span><?= htmlspecialchars($customer['account_mobile']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-icon">🏢</span>
                    <span><?= htmlspecialchars($customer['branch_name'] ?? $customer['branch_code']) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-icon">👨‍💼</span>
                    <span><?= htmlspecialchars($customer['agent_name'] ?? 'Agent ' . $customer['agent_code']) ?></span>
                </div>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📝</div>
                <div class="stat-label">Total Collections</div>
                <div class="stat-value"><?= number_format($total['count'] ?? 0) ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-label">Total Amount</div>
                <div class="stat-value">₹<?= number_format($total['total'] ?? 0, 0) ?></div>
            </div>
        </div>

        <!-- Transactions List -->
        <div class="transactions-section">
            <div class="section-header">
                <div class="section-title">
                    <span class="section-title-icon">💳</span>
                    <span>All Transactions</span>
                </div>
            </div>

            <div class="transactions-list">
                <?php if (!empty($transactions)): ?>
                    <?php foreach ($transactions as $transaction): ?>
                    <div class="transaction-item">
                        <div class="transaction-header">
                            <div class="transaction-date">
                                <span class="date-icon">📅</span>
                                <span><?= date('d M Y', strtotime($transaction['date'])) ?></span>
                                <span class="transaction-time">
                                    <?= date('h:i A', strtotime($transaction['time'])) ?>
                                </span>
                            </div>
                            <div class="transaction-amount">₹<?= number_format($transaction['amount'], 0) ?></div>
                        </div>
                        <div class="transaction-details">
                            <div class="transaction-id">
                                <?= htmlspecialchars($transaction['transaction_id']) ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon">📭</div>
                        <div class="empty-text">No transactions found</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

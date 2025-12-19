<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Collection Success</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .success-container {
            max-width: 500px;
            width: 100%;
        }

        .success-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            text-align: center;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: scaleIn 0.6s ease-out 0.2s both;
        }

        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }

        .success-icon i {
            font-size: 3rem;
            color: white;
        }

        .success-title {
            color: #28a745;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .success-subtitle {
            color: #6c757d;
            font-size: 1rem;
            margin-bottom: 2rem;
        }

        .transaction-details {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: left;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e9ecef;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .share-section {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .share-title {
            color: #0369a1;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .share-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .btn-share {
            flex: 1;
            max-width: 150px;
            padding: 1rem;
            border-radius: 12px;
            border: none;
            color: white;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-sms {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .btn-sms:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-whatsapp {
            background: linear-gradient(135deg, #25d366 0%, #128c7e 100%);
        }

        .btn-whatsapp:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(37, 211, 102, 0.3);
        }

        .btn-share i {
            font-size: 1.5rem;
        }

        .btn-share span {
            font-size: 0.95rem;
        }

        .detail-label {
            color: #6c757d;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .detail-value {
            color: #212529;
            font-weight: 600;
            text-align: right;
            word-break: break-word;
        }

        .amount-row {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
        }

        .amount-row .detail-label,
        .amount-row .detail-value {
            color: white;
        }

        .amount-row .detail-value {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .btn-action {
            flex: 1;
            padding: 1rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            min-height: 60px;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-action i {
            font-size: 1.5rem;
        }

        .btn-primary-action {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary-action {
            background: #f8f9fa;
            color: #495057;
            border: 2px solid #e9ecef;
        }

        .btn-action:active {
            transform: scale(0.95);
        }

        .btn-home {
            width: 100%;
            padding: 0.75rem;
            background: white;
            color: #6c757d;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-home:active {
            transform: scale(0.98);
        }

        .btn-print {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 152, 0, 0.3);
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 152, 0, 0.4);
        }

        /* Print Styles for Thermal Printer */
        @media print {
            body * {
                visibility: hidden;
            }
            
            #printReceipt, #printReceipt * {
                visibility: visible;
            }
            
            #printReceipt {
                position: absolute;
                left: 0;
                top: 0;
                width: 80mm; /* Standard thermal printer width */
                font-family: 'Courier New', monospace;
                font-size: 11pt;
                line-height: 1.4;
                color: black;
                background: white;
            }

            .print-header {
                text-align: center;
                font-weight: bold;
                font-size: 13pt;
                margin-bottom: 10px;
                border-bottom: 2px dashed #000;
                padding-bottom: 10px;
            }

            .print-row {
                display: flex;
                justify-content: space-between;
                margin: 5px 0;
                font-size: 11pt;
            }

            .print-amount {
                font-weight: bold;
                font-size: 12pt;
                border-top: 1px dashed #000;
                border-bottom: 1px dashed #000;
                padding: 8px 0;
                margin: 10px 0;
            }

            .print-footer {
                text-align: center;
                margin-top: 15px;
                border-top: 2px dashed #000;
                padding-top: 10px;
                font-size: 10pt;
            }
        }

        @media (max-width: 576px) {
            .success-card {
                padding: 1.5rem;
            }

            .success-icon {
                width: 80px;
                height: 80px;
            }

            .success-icon i {
                font-size: 2.5rem;
            }

            .success-title {
                font-size: 1.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-card">
            <!-- Success Icon -->
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>

            <!-- Success Message -->
            <h1 class="success-title">Collection Successful!</h1>
            <p class="success-subtitle">Transaction completed successfully</p>

            <!-- Transaction Details -->
            <div class="transaction-details">
                <div class="detail-row">
                    <span class="detail-label">Transaction ID</span>
                    <span class="detail-value"><?= htmlspecialchars($transaction_id) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Account Number</span>
                    <span class="detail-value"><?= htmlspecialchars($account_number) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Customer Name</span>
                    <span class="detail-value"><?= htmlspecialchars($account_name) ?></span>
                </div>
                
                <div class="amount-row">
                    <div class="detail-row" style="border: none; padding: 0;">
                        <span class="detail-label">Collection Amount</span>
                        <span class="detail-value">₹<?= number_format($credit_amount, 2) ?></span>
                    </div>
                </div>

                <div class="detail-row">
                    <span class="detail-label">New Balance</span>
                    <span class="detail-value">₹<?= number_format($new_balance, 2) ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date & Time</span>
                    <span class="detail-value" id="datetime">-</span>
                </div>
            </div>

            <!-- Hidden Print Receipt (for thermal printer) -->
            <div id="printReceipt" style="display: none;">
                <div class="print-header">
                    <?= htmlspecialchars($branch_name ?? 'Branch') ?><br>
                    COLLECTION RECEIPT
                </div>
                
                <div style="margin: 15px 0;">
                    <div class="print-row">
                        <span>Agent:</span>
                        <span><?= htmlspecialchars($agent_name ?? 'N/A') ?></span>
                    </div>
                    <div class="print-row">
                        <span>Account No:</span>
                        <span><?= htmlspecialchars($account_number) ?></span>
                    </div>
                    <div class="print-row">
                        <span>Name:</span>
                        <span><?= htmlspecialchars($account_name) ?></span>
                    </div>
                    <div class="print-row">
                        <span>Date & Time:</span>
                        <span id="printDateTime"></span>
                    </div>
                    <div class="print-row">
                        <span>Trans ID:</span>
                        <span><?= htmlspecialchars($transaction_id) ?></span>
                    </div>
                </div>

                <div class="print-amount">
                    <div class="print-row">
                        <span>Collection:</span>
                        <span>Rs. <?= number_format($credit_amount, 2) ?></span>
                    </div>
                    <div class="print-row">
                        <span>Total Balance:</span>
                        <span>Rs. <?= number_format($new_balance, 2) ?></span>
                    </div>
                </div>

                <div class="print-footer">
                    Thank you for banking with us!<br>
                    Sookth Mobile Pigmy<br>
                    <?= date('d/m/Y h:i A') ?>
                </div>
            </div>

            <!-- Share via SMS/WhatsApp/Print -->
            <?php if (($text_message ?? 0) || ($whatsapp_message ?? 0) || ($printer_support ?? 0)): ?>
            <div class="share-section">
                <h5 class="share-title"><i class="fas fa-share-alt"></i> Share Receipt</h5>
                <div class="share-buttons">
                    <?php if (($printer_support ?? 0)): ?>
                    <button onclick="printReceipt()" class="btn-share btn-print" style="border: none; cursor: pointer;">
                        <i class="fas fa-print"></i>
                        <span>Print</span>
                    </button>
                    <?php endif; ?>
                    
                    <?php if (($text_message ?? 0) && !empty($sms_link)): ?>
                    <a href="<?= $sms_link ?>" class="btn-share btn-sms">
                        <i class="fas fa-sms"></i>
                        <span>SMS</span>
                    </a>
                    <?php endif; ?>
                    
                    <?php if (($whatsapp_message ?? 0) && !empty($whatsapp_link)): ?>
                    <a href="<?= $whatsapp_link ?>" class="btn-share btn-whatsapp" target="_blank">
                        <i class="fab fa-whatsapp"></i>
                        <span>WhatsApp</span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="btn-action btn-primary-action" onclick="window.location.href='<?= url('agent/dashboard') ?>'">
                    <i class="fas fa-plus-circle"></i>
                    <span>New Collection</span>
                </button>
                <button class="btn-action btn-secondary-action" onclick="window.location.href='<?= url('agent/reports') ?>'">
                    <i class="fas fa-chart-bar"></i>
                    <span>View Reports</span>
                </button>
            </div>

            <!-- Home Button -->
            <button class="btn-home" onclick="window.location.href='<?= url('agent/dashboard') ?>'">
                <i class="fas fa-home"></i> Back to Dashboard
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
    <script>
        // Display current date & time
        function updateDateTime() {
            const now = new Date();
            const formattedDate = now.toLocaleDateString('en-GB', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
            const formattedTime = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            const dateTimeStr = formattedDate + ", " + formattedTime;
            document.getElementById("datetime").innerText = dateTimeStr;
            
            // Also update print receipt datetime
            const printDateTime = document.getElementById("printDateTime");
            if (printDateTime) {
                printDateTime.innerText = now.toLocaleDateString('en-GB') + " " + now.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }
        }
        updateDateTime();

        // Print Receipt Function
        function printReceipt() {
            // Show print content
            const printContent = document.getElementById('printReceipt');
            const originalDisplay = printContent.style.display;
            
            printContent.style.display = 'block';
            
            // Trigger print dialog
            window.print();
            
            // Hide print content after printing
            setTimeout(() => {
                printContent.style.display = originalDisplay;
            }, 500);
        }

        // Auto redirect to dashboard after 10 seconds (optional)
        // setTimeout(function() {
        //     window.location.href = '<?= url('agent/dashboard') ?>';
        // }, 10000);
    </script>
</body>
</html>

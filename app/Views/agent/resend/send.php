<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resend Receipt - Agent Portal</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .container-wrapper {
            width: 100%;
            max-width: 500px;
        }

        .card-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .card-header-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 1.5rem;
            text-align: center;
        }

        .header-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            backdrop-filter: blur(10px);
        }

        .header-icon i {
            font-size: 2.5rem;
        }

        .card-title-custom {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .card-subtitle-custom {
            font-size: 1rem;
            opacity: 0.9;
        }

        .card-body-custom {
            padding: 2rem 1.5rem;
        }

        .transaction-details {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
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

        .detail-label {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .detail-value {
            font-weight: 600;
            color: #212529;
            text-align: right;
        }

        .amount-row {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 1rem;
            border-radius: 12px;
            margin: 1rem 0;
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
            flex-wrap: wrap;
        }

        .btn-share {
            flex: 1;
            min-width: 120px;
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
            cursor: pointer;
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
        
        .btn-print {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(255, 152, 0, 0.4);
        }

        .btn-share-image {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
        }

        .btn-share-image:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(6, 182, 212, 0.4);
        }

        .btn-share i {
            font-size: 1.5rem;
        }

        .btn-share span {
            font-size: 0.95rem;
        }

        .btn-back {
            width: 100%;
            padding: 1rem;
            background: #f3f4f6;
            color: #374151;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-back:hover {
            background: #e5e7eb;
            color: #374151;
        }

        /* Print Styles */
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
                width: 80mm;
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
            .card-header-custom {
                padding: 1.5rem 1rem;
            }

            .header-icon {
                width: 60px;
                height: 60px;
            }

            .header-icon i {
                font-size: 2rem;
            }

            .card-title-custom {
                font-size: 1.5rem;
            }

            .card-body-custom {
                padding: 1.5rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-wrapper">
        <div class="card-container">
            <!-- Header -->
            <div class="card-header-custom">
                <div class="header-icon">
                    <i class="fas fa-share-alt"></i>
                </div>
                <h1 class="card-title-custom">Resend Receipt</h1>
                <p class="card-subtitle-custom">Share transaction details with customer</p>
            </div>

            <!-- Body -->
            <div class="card-body-custom">
                <!-- Transaction Details -->
                <div class="transaction-details">
                    <div class="detail-row">
                        <span class="detail-label">Transaction ID</span>
                        <span class="detail-value">#<?= htmlspecialchars($transaction_id) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Account Number</span>
                        <span class="detail-value"><?= htmlspecialchars($account_number) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Account Name</span>
                        <span class="detail-value"><?= htmlspecialchars($account_name) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Mobile Number</span>
                        <span class="detail-value"><?= htmlspecialchars($account_mobile) ?></span>
                    </div>

                    <div class="amount-row">
                        <div class="detail-row" style="border: none; padding: 0;">
                            <span class="detail-label">Collection Amount</span>
                            <span class="detail-value">₹<?= number_format($amount, 2) ?></span>
                        </div>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label">New Balance</span>
                        <span class="detail-value">₹<?= number_format($new_balance, 2) ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date & Time</span>
                        <span class="detail-value"><?= $date_time ?></span>
                    </div>
                </div>

                <!-- Hidden Print Receipt -->
                <div id="printReceipt" style="display: none;">
                    <div class="print-header">
                        <?= htmlspecialchars($branch_name ?? 'Branch') ?><br>
                        COLLECTION RECEIPT (RESEND)
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
                            <span><?= htmlspecialchars($date_time) ?></span>
                        </div>
                        <div class="print-row">
                            <span>Trans ID:</span>
                            <span><?= htmlspecialchars($transaction_id) ?></span>
                        </div>
                    </div>

                    <div class="print-amount">
                        <div class="print-row">
                            <span>Collection:</span>
                            <span>Rs. <?= number_format($amount, 2) ?></span>
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

                <!-- Share Buttons -->
                <?php if (($text_message ?? 0) || ($whatsapp_message ?? 0) || ($printer_support ?? 0)): ?>
                    <div class="share-section">
                        <h5 class="share-title">
                            <i class="fas fa-paper-plane"></i>
                            Send Receipt to Customer
                        </h5>
                        <div class="share-buttons">
                            <button onclick="shareReceiptImage()" class="btn-share btn-share-image">
                                <i class="fas fa-share-alt"></i>
                                <span>Share</span>
                            </button>
                            
                            <?php if (($printer_support ?? 0)): ?>
                            <button onclick="printReceipt()" class="btn-share btn-print">
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

                <!-- Back Button -->
                <a href="<?= url('agent/resend') ?>" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
    <script>
        // Share Receipt as Image Function
        async function shareReceiptImage() {
            try {
                // Create a temporary container for the receipt
                const receiptContainer = document.createElement('div');
                receiptContainer.style.position = 'absolute';
                receiptContainer.style.left = '-9999px';
                receiptContainer.style.width = '80mm';
                receiptContainer.style.background = 'white';
                receiptContainer.style.padding = '20px';
                receiptContainer.style.fontFamily = '\'Courier New\', monospace';
                receiptContainer.style.fontSize = '11pt';
                receiptContainer.style.lineHeight = '1.4';
                receiptContainer.style.color = 'black';
                
                // Add receipt content
                receiptContainer.innerHTML = `
                    <div style="text-align: center; font-weight: bold; font-size: 13pt; margin-bottom: 10px; border-bottom: 2px dashed #000; padding-bottom: 10px;">
                        <?= htmlspecialchars($branch_name ?? 'Branch') ?><br>
                        COLLECTION RECEIPT (RESEND)
                    </div>
                    
                    <div style="margin: 15px 0;">
                        <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                            <span>Agent:</span>
                            <span><?= htmlspecialchars($agent_name ?? 'N/A') ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                            <span>Account No:</span>
                            <span><?= htmlspecialchars($account_number) ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                            <span>Name:</span>
                            <span><?= htmlspecialchars($account_name) ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                            <span>Date & Time:</span>
                            <span><?= htmlspecialchars($date_time) ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                            <span>Trans ID:</span>
                            <span><?= htmlspecialchars($transaction_id) ?></span>
                        </div>
                    </div>

                    <div style="font-weight: bold; font-size: 12pt; border-top: 1px dashed #000; border-bottom: 1px dashed #000; padding: 8px 0; margin: 10px 0;">
                        <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                            <span>Collection:</span>
                            <span>Rs. <?= number_format($amount, 2) ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin: 5px 0;">
                            <span>Total Balance:</span>
                            <span>Rs. <?= number_format($new_balance, 2) ?></span>
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 15px; border-top: 2px dashed #000; padding-top: 10px; font-size: 10pt;">
                        Thank you for banking with us!<br>
                        Sookth Mobile Pigmy<br>
                        <?= date('d/m/Y h:i A') ?>
                    </div>
                `;
                
                document.body.appendChild(receiptContainer);
                
                // Convert to canvas using html2canvas
                const canvas = await html2canvas(receiptContainer, {
                    backgroundColor: '#ffffff',
                    scale: 3, // Higher quality
                    logging: false,
                    width: 302, // 80mm in pixels at 96dpi
                    windowWidth: 302
                });
                
                // Remove temporary container
                document.body.removeChild(receiptContainer);
                
                // Convert canvas to blob
                canvas.toBlob(async (blob) => {
                    if (!blob) {
                        alert('Failed to generate receipt image');
                        return;
                    }
                    
                    const file = new File([blob], 'receipt.png', { type: 'image/png' });
                    
                    // Check if Web Share API is supported
                    if (navigator.share && navigator.canShare && navigator.canShare({ files: [file] })) {
                        try {
                            await navigator.share({
                                files: [file],
                                title: 'Collection Receipt',
                                text: 'Receipt for Transaction ID: <?= htmlspecialchars($transaction_id) ?>'
                            });
                        } catch (err) {
                            if (err.name !== 'AbortError') {
                                console.error('Error sharing:', err);
                                downloadImage(canvas);
                            }
                        }
                    } else {
                        // Fallback: Download the image
                        downloadImage(canvas);
                    }
                }, 'image/png');
                
            } catch (error) {
                console.error('Error generating receipt:', error);
                alert('Failed to generate receipt. Please try again.');
            }
        }
        
        // Helper function to download image
        function downloadImage(canvas) {
            const link = document.createElement('a');
            link.download = 'receipt_<?= htmlspecialchars($transaction_id) ?>.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
            alert('Receipt image downloaded. You can now share or print it.');
        }

        function printReceipt() {
            const printContent = document.getElementById('printReceipt');
            const originalDisplay = printContent.style.display;
            
            printContent.style.display = 'block';
            window.print();
            
            setTimeout(() => {
                printContent.style.display = originalDisplay;
            }, 500);
        }
    </script>
</body>
</html>

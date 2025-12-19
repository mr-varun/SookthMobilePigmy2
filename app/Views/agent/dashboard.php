<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Dashboard - Pigmy Collection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        :root {
            --primary: #06b6d4;
            --secondary: #0891b2;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #0f172a;
            --light: #f1f5f9;
            --gradient-main: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            --gradient-blue: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            --gradient-green: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --gradient-pink: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
            --gradient-orange: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: #f0f4f8;
            font-family: 'Inter', 'Segoe UI', sans-serif;
            min-height: 100vh;
            padding-bottom: 20px;
        }
        .top-bar {
            background: var(--gradient-main);
            padding: 0;
            box-shadow: 0 4px 24px rgba(0,0,0,0.12);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .top-bar .container-fluid {
            padding: 12px 16px;
        }
        .agent-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
        }
        .agent-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            border: 2px solid rgba(255, 255, 255, 0.4);
        }
        .agent-info h6 {
            font-size: 11px;
            font-weight: 600;
            margin: 0;
            opacity: 0.9;
        }
        .agent-info p {
            font-size: 14px;
            font-weight: 700;
            margin: 0;
        }
        .nav-actions {
            display: flex;
            gap: 10px;
        }
        .nav-actions .btn {
            border-radius: 10px;
            padding: 8px 14px;
            font-weight: 600;
            font-size: 13px;
            border: none;
            transition: all 0.3s;
        }
        .nav-actions .btn-light {
            background: white;
            color: #667eea;
        }
        .nav-actions .btn-light:hover {
            background: rgba(255,255,255,0.9);
            transform: translateY(-2px);
        }
        .nav-actions .btn-outline-light {
            border: 2px solid white;
            color: white;
        }
        .nav-actions .btn-outline-light:hover {
            background: white;
            color: #667eea;
            transform: translateY(-2px);
        }
        .container {
            max-width: 480px;
            padding: 0 12px;
        }
        .welcome-banner {
            background: white;
            border-radius: 16px;
            padding: 16px;
            margin: 12px 0;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
        }
        .welcome-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--gradient-main);
        }
        .welcome-banner h1 {
            font-size: 20px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 8px;
        }
        .branch-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--gradient-main);
            color: white;
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
        }
        .time-card {
            background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            border-radius: 16px;
            padding: 14px;
            margin: 12px 0;
            text-align: center;
            color: white;
            box-shadow: 0 8px 24px rgba(6, 182, 212, 0.35);
        }
        .time-card .icon {
            font-size: 24px;
            margin-bottom: 6px;
            opacity: 0.9;
        }
        .time-card .time {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin: 12px 0;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            border-left: 3px solid;
        }
        .stat-card.total {
            border-left-color: #06b6d4;
        }
        .stat-card.today {
            border-left-color: #10b981;
        }
        .stat-label {
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .stat-value {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }
        .stat-value .rupee {
            font-size: 14px;
            font-weight: 700;
        }
        .form-card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin: 12px 0;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        .form-card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f1f5f9;
        }
        .form-card-header .icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--gradient-main);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        .form-card-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }
        .input-group-custom {
            margin-bottom: 16px;
        }
        .input-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .input-label i {
            color: #06b6d4;
        }
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            background: #f8fafc;
        }
        .form-control:focus, .form-select:focus {
            border-color: #06b6d4;
            background: white;
            box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.12);
        }
        .info-box {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 12px;
            border-left: 3px solid #06b6d4;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s;
        }
        .info-box:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .info-box .label {
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-box .value {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
        }
        .btn-primary-custom {
            width: 100%;
            padding: 14px;
            background: var(--gradient-main);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            box-shadow: 0 8px 24px rgba(6, 182, 212, 0.35);
            margin-top: 16px;
        }
        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(6, 182, 212, 0.45);
        }
        .btn-primary-custom:active {
            transform: translateY(-2px);
        }
        .section-header {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 20px 0 12px;
        }
        .section-header h2 {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }
        .section-header i {
            color: #06b6d4;
            font-size: 18px;
        }
        .action-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin: 12px 0 20px;
        }
        .action-item {
            background: white;
            border-radius: 16px;
            padding: 18px 12px;
            text-align: center;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            transition: all 0.3s;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }
        .action-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
        }
        .action-item.blue::before { background: var(--gradient-blue); }
        .action-item.green::before { background: var(--gradient-green); }
        .action-item.pink::before { background: var(--gradient-pink); }
        .action-item.orange::before { background: var(--gradient-orange); }
        .action-item:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 28px rgba(0,0,0,0.15);
        }
        .action-item .icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .action-item.blue .icon {
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.15), rgba(0, 153, 255, 0.15));
            color: #0099ff;
        }
        .action-item.green .icon {
            background: linear-gradient(135deg, rgba(0, 230, 118, 0.15), rgba(0, 200, 83, 0.15));
            color: #00c853;
        }
        .action-item.pink .icon {
            background: linear-gradient(135deg, rgba(255, 107, 157, 0.15), rgba(196, 69, 105, 0.15));
            color: #c44569;
        }
        .action-item.orange .icon {
            background: linear-gradient(135deg, rgba(255, 167, 38, 0.15), rgba(251, 140, 0, 0.15));
            color: #fb8c00;
        }
        .action-item .title {
            font-size: 12px;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(30, 41, 59, 0.75);
            backdrop-filter: blur(8px);
            z-index: 9999;
        }
        .modal-box {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 20px;
            padding: 28px 20px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.3);
            z-index: 10000;
            max-width: 380px;
            width: 90%;
        }
        .modal-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ffd740 0%, #ffa726 100%);
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: white;
        }
        .modal-box h3 {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 16px;
            text-align: center;
        }
        .modal-info {
            background: #f8fafc;
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 16px;
        }
        .modal-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .modal-info-row:last-child {
            margin-bottom: 0;
        }
        .modal-info-row .label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
        }
        .modal-info-row .value {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
        }
        .modal-actions {
            display: flex;
            gap: 12px;
        }
        .modal-actions button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .modal-actions .btn-confirm {
            background: var(--gradient-green);
            color: white;
        }
        .modal-actions .btn-cancel {
            background: var(--gradient-pink);
            color: white;
        }
        .modal-actions button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        .select2-container--bootstrap-5 .select2-selection {
            border: 2px solid #e2e8f0 !important;
            border-radius: 12px !important;
            padding: 6px 14px !important;
            min-height: 46px !important;
            background: #f8fafc !important;
        }
        .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: #06b6d4 !important;
            box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.12) !important;
            background: white !important;
        }
        .select2-container--bootstrap-5 .select2-dropdown {
            border: 2px solid #06b6d4 !important;
            border-radius: 16px !important;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15) !important;
        }
        @media (max-width: 576px) {
            .top-bar .container-fluid {
                padding: 12px 16px;
            }
            .agent-avatar {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
            .agent-info h6 {
                font-size: 12px;
            }
            .agent-info p {
                font-size: 14px;
            }
            .nav-actions .btn {
                padding: 8px 14px;
                font-size: 13px;
            }
            .nav-actions .btn span {
                display: none;
            }
            .welcome-banner {
                padding: 20px;
            }
            .welcome-banner h1 {
                font-size: 20px;
            }
            .form-card {
                padding: 20px;
            }
            .action-grid {
                gap: 12px;
            }
            .action-item {
                padding: 20px 12px;
            }
            .action-item .icon {
                width: 48px;
                height: 48px;
                font-size: 24px;
            }
            .action-item .title {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <div class="top-bar">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="agent-profile">
                    <div class="agent-avatar">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="agent-info">
                        <h6>Agent</h6>
                        <p><?= htmlspecialchars($agent_name) ?></p>
                    </div>
                </div>
                <div class="nav-actions">
                    <a href="<?= url('agent/reports') ?>" class="btn btn-light btn-sm">
                        <i class="fas fa-chart-bar me-1"></i><span>Reports</span>
                    </a>
                    <a href="<?= url('agent/logout') ?>" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i><span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <h1>Mobile Pigmy Collection</h1>
            <div class="branch-badge">
                <i class="fas fa-building"></i>
                <?= htmlspecialchars($branch_name) ?>
            </div>
        </div>

        <!-- Date Time Card -->
        <div class="time-card">
            <!-- <div class="icon">
                <i class="far fa-clock"></i>
            </div> -->
            <div class="time" id="datetime"></div>
        </div>

        <!-- Collection Stats -->
        <div class="stats-grid">
            <div class="stat-card total">
                <div class="stat-label">Total Collections</div>
                <div class="stat-value">
                    <span class="rupee">₹</span><?= number_format($total_amount ?? 0, 0) ?>
                </div>
            </div>
            <div class="stat-card today">
                <div class="stat-label">Today's Collection</div>
                <div class="stat-value">
                    <span class="rupee">₹</span><?= number_format($today_total ?? 0, 0) ?>
                </div>
            </div>
        </div>

        <!-- Collection Form -->
        <div class="form-card">
            <div class="form-card-header">
                <div class="icon-circle">
                    <i class="fas fa-coins"></i>
                </div>
                <h3>New Collection</h3>
            </div>

            <form id="pigmyForm" method="POST" action="<?= url('agent/collection/save') ?>">
                <div class="input-group-custom">
                    <div class="input-label">
                        <i class="fas fa-user"></i>
                        Select Account
                    </div>
                    <select id="account" name="account" class="form-select" required>
                        <option value="">Choose an account...</option>
                        <?php foreach ($accounts as $row): ?>
                            <option value="<?= $row['account_number'] ?>" 
                                    data-name="<?= htmlspecialchars($row['account_name']) ?>" 
                                    data-balance="<?= $row['account_new_balance'] ?>" 
                                    data-opening-date="<?= $row['account_opening_date'] ?>">
                                <?= htmlspecialchars($row['account_name']) ?> - <?= $row['account_number'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="account_details" style="display: none;">
                    <div class="info-box">
                        <div>
                            <div class="label">Account Name</div>
                            <div class="value" id="account_name"></div>
                        </div>
                    </div>
                    <div class="info-box">
                        <div>
                            <div class="label">Opening Date</div>
                            <div class="value" id="account_opening_date"></div>
                        </div>
                    </div>
                    <div class="info-box">
                        <div>
                            <div class="label">Account Number</div>
                            <div class="value" id="selected_account"></div>
                        </div>
                    </div>
                    <div class="info-box">
                        <div>
                            <div class="label">Current Balance</div>
                            <div class="value">₹<span id="old_balance"></span></div>
                        </div>
                    </div>
                </div>

                <div class="input-group-custom">
                    <div class="input-label">
                        <i class="fas fa-rupee-sign"></i>
                        Collection Amount
                    </div>
                    <input type="number" id="credit_amount" name="credit_amount" 
                           class="form-control" min="1" placeholder="Enter collection amount" required>
                </div>

                <button type="button" id="submitBtn" class="btn-primary-custom">
                    <i class="fas fa-check-circle me-2"></i>Submit Collection
                </button>
            </form>
        </div>

        <!-- Quick Actions -->
        <div class="section-header">
            <i class="fas fa-bolt"></i>
            <h2>Quick Actions</h2>
        </div>
        <div class="action-grid">
            <a href="<?= url('agent/resend') ?>" class="action-item blue">
                <div class="icon">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="title">Resend Messages</div>
            </a>
            <a href="<?= url('agent/reports/daywise') ?>" class="action-item green">
                <div class="icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="title">DCS Report</div>
            </a>
            <a href="<?= url('agent/reports') ?>" class="action-item pink">
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="title">All Reports</div>
            </a>
            <a href="<?= url('agent/reset-pin') ?>" class="action-item orange">
                <div class="icon">
                    <i class="fas fa-key"></i>
                </div>
                <div class="title">Reset PIN</div>
            </a>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal-overlay" id="popupOverlay"></div>
    <div id="confirmationPopup" class="modal-box">
        <div class="modal-icon">
            <i class="fas fa-question-circle"></i>
        </div>
        <h3>Confirm Transaction</h3>
        <div class="modal-info">
            <div class="modal-info-row">
                <span class="label">Account</span>
                <span class="value" id="popup_account"></span>
            </div>
            <div class="modal-info-row">
                <span class="label">Amount</span>
                <span class="value">₹<span id="popup_amount"></span></span>
            </div>
        </div>
        <div class="modal-actions">
            <button id="confirmYes" class="btn-confirm">
                <i class="fas fa-check me-2"></i>Confirm
            </button>
            <button id="confirmNo" class="btn-cancel">
                <i class="fas fa-times me-2"></i>Cancel
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
    <script>
        // Update Date Time
        function updateDateTime() {
            const now = new Date();
            document.getElementById("datetime").innerText = 
                now.toLocaleDateString('en-GB') + ", " + now.toLocaleTimeString('en-US');
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();

        // Initialize Select2
        $('#account').select2({
            theme: 'bootstrap-5',
            placeholder: '-- Select Account --',
            allowClear: true,
            width: '100%'
        });

        // Account Selection
        $("#account").change(function() {
            let selectedOption = $(this).find(':selected');
            let accountNumber = selectedOption.val();
            let accountName = selectedOption.data("name");
            let oldBalance = selectedOption.data("balance");
            let accountOpeningDate = selectedOption.data("opening-date");
            
            if (accountNumber) {
                $("#account_name").text(accountName);
                $("#account_opening_date").text(accountOpeningDate);
                $("#selected_account").text(accountNumber);
                $("#old_balance").text(oldBalance);
                $("#account_details").fadeIn();

                // Check for duplicate transaction
                fetch('<?= url('agent/check-transaction') ?>', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        account_number: accountNumber,
                        branch_code: "<?= $branch_code ?>",
                        agent_code: "<?= $agent_code ?>"
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        alert(`⚠️ Amount ₹${data.collected_amount} already collected for account ${accountNumber} today.`);
                    }
                })
                .catch(error => console.error('Error:', error));
            } else {
                $("#account_details").fadeOut();
            }
        });

        // Submit Button
        $("#submitBtn").click(function() {
            let selectedAccount = $("#selected_account").text();
            let creditAmount = $("#credit_amount").val();
            
            if (!selectedAccount || creditAmount <= 0) {
                alert("⚠️ Please select an account and enter a valid amount.");
                return;
            }
            
            $("#popup_account").text(selectedAccount);
            $("#popup_amount").text(creditAmount);
            $("#popupOverlay").fadeIn();
            $("#confirmationPopup").fadeIn();
        });

        // Confirm Yes
        $("#confirmYes").click(function() {
            $("#popupOverlay").fadeOut();
            $("#confirmationPopup").fadeOut();
            $("#pigmyForm").submit();
        });

        // Confirm No
        $("#confirmNo").click(function() {
            $("#popupOverlay").fadeOut();
            $("#confirmationPopup").fadeOut();
        });

        // Close on overlay click
        $("#popupOverlay").click(function() {
            $(this).fadeOut();
            $("#confirmationPopup").fadeOut();
        });

        // Prevent back button cache
        window.onload = function() {
            if (performance.navigation.type === 2) {
                location.reload(true);
            }
        };
    </script>
</body>
</html>
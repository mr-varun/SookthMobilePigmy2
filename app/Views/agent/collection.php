<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Pigmy Collection - <?= htmlspecialchars($branch_name) ?></title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding-bottom: 80px;
        }

        .container {
            max-width: 600px;
            padding: 1rem;
            padding-top: 1.5rem;
        }

        .header-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .header-card h2 {
            color: #667eea;
            margin: 0 0 0.5rem 0;
            font-size: 1.75rem;
            font-weight: 700;
        }

        .header-card .branch-name {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 1rem;
        }

        .datetime {
            color: #495057;
            font-size: 0.9rem;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 8px;
            text-align: center;
        }

        .form-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem;
            font-size: 1rem;
            min-height: 50px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .account-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
        }

        .account-info p {
            margin: 0.5rem 0;
            color: #495057;
            font-size: 0.95rem;
        }

        .account-info strong {
            color: #212529;
        }

        .account-info .balance {
            color: #28a745;
            font-size: 1.25rem;
            font-weight: 700;
        }

        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-size: 1.1rem;
            font-weight: 600;
            width: 100%;
            min-height: 60px;
            margin-top: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-submit:active {
            transform: scale(0.98);
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            padding: 0.75rem;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .nav-btn {
            flex: 1;
            padding: 0.75rem;
            border: none;
            background: white;
            color: #6c757d;
            font-size: 0.85rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            min-height: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
        }

        .nav-btn i {
            font-size: 1.5rem;
        }

        .nav-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .nav-btn:active {
            transform: scale(0.95);
        }

        /* Popup Styles */
        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1001;
            backdrop-filter: blur(4px);
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 20px;
            padding: 2rem;
            z-index: 1002;
            max-width: 90%;
            width: 400px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .popup h3 {
            color: #667eea;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            text-align: center;
        }

        .popup .confirm-details {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .popup .confirm-details p {
            margin: 0.75rem 0;
            font-size: 1rem;
            color: #495057;
        }

        .popup .confirm-details strong {
            color: #212529;
        }

        .popup .amount-display {
            color: #28a745;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .popup-buttons {
            display: flex;
            gap: 0.75rem;
        }

        .popup-btn {
            flex: 1;
            padding: 1rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            min-height: 55px;
            transition: all 0.3s ease;
        }

        .popup-btn.yes {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .popup-btn.no {
            background: #dc3545;
            color: white;
        }

        .popup-btn:active {
            transform: scale(0.95);
        }

        .alert-mobile {
            position: fixed;
            top: 1rem;
            left: 1rem;
            right: 1rem;
            z-index: 1003;
            border-radius: 12px;
            padding: 1rem;
            font-size: 0.95rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .already-collected {
            background: #fff3cd;
            border: 2px solid #ffc107;
            color: #856404;
            padding: 0.75rem;
            border-radius: 10px;
            margin-top: 0.5rem;
            font-size: 0.9rem;
            display: none;
        }

        @media (max-width: 576px) {
            .container {
                padding: 0.75rem;
            }

            .header-card, .form-card {
                padding: 1.25rem;
            }

            .popup {
                width: 90%;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Flash Messages -->
        <?php if (isset($flash['error'])): ?>
        <div class="alert alert-danger alert-mobile alert-dismissible fade show">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($flash['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (isset($flash['success'])): ?>
        <div class="alert alert-success alert-mobile alert-dismissible fade show">
            <i class="fas fa-check-circle"></i> <?= htmlspecialchars($flash['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Header -->
        <div class="header-card">
            <h2><i class="fas fa-wallet"></i> Pigmy Collection</h2>
            <div class="branch-name">
                <i class="fas fa-building"></i> <?= htmlspecialchars($branch_name) ?>
            </div>
            <div class="datetime">
                <i class="fas fa-clock"></i> <span id="datetime"></span>
            </div>
        </div>

        <!-- Collection Form -->
        <div class="form-card">
            <form id="pigmyForm" method="POST" action="<?= url('agent/collection/save') ?>">
                <!-- Search Account -->
                <div class="mb-3">
                    <label class="form-label">
                        <i class="fas fa-search"></i> Search Account
                    </label>
                    <input type="text" 
                           id="search_account" 
                           class="form-control" 
                           placeholder="Search by Account Number or Name">
                </div>

                <!-- Select Account -->
                <div class="mb-3">
                    <label for="account" class="form-label">
                        <i class="fas fa-user"></i> Select Account *
                    </label>
                    <select id="account" name="account" class="form-select" required>
                        <option value="">-- Select Account --</option>
                        <?php while ($row = $accounts->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($row['account_number']) ?>" 
                                    data-name="<?= htmlspecialchars($row['account_name']) ?>" 
                                    data-balance="<?= htmlspecialchars($row['account_new_balance']) ?>" 
                                    data-opening-date="<?= htmlspecialchars($row['account_opening_date']) ?>">
                                <?= htmlspecialchars($row['account_name']) ?> - <?= htmlspecialchars($row['account_number']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Account Info -->
                <div class="account-info" id="account-info" style="display: none;">
                    <p><strong>Opening Date:</strong> <span id="account_opening_date">-</span></p>
                    <p><strong>Account Number:</strong> <span id="selected_account">-</span></p>
                    <p><strong>Current Balance:</strong> <span class="balance">₹<span id="old_balance">0</span></span></p>
                    <div class="already-collected" id="already-collected">
                        <i class="fas fa-info-circle"></i> <span id="collected-message"></span>
                    </div>
                </div>

                <!-- Enter Amount -->
                <div class="mb-3">
                    <label for="credit_amount" class="form-label">
                        <i class="fas fa-rupee-sign"></i> Enter Amount *
                    </label>
                    <input type="number" 
                           id="credit_amount" 
                           name="credit_amount" 
                           class="form-control" 
                           min="1" 
                           step="1"
                           placeholder="Enter collection amount" 
                           required>
                </div>

                <!-- Submit Button -->
                <button type="button" id="submitBtn" class="btn-submit">
                    <i class="fas fa-check-circle"></i> Submit Collection
                </button>
            </form>
        </div>

        <!-- Agent Info -->
        <div class="text-center text-white">
            <small><i class="fas fa-user"></i> <?= htmlspecialchars($agent_name) ?></small>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="d-flex gap-2">
            <button class="nav-btn" onclick="window.location.href='<?= url('agent/dashboard') ?>'">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </button>
            <button class="nav-btn active">
                <i class="fas fa-wallet"></i>
                <span>Collect</span>
            </button>
            <button class="nav-btn" onclick="window.location.href='<?= url('agent/reports') ?>'">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </button>
            <button class="nav-btn" onclick="window.location.href='<?= url('agent/logout') ?>'">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </div>
    </div>

    <!-- Background Overlay -->
    <div class="popup-overlay" id="popupOverlay"></div>

    <!-- Confirmation Popup -->
    <div id="confirmationPopup" class="popup">
        <h3><i class="fas fa-check-circle"></i> Confirm Transaction</h3>
        <div class="confirm-details">
            <p><strong>Account:</strong> <span id="popup_account">-</span></p>
            <p><strong>Customer:</strong> <span id="popup_name">-</span></p>
            <p><strong>Collection Amount:</strong> <span class="amount-display">₹<span id="popup_amount">0</span></span></p>
        </div>
        <div class="popup-buttons">
            <button class="popup-btn no" id="confirmNo">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button class="popup-btn yes" id="confirmYes">
                <i class="fas fa-check"></i> Confirm
            </button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
    <script src="<?= asset('assets/js/security.js') ?>"></script>
    <script>
        // Display Date & Time
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
            document.getElementById("datetime").innerText = formattedDate + ", " + formattedTime;
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();

        // Update Selected Account Details
        $("#account").change(function() {
            let selectedOption = $(this).find(':selected');
            let accountNumber = selectedOption.val();
            
            if (!accountNumber) {
                $("#account-info").hide();
                $("#already-collected").hide();
                return;
            }

            let accountName = selectedOption.data("name");
            let oldBalance = selectedOption.data("balance");
            let accountOpeningDate = selectedOption.data("opening-date");

            $("#account_opening_date").text(accountOpeningDate || '-');
            $("#selected_account").text(accountNumber);
            $("#old_balance").text(parseFloat(oldBalance).toFixed(2));
            $("#search_account").val(accountNumber);
            $("#account-info").show();

            // Check if already collected today
            checkTodayCollection(accountNumber);
        });

        // Check if collection already done today
        function checkTodayCollection(accountNumber) {
            const branchCode = "<?= $branch_code ?>";
            const agentCode = "<?= $agent_code ?>";

            fetch('<?= url('agent/check-transaction') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    account_number: accountNumber,
                    branch_code: branchCode,
                    agent_code: agentCode
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    $("#collected-message").text(`Amount ₹${parseFloat(data.collected_amount).toFixed(2)} already collected today for this account.`);
                    $("#already-collected").show();
                } else {
                    $("#already-collected").hide();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                $("#already-collected").hide();
            });
        }

        // Show Confirmation Popup
        $("#submitBtn").click(function() {
            let selectedAccount = $("#selected_account").text();
            let selectedName = $("#account option:selected").data("name");
            let creditAmount = $("#credit_amount").val();

            if (!selectedAccount || selectedAccount === '-' || !creditAmount || creditAmount <= 0) {
                alert("Please select an account and enter a valid amount.");
                return;
            }

            $("#popup_account").text(selectedAccount);
            $("#popup_name").text(selectedName);
            $("#popup_amount").text(parseFloat(creditAmount).toFixed(2));

            $("#popupOverlay").fadeIn();
            $("#confirmationPopup").fadeIn();
        });

        // Confirm Transaction
        $("#confirmYes").click(function() {
            $("#popupOverlay").fadeOut();
            $("#confirmationPopup").fadeOut();
            $("#pigmyForm").submit();
        });

        // Cancel Transaction
        $("#confirmNo").click(function() {
            $("#popupOverlay").fadeOut();
            $("#confirmationPopup").fadeOut();
        });

        // Close popup on overlay click
        $("#popupOverlay").click(function() {
            $("#popupOverlay").fadeOut();
            $("#confirmationPopup").fadeOut();
        });

        // Search/Filter accounts
        $("#search_account").on("input", function() {
            const searchValue = $(this).val().toLowerCase();
            
            $("#account option").each(function() {
                if ($(this).val() === '') return; // Skip the default option
                
                const accountNumber = $(this).val().toLowerCase();
                const accountName = $(this).data("name") ? $(this).data("name").toString().toLowerCase() : '';
                const optionText = $(this).text().toLowerCase();
                
                if (accountNumber.includes(searchValue) || 
                    accountName.includes(searchValue) || 
                    optionText.includes(searchValue)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert-mobile').fadeOut();
        }, 5000);
    </script>
</body>
</html>

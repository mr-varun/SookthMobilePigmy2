<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Login - Sookth Mobile Pigmy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            max-width: 450px;
            width: 100%;
            padding: 20px;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 3rem;
        }
        .logo-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: white;
            font-size: 2rem;
        }
        .btn-login {
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-circle">
                <i class="bi bi-building"></i>
            </div>
            <h3 class="text-center mb-4">Bank Login</h3>
            
            <?php 
                $errorMsg = Session::getFlash('error');
                $successMsg = Session::getFlash('success');
            ?>
            
            <?php if ($errorMsg): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= e($errorMsg) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($successMsg): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= e($successMsg) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form action="<?= url('bank/login') ?>" method="POST">
                <div class="mb-3">
                    <label class="form-label">Branch Code</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-building"></i></span>
                        <input type="text" name="branch_code" id="branch_code" class="form-control" 
                               value="<?= e(Session::getFlash('old_branch_code') ?? '') ?>"
                               required autofocus style="text-transform: uppercase;">
                    </div>
                </div>
                
                <!-- Branch Info Display -->
                <div id="branchInfo" class="alert alert-info d-none mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle me-2"></i>
                        <div>
                            <strong id="branchName"></strong><br>
                            <small id="managerName" class="text-muted"></small>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Manager Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="manager_password" class="form-control" required>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-secondary btn-login w-100 mb-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </button>
                
                <div class="text-center">
                    <a href="<?= url('/') ?>" class="text-decoration-none">
                        <i class="bi bi-arrow-left"></i> Back to Home
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= asset('js/security.js') ?>"></script>
    <script>
        const branchCodeInput = document.getElementById('branch_code');
        const branchInfo = document.getElementById('branchInfo');
        const branchName = document.getElementById('branchName');
        const managerName = document.getElementById('managerName');
        let debounceTimer;

        // Convert to uppercase on input
        branchCodeInput.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase();
            
            // Clear previous timer
            clearTimeout(debounceTimer);
            
            // Hide branch info if input is too short
            if (this.value.length < 3) {
                branchInfo.classList.add('d-none');
                return;
            }
            
            // Debounce API call
            debounceTimer = setTimeout(() => {
                fetchBranchDetails(this.value);
            }, 500);
        });

        function fetchBranchDetails(branchCode) {
            if (!branchCode || branchCode.length < 3) {
                branchInfo.classList.add('d-none');
                return;
            }

            fetch('<?= url('api/fetch-branch') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'branch_code=' + encodeURIComponent(branchCode)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.branch) {
                    branchName.textContent = data.branch.branch_name;
                    managerName.textContent = 'Manager: ' + data.branch.manager_name;
                    branchInfo.classList.remove('d-none');
                    branchInfo.classList.remove('alert-danger');
                    branchInfo.classList.add('alert-info');
                } else {
                    branchName.textContent = 'Branch not found';
                    managerName.textContent = 'Please check the branch code';
                    branchInfo.classList.remove('d-none');
                    branchInfo.classList.remove('alert-info');
                    branchInfo.classList.add('alert-danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                branchInfo.classList.add('d-none');
            });
        }
    </script>
</body>
</html>

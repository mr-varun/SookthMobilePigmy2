<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Login - Sookth Mobile Pigmy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            max-width: 480px;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: white;
            font-size: 2rem;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e0e0e0;
            border-right: none;
        }
        .form-control {
            border: 2px solid #e0e0e0;
            border-left: none;
            padding: 0.75rem;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: none;
        }
        .form-control:focus + .input-group-text,
        .input-group-text + .form-control:focus {
            border-color: #667eea;
        }
        .pin-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.75rem;
            display: block;
        }
        .pin-container {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin: 12px 0;
        }
        .pin-box {
            width: 50px;
            height: 55px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            outline: none;
            transition: all 0.2s;
            background: #f8f9fa;
        }
        .pin-box:focus {
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }
        .alert {
            border-radius: 10px;
        }
        @media (max-width: 576px) {
            .login-card {
                padding: 2rem 1.5rem;
            }
            .pin-box {
                width: 45px;
                height: 50px;
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-circle">
                <i class="bi bi-person-badge"></i>
            </div>
            <h3 class="text-center mb-4">Agent Login</h3>
            
            <?php 
                $errorMsg = Session::getFlash('error');
                $successMsg = Session::getFlash('success');
                // Use variables from controller (cookies for remember me)
                $branch_code = $branch_code ?? '';
                $agent_code = $agent_code ?? '';
                $password = $password ?? '';
            ?>
            
            <?php if ($errorMsg): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong><?= htmlspecialchars($errorMsg) ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($successMsg): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong><?= htmlspecialchars($successMsg) ?></strong>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form action="<?= url('agent/login') ?>" method="POST" id="loginForm">
                <!-- Branch Code -->
                <div class="mb-3">
                    <label class="form-label">Branch Code</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-building"></i></span>
                        <input type="text" name="branch_code" id="branch_code" class="form-control" 
                               value="<?= e($branch_code) ?>"
                               required autofocus style="text-transform: uppercase;">
                    </div>
                </div>
                
                <!-- Branch Info Display -->
                <div id="branchInfo" class="alert alert-info d-none mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle me-2"></i>
                        <div>
                            <strong id="branchName"></strong>
                        </div>
                    </div>
                </div>
                
                <!-- Agent Code -->
                <div class="mb-3">
                    <label class="form-label">Agent Code</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-qr-code"></i></span>
                        <input type="text" name="agent_code" id="agent_code" class="form-control" 
                               value="<?= e($agent_code) ?>"
                               required style="text-transform: uppercase;">
                    </div>
                </div>
                
                <!-- Agent Info Display -->
                <div id="agentInfo" class="alert alert-success d-none mb-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-check me-2"></i>
                            <div>
                                <strong id="agentName"></strong><br>
                                <small id="agentMobile" class="text-muted"></small>
                            </div>
                        </div>
                        <span id="agentStatus" class="badge"></span>
                    </div>
                </div>
                
                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" 
                               value="<?= e($password) ?>" required>
                    </div>
                </div>
                
                <!-- PIN (Box Design) -->
                <div class="mb-3">
                    <label class="pin-label">
                        <i class="bi bi-key-fill me-2"></i>6-Digit PIN
                    </label>
                    <div class="pin-container">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="pin-box" maxlength="1" id="pin_1" autocomplete="off">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="pin-box" maxlength="1" id="pin_2" autocomplete="off">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="pin-box" maxlength="1" id="pin_3" autocomplete="off">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="pin-box" maxlength="1" id="pin_4" autocomplete="off">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="pin-box" maxlength="1" id="pin_5" autocomplete="off">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="pin-box" maxlength="1" id="pin_6" autocomplete="off">
                    </div>
                    <input type="hidden" name="pin" id="pin_hidden">
                </div>
                
                <!-- Remember Me -->
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me" 
                           <?= !empty($branch_code) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="remember_me">
                        Remember Me (Only ask for PIN next time)
                    </label>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn btn-login mb-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login
                </button>
                
                <div class="text-center mb-3">
                    <a href="<?= url('agent/forgot-pin') ?>" class="text-decoration-none text-primary">
                        <i class="bi bi-key"></i> Forgot PIN?
                    </a>
                </div>
                
                <div class="text-center">
                    <a href="<?= url('/') ?>" class="text-decoration-none">
                        <i class="bi bi-arrow-left"></i> Back to Home
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Convert codes to uppercase
        const branchCodeInput = document.getElementById('branch_code');
        const agentCodeInput = document.getElementById('agent_code');
        
        branchCodeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            if (this.value.length >= 3) {
                fetchBranchDetails(this.value);
            } else {
                document.getElementById('branchInfo').classList.add('d-none');
            }
        });
        
        // Also trigger on blur (when field loses focus)
        branchCodeInput.addEventListener('blur', function() {
            if (this.value.length >= 3) {
                fetchBranchDetails(this.value);
            }
        });
        
        agentCodeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            const branchCode = branchCodeInput.value;
            if (this.value.length >= 1 && branchCode.length >= 3) {
                fetchAgentDetails(this.value, branchCode);
            } else {
                document.getElementById('agentInfo').classList.add('d-none');
            }
        });
        
        // Also trigger on blur (when field loses focus)
        agentCodeInput.addEventListener('blur', function() {
            const branchCode = branchCodeInput.value;
            if (this.value.length >= 1 && branchCode.length >= 3) {
                fetchAgentDetails(this.value, branchCode);
            }
        });

        function fetchBranchDetails(branchCode) {
            if (!branchCode || branchCode.length < 3) {
                document.getElementById('branchInfo').classList.add('d-none');
                return;
            }

            fetch('<?= url('api/fetch-branch') ?>', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'branch_code=' + encodeURIComponent(branchCode)
            })
            .then(response => response.json())
            .then(data => {
                const branchInfo = document.getElementById('branchInfo');
                const branchName = document.getElementById('branchName');
                
                if (data.success && data.branch) {
                    branchName.textContent = data.branch.branch_name;
                    branchInfo.classList.remove('d-none', 'alert-danger');
                    branchInfo.classList.add('alert-info');
                } else {
                    branchName.textContent = 'Branch not found';
                    branchInfo.classList.remove('d-none', 'alert-info');
                    branchInfo.classList.add('alert-danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('branchInfo').classList.add('d-none');
            });
        }

        function fetchAgentDetails(agentCode, branchCode) {
            if (!agentCode || agentCode.length < 1) {
                document.getElementById('agentInfo').classList.add('d-none');
                return;
            }

            // Send both agent code and branch code for better validation
            fetch('<?= url('api/fetch-agent') ?>', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'agent_code=' + encodeURIComponent(agentCode) + '&branch_code=' + encodeURIComponent(branchCode)
            })
            .then(response => response.json())
            .then(data => {
                const agentInfo = document.getElementById('agentInfo');
                const agentName = document.getElementById('agentName');
                const agentMobile = document.getElementById('agentMobile');
                const agentStatus = document.getElementById('agentStatus');
                
                if (data.success && data.agent) {
                    agentName.textContent = data.agent.agent_name;
                    agentMobile.textContent = 'Mobile: ' + data.agent.agent_mobile;
                    
                    // Display status badge
                    if (data.agent.status == 1 || data.agent.status === '1') {
                        agentStatus.textContent = 'Active';
                        agentStatus.className = 'badge bg-success';
                        agentInfo.classList.remove('d-none', 'alert-danger');
                        agentInfo.classList.add('alert-success');
                    } else {
                        agentStatus.textContent = 'Disabled';
                        agentStatus.className = 'badge bg-danger';
                        agentInfo.classList.remove('d-none', 'alert-success');
                        agentInfo.classList.add('alert-warning');
                    }
                } else {
                    agentName.textContent = 'Agent not found';
                    agentMobile.textContent = 'Please check the agent code and branch code';
                    agentStatus.textContent = '';
                    agentInfo.classList.remove('d-none', 'alert-success');
                    agentInfo.classList.add('alert-danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('agentInfo').classList.add('d-none');
            });
        }

        // Fetch on page load if values exist
        window.addEventListener('DOMContentLoaded', function() {
            if (branchCodeInput.value.length >= 3) {
                fetchBranchDetails(branchCodeInput.value);
            }
            if (agentCodeInput.value.length >= 1 && branchCodeInput.value.length >= 3) {
                fetchAgentDetails(agentCodeInput.value, branchCodeInput.value);
            }
        });

        // PIN box behavior
        const pinBoxes = document.querySelectorAll('.pin-box');

        pinBoxes.forEach((box, index) => {
            box.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length === 1 && index < 5) {
                    pinBoxes[index + 1].focus();
                }
                assemblePin();
            });

            box.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace') {
                    if (this.value === '' && index > 0) {
                        pinBoxes[index - 1].focus();
                        pinBoxes[index - 1].value = '';
                    }
                } else if (e.key === 'ArrowLeft' && index > 0) {
                    pinBoxes[index - 1].focus();
                    e.preventDefault();
                } else if (e.key === 'ArrowRight' && index < 5) {
                    pinBoxes[index + 1].focus();
                    e.preventDefault();
                }
            });

            box.addEventListener('paste', function(e) {
                e.preventDefault();
                const clipboard = (e.clipboardData || window.clipboardData).getData('text') || '';
                const numbers = clipboard.replace(/[^0-9]/g, '');
                for (let i = 0; i < 6 && i < numbers.length; i++) {
                    pinBoxes[i].value = numbers[i];
                }
                pinBoxes[5].focus();
                assemblePin();
            });
        });

        function assemblePin() {
            let pin = '';
            pinBoxes.forEach(box => pin += box.value || '');
            document.getElementById('pin_hidden').value = pin;
        }

        // Form validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const branchCode = branchCodeInput.value.trim();
            const agentCode = agentCodeInput.value.trim();
            const password = document.getElementById('password').value.trim();
            
            // Validate branch code
            if (!branchCode || branchCode.length < 3) {
                e.preventDefault();
                alert('Please enter a valid Branch Code (minimum 3 characters)');
                branchCodeInput.focus();
                return false;
            }
            
            // Validate agent code
            if (!agentCode || agentCode.length < 1) {
                e.preventDefault();
                alert('Please enter a valid Agent Code');
                agentCodeInput.focus();
                return false;
            }
            
            // Validate password
            if (!password || password.length < 1) {
                e.preventDefault();
                alert('Please enter your Password');
                document.getElementById('password').focus();
                return false;
            }
            
            // Validate PIN
            assemblePin();
            const pinVal = document.getElementById('pin_hidden').value;
            if (pinVal.length !== 6) {
                e.preventDefault();
                alert('Please enter a complete 6-digit PIN');
                pinBoxes[0].focus();
                return false;
            }
            
            // Validate PIN contains only digits
            if (!/^\d{6}$/.test(pinVal)) {
                e.preventDefault();
                alert('PIN must contain only numbers');
                pinBoxes[0].focus();
                return false;
            }
            
            return true;
        });

        // Auto-focus first PIN box if fields are prefilled
        if (branchCodeInput.value && agentCodeInput.value) {
            pinBoxes[0].focus();
        }
    </script>
</body>
</html>

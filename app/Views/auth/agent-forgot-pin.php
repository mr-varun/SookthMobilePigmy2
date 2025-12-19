<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot PIN - Sookth Mobile Pigmy</title>
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
        .forgot-container {
            max-width: 500px;
            width: 100%;
            padding: 20px;
        }
        .forgot-card {
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
        .btn-submit {
            width: 100%;
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .info-box i {
            color: #667eea;
        }
        @media (max-width: 576px) {
            .forgot-card {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="forgot-card">
            <div class="logo-circle">
                <i class="bi bi-key-fill"></i>
            </div>
            <h3 class="text-center mb-2">Forgot PIN?</h3>
            <p class="text-center text-muted mb-4">Enter your details to receive a reset link via email</p>
            
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
            
            <div class="info-box">
                <i class="bi bi-info-circle-fill me-2"></i>
                <small>We'll send a PIN reset link to your registered email address. The link will be valid for 10 minutes.</small>
            </div>
            
            <form action="<?= url('agent/forgot-pin') ?>" method="POST" id="forgotForm">
                <!-- Branch Code -->
                <div class="mb-3">
                    <label class="form-label">Branch Code</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-building"></i></span>
                        <input type="text" name="branch_code" id="branch_code" class="form-control" 
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
                               required style="text-transform: uppercase;">
                    </div>
                </div>
                
                <!-- Agent Info Display -->
                <div id="agentInfo" class="alert alert-success d-none mb-3">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-person-check me-2"></i>
                        <div>
                            <strong id="agentName"></strong><br>
                            <small id="agentEmail" class="text-muted"></small>
                        </div>
                    </div>
                </div>
                
                <!-- Password -->
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                </div>
                
                <!-- Email -->
                <div class="mb-4">
                    <label class="form-label">Registered Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn btn-submit mb-3">
                    <i class="bi bi-send me-2"></i>Send Reset Link
                </button>
                
                <div class="text-center">
                    <a href="<?= url('agent/login') ?>" class="text-decoration-none">
                        <i class="bi bi-arrow-left"></i> Back to Login
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
        
        agentCodeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            const branchCode = branchCodeInput.value;
            if (this.value.length >= 1 && branchCode.length >= 3) {
                fetchAgentDetails(this.value, branchCode);
            } else {
                document.getElementById('agentInfo').classList.add('d-none');
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

            fetch('<?= url('api/fetch-agent') ?>', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'agent_code=' + encodeURIComponent(agentCode) + '&branch_code=' + encodeURIComponent(branchCode)
            })
            .then(response => response.json())
            .then(data => {
                const agentInfo = document.getElementById('agentInfo');
                const agentName = document.getElementById('agentName');
                const agentEmail = document.getElementById('agentEmail');
                
                if (data.success && data.agent) {
                    agentName.textContent = data.agent.agent_name;
                    agentEmail.textContent = data.agent.agent_email ? 'Email: ' + data.agent.agent_email : 'No email registered';
                    agentInfo.classList.remove('d-none', 'alert-danger');
                    agentInfo.classList.add('alert-success');
                } else {
                    agentName.textContent = 'Agent not found';
                    agentEmail.textContent = 'Please check the agent code and branch code';
                    agentInfo.classList.remove('d-none', 'alert-success');
                    agentInfo.classList.add('alert-danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('agentInfo').classList.add('d-none');
            });
        }
    </script>
</body>
</html>

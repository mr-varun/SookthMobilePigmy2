<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Add New Agent' ?> - Sookth Mobile Pigmy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 800px;
        }
        .form-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 2.5rem;
        }
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }
        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 0.75rem;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-submit {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            color: white;
        }
        .btn-back {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn-back:hover {
            transform: translateY(-2px);
            color: white;
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #667eea;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .password-wrapper {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            font-size: 1.2rem;
            z-index: 10;
        }
        .password-toggle:hover {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="d-flex align-items-center mb-4">
                <div class="me-3">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-person-plus text-white" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
                <div>
                    <h2 class="mb-0">Add New Agent</h2>
                    <p class="text-muted mb-0">Create a new agent in the system</p>
                </div>
            </div>

            <div class="info-box">
                <small>
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Note:</strong> Agent code must be unique and cannot be changed later.
                </small>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="<?= url('admin/agents/add') ?>" method="POST" id="agentForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Branch *</label>
                        <select class="form-control" name="branch_code" id="branch_code" required>
                            <option value="">Select Branch</option>
                            <?php foreach ($branches as $branch): ?>
                                <option value="<?= htmlspecialchars($branch['branch_code']) ?>">
                                    <?= htmlspecialchars($branch['branch_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Agent Code *</label>
                        <input type="text" 
                               class="form-control" 
                               name="agent_code" 
                               id="agent_code" 
                               required 
                               style="text-transform: uppercase;"
                               placeholder="e.g., 001">
                        <small class="text-muted" id="agent_code_hint">Unique identifier for the agent</small>
                        <div id="agent_exists_warning" class="alert alert-danger mt-2" style="display: none; padding: 0.5rem; font-size: 0.875rem;">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <span id="warning_message"></span>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Agent Name *</label>
                        <input type="text" 
                               class="form-control" 
                               name="agent_name" 
                               required
                               placeholder="Full name of agent">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Agent Mobile *</label>
                        <input type="tel" 
                               class="form-control" 
                               name="agent_mobile" 
                               required
                               pattern="[0-9]{10}"
                               maxlength="10"
                               placeholder="10-digit mobile number">
                        <small class="text-muted">10 digits only</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Agent Email *</label>
                        <input type="email" 
                               class="form-control" 
                               name="agent_email" 
                               required
                               placeholder="agent@example.com">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password *</label>
                        <div class="password-wrapper">
                            <input type="password" 
                                   class="form-control" 
                                   name="password" 
                                   id="password"
                                   required
                                   minlength="6"
                                   value="pass123"
                                   placeholder="Minimum 6 characters"
                                   style="padding-right: 40px;">
                            <i class="bi bi-eye-slash password-toggle" id="togglePassword" onclick="togglePasswordVisibility('password', 'togglePassword')"></i>
                        </div>
                        <small class="text-muted">Default: pass123</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Agent PIN *</label>
                        <input type="text" 
                               class="form-control" 
                               name="pin" 
                               id="pin"
                               required
                               pattern="[0-9]{6}"
                               maxlength="6"
                               value="123456"
                               placeholder="6-digit PIN">
                        <small class="text-muted">Exactly 6 digits (Default: 123456)</small>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-submit" id="submitBtn">
                        <i class="bi bi-check-circle me-2"></i>Add Agent
                    </button>
                    <a href="<?= url('admin/agents') ?>" class="btn btn-back">
                        <i class="bi bi-arrow-left me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
    <script>
        // Toggle password visibility
        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            }
        }

        // Convert agent code to uppercase
        document.getElementById('agent_code').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Check if agent code already exists on blur
        document.getElementById('agent_code').addEventListener('blur', function() {
            const agentCode = this.value.trim();
            const warningDiv = document.getElementById('agent_exists_warning');
            const warningMessage = document.getElementById('warning_message');
            const submitBtn = document.getElementById('submitBtn');
            
            if (agentCode === '') {
                warningDiv.style.display = 'none';
                submitBtn.disabled = false;
                return;
            }

            // Make AJAX request to check if agent exists
            fetch('<?= url('admin/agents/check-code') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'code=' + encodeURIComponent(agentCode)
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    // Agent exists - show warning
                    warningMessage.innerHTML = `Agent code <strong>${agentCode}</strong> already exists in the system`;
                    warningDiv.style.display = 'block';
                    submitBtn.disabled = true;
                    this.classList.add('is-invalid');
                } else {
                    // Agent doesn't exist - hide warning
                    warningDiv.style.display = 'none';
                    submitBtn.disabled = false;
                    this.classList.remove('is-invalid');
                }
            })
            .catch(error => {
                console.error('Error checking agent code:', error);
                warningDiv.style.display = 'none';
                submitBtn.disabled = false;
            });
        });

        // Mobile number validation
        document.querySelector('input[name="agent_mobile"]').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // PIN validation
        document.getElementById('pin').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Form validation
        document.getElementById('agentForm').addEventListener('submit', function(e) {
            const pin = document.getElementById('pin').value;
            
            if (pin.length !== 6) {
                e.preventDefault();
                alert('PIN must be exactly 6 digits!');
                document.getElementById('pin').focus();
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>

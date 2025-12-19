<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Change Password' ?> - SMP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= url('bank/dashboard') ?>">
                <i class="bi bi-bank"></i> SMP Bank
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('bank/dashboard') ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('bank/agents') ?>">
                            <i class="bi bi-people"></i> Agents
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('bank/reports') ?>">
                            <i class="bi bi-file-text"></i> Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('bank/profile') ?>">
                            <i class="bi bi-person-circle"></i> Profile
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?= e(user('name')) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= url('bank/profile') ?>">Profile</a></li>
                            <li><a class="dropdown-item active" href="<?= url('bank/reset-password') ?>">Change Password</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= url('bank/logout') ?>">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Flash Messages -->
        <?= flashMessages() ?>

        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <!-- Page Header -->
                <div class="d-flex align-items-center mb-4">
                    <a href="<?= url('bank/profile') ?>" class="btn btn-outline-secondary me-3">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                    <h2 class="mb-0"><i class="bi bi-key"></i> Change Password</h2>
                </div>

                <!-- Change Password Form -->
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-shield-lock"></i> Update Password</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?= url('bank/reset-password') ?>" id="changePasswordForm">
                            <!-- User Type Selection -->
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="bi bi-people"></i> Change Password For *
                                </label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="user_type" 
                                               id="type_branch" value="branch" checked onchange="togglePasswordFields()">
                                        <label class="form-check-label" for="type_branch">
                                            <i class="bi bi-building"></i> Branch Manager
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="user_type" 
                                               id="type_agent" value="agent" onchange="togglePasswordFields()">
                                        <label class="form-check-label" for="type_agent">
                                            <i class="bi bi-person-badge"></i> Agent
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Agent Selection (shown only for agent type) -->
                            <div class="mb-3" id="agent_selection" style="display: none;">
                                <label for="agent_code" class="form-label">
                                    <i class="bi bi-person"></i> Select Agent *
                                </label>
                                <select class="form-select" id="agent_code" name="agent_code">
                                    <option value="">-- Select Agent --</option>
                                    <?php if (!empty($agents)): ?>
                                        <?php foreach ($agents as $agent): ?>
                                            <option value="<?= $agent['agent_code'] ?>">
                                                <?= htmlspecialchars($agent['agent_name']) ?> (<?= $agent['agent_code'] ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <!-- Current Password (only for branch) -->
                            <div class="mb-3" id="current_password_field">
                                <label for="current_password" class="form-label">
                                    <i class="bi bi-lock"></i> Current Password *
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="current_password" 
                                           name="current_password">
                                    <button class="btn btn-outline-secondary" type="button" 
                                            onclick="togglePassword('current_password')">
                                        <i class="bi bi-eye" id="current_password_icon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">
                                    <i class="bi bi-key"></i> New Password *
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="new_password" 
                                           name="new_password" required minlength="6">
                                    <button class="btn btn-outline-secondary" type="button" 
                                            onclick="togglePassword('new_password')">
                                        <i class="bi bi-eye" id="new_password_icon"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Password must be at least 6 characters</small>
                            </div>

                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">
                                    <i class="bi bi-key-fill"></i> Confirm New Password *
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm_password" 
                                           name="confirm_password" required minlength="6">
                                    <button class="btn btn-outline-secondary" type="button" 
                                            onclick="togglePassword('confirm_password')">
                                        <i class="bi bi-eye" id="confirm_password_icon"></i>
                                    </button>
                                </div>
                                <div id="password_match_message" class="mt-1"></div>
                            </div>

                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i> 
                                <strong>Password Security Tips:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Use at least 6 characters</li>
                                    <li>Mix uppercase and lowercase letters</li>
                                    <li>Include numbers and special characters</li>
                                    <li>Avoid common words or patterns</li>
                                </ul>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-check-circle"></i> Change Password
                                </button>
                                <a href="<?= url('bank/profile') ?>" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Additional Info -->
                <div class="card shadow-sm mt-3">
                    <div class="card-body">
                        <h6 class="card-title"><i class="bi bi-shield-check"></i> Security Notice</h6>
                        <p class="card-text text-muted mb-0">
                            <small>
                                After changing your password, you will remain logged in on this device. 
                                Make sure to remember your new password for future logins.
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
    <script src="<?= asset('js/security.js') ?>"></script>
    <script>
        // Toggle between branch and agent password fields
        function togglePasswordFields() {
            const userType = document.querySelector('input[name="user_type"]:checked').value;
            const currentPasswordField = document.getElementById('current_password_field');
            const currentPasswordInput = document.getElementById('current_password');
            const agentSelection = document.getElementById('agent_selection');
            const agentCodeInput = document.getElementById('agent_code');
            
            if (userType === 'branch') {
                currentPasswordField.style.display = 'block';
                currentPasswordInput.required = true;
                agentSelection.style.display = 'none';
                agentCodeInput.required = false;
            } else {
                currentPasswordField.style.display = 'none';
                currentPasswordInput.required = false;
                agentSelection.style.display = 'block';
                agentCodeInput.required = true;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            togglePasswordFields();
        });

        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '_icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        // Check password match
        document.getElementById('confirm_password').addEventListener('input', function() {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = this.value;
            const messageDiv = document.getElementById('password_match_message');
            
            if (confirmPassword === '') {
                messageDiv.innerHTML = '';
                return;
            }
            
            if (newPassword === confirmPassword) {
                messageDiv.innerHTML = '<small class="text-success"><i class="bi bi-check-circle"></i> Passwords match</small>';
            } else {
                messageDiv.innerHTML = '<small class="text-danger"><i class="bi bi-x-circle"></i> Passwords do not match</small>';
            }
        });

        // Form validation
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('New password and confirm password do not match!');
                return false;
            }
            
            if (newPassword.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long!');
                return false;
            }
        });
    </script>
</body>
</html>

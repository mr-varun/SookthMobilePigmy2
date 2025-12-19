<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Edit Branch' ?> - Sookth Mobile Pigmy</title>
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
        .form-control:disabled {
            background-color: #f8f9fa;
            cursor: not-allowed;
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
            background: #fff3cd;
            border-left: 4px solid #ffc107;
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
                        <i class="bi bi-pencil-square text-white" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
                <div>
                    <h2 class="mb-0">Edit Branch</h2>
                    <p class="text-muted mb-0">Update branch information</p>
                </div>
            </div>

            <div class="info-box">
                <small>
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Note:</strong> Branch code cannot be changed once created.
                </small>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="<?= url('admin/branches/edit/' . $branch['id']) ?>" method="POST" id="branchForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Branch Code *</label>
                        <input type="text" 
                               class="form-control" 
                               name="branch_code" 
                               value="<?= htmlspecialchars($branch['branch_code']) ?>"
                               disabled
                               style="text-transform: uppercase;">
                        <small class="text-muted">Branch code cannot be modified</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Branch Name *</label>
                        <input type="text" 
                               class="form-control" 
                               name="branch_name" 
                               value="<?= htmlspecialchars($branch['branch_name']) ?>"
                               required
                               placeholder="e.g., Main Branch">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Manager Name *</label>
                        <input type="text" 
                               class="form-control" 
                               name="manager_name" 
                               value="<?= htmlspecialchars($branch['manager_name']) ?>"
                               required
                               placeholder="Full name of branch manager">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Manager Mobile *</label>
                        <input type="tel" 
                               class="form-control" 
                               name="manager_mobile" 
                               value="<?= htmlspecialchars($branch['manager_mobile']) ?>"
                               required
                               pattern="[0-9]{10}"
                               maxlength="10"
                               placeholder="10-digit mobile number">
                        <small class="text-muted">10 digits only</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Manager Email *</label>
                        <input type="email" 
                               class="form-control" 
                               name="manager_email" 
                               value="<?= htmlspecialchars($branch['manager_email']) ?>"
                               required
                               placeholder="manager@example.com">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Manager Password *</label>
                        <div class="password-wrapper">
                            <input type="password" 
                                   class="form-control" 
                                   name="manager_password" 
                                   id="manager_password"
                                   value="<?= htmlspecialchars($branch['manager_password']) ?>"
                                   required
                                   minlength="6"
                                   placeholder="Minimum 6 characters"
                                   style="padding-right: 40px;">
                            <i class="bi bi-eye-slash password-toggle" id="togglePassword" onclick="togglePasswordVisibility('manager_password', 'togglePassword')"></i>
                        </div>
                        <small class="text-muted">Password for branch manager login</small>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-submit">
                        <i class="bi bi-check-circle me-2"></i>Update Branch
                    </button>
                    <a href="<?= url('admin/branches') ?>" class="btn btn-back">
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

        // Mobile number validation
        document.querySelector('input[name="manager_mobile"]').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>

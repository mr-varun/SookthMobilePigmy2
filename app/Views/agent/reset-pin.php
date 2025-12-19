<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset PIN - Agent Portal</title>
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

        .form-label-custom {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .form-control-custom {
            width: 100%;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            letter-spacing: 2px;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1.1rem;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-cancel {
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
            margin-top: 0.5rem;
        }

        .btn-cancel:hover {
            background: #e5e7eb;
        }

        .pin-requirements {
            background: #f3f4f6;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .pin-requirements .title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .pin-requirements ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .pin-requirements li {
            padding: 0.25rem 0;
            color: #6b7280;
            font-size: 0.85rem;
        }

        .pin-requirements li i {
            color: #667eea;
            margin-right: 0.5rem;
            font-size: 0.7rem;
        }

        @media (max-width: 576px) {
            body {
                padding: 0.5rem;
            }

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
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1 class="card-title-custom">Reset PIN</h1>
                <p class="card-subtitle-custom">Update your security PIN</p>
            </div>

            <!-- Form -->
            <div class="card-body-custom">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-times-circle"></i> <?= $error ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($success)): ?>
                    <div class="alert alert-success" role="alert">
                        <i class="fas fa-check-circle"></i> <?= $success ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= url('agent/reset-pin') ?>" id="resetPinForm">
                    <div class="input-group-custom">
                        <label class="form-label-custom">Current PIN</label>
                        <i class="input-icon fas fa-lock"></i>
                        <input type="password" 
                               class="form-control-custom" 
                               name="current_pin" 
                               id="current_pin"
                               maxlength="6"
                               pattern="[0-9]*"
                               inputmode="numeric"
                               required 
                               placeholder="Enter current PIN">
                        <button type="button" class="password-toggle" onclick="togglePassword('current_pin')">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>

                    <div class="pin-requirements">
                        <div class="title"><i class="fas fa-info-circle"></i> PIN Requirements</div>
                        <ul>
                            <li><i class="fas fa-circle"></i> Must be exactly 6 digits</li>
                            <li><i class="fas fa-circle"></i> Only numbers allowed</li>
                            <li><i class="fas fa-circle"></i> Cannot be 123456</li>
                            <li><i class="fas fa-circle"></i> Must be different from current PIN</li>
                        </ul>
                    </div>

                    <div class="input-group-custom">
                        <label class="form-label-custom">New PIN</label>
                        <i class="input-icon fas fa-key"></i>
                        <input type="password" 
                               class="form-control-custom" 
                               name="new_pin" 
                               id="new_pin"
                               maxlength="6"
                               pattern="[0-9]*"
                               inputmode="numeric"
                               required 
                               placeholder="Enter new PIN">
                        <button type="button" class="password-toggle" onclick="togglePassword('new_pin')">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>

                    <div class="input-group-custom">
                        <label class="form-label-custom">Confirm New PIN</label>
                        <i class="input-icon fas fa-check-circle"></i>
                        <input type="password" 
                               class="form-control-custom" 
                               name="confirm_pin" 
                               id="confirm_pin"
                               maxlength="6"
                               pattern="[0-9]*"
                               inputmode="numeric"
                               required 
                               placeholder="Confirm new PIN">
                        <button type="button" class="password-toggle" onclick="togglePassword('confirm_pin')">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-sync-alt"></i> Reset PIN
                    </button>
                    
                    <button type="button" class="btn-cancel" onclick="window.location.href='<?= url('agent/dashboard') ?>'">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = field.nextElementSibling;
            const icon = button.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Numeric input validation
        document.querySelectorAll('input[inputmode="numeric"]').forEach(input => {
            input.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });

        // Form validation
        document.getElementById('resetPinForm').addEventListener('submit', function(e) {
            const newPin = document.getElementById('new_pin').value;
            const confirmPin = document.getElementById('confirm_pin').value;

            if (newPin !== confirmPin) {
                e.preventDefault();
                alert('New PIN and Confirm PIN do not match!');
                return false;
            }

            if (newPin.length !== 6) {
                e.preventDefault();
                alert('PIN must be exactly 6 digits!');
                return false;
            }

            if (newPin === '123456') {
                e.preventDefault();
                alert('PIN cannot be 123456!');
                return false;
            }
        });
    </script>
</body>
</html>

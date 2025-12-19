<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset PIN - Sookth Mobile Pigmy</title>
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
        .reset-container {
            max-width: 480px;
            width: 100%;
            padding: 20px;
        }
        .reset-card {
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
        .pin-requirements {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
        }
        .pin-requirements .title {
            font-weight: 600;
            color: #667eea;
            margin-bottom: 0.5rem;
        }
        .pin-requirements ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .pin-requirements li {
            padding: 0.25rem 0;
            font-size: 0.9rem;
            color: #666;
        }
        .pin-requirements li i {
            font-size: 6px;
            margin-right: 8px;
            color: #667eea;
        }
        .agent-info {
            background: #e8f4fd;
            border-left: 4px solid #0d6efd;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        .agent-info h6 {
            margin-bottom: 0.5rem;
            color: #0d6efd;
        }
        @media (max-width: 576px) {
            .reset-card {
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
    <div class="reset-container">
        <div class="reset-card">
            <div class="logo-circle">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h3 class="text-center mb-4">Reset Your PIN</h3>
            
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
            
            <?php if (isset($agent)): ?>
            <div class="agent-info">
                <h6><i class="bi bi-person-check-fill me-2"></i>Agent Information</h6>
                <div><strong><?= e($agent['agent_name']) ?></strong></div>
                <small class="text-muted">Agent Code: <?= e($agent['agent_code']) ?> | Branch: <?= e($agent['branch_code']) ?></small>
            </div>
            
            <form action="<?= url('agent/reset-pin-token') ?>" method="POST" id="resetForm">
                <input type="hidden" name="token" value="<?= e($token) ?>">
                
                <!-- New PIN -->
                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-key-fill me-2"></i>New 6-Digit PIN
                    </label>
                    <div class="pin-container">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="pin-box" maxlength="1" id="new_pin_1" autocomplete="off">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="pin-box" maxlength="1" id="new_pin_2" autocomplete="off">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="pin-box" maxlength="1" id="new_pin_3" autocomplete="off">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="pin-box" maxlength="1" id="new_pin_4" autocomplete="off">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="pin-box" maxlength="1" id="new_pin_5" autocomplete="off">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="pin-box" maxlength="1" id="new_pin_6" autocomplete="off">
                    </div>
                    <input type="hidden" name="new_pin" id="new_pin_hidden">
                </div>
                
                <!-- Confirm PIN -->
                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-check2-square me-2"></i>Confirm 6-Digit PIN
                    </label>
                    <div class="pin-container">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="pin-box" maxlength="1" id="confirm_pin_1" autocomplete="off">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="pin-box" maxlength="1" id="confirm_pin_2" autocomplete="off">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="pin-box" maxlength="1" id="confirm_pin_3" autocomplete="off">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="pin-box" maxlength="1" id="confirm_pin_4" autocomplete="off">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="pin-box" maxlength="1" id="confirm_pin_5" autocomplete="off">
                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="pin-box" maxlength="1" id="confirm_pin_6" autocomplete="off">
                    </div>
                    <input type="hidden" name="confirm_pin" id="confirm_pin_hidden">
                </div>
                
                <div class="pin-requirements">
                    <div class="title"><i class="fas fa-info-circle"></i> PIN Requirements</div>
                    <ul>
                        <li><i class="fas fa-circle"></i> Must be exactly 6 digits</li>
                        <li><i class="fas fa-circle"></i> Only numbers allowed</li>
                        <li><i class="fas fa-circle"></i> Cannot be 123456</li>
                    </ul>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn btn-submit mt-4 mb-3">
                    <i class="bi bi-check-circle me-2"></i>Reset PIN
                </button>
                
                <div class="text-center">
                    <a href="<?= url('agent/login') ?>" class="text-decoration-none">
                        <i class="bi bi-arrow-left"></i> Back to Login
                    </a>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // PIN box behavior for New PIN
        const newPinBoxes = document.querySelectorAll('#new_pin_1, #new_pin_2, #new_pin_3, #new_pin_4, #new_pin_5, #new_pin_6');
        const confirmPinBoxes = document.querySelectorAll('#confirm_pin_1, #confirm_pin_2, #confirm_pin_3, #confirm_pin_4, #confirm_pin_5, #confirm_pin_6');

        function setupPinBoxes(boxes, hiddenFieldId) {
            boxes.forEach((box, index) => {
                box.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length === 1 && index < boxes.length - 1) {
                        boxes[index + 1].focus();
                    }
                    assemblePin(boxes, hiddenFieldId);
                });

                box.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace') {
                        if (this.value === '' && index > 0) {
                            boxes[index - 1].focus();
                            boxes[index - 1].value = '';
                        }
                    } else if (e.key === 'ArrowLeft' && index > 0) {
                        boxes[index - 1].focus();
                        e.preventDefault();
                    } else if (e.key === 'ArrowRight' && index < boxes.length - 1) {
                        boxes[index + 1].focus();
                        e.preventDefault();
                    }
                });

                box.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const clipboard = (e.clipboardData || window.clipboardData).getData('text') || '';
                    const numbers = clipboard.replace(/[^0-9]/g, '');
                    for (let i = 0; i < 6 && i < numbers.length; i++) {
                        boxes[i].value = numbers[i];
                    }
                    if (boxes.length > 0) {
                        boxes[boxes.length - 1].focus();
                    }
                    assemblePin(boxes, hiddenFieldId);
                });
            });
        }

        function assemblePin(boxes, hiddenFieldId) {
            let pin = '';
            boxes.forEach(box => pin += box.value || '');
            document.getElementById(hiddenFieldId).value = pin;
        }

        setupPinBoxes(newPinBoxes, 'new_pin_hidden');
        setupPinBoxes(confirmPinBoxes, 'confirm_pin_hidden');

        // Form validation
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            assemblePin(newPinBoxes, 'new_pin_hidden');
            assemblePin(confirmPinBoxes, 'confirm_pin_hidden');
            
            const newPin = document.getElementById('new_pin_hidden').value;
            const confirmPin = document.getElementById('confirm_pin_hidden').value;
            
            if (newPin.length !== 6) {
                e.preventDefault();
                alert('Please enter a complete 6-digit new PIN.');
                newPinBoxes[0].focus();
                return false;
            }
            
            if (confirmPin.length !== 6) {
                e.preventDefault();
                alert('Please enter a complete 6-digit confirm PIN.');
                confirmPinBoxes[0].focus();
                return false;
            }
            
            if (newPin !== confirmPin) {
                e.preventDefault();
                alert('New PIN and Confirm PIN do not match!');
                confirmPinBoxes[0].focus();
                return false;
            }
            
            if (newPin === '123456') {
                e.preventDefault();
                alert('PIN cannot be 123456!');
                newPinBoxes[0].focus();
                return false;
            }
            
            return true;
        });

        // Auto-focus first new PIN box
        newPinBoxes[0].focus();
    </script>
</body>
</html>

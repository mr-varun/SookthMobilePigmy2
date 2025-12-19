<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Edit Agent' ?> - Sookth Mobile Pigmy</title>
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
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="d-flex align-items-center mb-4">
                <div class="me-3">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-person-badge text-white" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
                <div>
                    <h2 class="mb-0">Edit Agent</h2>
                    <p class="text-muted mb-0">Update agent information</p>
                </div>
            </div>

            <div class="info-box">
                <small>
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Note:</strong> Agent code and branch cannot be changed once created.
                </small>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="<?= url('admin/agents/edit/' . $agent['agent_code']) ?>" method="POST" id="agentForm">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Branch *</label>
                        <select class="form-control" name="branch_code" disabled>
                            <?php foreach ($branches as $branch): ?>
                                <option value="<?= $branch['branch_code'] ?>" 
                                    <?= $branch['branch_code'] == $agent['branch_code'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($branch['branch_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Branch cannot be changed</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Agent Code *</label>
                        <input type="text" 
                               class="form-control" 
                               value="<?= htmlspecialchars($agent['agent_code']) ?>"
                               disabled
                               style="text-transform: uppercase;">
                        <small class="text-muted">Agent code cannot be modified</small>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Agent Name *</label>
                        <input type="text" 
                               class="form-control" 
                               name="agent_name" 
                               value="<?= htmlspecialchars($agent['agent_name']) ?>"
                               required
                               placeholder="Full name of agent">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Agent Mobile *</label>
                        <input type="tel" 
                               class="form-control" 
                               name="agent_mobile" 
                               value="<?= htmlspecialchars($agent['agent_mobile']) ?>"
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
                               value="<?= htmlspecialchars($agent['agent_email']) ?>"
                               required
                               placeholder="agent@example.com">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Agent PIN *</label>
                        <input type="text" 
                               class="form-control" 
                               name="pin" 
                               id="pin"
                               value="<?= htmlspecialchars($agent['pin']) ?>"
                               required
                               pattern="[0-9]{6}"
                               maxlength="6"
                               placeholder="6-digit PIN">
                        <small class="text-muted">Exactly 6 digits</small>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-submit">
                        <i class="bi bi-check-circle me-2"></i>Update Agent
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

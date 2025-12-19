<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Add New License' ?> - Sookth Mobile Pigmy</title>
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
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="d-flex align-items-center mb-4">
                <div class="me-3">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-shield-plus text-white" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
                <div>
                    <h2 class="mb-0">Add New License</h2>
                    <p class="text-muted mb-0">Create a new software license</p>
                </div>
            </div>

            <div class="info-box">
                <small>
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Note:</strong> License key will be generated automatically. Default duration is 1 year.
                </small>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="<?= url('admin/licenses/add') ?>" method="POST" id="licenseForm">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Branch *</label>
                        <select class="form-control" name="branch_code" id="branch_code" required>
                            <option value="">Select Branch</option>
                            <?php foreach ($branches as $branch): ?>
                                <option value="<?= htmlspecialchars($branch['branch_code']) ?>">
                                    <?= htmlspecialchars($branch['branch_name']) ?> (<?= htmlspecialchars($branch['branch_code']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Date *</label>
                        <input type="date" 
                               class="form-control" 
                               name="start_date" 
                               id="start_date" 
                               required
                               value="<?= date('Y-m-d') ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Expiry Date *</label>
                        <input type="date" 
                               class="form-control" 
                               name="expiry_date" 
                               id="expiry_date" 
                               required
                               value="<?= date('Y-m-d', strtotime('+1 year')) ?>">
                        <small class="text-muted">Default: 1 year from start date</small>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">License Key</label>
                        <input type="text" 
                               class="form-control" 
                               name="license_key" 
                               id="license_key" 
                               readonly
                               value="<?= htmlspecialchars($generatedKey ?? '') ?>"
                               style="background-color: #f8f9fa; font-family: 'Courier New', monospace; font-size: 1.1rem; font-weight: bold; color: #667eea;">
                        <small class="text-muted"><i class="bi bi-shield-check me-1"></i>This key has been auto-generated and is unique</small>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-submit">
                        <i class="bi bi-check-circle me-2"></i>Add License
                    </button>
                    <a href="<?= url('admin/licenses') ?>" class="btn btn-back">
                        <i class="bi bi-arrow-left me-2"></i>Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
    <script>
        // Update expiry date when start date changes
        document.getElementById('start_date').addEventListener('change', function() {
            const startDate = new Date(this.value);
            const expiryDate = new Date(startDate);
            expiryDate.setFullYear(expiryDate.getFullYear() + 1);
            
            const year = expiryDate.getFullYear();
            const month = String(expiryDate.getMonth() + 1).padStart(2, '0');
            const day = String(expiryDate.getDate()).padStart(2, '0');
            
            document.getElementById('expiry_date').value = `${year}-${month}-${day}`;
        });

        // Form validation
        document.getElementById('licenseForm').addEventListener('submit', function(e) {
            const startDate = new Date(document.getElementById('start_date').value);
            const expiryDate = new Date(document.getElementById('expiry_date').value);
            
            if (expiryDate <= startDate) {
                e.preventDefault();
                alert('Expiry date must be after start date!');
                document.getElementById('expiry_date').focus();
                return false;
            }
            
            return true;
        });
    </script>
</body>
</html>

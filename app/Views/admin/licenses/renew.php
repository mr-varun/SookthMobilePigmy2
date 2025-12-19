<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Renew License' ?> - Sookth Mobile Pigmy</title>
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
        .license-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .license-info .row {
            margin-bottom: 0.75rem;
        }
        .license-info .label {
            font-weight: 600;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="d-flex align-items-center mb-4">
                <div class="me-3">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-arrow-clockwise text-white" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
                <div>
                    <h2 class="mb-0">Renew License</h2>
                    <p class="text-muted mb-0">Extend license validity period</p>
                </div>
            </div>

            <div class="info-box">
                <small>
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Note:</strong> Renewing will extend the license validity. Current expiry date will be updated.
                </small>
            </div>

            <div class="license-info">
                <h5 class="mb-3">Current License Details</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="label">License Key:</div>
                        <div><code><?= htmlspecialchars($license['licence_key']) ?></code></div>
                    </div>
                    <div class="col-md-6">
                        <div class="label">Branch Code:</div>
                        <div><?= htmlspecialchars($license['branch_code']) ?></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="label">Registration Date:</div>
                        <div><?= date('d M Y', strtotime($license['reg_date'])) ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="label">Current Expiry Date:</div>
                        <div class="text-danger fw-bold"><?= date('d M Y', strtotime($license['expiry_date'])) ?></div>
                    </div>
                </div>
            </div>

            <form action="<?= url('admin/licenses/renew/' . $license['id']) ?>" method="POST" id="renewForm">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">New Expiry Date *</label>
                        <input type="date" 
                               class="form-control" 
                               name="expiry_date" 
                               id="expiry_date" 
                               required
                               min="<?= date('Y-m-d') ?>"
                               value="<?= date('Y-m-d', strtotime($license['expiry_date'] . ' +1 year')) ?>">
                        <small class="text-muted">Default: 1 year from current expiry date</small>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-submit">
                        <i class="bi bi-check-circle me-2"></i>Renew License
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
        // Form validation
        document.getElementById('renewForm').addEventListener('submit', function(e) {
            const currentExpiry = new Date('<?= $license['expiry_date'] ?>');
            const newExpiry = new Date(document.getElementById('expiry_date').value);
            
            if (newExpiry <= currentExpiry) {
                e.preventDefault();
                alert('New expiry date must be after the current expiry date!');
                document.getElementById('expiry_date').focus();
                return false;
            }
            
            return confirm('Are you sure you want to renew this license?');
        });
    </script>
</body>
</html>

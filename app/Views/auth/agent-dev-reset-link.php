<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Development Reset Link - Sookth Mobile Pigmy</title>
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
        .dev-container {
            max-width: 700px;
            width: 100%;
            padding: 20px;
        }
        .dev-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 3rem;
        }
        .dev-badge {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1.5rem;
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
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
        }
        .info-box h6 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        .link-box {
            background: #fff3cd;
            border: 2px dashed #ffc107;
            padding: 1.5rem;
            border-radius: 10px;
            margin: 1.5rem 0;
            word-break: break-all;
        }
        .link-box .link-text {
            color: #856404;
            font-size: 14px;
            font-family: 'Courier New', monospace;
            margin-bottom: 1rem;
        }
        .btn-reset {
            width: 100%;
            padding: 12px;
            font-weight: 600;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 1rem;
        }
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .btn-copy {
            background: #6c757d;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
        }
        .btn-copy:hover {
            background: #5a6268;
        }
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1rem;
            border-radius: 8px;
            margin: 1.5rem 0;
        }
        .warning-box p {
            margin: 0;
            color: #856404;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="dev-container">
        <div class="dev-card">
            <div class="text-center">
                <span class="dev-badge">
                    <i class="bi bi-code-slash"></i> DEVELOPMENT MODE
                </span>
            </div>
            
            <div class="logo-circle">
                <i class="bi bi-envelope-check"></i>
            </div>
            
            <h3 class="text-center mb-2">Email Simulation</h3>
            <p class="text-center text-muted mb-4">Reset link generated successfully</p>
            
            <div class="info-box">
                <h6><i class="bi bi-info-circle-fill me-2"></i>Email Details</h6>
                <p class="mb-1"><strong>To:</strong> <?= e($email) ?></p>
                <p class="mb-1"><strong>Agent:</strong> <?= e($agent_name) ?></p>
                <p class="mb-0"><strong>Subject:</strong> PIN Reset Request - Sookth Mobile Pigmy</p>
            </div>
            
            <div class="warning-box">
                <p>
                    <strong><i class="bi bi-exclamation-triangle-fill me-2"></i>Note:</strong>
                    Email sending is disabled in development mode (localhost). The reset link has been generated for you below.
                </p>
            </div>
            
            <div class="link-box">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong style="color: #856404;">Reset Link:</strong>
                    <button class="btn-copy" onclick="copyLink(event)">
                        <i class="bi bi-clipboard"></i> Copy
                    </button>
                </div>
                <div class="link-text" id="resetLink"><?= e($reset_link) ?></div>
            </div>
            
            <a href="<?= e($reset_link) ?>" class="btn btn-reset">
                <i class="bi bi-box-arrow-up-right me-2"></i>Open Reset Link
            </a>
            
            <div class="text-center mt-3">
                <a href="<?= url('agent/login') ?>" class="text-decoration-none">
                    <i class="bi bi-arrow-left"></i> Back to Login
                </a>
            </div>
            
            <div class="alert alert-info mt-4 mb-0" role="alert">
                <small>
                    <strong>Production Mode:</strong> In production, an email will be automatically sent to the registered email address with this reset link.
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copyLink(event) {
            const linkText = document.getElementById('resetLink').textContent;
            navigator.clipboard.writeText(linkText).then(() => {
                const btn = event.target.closest('button');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check"></i> Copied!';
                btn.style.background = '#28a745';
                
                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = '';
                }, 2000);
            }).catch(err => {
                alert('Failed to copy: ' + err);
            });
        }
    </script>
</body>
</html>

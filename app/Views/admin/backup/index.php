<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Database Backup' ?> - Sookth Mobile Pigmy</title>
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
            max-width: 1200px;
        }
        .card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 2rem;
        }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid #667eea;
        }
        .page-title {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 0;
        }
        .icon-box {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-create {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn-create:hover {
            transform: translateY(-2px);
            color: white;
        }
        .btn-back {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn-back:hover {
            transform: translateY(-2px);
            color: white;
        }
        .backup-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 4px solid #667eea;
        }
        .backup-info {
            flex-grow: 1;
        }
        .backup-name {
            font-weight: 600;
            color: #333;
            font-family: 'Courier New', monospace;
        }
        .backup-meta {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #666;
        }
        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="page-header">
                <div class="page-title">
                    <div class="icon-box">
                        <i class="bi bi-database-fill-down text-white" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h2 class="mb-0">Database Backup</h2>
                        <p class="text-muted mb-0">Create and manage database backups</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <form action="<?= url('admin/backup/create') ?>" method="POST" style="display: inline;">
                        <button type="submit" class="btn btn-create" onclick="return confirm('Create a new database backup?')">
                            <i class="bi bi-plus-circle me-2"></i>Create Backup
                        </button>
                    </form>
                    <a href="<?= url('admin/dashboard') ?>" class="btn btn-back">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i><?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i><?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (empty($backups)): ?>
                <div class="empty-state">
                    <i class="bi bi-database"></i>
                    <h4>No Backups Found</h4>
                    <p>Click "Create Backup" to create your first database backup</p>
                </div>
            <?php else: ?>
                <div class="backup-list">
                    <h5 class="mb-3">Available Backups (<?= count($backups) ?>)</h5>
                    <?php foreach ($backups as $backup): ?>
                        <div class="backup-item">
                            <div class="backup-info">
                                <div class="backup-name">
                                    <i class="bi bi-file-earmark-zip me-2"></i><?= htmlspecialchars($backup['name']) ?>
                                </div>
                                <div class="backup-meta">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    <?= date('d M Y, h:i A', $backup['date']) ?>
                                    <span class="mx-2">•</span>
                                    <i class="bi bi-hdd me-1"></i>
                                    <?= number_format($backup['size'] / 1024, 2) ?> KB
                                </div>
                            </div>
                            <div class="backup-actions">
                                <a href="<?= url('storage/backups/' . $backup['name']) ?>" 
                                   class="btn btn-sm btn-primary" 
                                   download
                                   title="Download backup">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
</body>
</html>

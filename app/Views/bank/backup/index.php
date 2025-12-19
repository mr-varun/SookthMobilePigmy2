<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Database Backup' ?> - SMP</title>
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
                        <a class="nav-link active" href="<?= url('bank/backup') ?>">
                            <i class="bi bi-download"></i> Backup
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
                            <li><a class="dropdown-item" href="<?= url('bank/reset-password') ?>">Change Password</a></li>
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

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-database"></i> Database Backup</h2>
            <button class="btn btn-success" onclick="createBackup()">
                <i class="bi bi-plus-circle"></i> Create New Backup
            </button>
        </div>

        <!-- Info Alert -->
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            <strong>Important:</strong> Regular backups help protect your data. It's recommended to create backups daily or before making significant changes.
        </div>

        <!-- Backup Statistics -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-archive fs-1 text-primary"></i>
                        <h3 class="mt-2"><?= count($backups) ?></h3>
                        <p class="text-muted mb-0">Total Backups</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-calendar-check fs-1 text-success"></i>
                        <h3 class="mt-2">
                            <?= !empty($backups) ? date('d-m-Y', $backups[0]['date']) : 'N/A' ?>
                        </h3>
                        <p class="text-muted mb-0">Last Backup</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <i class="bi bi-hdd fs-1 text-info"></i>
                        <h3 class="mt-2">
                            <?php 
                                $totalSize = array_sum(array_column($backups, 'size'));
                                echo $totalSize > 0 ? number_format($totalSize / 1024 / 1024, 2) . ' MB' : '0 MB';
                            ?>
                        </h3>
                        <p class="text-muted mb-0">Total Size</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Backups List -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Backup History</h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($backups)): ?>
                    <div class="text-center p-5">
                        <i class="bi bi-inbox fs-1 text-muted"></i>
                        <p class="text-muted mt-3 mb-0">No backups found. Create your first backup to get started.</p>
                        <button class="btn btn-primary mt-3" onclick="createBackup()">
                            <i class="bi bi-plus-circle"></i> Create Backup
                        </button>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="40%">Filename</th>
                                    <th width="20%">Date & Time</th>
                                    <th width="15%">Size</th>
                                    <th width="20%" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($backups as $index => $backup): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td>
                                            <i class="bi bi-file-earmark-zip text-primary"></i>
                                            <?= e($backup['name']) ?>
                                        </td>
                                        <td>
                                            <small>
                                                <?= date('d-m-Y', $backup['date']) ?><br>
                                                <span class="text-muted"><?= date('h:i A', $backup['date']) ?></span>
                                            </small>
                                        </td>
                                        <td>
                                            <?= number_format($backup['size'] / 1024, 2) ?> KB
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button class="btn btn-outline-primary" 
                                                        onclick="downloadBackup('<?= e($backup['name']) ?>')"
                                                        title="Download">
                                                    <i class="bi bi-download"></i>
                                                </button>
                                                <button class="btn btn-outline-danger" 
                                                        onclick="deleteBackup('<?= e($backup['name']) ?>')"
                                                        title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Backup Instructions -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Backup Best Practices</h5>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>Create backups regularly (daily recommended)</li>
                    <li>Store backup files in a secure location</li>
                    <li>Test backup restoration periodically</li>
                    <li>Keep multiple backup versions</li>
                    <li>Download important backups to external storage</li>
                    <li>Never delete all backups at once</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div class="modal fade" id="loadingModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <h5 id="loadingMessage">Processing...</h5>
                    <p class="text-muted mb-0">Please wait, this may take a few moments.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
    <script src="<?= asset('js/security.js') ?>"></script>
    <script>
        const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

        function createBackup() {
            if (!confirm('Create a new database backup? This may take a few moments.')) {
                return;
            }

            document.getElementById('loadingMessage').textContent = 'Creating backup...';
            loadingModal.show();

            // Simulate backup creation (replace with actual API call)
            setTimeout(() => {
                loadingModal.hide();
                alert('Backup feature is currently under development. This will create a database backup when implemented.');
                // location.reload();
            }, 2000);
        }

        function downloadBackup(filename) {
            document.getElementById('loadingMessage').textContent = 'Preparing download...';
            loadingModal.show();

            // Simulate download (replace with actual download logic)
            setTimeout(() => {
                loadingModal.hide();
                alert('Download feature is currently under development. File: ' + filename);
            }, 1000);
        }

        function deleteBackup(filename) {
            if (!confirm('Are you sure you want to delete this backup?\n\nFilename: ' + filename + '\n\nThis action cannot be undone.')) {
                return;
            }

            document.getElementById('loadingMessage').textContent = 'Deleting backup...';
            loadingModal.show();

            // Simulate deletion (replace with actual API call)
            setTimeout(() => {
                loadingModal.hide();
                alert('Delete feature is currently under development. File: ' + filename);
                // location.reload();
            }, 1000);
        }
    </script>
</body>
</html>

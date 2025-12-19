<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Manage Branches' ?> - Sookth Mobile Pigmy</title>
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
            max-width: 1400px;
        }
        .page-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 2rem;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .btn-add {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn-add:hover {
            transform: translateY(-2px);
            color: white;
        }
        .btn-back {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn-back:hover {
            transform: translateY(-2px);
            color: white;
        }
        .table-container {
            overflow-x: auto;
        }
        .table {
            margin-bottom: 0;
        }
        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .table thead th {
            border: none;
            font-weight: 600;
            padding: 1rem;
        }
        .table tbody tr {
            transition: background-color 0.2s;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .btn-sm {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.875rem;
        }
        .badge {
            padding: 6px 12px;
            border-radius: 6px;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="page-card">
            <div class="header-section">
                <div>
                    <h2 class="mb-0"><i class="bi bi-building me-2"></i>Manage Branches</h2>
                    <p class="text-muted mb-0">Add, edit, and manage all branches</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= url('admin/branches/add') ?>" class="btn btn-add">
                        <i class="bi bi-plus-circle me-2"></i>Add New Branch
                    </a>
                    <a href="<?= url('admin/dashboard') ?>" class="btn btn-back">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>

            <?php if (Session::has('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <?= e(Session::getFlash('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (Session::has('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <?= e(Session::getFlash('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (empty($branches)): ?>
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h4>No Branches Found</h4>
                    <p class="text-muted">Get started by adding your first branch</p>
                    <a href="<?= url('admin/branches/add') ?>" class="btn btn-add mt-3">
                        <i class="bi bi-plus-circle me-2"></i>Add Branch
                    </a>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Sl. No</th>
                                <th>Branch Code</th>
                                <th>Branch Name</th>
                                <th>Manager Name</th>
                                <th>Manager Mobile</th>
                                <th>Manager Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $slNo = 1; foreach ($branches as $branch): ?>
                                <tr>
                                    <td><?= $slNo++ ?></td>
                                    <td><strong><?= e($branch['branch_code']) ?></strong></td>
                                    <td><?= e($branch['branch_name']) ?></td>
                                    <td><?= e($branch['manager_name']) ?></td>
                                    <td>
                                        <i class="bi bi-telephone text-muted"></i>
                                        <?= e($branch['manager_mobile']) ?>
                                    </td>
                                    <td>
                                        <i class="bi bi-envelope text-muted"></i>
                                        <?= e($branch['manager_email']) ?>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="<?= url('admin/branches/edit/' . $branch['id']) ?>" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="<?= url('admin/branches/delete/' . $branch['id']) ?>" 
                                                  method="POST" 
                                                  style="display: inline;"
                                                  onsubmit="return confirm('Are you sure you want to delete this branch?')">
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
</body>
</html>

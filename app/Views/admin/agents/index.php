<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Manage Agents' ?> - Sookth Mobile Pigmy</title>
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
                    <h2 class="mb-1">
                        <i class="bi bi-people-fill text-primary me-2"></i>
                        Manage Agents
                    </h2>
                    <p class="text-muted mb-0">View and manage all agents in the system</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="<?= url('admin/agents/add') ?>" class="btn btn-add">
                        <i class="bi bi-plus-circle me-2"></i>Add New Agent
                    </a>
                    <a href="<?= url('admin/dashboard') ?>" class="btn btn-back">
                        <i class="bi bi-arrow-left me-2"></i>Back
                    </a>
                </div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (empty($agents)): ?>
                <div class="empty-state">
                    <i class="bi bi-people"></i>
                    <h4>No Agents Found</h4>
                    <p>Start by adding your first agent to the system.</p>
                    <a href="<?= url('admin/agents/add') ?>" class="btn btn-add mt-3">
                        <i class="bi bi-plus-circle me-2"></i>Add First Agent
                    </a>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Agent Code</th>
                                <th>Agent Name</th>
                                <th>Branch</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>PIN</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($agents as $agent): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($agent['agent_code']) ?></strong></td>
                                    <td><?= htmlspecialchars($agent['agent_name']) ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= htmlspecialchars($agent['branch_name'] ?? $agent['branch_code']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($agent['agent_mobile']) ?></td>
                                    <td><?= htmlspecialchars($agent['agent_email']) ?></td>
                                    <td>
                                        <?php if (isset($agent['pin']) && !empty($agent['pin'])): ?>
                                            <code><?= htmlspecialchars($agent['pin']) ?></code>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($agent['status']) && $agent['status'] == 1): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Disabled</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?= url('admin/agents/edit/' . $agent['agent_code']) ?>" 
                                               class="btn btn-sm btn-primary" 
                                               title="Edit Agent">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-danger" 
                                                    onclick="confirmDelete('<?= $agent['agent_code'] ?>', '<?= htmlspecialchars($agent['agent_name']) ?>')"
                                                    title="Delete Agent">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 text-muted">
                    <small>Total Agents: <strong><?= count($agents) ?></strong></small>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
    <script>
        function confirmDelete(agentCode, agentName) {
            if (confirm(`Are you sure you want to delete agent "${agentName}" (${agentCode})?\n\nThis action cannot be undone.`)) {
                // Create and submit a form for deletion
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= url('admin/agents/delete/') ?>' + agentCode;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>

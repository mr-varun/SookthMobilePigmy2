<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin Dashboard' ?> - Sookth Mobile Pigmy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .dashboard-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .dashboard-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        .logo-circle {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 1.5rem;
            border-radius: 15px;
            border-left: 4px solid;
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-card.branches { border-left-color: #007bff; }
        .stat-card.agents { border-left-color: #28a745; }
        .stat-card.collections { border-left-color: #ffc107; }
        .stat-card.customers { border-left-color: #17a2b8; }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
        }
        .stat-icon.branches { background: #007bff; }
        .stat-icon.agents { background: #28a745; }
        .stat-icon.collections { background: #ffc107; }
        .stat-icon.customers { background: #17a2b8; }
        
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        .menu-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            text-decoration: none;
            color: #333;
        }
        .menu-icon {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin-bottom: 1rem;
        }
        .menu-icon.branches { background: linear-gradient(135deg, #007bff, #0056b3); }
        .menu-icon.agents { background: linear-gradient(135deg, #28a745, #1e7e34); }
        .menu-icon.licenses { background: linear-gradient(135deg, #6f42c1, #5936a8); }
        .menu-icon.backup { background: linear-gradient(135deg, #fd7e14, #e8590c); }
        
        .btn-logout {
            background: linear-gradient(135deg, #dc3545, #b21f2d);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        .btn-logout:hover {
            transform: translateY(-2px);
            color: white;
        }
        @media (max-width: 768px) {
            .header-section {
                flex-direction: column;
                text-align: center;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
            .menu-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-card">
            <div class="header-section">
                <div class="d-flex align-items-center gap-3">
                    <div class="logo-circle">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div>
                        <h2 class="mb-0">Admin Dashboard</h2>
                        <p class="text-muted mb-0">Welcome, <?= e($userName ?? 'Admin') ?>!</p>
                    </div>
                </div>
                <a href="<?= url('admin/logout') ?>" class="btn btn-logout">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                </a>
            </div>

            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card branches">
                    <div class="stat-icon branches">
                        <i class="bi bi-building"></i>
                    </div>
                    <h3 class="mb-1"><?= $stats['total_branches'] ?? 0 ?></h3>
                    <p class="text-muted mb-0">Total Branches</p>
                </div>
                
                <div class="stat-card agents">
                    <div class="stat-icon agents">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3 class="mb-1"><?= $stats['total_agents'] ?? 0 ?></h3>
                    <p class="text-muted mb-0">Total Agents</p>
                </div>
                
                <div class="stat-card collections">
                    <div class="stat-icon collections">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <h3 class="mb-1">₹<?= number_format($stats['total_collections_today'] ?? 0, 2) ?></h3>
                    <p class="text-muted mb-0">Today's Collection</p>
                </div>
                
                <div class="stat-card customers">
                    <div class="stat-icon customers">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <h3 class="mb-1"><?= $stats['total_customers'] ?? 0 ?></h3>
                    <p class="text-muted mb-0">Total Customers</p>
                </div>
            </div>

            <!-- Branch Settings Section -->
            <div class="settings-section mb-4">
                <h4 class="mb-3">
                    <i class="bi bi-gear-fill me-2" style="color: #667eea;"></i>Branch Settings
                </h4>
                <div class="settings-card" style="background: #f8f9fa; border-radius: 15px; padding: 1.5rem;">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Select Branch</label>
                            <select class="form-select" id="settingsBranchSelect" style="border-radius: 10px;">
                                <option value="">-- Select Branch --</option>
                                <?php foreach ($branches as $branch): ?>
                                    <option value="<?= htmlspecialchars($branch['branch_code']) ?>">
                                        <?= htmlspecialchars($branch['branch_name']) ?> (<?= htmlspecialchars($branch['branch_code']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div id="settingsOptions" style="display: none;">
                        <hr class="my-3">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="d-flex justify-content-between align-items-center p-3" style="background: white; border-radius: 10px;">
                                    <div>
                                        <h6 class="mb-0"><i class="bi bi-printer me-2"></i>Printer Support</h6>
                                        <small class="text-muted">Enable/Disable printer</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input setting-toggle" 
                                               type="checkbox" 
                                               id="printerSupport" 
                                               data-setting="printer_support"
                                               style="width: 50px; height: 25px; cursor: pointer;">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="d-flex justify-content-between align-items-center p-3" style="background: white; border-radius: 10px;">
                                    <div>
                                        <h6 class="mb-0"><i class="bi bi-chat-dots me-2"></i>Text Message</h6>
                                        <small class="text-muted">SMS notifications</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input setting-toggle" 
                                               type="checkbox" 
                                               id="textMessage" 
                                               data-setting="text_message"
                                               style="width: 50px; height: 25px; cursor: pointer;">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <div class="d-flex justify-content-between align-items-center p-3" style="background: white; border-radius: 10px;">
                                    <div>
                                        <h6 class="mb-0"><i class="bi bi-whatsapp me-2"></i>WhatsApp Message</h6>
                                        <small class="text-muted">WhatsApp notifications</small>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input setting-toggle" 
                                               type="checkbox" 
                                               id="whatsappMessage" 
                                               data-setting="whatsapp_message"
                                               style="width: 50px; height: 25px; cursor: pointer;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menu Options -->
            <div class="menu-grid">
                <a href="<?= url('admin/branches') ?>" class="menu-card">
                    <div class="menu-icon branches">
                        <i class="bi bi-building"></i>
                    </div>
                    <h5 class="mb-2">Manage Branches</h5>
                    <small class="text-muted">Add, edit, and manage branches</small>
                </a>

                <a href="<?= url('admin/agents') ?>" class="menu-card">
                    <div class="menu-icon agents">
                        <i class="bi bi-people"></i>
                    </div>
                    <h5 class="mb-2">Manage Agents</h5>
                    <small class="text-muted">Add, edit, and manage agents</small>
                </a>

                <a href="<?= url('admin/licenses') ?>" class="menu-card">
                    <div class="menu-icon licenses">
                        <i class="bi bi-key"></i>
                    </div>
                    <h5 class="mb-2">Manage Licenses</h5>
                    <small class="text-muted">Issue and renew licenses</small>
                </a>

                <a href="<?= url('admin/backup') ?>" class="menu-card">
                    <div class="menu-icon backup">
                        <i class="bi bi-database"></i>
                    </div>
                    <h5 class="mb-2">Database Backup</h5>
                    <small class="text-muted">Backup and restore data</small>
                </a>
            </div>
        </div>

        <div class="text-center text-white">
            <small>&copy; <?= date('Y') ?> Sookth Mobile Pigmy by Sookth Solutions. All rights reserved.</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= url('assets/js/security.js') ?>"></script>
    <script>
        // Branch Settings Management
        const settingsBranchSelect = document.getElementById('settingsBranchSelect');
        const settingsOptions = document.getElementById('settingsOptions');
        let currentBranchCode = '';

        settingsBranchSelect.addEventListener('change', function() {
            currentBranchCode = this.value;
            
            if (currentBranchCode) {
                loadBranchSettings(currentBranchCode);
            } else {
                settingsOptions.style.display = 'none';
            }
        });

        function loadBranchSettings(branchCode) {
            fetch('<?= url('admin/settings/get') ?>?branch_code=' + branchCode)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update toggle switches
                        document.getElementById('printerSupport').checked = data.settings.printer_support == 1;
                        document.getElementById('textMessage').checked = data.settings.text_message == 1;
                        document.getElementById('whatsappMessage').checked = data.settings.whatsapp_message == 1;
                        
                        settingsOptions.style.display = 'block';
                    } else {
                        alert('Failed to load settings: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load settings');
                });
        }

        // Handle toggle changes
        document.querySelectorAll('.setting-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                if (!currentBranchCode) {
                    this.checked = !this.checked;
                    alert('Please select a branch first');
                    return;
                }

                const setting = this.dataset.setting;
                const value = this.checked ? 1 : 0;

                // Disable toggle during request
                this.disabled = true;

                fetch('<?= url('admin/settings/update') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `branch_code=${currentBranchCode}&setting=${setting}&value=${value}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success feedback
                        const icon = this.parentElement.parentElement.querySelector('h6 i');
                        icon.style.color = this.checked ? '#28a745' : '#6c757d';
                    } else {
                        // Revert on error
                        this.checked = !this.checked;
                        alert('Failed to update setting: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.checked = !this.checked;
                    alert('Failed to update setting');
                })
                .finally(() => {
                    this.disabled = false;
                });
            });
        });
    </script>
</body>
</html>

<?php
/**
 * Admin Settings Controller
 * Handles branch settings management
 */

class SettingsController extends Controller
{
    public function __construct()
    {
        if (!Session::isLoggedIn() || Session::getUserRole() !== 'admin') {
            Session::flash('error', 'Please login as admin');
            $this->redirect('admin/login');
        }
    }

    public function getBranchSettings()
    {
        header('Content-Type: application/json');
        
        $branchCode = $_GET['branch_code'] ?? '';
        
        if (empty($branchCode)) {
            echo json_encode(['success' => false, 'message' => 'Branch code required']);
            exit;
        }

        // Get or create settings
        $settings = Database::fetchOne("SELECT * FROM branch_settings WHERE branch_code = ?", [$branchCode]);
        
        if (!$settings) {
            // Create default settings
            Database::insert(
                "INSERT INTO branch_settings (branch_code, printer_support, text_message, whatsapp_message, share_support) VALUES (?, 0, 0, 0, 0)",
                [$branchCode]
            );
            $settings = [
                'branch_code' => $branchCode,
                'printer_support' => 0,
                'text_message' => 0,
                'whatsapp_message' => 0,
                'share_support' => 0
            ];
        }

        echo json_encode(['success' => true, 'settings' => $settings]);
        exit;
    }

    public function updateSetting()
    {
        header('Content-Type: application/json');
        
        if (!$this->isPost()) {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }

        $branchCode = $this->post('branch_code');
        $setting = $this->post('setting');
        $value = (int)$this->post('value');

        if (empty($branchCode) || empty($setting)) {
            echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
            exit;
        }

        // Validate setting name
        $allowedSettings = ['printer_support', 'text_message', 'whatsapp_message', 'share_support'];
        if (!in_array($setting, $allowedSettings)) {
            echo json_encode(['success' => false, 'message' => 'Invalid setting']);
            exit;
        }

        try {
            // Check if settings exist
            $exists = Database::fetchOne("SELECT id FROM branch_settings WHERE branch_code = ?", [$branchCode]);
            
            if ($exists) {
                // Update existing
                Database::query(
                    "UPDATE branch_settings SET {$setting} = ? WHERE branch_code = ?",
                    [$value, $branchCode]
                );
            } else {
                // Create new
                Database::insert(
                    "INSERT INTO branch_settings (branch_code, {$setting}) VALUES (?, ?)",
                    [$branchCode, $value]
                );
            }

            echo json_encode(['success' => true, 'message' => 'Setting updated successfully']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Failed to update setting: ' . $e->getMessage()]);
        }
        exit;
    }
}

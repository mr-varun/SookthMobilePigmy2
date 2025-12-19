<?php
/**
 * Admin License Controller
 * Handles license management operations
 */

class LicenseController extends Controller
{
    public function __construct()
    {
        if (!Session::isLoggedIn() || Session::getUserRole() !== 'admin') {
            Session::flash('error', 'Please login as admin');
            $this->redirect('admin/login');
        }
    }

    public function index()
    {
        $licenses = Database::fetchAll("
            SELECT l.*, b.branch_name 
            FROM licence_management l 
            LEFT JOIN branch b ON l.branch_code = b.branch_code 
            ORDER BY l.expiry_date DESC
        ");

        echo $this->view('admin.licenses.index', [
            'pageTitle' => 'License Management',
            'licenses' => $licenses
        ]);
    }

    public function create()
    {
        // Get all branches that don't have a license yet
        $branches = Database::fetchAll("
            SELECT b.* FROM branch b 
            WHERE b.branch_code NOT IN (
                SELECT branch_code FROM licence_management
            )
            ORDER BY b.branch_name
        ");
        
        // Generate random license key
        $licenseKey = $this->generateLicenseKey();
        
        echo $this->view('admin.licenses.create', [
            'pageTitle' => 'Add New License',
            'branches' => $branches,
            'generatedKey' => $licenseKey
        ]);
    }
    
    private function generateLicenseKey()
    {
        // Generate 12 character alphanumeric key
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $key = '';
        for ($i = 0; $i < 12; $i++) {
            $key .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $key;
    }

    public function store()
    {
        if ($this->isPost()) {
            $branchCode = $this->post('branch_code');
            $licenseKey = $this->post('license_key');
            $startDate = $this->post('start_date');
            $expiryDate = $this->post('expiry_date');

            // Validate inputs
            if (empty($branchCode) || empty($licenseKey) || empty($startDate) || empty($expiryDate)) {
                Session::flash('error', 'All fields are required');
                $this->redirect('admin/licenses/add');
            }

            // Check if branch already has a license
            $existing = Database::fetchOne("SELECT id FROM licence_management WHERE branch_code = ?", [$branchCode]);
            if ($existing) {
                Session::flash('error', 'This branch already has a license');
                $this->redirect('admin/licenses/add');
            }

            Database::insert("
                INSERT INTO licence_management (branch_code, licence_key, reg_date, expiry_date, status) 
                VALUES (?, ?, ?, ?, 1)
            ", [$branchCode, $licenseKey, $startDate, $expiryDate]);

            Session::flash('success', 'License added successfully');
            $this->redirect('admin/licenses');
        }

        $this->redirect('admin/licenses/add');
    }

    public function renew($id)
    {
        $license = Database::fetchOne("SELECT * FROM licence_management WHERE id = ?", [$id]);
        if (!$license) {
            Session::flash('error', 'License not found');
            $this->redirect('admin/licenses');
        }

        echo $this->view('admin.licenses.renew', [
            'pageTitle' => 'Renew License',
            'license' => $license
        ]);
    }

    public function processRenew($id)
    {
        if ($this->isPost()) {
            $newExpiryDate = $this->post('expiry_date');

            Database::query("
                UPDATE licence_management 
                SET expiry_date = ?, status = 'active' 
                WHERE id = ?
            ", [$newExpiryDate, $id]);

            Session::flash('success', 'License renewed successfully');
            $this->redirect('admin/licenses');
        }

        $this->redirect('admin/licenses/renew/' . $id);
    }
}

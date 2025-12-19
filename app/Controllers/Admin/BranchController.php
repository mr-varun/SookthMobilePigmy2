<?php
/**
 * Admin Branch Controller
 * Handles branch management operations
 */

class BranchController extends Controller
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
        $branches = Database::fetchAll("
            SELECT b.id, b.branch_code, b.branch_name, b.manager_name, b.manager_mobile, b.manager_email
            FROM branch b
            ORDER BY b.branch_name
        ");

        echo $this->view('admin.branches.index', [
            'pageTitle' => 'Manage Branches',
            'branches' => $branches
        ]);
    }

    public function create()
    {
        echo $this->view('admin.branches.create', [
            'pageTitle' => 'Add New Branch'
        ]);
    }

    public function store()
    {
        if ($this->isPost()) {
            $branchCode = strtoupper(trim($this->post('branch_code')));
            $branchName = trim($this->post('branch_name'));
            $managerName = trim($this->post('manager_name'));
            $managerMobile = trim($this->post('manager_mobile'));
            $managerEmail = trim($this->post('manager_email'));
            $managerPassword = $this->post('manager_password');
            $confirmPassword = $this->post('confirm_password');

            // Validate required fields
            if (empty($branchCode) || empty($branchName) || empty($managerName) || empty($managerMobile) || empty($managerEmail) || empty($managerPassword)) {
                Session::flash('error', 'All fields are required');
                $this->redirect('admin/branches/create');
            }

            // Validate mobile number
            if (!preg_match('/^[0-9]{10}$/', $managerMobile)) {
                Session::flash('error', 'Manager mobile must be exactly 10 digits');
                $this->redirect('admin/branches/create');
            }

            // Validate email
            if (!filter_var($managerEmail, FILTER_VALIDATE_EMAIL)) {
                Session::flash('error', 'Invalid email address');
                $this->redirect('admin/branches/create');
            }

            // Validate password match
            if ($managerPassword !== $confirmPassword) {
                Session::flash('error', 'Passwords do not match');
                $this->redirect('admin/branches/create');
            }

            // Validate password length
            if (strlen($managerPassword) < 6) {
                Session::flash('error', 'Password must be at least 6 characters');
                $this->redirect('admin/branches/create');
            }

            // Check if branch code already exists
            $exists = Database::fetchOne("SELECT id FROM branch WHERE branch_code = ?", [$branchCode]);
            if ($exists) {
                Session::flash('error', 'Branch code already exists');
                $this->redirect('admin/branches/create');
            }

            // Hash the password
            // $hashedPassword = password_hash($managerPassword, PASSWORD_BCRYPT);

            // Insert branch
            Database::insert("
                INSERT INTO branch (branch_code, branch_name, manager_name, manager_mobile, manager_email, manager_password, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ", [$branchCode, $branchName, $managerName, $managerMobile, $managerEmail, $managerPassword]);

            // Insert branch settings with default values
            Database::insert("
                INSERT INTO branch_settings (branch_code, printer_support, text_message, whatsapp_message, created_at) 
                VALUES (?, 0, 0, 0, NOW())
            ", [$branchCode]);

            Session::flash('success', 'Branch added successfully');
            $this->redirect('admin/branches');
        }

        $this->redirect('admin/branches/create');
    }

    public function edit($id)
    {
        $branch = Database::fetchOne("SELECT * FROM branch WHERE id = ?", [$id]);
        if (!$branch) {
            Session::flash('error', 'Branch not found');
            $this->redirect('admin/branches');
        }

        echo $this->view('admin.branches.edit', [
            'pageTitle' => 'Edit Branch',
            'branch' => $branch
        ]);
    }

    public function update($id)
    {
        if ($this->isPost()) {
            $branchName = trim($this->post('branch_name'));
            $managerName = trim($this->post('manager_name'));
            $managerMobile = trim($this->post('manager_mobile'));
            $managerEmail = trim($this->post('manager_email'));
            $managerPassword = $this->post('manager_password');

            // Validate required fields
            if (empty($branchName) || empty($managerName) || empty($managerMobile) || empty($managerEmail)) {
                Session::flash('error', 'All fields are required');
                $this->redirect('admin/branches/edit/' . $id);
            }

            Database::query("
                UPDATE branch 
                SET branch_name = ?, manager_name = ?, manager_mobile = ?, manager_email = ?, manager_password = ? 
                WHERE id = ?
            ", [$branchName, $managerName, $managerMobile, $managerEmail, $managerPassword, $id]);

            Session::flash('success', 'Branch updated successfully');
            $this->redirect('admin/branches');
        }

        $this->redirect('admin/branches/edit/' . $id);
    }

    public function delete($id)
    {
        if ($this->isPost()) {
            // Get branch code before deletion
            $branch = Database::fetchOne("SELECT branch_code FROM branch WHERE id = ?", [$id]);
            
            if ($branch) {
                // Delete branch settings first
                Database::query("DELETE FROM branch_settings WHERE branch_code = ?", [$branch['branch_code']]);
                
                // Delete branch
                Database::query("DELETE FROM branch WHERE id = ?", [$id]);
                Session::flash('success', 'Branch deleted successfully');
            } else {
                Session::flash('error', 'Branch not found');
            }
        }

        $this->redirect('admin/branches');
    }

    public function checkCode()
    {
        if ($this->isPost()) {
            $code = $this->post('code');
            $exists = Database::fetchOne("SELECT id FROM branch WHERE branch_code = ?", [$code]);
            $this->json(['exists' => !empty($exists)]);
        }
    }
}

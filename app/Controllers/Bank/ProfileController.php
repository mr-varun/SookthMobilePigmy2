<?php
/**
 * Bank Profile Controller
 */

class ProfileController extends Controller
{
    public function __construct()
    {
        if (!Session::isLoggedIn() || Session::getUserRole() !== 'bank') {
            Session::flash('error', 'Please login as bank user');
            $this->redirect('bank/login');
        }
    }

    public function index()
    {
        $branchCode = Session::get('user_data')['branch_code'] ?? '';
        
        // Get branch details
        $branch = Database::fetchOne("SELECT * FROM branch WHERE branch_code = ?", [$branchCode]);
        
        echo $this->view('bank.profile.index', [
            'pageTitle' => 'Profile',
            'branch' => $branch
        ]);
    }

    public function showResetPassword()
    {
        $branchCode = Session::get('user_data')['branch_code'] ?? '';
        
        // Get all agents for this branch
        $agents = Database::fetchAll(
            "SELECT agent_code, agent_name FROM agent WHERE branch_code = ? ORDER BY agent_name",
            [$branchCode]
        );
        
        echo $this->view('bank.profile.reset-password', [
            'pageTitle' => 'Reset Password',
            'agents' => $agents
        ]);
    }

    public function resetPassword()
    {
        if ($this->isPost()) {
            $branchCode = Session::get('user_data')['branch_code'] ?? '';
            $userType = $this->post('user_type');
            $newPassword = $this->post('new_password');
            $confirmPassword = $this->post('confirm_password');

            if ($newPassword !== $confirmPassword) {
                Session::flash('error', 'New password and confirm password do not match');
                $this->redirect('bank/reset-password');
            }

            if ($userType === 'branch') {
                // Change branch manager password
                $currentPassword = $this->post('current_password');
                
                // Get current branch data
                $branch = Database::fetchOne("SELECT manager_password FROM branch WHERE branch_code = ?", [$branchCode]);
                
                // Support both plain text and hashed passwords
                $passwordMatch = false;
                if (password_get_info($branch['manager_password'])['algo'] !== null) {
                    // It's a hashed password
                    $passwordMatch = password_verify($currentPassword, $branch['manager_password']);
                } else {
                    // Plain text password
                    $passwordMatch = ($currentPassword === $branch['manager_password']);
                }
                
                if (!$passwordMatch) {
                    Session::flash('error', 'Current password is incorrect');
                    $this->redirect('bank/reset-password');
                }

                // Hash the new password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                Database::query("UPDATE branch SET manager_password = ? WHERE branch_code = ?", [$hashedPassword, $branchCode]);

                Session::flash('success', 'Branch manager password changed successfully');
            } else {
                // Change agent password
                $agentCode = $this->post('agent_code');
                
                if (empty($agentCode)) {
                    Session::flash('error', 'Please select an agent');
                    $this->redirect('bank/reset-password');
                }
                
                // Verify agent belongs to this branch
                $agent = Database::fetchOne(
                    "SELECT agent_code FROM agent WHERE agent_code = ? AND branch_code = ?",
                    [$agentCode, $branchCode]
                );
                
                if (!$agent) {
                    Session::flash('error', 'Agent not found or does not belong to your branch');
                    $this->redirect('bank/reset-password');
                }

                // Update agent password (store as plain text as per original system)
                Database::query(
                    "UPDATE agent SET password = ? WHERE agent_code = ? AND branch_code = ?",
                    [$newPassword, $agentCode, $branchCode]
                );

                Session::flash('success', 'Agent password changed successfully');
            }

            $this->redirect('bank/dashboard');
        }

        $this->redirect('bank/reset-password');
    }
}

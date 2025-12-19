<?php
/**
 * Bank Authentication Controller
 */

class BankAuthController extends Controller
{
    public function showLogin()
    {
        if (Session::isLoggedIn() && Session::getUserRole() === 'bank') {
            $this->redirect('bank/dashboard');
        }

        echo $this->view('auth.bank-login');
    }

    public function login()
    {
        if ($this->isPost()) {
            $branchCode = $this->post('branch_code');
            $password = $this->post('manager_password');

            if (empty($branchCode) || empty($password)) {
                Session::flash('error', 'Please enter branch code and password');
                Session::flash('old_branch_code', $branchCode);
                $this->redirect('bank/login');
            }

            $sql = "SELECT * FROM branch WHERE branch_code = ? LIMIT 1";
            $branch = Database::fetchOne($sql, [$branchCode]);

            // Check password (supports both plain text and hashed)
            $passwordValid = false;
            if ($branch) {
                if (password_verify($password, $branch['manager_password'])) {
                    $passwordValid = true;
                } elseif ($password === $branch['manager_password']) {
                    $passwordValid = true;
                }
            }

            if ($passwordValid) {
                Session::login($branch['id'], 'bank', [
                    'branch_code' => $branch['branch_code'],
                    'name' => $branch['manager_name'] ?? 'Manager',
                    'branch_id' => $branch['id']
                ]);

                Session::flash('success', 'Login successful!');
                $this->redirect('bank/dashboard');
            } else {
                if (!$branch) {
                    Session::flash('error', 'Branch code not found. Please check and try again.');
                } else {
                    Session::flash('error', 'Invalid password. Please try again.');
                }
                Session::flash('old_branch_code', $branchCode);
                $this->redirect('bank/login');
            }
        }

        $this->redirect('bank/login');
    }

    public function logout()
    {
        Session::logout();
        Session::flash('success', 'Logged out successfully');
        $this->redirect('bank/login');
    }
}

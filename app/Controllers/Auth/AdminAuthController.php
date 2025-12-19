<?php
/**
 * Admin Authentication Controller
 */

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        // If already logged in, redirect to dashboard
        if (Session::isLoggedIn() && Session::getUserRole() === 'admin') {
            $this->redirect('admin/dashboard');
        }

        echo $this->view('auth.admin-login');
    }

    public function login()
    {
        if ($this->isPost()) {
            $username = $this->post('username');
            $password = $this->post('password');

            // Validate input
            if (empty($username) || empty($password)) {
                Session::flash('error', 'Please enter username and password');
                $this->redirect('admin/login');
            }

            // Check credentials in database
            $sql = "SELECT * FROM admin WHERE username = ? LIMIT 1";
            $admin = Database::fetchOne($sql, [$username]);

            // Check password (supports both plain text and hashed)
            $passwordValid = false;
            if ($admin) {
                // Try hashed password first
                if (password_verify($password, $admin['password'])) {
                    $passwordValid = true;
                } elseif ($password === $admin['password']) {
                    // Fallback to plain text comparison
                    $passwordValid = true;
                }
            }

            if ($passwordValid) {
                // Login successful
                Session::login($admin['admin_id'], 'admin', [
                    'username' => $admin['username'],
                    'name' => $admin['username']
                ]);

                Session::flash('success', 'Login successful!');
                $this->redirect('admin/dashboard');
            } else {
                Session::flash('error', 'Invalid username or password');
                $this->redirect('admin/login');
            }
        }

        $this->redirect('admin/login');
    }

    public function logout()
    {
        Session::logout();
        Session::flash('success', 'Logged out successfully');
        $this->redirect('admin/login');
    }
}

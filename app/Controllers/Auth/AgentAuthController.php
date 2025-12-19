<?php
/**
 * Agent Authentication Controller
 */

// Set Indian timezone
date_default_timezone_set('Asia/Kolkata');

class AgentAuthController extends Controller
{
    /**
     * Show agent login page
     */
    public function showLogin()
    {
        // If already logged in, redirect to dashboard
        if (Session::has('agent_code')) {
            redirect('agent/dashboard');
            return;
        }

        // Get remember me cookies if they exist
        $branch_code = $_COOKIE['branch_code'] ?? '';
        $agent_code = $_COOKIE['agent_code'] ?? '';
        $password = $_COOKIE['agent_password'] ?? '';

        $data = [
            'branch_code' => $branch_code,
            'agent_code' => $agent_code,
            'password' => $password,
            'error' => Session::getFlash('error', '')
        ];

        echo View::render('auth.agent-login', $data);
    }

    /**
     * Process agent login
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('agent/login');
            return;
        }

        $branch_code = $_POST['branch_code'] ?? '';
        $agent_code = $_POST['agent_code'] ?? '';
        $password = $_POST['password'] ?? '';
        $pin = $_POST['pin'] ?? '';
        $remember = isset($_POST['remember_me']);

        // Validate input
        if (empty($branch_code) || empty($agent_code) || empty($password) || empty($pin)) {
            Session::setFlash('error', 'All fields are required');
            redirect('agent/login');
            return;
        }

        // Validate login credentials with password and status
        $agent = Database::fetchOne(
            "SELECT agent_name, pin, pin_changed, status FROM agent WHERE branch_code = ? AND agent_code = ? AND password = ?",
            [$branch_code, $agent_code, $password]
        );

        if (!$agent) {
            Session::setFlash('error', 'Invalid login details');
            redirect('agent/login');
            return;
        }

        // Check if agent is active/enabled (check before PIN verification)
        $agentStatus = isset($agent['status']) ? (int)$agent['status'] : 1;
        if ($agentStatus === 0) {
            Session::setFlash('error', 'Your account has been disabled by the branch manager. Please contact your branch for assistance.');
            redirect('agent/login');
            return;
        }

        // Verify PIN
        if ($agent['pin'] != $pin) {
            Session::setFlash('error', 'Invalid PIN');
            redirect('agent/login');
            return;
        }

        // Handle Remember Me cookies
        if ($remember) {
            $cookieExpire = time() + (14 * 24 * 60 * 60); // 14 days
            setcookie('branch_code', $branch_code, $cookieExpire, '/');
            setcookie('agent_code', $agent_code, $cookieExpire, '/');
            setcookie('agent_password', $password, $cookieExpire, '/');
        } else {
            // Clear cookies if user did not select remember
            setcookie('branch_code', '', time() - 3600, '/');
            setcookie('agent_code', '', time() - 3600, '/');
            setcookie('agent_password', '', time() - 3600, '/');
        }

        // Check license status for the branch
        $license = Database::fetchOne(
            "SELECT licence_key, status, expiry_date FROM licence_management WHERE branch_code = ?",
            [$branch_code]
        );

        if (!$license) {
            Session::setFlash('error', 'No license found for this branch. Please contact your administrator.');
            redirect('agent/login');
            return;
        }

        $today = date('Y-m-d');
        $expiry_date = $license['expiry_date'];
        $license_status = $license['status'];

        // Calculate days until expiry
        $expiry_timestamp = strtotime($expiry_date);
        $today_timestamp = strtotime($today);
        $days_until_expiry = ($expiry_timestamp - $today_timestamp) / (60 * 60 * 24);

        // Check if license is expired
        if ($license_status == 0 || $today > $expiry_date) {
            Session::setFlash('error', 'Your branch license has expired. Please contact your administrator.');
            redirect('agent/login');
            return;
        }

        // Set basic session data
        Session::set('branch_code', $branch_code);
        Session::set('agent_code', $agent_code);
        Session::set('agent_name', $agent['agent_name']);
        Session::set('license_status', $license_status);

        // Check for license warning (expiring within 7 days)
        if ($days_until_expiry <= 7 && $days_until_expiry > 0) {
            Session::set('license_warning', true);
            Session::set('days_until_expiry', ceil($days_until_expiry));
        }

        // Check if PIN needs to be changed
        if ($agent['pin_changed'] == 0 || $agent['pin'] == '123456') {
            Session::set('force_pin_change', true);
            redirect('agent/change-pin');
            return;
        }

        // Check if license warning needs to be shown
        if (Session::has('license_warning') && Session::get('license_warning') == true) {
            redirect('agent/license-warning');
            return;
        }

        // Normal login - redirect to dashboard
        redirect('agent/dashboard');
    }

    /**
     * Logout agent
     */
    public function logout()
    {
        Session::destroy();
        redirect('agent/login');
    }

    /**
     * Show forgot PIN form
     */
    public function showForgotPin()
    {
        echo View::render('auth.agent-forgot-pin');
    }

    /**
     * Process forgot PIN request
     */
    public function processForgotPin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('agent/forgot-pin');
            return;
        }

        $branchCode = trim($_POST['branch_code'] ?? '');
        $agentCode = trim($_POST['agent_code'] ?? '');
        $password = $_POST['password'] ?? '';
        $email = trim($_POST['email'] ?? '');

        // Validate inputs
        if (empty($branchCode) || empty($agentCode) || empty($password) || empty($email)) {
            Session::setFlash('error', 'All fields are required');
            redirect('agent/forgot-pin');
            return;
        }

        // Verify agent details
        $agent = Database::fetchOne(
            "SELECT agent_code, agent_name, agent_email, branch_code FROM agent 
             WHERE branch_code = ? AND agent_code = ? AND password = ?",
            [$branchCode, $agentCode, $password]
        );

        if (!$agent) {
            Session::setFlash('error', 'Invalid credentials. Please check your details.');
            redirect('agent/forgot-pin');
            return;
        }

        // Verify email matches
        if (strtolower($agent['agent_email']) !== strtolower($email)) {
            Session::setFlash('error', 'Email does not match our records.');
            redirect('agent/forgot-pin');
            return;
        }

        // Get branch name
        $branch = Database::fetchOne(
            "SELECT branch_name FROM branch WHERE branch_code = ?",
            [$branchCode]
        );

        // Generate unique reset token
        $token = bin2hex(random_bytes(32));
        $expiryTime = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        // Store token in database (create pin_reset_tokens table if needed)
        Database::query(
            "INSERT INTO pin_reset_tokens (agent_code, branch_code, token, expiry, created_at) 
             VALUES (?, ?, ?, ?, NOW())",
            [$agentCode, $branchCode, $token, $expiryTime]
        );

        // Send email
        $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") 
                   . "://" . $_SERVER['HTTP_HOST'];
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $basePath = str_replace('\\', '/', dirname($scriptName));
        if ($basePath === '/') {
            $basePath = '';
        }
        
        $resetLink = $baseUrl . $basePath . '/agent/reset-pin/' . $token;
        $subject = "PIN Reset Request - Sookth Mobile Pigmy";
        
        // HTML Email Template
        $message = $this->getEmailTemplate([
            'agent_name' => $agent['agent_name'],
            'agent_code' => $agent['agent_code'],
            'branch_name' => $branch['branch_name'] ?? $branchCode,
            'branch_code' => $branchCode,
            'reset_link' => $resetLink,
            'expiry_minutes' => 10
        ]);

        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: Sookth Mobile Pigmy <noreply@sookthmobilepigmy.com>" . "\r\n";

        // Check if we're in development mode (localhost)
        $isLocalhost = in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1', 'localhost:8000', '127.0.0.1:8000']);
        
        if ($isLocalhost) {
            // Development mode - show link directly instead of sending email
            Session::set('dev_reset_link', $resetLink);
            Session::set('dev_reset_email', $email);
            Session::set('dev_reset_agent', $agent['agent_name']);
            Session::setFlash('success', 'Development Mode: Reset link generated. Click "View Reset Link" below.');
            redirect('agent/forgot-pin-dev-link');
        } else {
            // Production mode - send actual email
            if (mail($email, $subject, $message, $headers)) {
                Session::setFlash('success', 'Reset link has been sent to your email. Please check your inbox.');
                redirect('agent/login');
            } else {
                Session::setFlash('error', 'Failed to send email. Please try again later or contact your administrator.');
                redirect('agent/forgot-pin');
            }
        }
    }

    /**
     * Show development reset link (localhost only)
     */
    public function showDevResetLink()
    {
        $resetLink = Session::get('dev_reset_link');
        $email = Session::get('dev_reset_email');
        $agentName = Session::get('dev_reset_agent');
        
        if (!$resetLink) {
            Session::setFlash('error', 'No reset link found.');
            redirect('agent/forgot-pin');
            return;
        }
        
        echo View::render('auth.agent-dev-reset-link', [
            'reset_link' => $resetLink,
            'email' => $email,
            'agent_name' => $agentName
        ]);
    }

    /**
     * Show reset PIN form with token
     */
    public function showResetPinToken($token)
    {
        // Verify token
        $resetRequest = Database::fetchOne(
            "SELECT agent_code, branch_code, expiry FROM pin_reset_tokens 
             WHERE token = ? AND used = 0",
            [$token]
        );

        if (!$resetRequest) {
            Session::setFlash('error', 'Invalid or expired reset link.');
            redirect('agent/login');
            return;
        }

        // Check if token expired
        if (strtotime($resetRequest['expiry']) < time()) {
            Session::setFlash('error', 'This reset link has expired. Please request a new one.');
            redirect('agent/forgot-pin');
            return;
        }

        // Get agent details
        $agent = Database::fetchOne(
            "SELECT agent_code, agent_name, branch_code FROM agent 
             WHERE agent_code = ? AND branch_code = ?",
            [$resetRequest['agent_code'], $resetRequest['branch_code']]
        );

        if (!$agent) {
            Session::setFlash('error', 'Agent not found.');
            redirect('agent/login');
            return;
        }

        echo View::render('auth.agent-reset-pin-token', [
            'token' => $token,
            'agent' => $agent
        ]);
    }

    /**
     * Process reset PIN with token
     */
    public function processResetPinToken()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('agent/login');
            return;
        }

        $token = $_POST['token'] ?? '';
        $newPin = $_POST['new_pin'] ?? '';
        $confirmPin = $_POST['confirm_pin'] ?? '';

        // Validate inputs
        if (empty($token) || empty($newPin) || empty($confirmPin)) {
            Session::setFlash('error', 'All fields are required');
            redirect('agent/reset-pin/' . $token);
            return;
        }

        // Verify PINs match
        if ($newPin !== $confirmPin) {
            Session::setFlash('error', 'New PIN and Confirm PIN do not match');
            redirect('agent/reset-pin/' . $token);
            return;
        }

        // Validate PIN format
        if (strlen($newPin) !== 6 || !ctype_digit($newPin)) {
            Session::setFlash('error', 'PIN must be exactly 6 digits');
            redirect('agent/reset-pin/' . $token);
            return;
        }

        if ($newPin === '123456') {
            Session::setFlash('error', 'PIN cannot be 123456');
            redirect('agent/reset-pin/' . $token);
            return;
        }

        // Verify token
        $resetRequest = Database::fetchOne(
            "SELECT agent_code, branch_code, expiry FROM pin_reset_tokens 
             WHERE token = ? AND used = 0",
            [$token]
        );

        if (!$resetRequest) {
            Session::setFlash('error', 'Invalid or expired reset link');
            redirect('agent/login');
            return;
        }

        // Check if token expired
        if (strtotime($resetRequest['expiry']) < time()) {
            Session::setFlash('error', 'This reset link has expired. Please request a new one.');
            redirect('agent/forgot-pin');
            return;
        }

        // Update PIN
        Database::query(
            "UPDATE agent SET pin = ?, pin_changed = 1 WHERE agent_code = ? AND branch_code = ?",
            [$newPin, $resetRequest['agent_code'], $resetRequest['branch_code']]
        );

        // Mark token as used
        Database::query(
            "UPDATE pin_reset_tokens SET used = 1 WHERE token = ?",
            [$token]
        );

        Session::setFlash('success', 'Your PIN has been reset successfully. Please login with your new PIN.');
        redirect('agent/login');
    }

    /**
     * Get HTML email template for PIN reset
     */
    private function getEmailTemplate($data)
    {
        return '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIN Reset Request</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f4f4; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 28px;">Sookth Mobile Pigmy</h1>
                            <p style="color: #ffffff; margin: 5px 0 0 0; font-size: 14px;">by Sookth Solutions</p>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #333; margin: 0 0 20px 0; font-size: 24px;">PIN Reset Request</h2>
                            
                            <p style="color: #666; font-size: 16px; line-height: 1.6; margin: 0 0 15px 0;">
                                Hello <strong>' . htmlspecialchars($data['agent_name']) . '</strong>,
                            </p>
                            
                            <p style="color: #666; font-size: 16px; line-height: 1.6; margin: 0 0 15px 0;">
                                We received a request to reset your PIN. Click the button below to reset your PIN:
                            </p>
                            
                            <!-- Agent Details Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8f9fa; border-left: 4px solid #667eea; margin: 20px 0; border-radius: 5px;">
                                <tr>
                                    <td style="padding: 15px;">
                                        <p style="margin: 0 0 8px 0; color: #333; font-size: 14px;">
                                            <strong>Branch Name:</strong> ' . htmlspecialchars($data['branch_name']) . '
                                        </p>
                                        <p style="margin: 0 0 8px 0; color: #333; font-size: 14px;">
                                            <strong>Branch Code:</strong> ' . htmlspecialchars($data['branch_code']) . '
                                        </p>
                                        <p style="margin: 0; color: #333; font-size: 14px;">
                                            <strong>Agent Code:</strong> ' . htmlspecialchars($data['agent_code']) . '
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Reset Button -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="' . $data['reset_link'] . '" style="display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: #ffffff; text-decoration: none; border-radius: 8px; font-size: 16px; font-weight: bold;">
                                            Reset My PIN
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="color: #666; font-size: 14px; line-height: 1.6; margin: 20px 0 0 0;">
                                Or copy and paste this link into your browser:
                            </p>
                            <p style="color: #667eea; font-size: 13px; word-break: break-all; margin: 5px 0 20px 0;">
                                ' . $data['reset_link'] . '
                            </p>
                            
                            <!-- Warning Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #fff3cd; border-left: 4px solid #ffc107; margin: 20px 0; border-radius: 5px;">
                                <tr>
                                    <td style="padding: 15px;">
                                        <p style="margin: 0; color: #856404; font-size: 14px;">
                                            <strong>⚠️ Important:</strong> This link will expire in <strong>' . $data['expiry_minutes'] . ' minutes</strong> for security reasons.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="color: #666; font-size: 14px; line-height: 1.6; margin: 20px 0 0 0;">
                                If you did not request a PIN reset, please ignore this email or contact your branch manager immediately.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 20px 30px; text-align: center; border-radius: 0 0 10px 10px; border-top: 1px solid #e0e0e0;">
                            <p style="color: #999; font-size: 12px; margin: 0 0 5px 0;">
                                This is an automated email. Please do not reply.
                            </p>
                            <p style="color: #999; font-size: 12px; margin: 0;">
                                &copy; ' . date('Y') . ' Sookth Mobile Pigmy by Sookth Solutions. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';
    }}
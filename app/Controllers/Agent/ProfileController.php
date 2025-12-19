<?php
/**
 * Agent Profile Controller
 * Handles PIN changes and profile management
 */

class ProfileController extends Controller
{
    /**
     * Show change PIN form (forced - on first login)
     */
    public function showChangePin()
    {
        // Check if agent is logged in
        if (!Session::has('agent_code')) {
            Session::setFlash('error', 'Please login to continue');
            redirect('agent/login');
            return;
        }

        // Check if force PIN change is required
        if (!Session::has('force_pin_change') || !Session::get('force_pin_change')) {
            redirect('agent/dashboard');
            return;
        }

        $data = [
            'pageTitle' => 'Change PIN',
            'agent_code' => Session::get('agent_code'),
            'branch_code' => Session::get('branch_code'),
            'agent_name' => Session::get('agent_name')
        ];

        echo View::render('agent.change-pin', $data);
    }

    /**
     * Process forced PIN change
     */
    public function changePin()
    {
        // Check if agent is logged in
        if (!Session::has('agent_code')) {
            Session::setFlash('error', 'Please login to continue');
            redirect('agent/login');
            return;
        }

        // Validate request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('agent/change-pin');
            return;
        }

        $current_pin = $_POST['current_pin'] ?? '';
        $new_pin = $_POST['new_pin'] ?? '';
        $confirm_pin = $_POST['confirm_pin'] ?? '';
        
        $branch_code = Session::get('branch_code');
        $agent_code = Session::get('agent_code');

        // Validation
        $agent = Database::fetchOne(
            "SELECT pin FROM agent WHERE branch_code = ? AND agent_code = ?",
            [$branch_code, $agent_code]
        );

        if (!$agent) {
            Session::setFlash('error', 'Agent not found');
            redirect('agent/change-pin');
            return;
        }

        if ($agent['pin'] != $current_pin) {
            Session::setFlash('error', 'Current PIN is incorrect');
            redirect('agent/change-pin');
            return;
        }

        if ($new_pin != $confirm_pin) {
            Session::setFlash('error', 'New PIN and Confirm PIN do not match');
            redirect('agent/change-pin');
            return;
        }

        if (strlen($new_pin) != 6 || !ctype_digit($new_pin)) {
            Session::setFlash('error', 'PIN must be exactly 6 digits');
            redirect('agent/change-pin');
            return;
        }

        if ($new_pin == '123456') {
            Session::setFlash('error', 'New PIN cannot be the default PIN (123456)');
            redirect('agent/change-pin');
            return;
        }

        if ($new_pin == $current_pin) {
            Session::setFlash('error', 'New PIN must be different from current PIN');
            redirect('agent/change-pin');
            return;
        }

        // Update PIN
        Database::query(
            "UPDATE agent SET pin = ?, pin_changed = 1 WHERE branch_code = ? AND agent_code = ?",
            [$new_pin, $branch_code, $agent_code]
        );
        
        Session::delete('force_pin_change');
        Session::setFlash('success', 'PIN changed successfully!');
        
        // Check for license warning
        if (Session::has('license_warning') && Session::get('license_warning')) {
            redirect('agent/license-warning');
        } else {
            redirect('agent/dashboard');
        }
    }

    /**
     * Show reset PIN form (voluntary - from menu)
     */
    public function showResetPin()
    {
        // Check if agent is logged in
        if (!Session::has('agent_code')) {
            Session::setFlash('error', 'Please login to continue');
            redirect('agent/login');
            return;
        }

        $data = [
            'pageTitle' => 'Reset PIN',
            'agent_code' => Session::get('agent_code'),
            'branch_code' => Session::get('branch_code'),
            'agent_name' => Session::get('agent_name')
        ];

        echo View::render('agent.reset-pin', $data);
    }

    /**
     * Process voluntary PIN reset
     */
    public function resetPin()
    {
        // Check if agent is logged in
        if (!Session::has('agent_code')) {
            Session::setFlash('error', 'Please login to continue');
            redirect('agent/login');
            return;
        }

        // Validate request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('agent/reset-pin');
            return;
        }

        $current_pin = $_POST['current_pin'] ?? '';
        $new_pin = $_POST['new_pin'] ?? '';
        $confirm_pin = $_POST['confirm_pin'] ?? '';
        
        $branch_code = Session::get('branch_code');
        $agent_code = Session::get('agent_code');

        // Validation
        $agent = Database::fetchOne(
            "SELECT pin FROM agent WHERE branch_code = ? AND agent_code = ?",
            [$branch_code, $agent_code]
        );

        if (!$agent) {
            Session::setFlash('error', 'Agent not found');
            redirect('agent/reset-pin');
            return;
        }

        if ($agent['pin'] != $current_pin) {
            Session::setFlash('error', 'Current PIN is incorrect');
            redirect('agent/reset-pin');
            return;
        }

        if ($new_pin != $confirm_pin) {
            Session::setFlash('error', 'New PIN and Confirm PIN do not match');
            redirect('agent/reset-pin');
            return;
        }

        if (strlen($new_pin) != 6 || !ctype_digit($new_pin)) {
            Session::setFlash('error', 'PIN must be exactly 6 digits');
            redirect('agent/reset-pin');
            return;
        }

        if ($new_pin == $current_pin) {
            Session::setFlash('error', 'New PIN must be different from current PIN');
            redirect('agent/reset-pin');
            return;
        }

        // Update PIN
        Database::query(
            "UPDATE agent SET pin = ?, pin_changed = 1 WHERE branch_code = ? AND agent_code = ?",
            [$new_pin, $branch_code, $agent_code]
        );
        
        Session::setFlash('success', 'PIN reset successfully!');
        redirect('agent/dashboard');
    }
}

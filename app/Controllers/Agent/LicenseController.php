<?php
/**
 * Agent License Controller
 * Handles license warnings and checks
 */

class LicenseController extends Controller
{
    /**
     * Show license warning page
     */
    public function showWarning()
    {
        // Check if agent is logged in
        if (!Session::has('agent_code')) {
            Session::setFlash('error', 'Please login to continue');
            redirect('agent/login');
            return;
        }

        // Check if license warning is required
        if (!Session::has('license_warning') || !Session::get('license_warning')) {
            redirect('agent/dashboard');
            return;
        }

        $days_until_expiry = Session::get('days_until_expiry') ?? 0;
        $expiry_date = Session::get('license_expiry_date') ?? '';

        $data = [
            'pageTitle' => 'License Warning',
            'days_until_expiry' => $days_until_expiry,
            'expiry_date' => $expiry_date,
            'agent_name' => Session::get('agent_name'),
            'branch_code' => Session::get('branch_code')
        ];

        echo View::render('agent.license-warning', $data);
    }

    /**
     * Continue to dashboard after viewing warning
     */
    public function continueLogin()
    {
        // Check if agent is logged in
        if (!Session::has('agent_code')) {
            Session::setFlash('error', 'Please login to continue');
            redirect('agent/login');
            return;
        }

        // Validate request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('agent/license-warning');
            return;
        }

        // Clear license warning flag
        Session::delete('license_warning');

        // Redirect to dashboard
        redirect('agent/dashboard');
    }
}

<?php
/**
 * Agent Dashboard Controller
 */

// Set Indian timezone
date_default_timezone_set('Asia/Kolkata');

class DashboardController extends Controller
{
    /**
     * Show agent dashboard with pigmy collection interface
     */
    public function index()
    {
        // Check if agent is logged in
        if (!Session::has('agent_code')) {
            Session::setFlash('error', 'Please login to continue');
            redirect('agent/login');
            return;
        }

        $agent_code = Session::get('agent_code');
        $branch_code = Session::get('branch_code');
        $agent_name = Session::get('agent_name');

        // Check if license is expired (redirect to login if expired)
        $today = date('Y-m-d');
        $license = Database::fetchOne(
            "SELECT licence_key, status, expiry_date FROM licence_management WHERE branch_code = ?",
            [$branch_code]
        );

        if (!$license || $license['status'] == 0 || $today > $license['expiry_date']) {
            Session::destroy();
            redirect('agent/login?error=license_expired');
            return;
        }

        // Get branch name
        $branch = Database::fetchOne(
            "SELECT branch_name FROM branch WHERE branch_code = ?",
            [$branch_code]
        );
        $branch_name = $branch['branch_name'] ?? 'Unknown Branch';

        // Get all accounts for this agent
        $accounts = Database::fetchAll(
            "SELECT account_number, account_name, account_new_balance, account_opening_date 
             FROM accounts 
             WHERE branch_code = ? AND agent_code = ? 
             ORDER BY account_name ASC",
            [$branch_code, $agent_code]
        );

        // Get today's collection stats
        $today_date = date('Y-m-d');
        $todayStats = Database::fetchOne(
            "SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total 
             FROM transactions 
             WHERE agent_code = ? AND branch_code = ? AND DATE(date) = ?",
            [$agent_code, $branch_code, $today_date]
        );

        // Debug: Check if stats are null
        if (!$todayStats) {
            $todayStats = ['count' => 0, 'total' => 0];
        }

        // Get total collection stats
        $totalStats = Database::fetchOne(
            "SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total 
             FROM transactions 
             WHERE agent_code = ? AND branch_code = ?",
            [$agent_code, $branch_code]
        );

        // Debug: Check if stats are null
        if (!$totalStats) {
            $totalStats = ['count' => 0, 'total' => 0];
        }

        $data = [
            'agent_name' => $agent_name,
            'agent_code' => $agent_code,
            'branch_name' => $branch_name,
            'branch_code' => $branch_code,
            'accounts' => $accounts,
            'today_count' => $todayStats['count'] ?? 0,
            'today_total' => $todayStats['total'] ?? 0,
            'total_count' => $totalStats['count'] ?? 0,
            'total_amount' => $totalStats['total'] ?? 0
        ];

        echo View::render('agent.dashboard', $data);
    }
}

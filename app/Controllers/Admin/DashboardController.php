<?php
/**
 * Admin Dashboard Controller
 */

class DashboardController extends Controller
{
    public function __construct()
    {
        // Check if user is logged in as admin
        if (!Session::isLoggedIn() || Session::getUserRole() !== 'admin') {
            Session::flash('error', 'Please login to access admin area');
            header('Location: ' . $this->url('admin/login'));
            exit;
        }
    }

    public function index()
    {
        // Get dashboard statistics
        $stats = [
            'total_agents' => $this->getTotalAgents(),
            'total_branches' => $this->getTotalBranches(),
            'total_collections_today' => $this->getTodayCollections(),
            'total_customers' => $this->getTotalCustomers(),
        ];

        // Get all branches for settings dropdown
        $branches = Database::fetchAll("SELECT branch_code, branch_name FROM branch ORDER BY branch_name");

        $data = [
            'pageTitle' => 'Admin Dashboard',
            'stats' => $stats,
            'branches' => $branches,
            'userName' => Session::get('user_data')['name'] ?? 'Admin'
        ];

        echo $this->view('admin.dashboard', $data);
    }

    private function getTotalAgents()
    {
        $result = Database::fetchOne("SELECT COUNT(*) as count FROM agent");
        return $result['count'] ?? 0;
    }

    private function getTotalBranches()
    {
        $result = Database::fetchOne("SELECT COUNT(*) as count FROM branch");
        return $result['count'] ?? 0;
    }

    private function getTodayCollections()
    {
        $result = Database::fetchOne("SELECT SUM(amount) as total FROM transactions WHERE DATE(date) = CURDATE()");
        return $result['total'] ?? 0;
    }

    private function getTotalCustomers()
    {
        $result = Database::fetchOne("SELECT COUNT(DISTINCT account_number) as count FROM accounts");
        return $result['count'] ?? 0;
    }
}

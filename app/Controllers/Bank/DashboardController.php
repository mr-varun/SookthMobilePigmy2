<?php
/**
 * Bank Dashboard Controller
 */

class DashboardController extends Controller
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
        
        // Get overall statistics
        $stats = [
            'total_agents' => $this->getTotalAgents(),
            'total_collections_today' => $this->getTodayCollections(),
            'total_collections_month' => $this->getMonthCollections(),
            'total_customers' => $this->getTotalCustomers(),
        ];

        // Get top agents today
        $topAgents = Database::fetchAll("
            SELECT a.agent_name as name, a.agent_code, COUNT(t.id) as collection_count, SUM(t.amount) as total_amount
            FROM agent a
            LEFT JOIN transactions t ON a.agent_code = t.agent_code AND DATE(t.date) = CURDATE()
            WHERE a.branch_code = ?
            GROUP BY a.id
            ORDER BY total_amount DESC
            LIMIT 10
        ", [$branchCode]);

        // Get recent collections
        $recentCollections = Database::fetchAll("
            SELECT t.*, a.agent_name, t.account_name as customer_name, t.date as collection_date
            FROM transactions t
            LEFT JOIN agent a ON t.agent_code = a.agent_code
            WHERE t.branch_code = ?
            ORDER BY t.date DESC, t.time DESC
            LIMIT 20
        ", [$branchCode]);

        echo $this->view('bank.dashboard', [
            'pageTitle' => 'Bank Dashboard',
            'stats' => $stats,
            'topAgents' => $topAgents,
            'recentCollections' => $recentCollections
        ]);
    }

    private function getTotalAgents()
    {
        $branchCode = Session::get('user_data')['branch_code'] ?? '';
        $result = Database::fetchOne("SELECT COUNT(*) as count FROM agent WHERE branch_code = ?", [$branchCode]);
        return $result['count'] ?? 0;
    }

    private function getTodayCollections()
    {
        $branchCode = Session::get('user_data')['branch_code'] ?? '';
        $result = Database::fetchOne("SELECT SUM(amount) as total FROM transactions WHERE DATE(date) = CURDATE() AND branch_code = ?", [$branchCode]);
        return $result['total'] ?? 0;
    }

    private function getMonthCollections()
    {
        $branchCode = Session::get('user_data')['branch_code'] ?? '';
        $result = Database::fetchOne("SELECT SUM(amount) as total FROM transactions WHERE MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE()) AND branch_code = ?", [$branchCode]);
        return $result['total'] ?? 0;
    }

    private function getTotalCustomers()
    {
        $branchCode = Session::get('user_data')['branch_code'] ?? '';
        $result = Database::fetchOne("SELECT COUNT(*) as count FROM accounts WHERE branch_code = ?", [$branchCode]);
        return $result['count'] ?? 0;
    }
}

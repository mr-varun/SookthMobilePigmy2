<?php
/**
 * Bank Report Controller
 */

class ReportController extends Controller
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
        echo $this->view('bank.reports.index', [
            'pageTitle' => 'All Reports'
        ]);
    }

    public function agentTransactions()
    {
        $agentCode = $this->get('agent_code');
        $startDate = $this->get('start_date', date('Y-m-01'));
        $endDate = $this->get('end_date', date('Y-m-d'));

        $collections = Database::fetchAll("
            SELECT t.*, t.account_name as customer_name, t.account_number
            FROM transactions t
            WHERE t.agent_code = ? AND DATE(t.date) BETWEEN ? AND ?
            ORDER BY t.date DESC, t.time DESC
        ", [$agentCode, $startDate, $endDate]);

        $agent = Database::fetchOne("SELECT * FROM agent WHERE agent_code = ?", [$agentCode]);

        echo $this->view('bank.reports.agent-transactions', [
            'pageTitle' => 'Agent Transactions',
            'collections' => $collections,
            'agent' => $agent,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function detailedTransactions()
    {
        $branchCode = Session::get('user_data')['branch_code'] ?? '';
        $startDate = $this->get('start_date', date('Y-m-01'));
        $endDate = $this->get('end_date', date('Y-m-d'));

        $collections = Database::fetchAll("
            SELECT t.*, a.agent_name, a.agent_code, t.account_name as customer_name, t.account_number
            FROM transactions t
            LEFT JOIN agent a ON t.agent_code = a.agent_code
            WHERE t.branch_code = ? AND DATE(t.date) BETWEEN ? AND ?
            ORDER BY t.date DESC, t.time DESC
        ", [$branchCode, $startDate, $endDate]);

        echo $this->view('bank.reports.detailed-transactions', [
            'pageTitle' => 'Detailed Transactions',
            'collections' => $collections,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function exportAgentReport()
    {
        // Export logic would go here
        // For now, just show the report
        $this->agentTransactions();
    }

    public function printSummary()
    {
        $branchCode = Session::get('user_data')['branch_code'] ?? '';
        $date = $this->get('date', date('Y-m-d'));

        $summary = Database::fetchAll("
            SELECT a.agent_name as name, a.agent_code, COUNT(t.id) as collection_count, SUM(t.amount) as total_amount
            FROM agent a
            LEFT JOIN transactions t ON a.agent_code = t.agent_code AND DATE(t.date) = ?
            WHERE a.branch_code = ?
            GROUP BY a.id
            ORDER BY total_amount DESC
        ", [$date, $branchCode]);

        echo $this->view('bank.reports.print-summary', [
            'summary' => $summary,
            'date' => $date
        ]);
    }
}

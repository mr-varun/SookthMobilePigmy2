<?php
/**
 * Agent Pigmy Collection Controller
 */

// Set Indian timezone for all timestamps
date_default_timezone_set('Asia/Kolkata');

class PigmyController extends Controller
{
    /**
     * Show pigmy collection form
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
        $today = date('Y-m-d');
        $todayStats = Database::fetchOne(
            "SELECT COUNT(*) as count, COALESCE(SUM(amount), 0) as total 
             FROM transactions 
             WHERE agent_code = ? AND branch_code = ? AND DATE(date) = ?",
            [$agent_code, $branch_code, $today]
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

        echo View::render('agent.collection', $data);
    }

    /**
     * Save pigmy collection transaction
     */
    public function save()
    {
        // Check if agent is logged in
        if (!Session::has('agent_code')) {
            Session::setFlash('error', 'Please login to continue');
            redirect('agent/dashboard');
            return;
        }

        // Validate request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('agent/dashboard');
            return;
        }

        $agent_code = Session::get('agent_code');
        $branch_code = Session::get('branch_code');
        $agent_name = Session::get('agent_name');
        $account_number = $_POST['account'] ?? '';
        $credit_amount = $_POST['credit_amount'] ?? 0;

        // Validate inputs
        if (empty($account_number) || $credit_amount <= 0) {
            Session::setFlash('error', 'Please select account and enter valid amount');
            redirect('agent/dashboard');
            return;
        }

        // Fetch account details
        $account = Database::fetchOne(
            "SELECT account_name, account_new_balance, account_mobile 
             FROM accounts 
             WHERE account_number = ? AND branch_code = ? AND agent_code = ?",
            [$account_number, $branch_code, $agent_code]
        );

        if (!$account) {
            Session::setFlash('error', 'Account not found');
            redirect('agent/dashboard');
            return;
        }

        $account_name = $account['account_name'];
        $account_mobile = $account['account_mobile'] ?? '';
        $old_balance = $account['account_new_balance'];
        $new_balance = $old_balance + $credit_amount;

        // Generate unique transaction ID
        $transaction_id = "T" . time();

        try {
            // Insert transaction record
            Database::query(
                "INSERT INTO transactions 
                 (transaction_id, branch_code, agent_code, account_number, account_name, date, time, amount) 
                 VALUES (?, ?, ?, ?, ?, CURDATE(), CURTIME(), ?)",
                [$transaction_id, $branch_code, $agent_code, $account_number, $account_name, $credit_amount]
            );

            // Update account balance
            Database::query(
                "UPDATE accounts 
                 SET account_new_balance = ? 
                 WHERE account_number = ? AND branch_code = ? AND agent_code = ?",
                [$new_balance, $account_number, $branch_code, $agent_code]
            );

            // Store transaction details in session for success page
            Session::set('transaction_success', [
                'transaction_id' => $transaction_id,
                'account_number' => $account_number,
                'account_name' => $account_name,
                'account_mobile' => $account_mobile,
                'credit_amount' => $credit_amount,
                'new_balance' => $new_balance,
                'old_balance' => $old_balance,
                'branch_code' => $branch_code,
                'agent_code' => $agent_code,
                'agent_name' => $agent_name
            ]);

            // Redirect to success page
            redirect('agent/success');

        } catch (\Exception $e) {
            Session::setFlash('error', 'Transaction failed. Please try again.');
            redirect('agent/dashboard');
        }
    }

    /**
     * Show success page after transaction
     */
    public function success()
    {
        // Check if agent is logged in
        if (!Session::has('agent_code')) {
            Session::setFlash('error', 'Please login to continue');
            redirect('agent/login');
            return;
        }

        // Check if transaction data exists
        if (!Session::has('transaction_success')) {
            redirect('agent/dashboard');
            return;
        }

        $transaction = Session::get('transaction_success');
        
        // Get branch settings
        $branchSettings = Database::fetchOne(
            "SELECT printer_support, text_message, whatsapp_message FROM branch_settings WHERE branch_code = ?",
            [$transaction['branch_code']]
        );
        
        // Default all to disabled if no settings found
        if (!$branchSettings) {
            $branchSettings = [
                'printer_support' => 0,
                'text_message' => 0,
                'whatsapp_message' => 0
            ];
        }
        
        // Get branch name
        $branch = Database::fetchOne(
            "SELECT branch_name FROM branch WHERE branch_code = ?",
            [$transaction['branch_code']]
        );
        $branch_name = $branch['branch_name'] ?? 'Unknown Branch';
        
        // Get agent name
        $agent = Database::fetchOne(
            "SELECT agent_name FROM agent WHERE agent_code = ?",
            [$transaction['agent_code']]
        );
        $agent_name = $agent['agent_name'] ?? 'Agent';

        // Build transaction link (url() helper already includes base URL)
        $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
        $transaction_link = $base_url . '/customer/transactions/' . $transaction['agent_code'] . '_' . $transaction['branch_code'] . '_' . $transaction['account_number'] . '_' . intval($transaction['new_balance']);

        // Format date time with Indian timezone
        $date_time = date("d-m-Y h:i A");

        // Construct message
        $message = "Dear {$transaction['account_name']}, Rs. {$transaction['credit_amount']} has been collected by {$transaction['agent_name']} for {$branch_name} on {$date_time} with Transaction Id: {$transaction['transaction_id']} and Your total balance: Rs. {$transaction['new_balance']}. \nTo View transactions: {$transaction_link} \nThank you for banking with us! 🤝";

        // Create SMS and WhatsApp links only if enabled
        $sms_link = $branchSettings['text_message'] ? ("sms:" . $transaction['account_mobile'] . "?body=" . urlencode($message)) : '';
        $whatsapp_link = $branchSettings['whatsapp_message'] ? ("https://wa.me/91" . $transaction['account_mobile'] . "?text=" . urlencode($message)) : '';

        // Clear transaction data from session
        Session::delete('transaction_success');

        $data = [
            'transaction_id' => $transaction['transaction_id'],
            'account_number' => $transaction['account_number'],
            'account_name' => $transaction['account_name'],
            'credit_amount' => $transaction['credit_amount'],
            'new_balance' => $transaction['new_balance'],
            'branch_name' => $branch_name,
            'agent_name' => $agent_name,
            'sms_link' => $sms_link,
            'whatsapp_link' => $whatsapp_link,
            'printer_support' => $branchSettings['printer_support'],
            'text_message' => $branchSettings['text_message'],
            'whatsapp_message' => $branchSettings['whatsapp_message']
        ];

        echo View::render('agent.success', $data);
    }

    /**
     * Check if transaction already exists for account today (AJAX)
     */
    public function checkTransaction()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['exists' => false]);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $account_number = $data['account_number'] ?? '';
        $branch_code = $data['branch_code'] ?? '';
        $agent_code = $data['agent_code'] ?? '';
        $current_date = date('Y-m-d');

        if (empty($account_number) || empty($branch_code) || empty($agent_code)) {
            echo json_encode(['exists' => false]);
            return;
        }

        // Check if transaction exists today
        $result = Database::fetchOne(
            "SELECT SUM(amount) AS collected_amount 
             FROM transactions 
             WHERE account_number = ? AND branch_code = ? AND agent_code = ? AND DATE(date) = ?",
            [$account_number, $branch_code, $agent_code, $current_date]
        );

        if ($result && $result['collected_amount'] > 0) {
            echo json_encode([
                'exists' => true,
                'collected_amount' => $result['collected_amount']
            ]);
        } else {
            echo json_encode(['exists' => false]);
        }
    }
}


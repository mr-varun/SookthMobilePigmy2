<?php
/**
 * Agent Resend Controller
 * Handles resend message functionality for today's collections
 */

class ResendController extends Controller
{
    public function __construct()
    {
        if (!Session::has('agent_code')) {
            Session::flash('error', 'Please login to continue');
            $this->redirect('agent/login');
        }
    }

    /**
     * Display today's collections with resend option
     */
    public function index()
    {
        $agent_code = Session::get('agent_code');
        $branch_code = Session::get('branch_code');
        $agent_name = Session::get('agent_name');
        
        // Get branch name
        $branch = Database::fetchOne(
            "SELECT branch_name FROM branch WHERE branch_code = ?",
            [$branch_code]
        );
        $branch_name = $branch['branch_name'] ?? 'Unknown Branch';

        // Fetch today's collections only
        $today = date('Y-m-d');
        $collections = Database::fetchAll("
            SELECT 
                t.transaction_id,
                t.account_number,
                t.amount as credit_amount,
                t.date as date_time,
                a.account_name,
                a.account_mobile,
                a.account_new_balance
            FROM transactions t
            JOIN accounts a ON t.account_number = a.account_number 
                AND t.branch_code = a.branch_code 
                AND t.agent_code = a.agent_code
            WHERE t.agent_code = ? 
                AND t.branch_code = ? 
                AND DATE(t.date) = ?
            ORDER BY t.date DESC
        ", [$agent_code, $branch_code, $today]);

        $data = [
            'pageTitle' => 'Resend Messages',
            'agent_name' => $agent_name,
            'branch_name' => $branch_name,
            'collections' => $collections
        ];

        echo $this->view('agent.resend.index', $data);
    }

    /**
     * Process resend message request
     */
    public function send()
    {
        if (!$this->isPost()) {
            $this->redirect('agent/resend');
            return;
        }

        $account_number = $this->post('account_number');
        $credit_amount = $this->post('amount');
        $date_time = $this->post('date');

        $agent_code = Session::get('agent_code');
        $branch_code = Session::get('branch_code');
        $agent_name = Session::get('agent_name');

        // Get branch name
        $branch = Database::fetchOne(
            "SELECT branch_name FROM branch WHERE branch_code = ?",
            [$branch_code]
        );
        $branch_name = $branch['branch_name'] ?? 'Unknown Branch';
        
        // Get branch settings
        $branchSettings = Database::fetchOne(
            "SELECT printer_support, text_message, whatsapp_message, share_support FROM branch_settings WHERE branch_code = ?",
            [$branch_code]
        );
        
        if (!$branchSettings) {
            $branchSettings = [
                'printer_support' => 0,
                'text_message' => 0,
                'whatsapp_message' => 0,
                'share_support' => 0
            ];
        }

        // Fetch account details
        $account = Database::fetchOne("
            SELECT account_mobile, account_new_balance, account_name 
            FROM accounts 
            WHERE branch_code = ? AND agent_code = ? AND account_number = ?
        ", [$branch_code, $agent_code, $account_number]);

        if (!$account) {
            Session::flash('error', 'Account details not found');
            $this->redirect('agent/resend');
            return;
        }

        $account_mobile = $account['account_mobile'];
        $account_name = $account['account_name'];
        $new_balance = $account['account_new_balance'];

        // Generate transaction ID
        $transaction_id = $agent_code . '_' . $branch_code . '_' . $account_number . '_' . time();

        // Build transaction link
        $base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
        $transaction_link = $base_url . '/customer/transactions/' . $agent_code . '_' . $branch_code . '_' . $account_number . '_' . intval($new_balance);

        // Construct message
        $message = "Duplicate Message - Dear $account_name, Rs. $credit_amount has been collected by $agent_name for $branch_name on $date_time and Your total balance: Rs. $new_balance. \nTo View transactions: $transaction_link \nThank you for banking with us! 🤝";

        // Create SMS and WhatsApp links only if enabled
        $sms_link = $branchSettings['text_message'] ? ("sms:" . $account_mobile . "?body=" . urlencode($message)) : '';
        $whatsapp_link = $branchSettings['whatsapp_message'] ? ("https://wa.me/91" . $account_mobile . "?text=" . urlencode($message)) : '';

        $data = [
            'transaction_id' => $transaction_id,
            'account_number' => $account_number,
            'account_name' => $account_name,
            'account_mobile' => $account_mobile,
            'amount' => $credit_amount,
            'credit_amount' => $credit_amount,
            'new_balance' => $new_balance,
            'date_time' => $date_time,
            'branch_name' => $branch_name,
            'agent_name' => $agent_name,
            'sms_link' => $sms_link,
            'whatsapp_link' => $whatsapp_link,
            'message' => $message,
            'printer_support' => $branchSettings['printer_support'],
            'text_message' => $branchSettings['text_message'],
            'whatsapp_message' => $branchSettings['whatsapp_message'],
            'share_support' => $branchSettings['share_support']
        ];

        echo $this->view('agent.resend.send', $data);
    }
}

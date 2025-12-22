<?php
/**
 * Agent Report Controller
 * Handles all reporting functionality for agents
 */

// Set Indian timezone
date_default_timezone_set('Asia/Kolkata');

class ReportController extends Controller
{
    /**
     * Show main reports page with date range filter
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

        $transactions = [];
        $from_date = '';
        $to_date = '';
        $sort_by = 'date';
        $total_amount = 0;
        $total_count = 0;

        // Handle form submission or show today's transactions by default
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['from_date']) && isset($_POST['to_date'])) {
            $from_date = $_POST['from_date'];
            $to_date = $_POST['to_date'];
            $sort_by = $_POST['sort_by'] ?? 'date';
        } else {
            // Default: show today's transactions
            $from_date = date('Y-m-d');
            $to_date = date('Y-m-d');
            $sort_by = 'date';
        }

        // Set proper ORDER BY clause based on sort selection
        if ($sort_by === 'account_number') {
            $order_by = 'account_number ASC, date DESC, time DESC';
        } else {
            $order_by = 'date DESC, time DESC';
        }

        $results = Database::fetchAll(
            "SELECT * FROM transactions 
             WHERE agent_code = ? AND branch_code = ? AND date BETWEEN ? AND ? 
             ORDER BY $order_by",
            [$agent_code, $branch_code, $from_date, $to_date]
        );

        foreach ($results as $row) {
            $key = $row[$sort_by];
            $transactions[$key][] = $row;
            $total_amount += $row['amount'];
            $total_count++;
        }

        $data = [
            'agent_name' => $agent_name,
            'agent_code' => $agent_code,
            'branch_name' => $branch_name,
            'branch_code' => $branch_code,
            'transactions' => $transactions,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'sort_by' => $sort_by,
            'total_amount' => $total_amount,
            'total_count' => $total_count
        ];

        echo View::render('agent.reports.index', $data);
    }

    /**
     * Show daywise collection summary
     */
    public function daywise()
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

        // Fetch day-wise collection summary
        $daywise_data = Database::fetchAll("
            SELECT DATE(date) as transaction_date,
                   COUNT(id) as transaction_count,
                   SUM(amount) as daily_total
            FROM transactions
            WHERE agent_code = ? AND branch_code = ?
            GROUP BY DATE(date)
            ORDER BY transaction_date ASC
        ", [$agent_code, $branch_code]);

        $grand_total = 0;
        $total_transactions = 0;
        $total_days = count($daywise_data);

        foreach ($daywise_data as $row) {
            $grand_total += $row['daily_total'];
            $total_transactions += $row['transaction_count'];
        }

        $data = [
            'agent_name' => $agent_name,
            'agent_code' => $agent_code,
            'branch_name' => $branch_name,
            'branch_code' => $branch_code,
            'daywise_data' => $daywise_data,
            'grand_total' => $grand_total,
            'total_transactions' => $total_transactions,
            'total_days' => $total_days
        ];

        echo View::render('agent.reports.daywise', $data);
    }

    /**
     * Generate PDF for daywise collection report
     */
    public function printDaywise()
    {
        // Suppress deprecation warnings for dompdf compatibility
        error_reporting(E_ALL & ~E_DEPRECATED);
        
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

        // Fetch day-wise collection summary
        $daywise_data = Database::fetchAll("
            SELECT DATE(date) as transaction_date,
                   COUNT(id) as transaction_count,
                   SUM(amount) as daily_total
            FROM transactions
            WHERE agent_code = ? AND branch_code = ?
            GROUP BY DATE(date)
            ORDER BY transaction_date ASC
        ", [$agent_code, $branch_code]);

        $grand_total = 0;
        $total_transactions = 0;

        foreach ($daywise_data as $row) {
            $grand_total += $row['daily_total'];
            $total_transactions += $row['transaction_count'];
        }

        // Load DOMPDF
        require_once BASE_PATH . '/_/includes/dompdf/src/Autoloader.php';
        \Dompdf\Autoloader::register();

        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        $dompdf = new \Dompdf\Dompdf($options);

        // Generate HTML for PDF
        $html = $this->generateDaywisePdfHtml($agent_code, $agent_name, $branch_code, $branch_name, $daywise_data, $grand_total, $total_transactions);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("DCS_Report_" . date('d-m-Y') . ".pdf", array("Attachment" => false));
    }

    /**
     * Generate HTML for daywise PDF report
     */
    private function generateDaywisePdfHtml($agent_code, $agent_name, $branch_code, $branch_name, $daywise_data, $grand_total, $total_transactions)
    {
        $html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 15px; }
        h2 { text-align: center; margin: 0 0 15px 0; font-size: 18px; font-weight: bold; }
        .info-table { width: 100%; margin-bottom: 15px; border-collapse: collapse; }
        .info-table td { padding: 4px 8px; border: 1px solid #000; font-size: 11px; }
        .info-label { font-weight: bold; width: 30%; background-color: #f0f0f0; }
        h3 { text-align: center; margin: 15px 0 10px 0; font-size: 14px; font-weight: bold; }
        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .data-table th { background-color: #4CAF50; color: white; padding: 8px; text-align: center; border: 1px solid #000; font-size: 11px; }
        .data-table td { padding: 6px 8px; border: 1px solid #000; text-align: center; font-size: 11px; }
        .data-table tr:nth-child(even) { background-color: #f9f9f9; }
        .total-row { background-color: #FFEB3B !important; font-weight: bold; }
        .text-right { text-align: right; }
        </style></head><body>';

        $html .= '<h2>Agent Transaction Report</h2><br><br>';
        $html .= '<table class="info-table">';
        $html .= '<tr><td class="info-label">Agent Code:</td><td>' . htmlspecialchars($agent_code) . '</td></tr>';
        $html .= '<tr><td class="info-label">Agent Name:</td><td>' . htmlspecialchars($agent_name) . '</td></tr>';
        $html .= '<tr><td class="info-label">Branch Code:</td><td>' . htmlspecialchars($branch_code) . '</td></tr>';
        $html .= '<tr><td class="info-label">Branch Name:</td><td>' . htmlspecialchars($branch_name) . '</td></tr>';
        $html .= '<tr><td class="info-label">Report Date:</td><td>' . date('d-m-Y') . '</td></tr>';
        $html .= '</table>';

        $html .= '<h3>Day by Day Transaction Report</h3>';
        $html .= '<table class="data-table"><thead><tr>';
        $html .= '<th>#</th><th>Date</th><th>Day</th><th>Transaction Count</th><th>Daily Total (Rs.)</th>';
        $html .= '</tr></thead><tbody>';

        if (count($daywise_data) > 0) {
            $counter = 1;
            foreach ($daywise_data as $day) {
                $date = $day['transaction_date'];
                $day_name = date('l', strtotime($date));
                
                $html .= '<tr>';
                $html .= '<td>' . $counter++ . '</td>';
                $html .= '<td>' . date('d-m-Y', strtotime($date)) . '</td>';
                $html .= '<td>' . $day_name . '</td>';
                $html .= '<td>' . number_format($day['transaction_count']) . '</td>';
                $html .= '<td class="text-right">Rs. ' . number_format($day['daily_total'], 2) . '</td>';
                $html .= '</tr>';
            }
            
            $html .= '<tr class="total-row">';
            $html .= '<td colspan="3" class="text-right">Grand Total:</td>';
            $html .= '<td>' . number_format($total_transactions) . '</td>';
            $html .= '<td class="text-right">Rs. ' . number_format($grand_total, 2) . '</td>';
            $html .= '</tr>';
        } else {
            $html .= '<tr><td colspan="5">No collection data found</td></tr>';
        }

        $html .= '</tbody></table></body></html>';

        return $html;
    }

    /**
     * Resend SMS/WhatsApp message for a transaction
     */
    public function resendMessage()
    {
        // Check if agent is logged in
        if (!Session::has('agent_code')) {
            Session::setFlash('error', 'Please login to continue');
            redirect('agent/login');
            return;
        }

        // Validate request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('agent/reports');
            return;
        }

        $account_number = $_POST['account_number'] ?? '';
        $credit_amount = $_POST['amount'] ?? '';
        $date_time = $_POST['date'] ?? '';

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
        
        // Default all to disabled if no settings found
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
            Session::setFlash('error', 'Account details not found');
            redirect('agent/reports');
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

        echo View::render('agent.reports.resend-message', $data);
    }
}

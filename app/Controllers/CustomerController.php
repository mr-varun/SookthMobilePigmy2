<?php
/**
 * Customer Controller
 * Handles customer transaction viewing
 */

class CustomerController extends Controller
{
    public function transactions($customerId)
    {
        // Parse the ID parameter (format: {agent_code}_{branch_code}_{account_number}_{new_balance})
        $parts = explode('_', $customerId);
        
        if (count($parts) < 4) {
            http_response_code(404);
            echo "Invalid customer ID format";
            exit;
        }
        
        $agent_code = $parts[0];
        $branch_code = $parts[1];
        $account_number = $parts[2];
        
        // Fetch customer details from accounts table with branch and agent info
        $customer = Database::fetchOne(
            "SELECT 
                acc.*,
                b.branch_name,
                a.agent_name
            FROM accounts acc
            LEFT JOIN branch b ON acc.branch_code = b.branch_code
            LEFT JOIN agent a ON acc.agent_code = a.agent_code AND acc.branch_code = a.branch_code
            WHERE acc.branch_code = ? AND acc.agent_code = ? AND acc.account_number = ?", 
            [$branch_code, $agent_code, $account_number]
        );
        
        if (!$customer) {
            http_response_code(404);
            echo "Customer account not found";
            exit;
        }

        // Fetch all transactions for this customer from both tables
        $transactions = Database::fetchAll("
            SELECT t.*, a.agent_name
            FROM transactions t
            LEFT JOIN agent a ON t.agent_code = a.agent_code AND t.branch_code = a.branch_code
            WHERE t.branch_code = ? AND t.agent_code = ? AND t.account_number = ?
            
            UNION ALL
            
            SELECT 
                bt.id,
                bt.transaction_id,
                bt.branch_code,
                bt.agent_code,
                bt.account_number,
                bt.account_name,
                bt.date,
                bt.time,
                bt.amount,
                0 as status,
                bt.agent_name
            FROM backuptransaction bt
            WHERE bt.branch_code = ? AND bt.agent_code = ? AND bt.account_number = ?
            
            ORDER BY date DESC, time DESC
        ", [$branch_code, $agent_code, $account_number, $branch_code, $agent_code, $account_number]);

        // Calculate total collection from both tables
        $total = Database::fetchOne("
            SELECT 
                (SELECT COALESCE(SUM(amount), 0) FROM transactions WHERE branch_code = ? AND agent_code = ? AND account_number = ?) +
                (SELECT COALESCE(SUM(amount), 0) FROM backuptransaction WHERE branch_code = ? AND agent_code = ? AND account_number = ?) as total,
                (SELECT COUNT(*) FROM transactions WHERE branch_code = ? AND agent_code = ? AND account_number = ?) +
                (SELECT COUNT(*) FROM backuptransaction WHERE branch_code = ? AND agent_code = ? AND account_number = ?) as count
        ", [
            $branch_code, $agent_code, $account_number,
            $branch_code, $agent_code, $account_number,
            $branch_code, $agent_code, $account_number,
            $branch_code, $agent_code, $account_number
        ]);

        echo $this->view('customer.transactions', [
            'pageTitle' => 'Transaction History',
            'customer' => $customer,
            'transactions' => $transactions,
            'total' => $total
        ]);
    }
}

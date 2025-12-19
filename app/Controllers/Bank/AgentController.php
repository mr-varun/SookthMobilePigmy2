<?php
/**
 * Bank Agent Controller
 */

class AgentController extends Controller
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
        
        $agents = Database::fetchAll("
            SELECT a.*, b.branch_name,
                   (SELECT SUM(amount) FROM transactions WHERE agent_code = a.agent_code AND DATE(date) = CURDATE()) as today_collection,
                   (SELECT SUM(amount) FROM transactions WHERE agent_code = a.agent_code AND MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())) as month_collection,
                   (SELECT COUNT(*) FROM accounts WHERE agent_code = a.agent_code) as customer_count
            FROM agent a
            LEFT JOIN branch b ON a.branch_code = b.branch_code
            WHERE a.branch_code = ?
            ORDER BY a.agent_name
        ", [$branchCode]);

        echo $this->view('bank.agents.index', [
            'pageTitle' => 'Manage Agents',
            'agents' => $agents
        ]);
    }

    public function toggleStatus()
    {
        if ($this->isPost()) {
            $branchCode = Session::get('user_data')['branch_code'] ?? '';
            $agentCode = $this->post('agent_code');
            $currentStatus = $this->post('current_status');
            
            // Toggle status (1 -> 0, 0 -> 1)
            $newStatus = ($currentStatus == 1) ? 0 : 1;
            
            // Verify agent belongs to this branch before updating
            $agent = Database::fetchOne(
                "SELECT agent_code FROM agent WHERE agent_code = ? AND branch_code = ?",
                [$agentCode, $branchCode]
            );
            
            if (!$agent) {
                Session::flash('error', 'Agent not found or does not belong to your branch');
                $this->redirect('bank/agents');
                return;
            }
            
            // Update status
            Database::query(
                "UPDATE agent SET status = ? WHERE agent_code = ? AND branch_code = ?",
                [$newStatus, $agentCode, $branchCode]
            );
            
            $statusText = ($newStatus == 1) ? 'enabled' : 'disabled';
            Session::flash('success', "Agent has been {$statusText} successfully");
            $this->redirect('bank/agents');
        }
        
        $this->redirect('bank/agents');
    }
}

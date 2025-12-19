<?php
/**
 * Admin Agent Controller
 * Handles agent management operations
 */

class AgentController extends Controller
{
    public function __construct()
    {
        // Check admin authentication
        if (!Session::isLoggedIn() || Session::getUserRole() !== 'admin') {
            Session::flash('error', 'Please login as admin');
            $this->redirect('admin/login');
        }
    }

    /**
     * Display agent list
     */
    public function index()
    {
        $agents = Database::fetchAll("
            SELECT a.*, b.branch_name 
            FROM agent a 
            LEFT JOIN branch b ON a.branch_code = b.branch_code 
            ORDER BY a.agent_code DESC
        ");

        echo $this->view('admin.agents.index', [
            'pageTitle' => 'Manage Agents',
            'agents' => $agents
        ]);
    }

    /**
     * Show add agent form
     */
    public function create()
    {
        $branches = Database::fetchAll("SELECT * FROM branch ORDER BY branch_name");
        
        echo $this->view('admin.agents.create', [
            'pageTitle' => 'Add New Agent',
            'branches' => $branches
        ]);
    }

    /**
     * Store new agent
     */
    public function store()
    {
        if ($this->isPost()) {
            $agentCode = $this->post('agent_code');
            $name = $this->post('name');
            $mobile = $this->post('mobile');
            $branchId = $this->post('branch_id');
            $pin = $this->post('pin');

            // Validate
            if (empty($agentCode) || empty($name) || empty($pin)) {
                Session::flash('error', 'All fields are required');
                $this->redirect('admin/agents/add');
            }

            // Check if agent code exists
            $exists = Database::fetchOne("SELECT id FROM agents WHERE agent_code = ?", [$agentCode]);
            if ($exists) {
                Session::flash('error', 'Agent code already exists');
                $this->redirect('admin/agents/add');
            }

            // Insert agent
            $hashedPin = password_hash($pin, PASSWORD_DEFAULT);
            Database::insert("
                INSERT INTO agents (agent_code, name, mobile, branch_id, pin, status, created_at) 
                VALUES (?, ?, ?, ?, ?, 'active', NOW())
            ", [$agentCode, $name, $mobile, $branchId, $hashedPin]);

            Session::flash('success', 'Agent added successfully');
            $this->redirect('admin/agents');
        }

        $this->redirect('admin/agents/add');
    }

    /**
     * Show edit agent form
     */
    public function edit($id)
    {
        $agent = Database::fetchOne("SELECT * FROM agent WHERE agent_code = ?", [$id]);
        if (!$agent) {
            Session::flash('error', 'Agent not found');
            $this->redirect('admin/agents');
        }

        $branches = Database::fetchAll("SELECT * FROM branch ORDER BY branch_name");

        echo $this->view('admin.agents.edit', [
            'pageTitle' => 'Edit Agent',
            'agent' => $agent,
            'branches' => $branches
        ]);
    }

    /**
     * Update agent
     */
    public function update($id)
    {
        if ($this->isPost()) {
            $agentName = trim($this->post('agent_name'));
            $agentMobile = trim($this->post('agent_mobile'));
            $agentEmail = trim($this->post('agent_email'));
            $pin = trim($this->post('pin'));
            $branchCode = $this->post('branch_code');

            // Validate PIN
            if (strlen($pin) !== 6 || !ctype_digit($pin)) {
                Session::flash('error', 'PIN must be exactly 6 digits');
                $this->redirect('admin/agents/edit/' . $id);
            }

            Database::query("
                UPDATE agent 
                SET agent_name = ?, agent_mobile = ?, agent_email = ?, pin = ? 
                WHERE agent_code = ?
            ", [$agentName, $agentMobile, $agentEmail, $pin, $id]);

            Session::flash('success', 'Agent updated successfully');
            $this->redirect('admin/agents');
        }

        $this->redirect('admin/agents/edit/' . $id);
    }

    /**
     * Delete agent
     */
    public function delete($id)
    {
        if ($this->isPost()) {
            Database::query("DELETE FROM agent WHERE agent_code = ?", [$id]);
            Session::flash('success', 'Agent deleted successfully');
        }

        $this->redirect('admin/agents');
    }

    /**
     * Check if agent code exists (AJAX)
     */
    public function checkCode()
    {
        if ($this->isPost()) {
            $code = $this->post('code');
            $exists = Database::fetchOne("SELECT agent_code FROM agent WHERE agent_code = ?", [$code]);
            $this->json(['exists' => !empty($exists)]);
        }
    }
}

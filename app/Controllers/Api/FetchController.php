<?php
/**
 * API Fetch Controller
 * Handles AJAX requests for fetching data
 */

class FetchController extends Controller
{
    public function agent()
    {
        header('Content-Type: application/json');
        
        if ($this->isPost()) {
            $agentCode = $this->post('agent_code');
            $branchCode = $this->post('branch_code');
            
            if (empty($agentCode)) {
                echo json_encode(['success' => false, 'message' => 'Agent code is required']);
                return;
            }
            
            // If branch code is provided, search with both filters for more accurate results
            if (!empty($branchCode)) {
                $agent = Database::fetchOne(
                    "SELECT agent_code, agent_name, agent_mobile, agent_email, branch_code, status FROM agent WHERE agent_code = ? AND branch_code = ?", 
                    [$agentCode, $branchCode]
                );
            } else {
                $agent = Database::fetchOne(
                    "SELECT agent_code, agent_name, agent_mobile, agent_email, branch_code, status FROM agent WHERE agent_code = ?", 
                    [$agentCode]
                );
            }
            
            if ($agent) {
                echo json_encode([
                    'success' => true,
                    'agent' => $agent
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Agent not found'
                ]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    public function branch()
    {
        header('Content-Type: application/json');
        
        if ($this->isPost()) {
            $branchCode = $this->post('branch_code');
            
            if (empty($branchCode)) {
                echo json_encode(['success' => false, 'message' => 'Branch code is required']);
                return;
            }
            
            $branch = Database::fetchOne("SELECT branch_code, branch_name, manager_name, manager_mobile, manager_email FROM branch WHERE branch_code = ?", [$branchCode]);
            
            if ($branch) {
                echo json_encode([
                    'success' => true,
                    'branch' => $branch
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Branch not found'
                ]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    public function manager()
    {
        header('Content-Type: application/json');
        
        if ($this->isPost()) {
            $branchCode = $this->post('branch_code');
            
            if (empty($branchCode)) {
                echo json_encode(['success' => false, 'message' => 'Branch code is required']);
                return;
            }
            
            $manager = Database::fetchOne("SELECT manager_name, manager_mobile, manager_email FROM branch WHERE branch_code = ?", [$branchCode]);
            
            if ($manager) {
                echo json_encode([
                    'success' => true,
                    'manager' => $manager
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Manager not found'
                ]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }
}

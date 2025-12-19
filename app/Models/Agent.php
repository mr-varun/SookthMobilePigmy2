<?php
/**
 * Agent Model
 * Handles agent-related database operations
 */

require_once APP_PATH . '/Models/Model.php';

class Agent extends Model
{
    protected $table = 'agent';

    /**
     * Get agent by code
     */
    public function findByCode($code)
    {
        return $this->whereOne('agent_code', $code);
    }

    /**
     * Get active agents
     */
    public function getActive()
    {
        return $this->where('status', 'active');
    }

    /**
     * Get agents by branch
     */
    public function getByBranch($branchId)
    {
        return $this->where('branch_id', $branchId);
    }

    /**
     * Verify PIN
     */
    public function verifyPin($agentId, $pin)
    {
        $agent = $this->find($agentId);
        if (!$agent) {
            return false;
        }
        return password_verify($pin, $agent['pin']);
    }

    /**
     * Update PIN
     */
    public function updatePin($agentId, $newPin)
    {
        $hashedPin = password_hash($newPin, PASSWORD_DEFAULT);
        return $this->update($agentId, ['pin' => $hashedPin]);
    }

    /**
     * Get agent statistics
     */
    public function getStats($agentId)
    {
        $sql = "SELECT 
                    COUNT(*) as total_collections,
                    SUM(amount) as total_amount,
                    COUNT(DISTINCT customer_id) as total_customers
                FROM pigmy_collections 
                WHERE agent_id = ?";
        
        return Database::fetchOne($sql, [$agentId]);
    }

    /**
     * Get today's collections for agent
     */
    public function getTodayCollections($agentId)
    {
        $sql = "SELECT * FROM pigmy_collections 
                WHERE agent_id = ? 
                AND DATE(collection_date) = CURDATE()
                ORDER BY collection_date DESC";
        
        return Database::fetchAll($sql, [$agentId]);
    }
}

<?php
/**
 * Branch Model
 * Handles branch-related database operations
 */

require_once APP_PATH . '/Models/Model.php';

class Branch extends Model
{
    protected $table = 'branch';

    /**
     * Get branch by code
     */
    public function findByCode($code)
    {
        return $this->whereOne('branch_code', $code);
    }

    /**
     * Get active branches
     */
    public function getActive()
    {
        return $this->where('status', 'active');
    }

    /**
     * Get branch with agent count
     */
    public function getBranchWithAgentCount($branchId)
    {
        $sql = "SELECT b.*, COUNT(a.id) as agent_count 
                FROM branches b 
                LEFT JOIN agents a ON b.id = a.branch_id AND a.status = 'active'
                WHERE b.id = ?
                GROUP BY b.id";
        
        return Database::fetchOne($sql, [$branchId]);
    }

    /**
     * Get all branches with statistics
     */
    public function getAllWithStats()
    {
        $sql = "SELECT b.*, 
                    COUNT(DISTINCT a.id) as agent_count,
                    COUNT(pc.id) as collection_count,
                    SUM(pc.amount) as total_amount
                FROM branches b
                LEFT JOIN agents a ON b.id = a.branch_id AND a.status = 'active'
                LEFT JOIN pigmy_collections pc ON a.id = pc.agent_id
                WHERE b.status = 'active'
                GROUP BY b.id
                ORDER BY b.branch_name";
        
        return Database::fetchAll($sql);
    }
}

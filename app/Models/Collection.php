<?php
/**
 * Collection Model
 * Handles pigmy collection database operations
 */

require_once APP_PATH . '/Models/Model.php';

class Collection extends Model
{
    protected $table = 'transactions';

    /**
     * Get collections by agent
     */
    public function getByAgent($agentId, $startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            $sql = "SELECT * FROM pigmy_collections 
                    WHERE agent_id = ? 
                    AND DATE(collection_date) BETWEEN ? AND ?
                    ORDER BY collection_date DESC";
            return Database::fetchAll($sql, [$agentId, $startDate, $endDate]);
        }
        
        return $this->where('agent_id', $agentId);
    }

    /**
     * Get collections by customer
     */
    public function getByCustomer($customerId, $limit = null)
    {
        $sql = "SELECT * FROM pigmy_collections 
                WHERE customer_id = ? 
                ORDER BY collection_date DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . intval($limit);
        }
        
        return Database::fetchAll($sql, [$customerId]);
    }

    /**
     * Get today's collections
     */
    public function getTodayCollections($agentId = null)
    {
        if ($agentId) {
            $sql = "SELECT * FROM pigmy_collections 
                    WHERE agent_id = ? AND DATE(collection_date) = CURDATE()
                    ORDER BY collection_date DESC";
            return Database::fetchAll($sql, [$agentId]);
        }
        
        $sql = "SELECT * FROM pigmy_collections 
                WHERE DATE(collection_date) = CURDATE()
                ORDER BY collection_date DESC";
        return Database::fetchAll($sql);
    }

    /**
     * Get collection statistics
     */
    public function getStats($agentId = null, $startDate = null, $endDate = null)
    {
        $params = [];
        $sql = "SELECT 
                    COUNT(*) as total_count,
                    SUM(amount) as total_amount,
                    AVG(amount) as avg_amount,
                    MIN(amount) as min_amount,
                    MAX(amount) as max_amount,
                    COUNT(DISTINCT customer_id) as unique_customers
                FROM pigmy_collections WHERE 1=1";
        
        if ($agentId) {
            $sql .= " AND agent_id = ?";
            $params[] = $agentId;
        }
        
        if ($startDate && $endDate) {
            $sql .= " AND DATE(collection_date) BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
        }
        
        return Database::fetchOne($sql, $params);
    }

    /**
     * Get collections with full details
     */
    public function getWithDetails($filters = [])
    {
        $sql = "SELECT pc.*, 
                    a.name as agent_name, a.agent_code,
                    c.name as customer_name, c.account_no, c.mobile as customer_mobile,
                    b.branch_name
                FROM pigmy_collections pc
                LEFT JOIN agents a ON pc.agent_id = a.id
                LEFT JOIN customers c ON pc.customer_id = c.id
                LEFT JOIN branches b ON a.branch_id = b.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($filters['agent_id'])) {
            $sql .= " AND pc.agent_id = ?";
            $params[] = $filters['agent_id'];
        }
        
        if (!empty($filters['customer_id'])) {
            $sql .= " AND pc.customer_id = ?";
            $params[] = $filters['customer_id'];
        }
        
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $sql .= " AND DATE(pc.collection_date) BETWEEN ? AND ?";
            $params[] = $filters['start_date'];
            $params[] = $filters['end_date'];
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND pc.status = ?";
            $params[] = $filters['status'];
        }
        
        $sql .= " ORDER BY pc.collection_date DESC";
        
        if (!empty($filters['limit'])) {
            $sql .= " LIMIT " . intval($filters['limit']);
        }
        
        return Database::fetchAll($sql, $params);
    }

    /**
     * Get daily collection summary
     */
    public function getDailySummary($startDate, $endDate)
    {
        $sql = "SELECT 
                    DATE(collection_date) as date,
                    COUNT(*) as count,
                    SUM(amount) as total
                FROM pigmy_collections
                WHERE DATE(collection_date) BETWEEN ? AND ?
                GROUP BY DATE(collection_date)
                ORDER BY date DESC";
        
        return Database::fetchAll($sql, [$startDate, $endDate]);
    }

    /**
     * Get agent-wise summary
     */
    public function getAgentSummary($startDate = null, $endDate = null)
    {
        $sql = "SELECT 
                    a.id, a.name, a.agent_code, b.branch_name,
                    COUNT(pc.id) as collection_count,
                    SUM(pc.amount) as total_amount
                FROM agents a
                LEFT JOIN pigmy_collections pc ON a.id = pc.agent_id";
        
        $params = [];
        
        if ($startDate && $endDate) {
            $sql .= " AND DATE(pc.collection_date) BETWEEN ? AND ?";
            $params[] = $startDate;
            $params[] = $endDate;
        }
        
        $sql .= " LEFT JOIN branches b ON a.branch_id = b.id
                  WHERE a.status = 'active'
                  GROUP BY a.id
                  ORDER BY total_amount DESC";
        
        return Database::fetchAll($sql, $params);
    }
}

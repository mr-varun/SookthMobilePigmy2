<?php
/**
 * Customer Model
 * Handles customer-related database operations
 */

require_once APP_PATH . '/Models/Model.php';

class Customer extends Model
{
    protected $table = 'accounts';

    /**
     * Get customer by account number
     */
    public function findByAccountNo($accountNo)
    {
        return $this->whereOne('account_no', $accountNo);
    }

    /**
     * Get customer by mobile
     */
    public function findByMobile($mobile)
    {
        return $this->whereOne('mobile', $mobile);
    }

    /**
     * Get active customers
     */
    public function getActive()
    {
        return $this->where('status', 'active');
    }

    /**
     * Get customer with transaction summary
     */
    public function getCustomerWithTransactionSummary($customerId)
    {
        $sql = "SELECT c.*,
                    COUNT(pc.id) as total_transactions,
                    SUM(pc.amount) as total_amount,
                    MAX(pc.collection_date) as last_collection_date
                FROM customers c
                LEFT JOIN pigmy_collections pc ON c.id = pc.customer_id
                WHERE c.id = ?
                GROUP BY c.id";
        
        return Database::fetchOne($sql, [$customerId]);
    }

    /**
     * Get customers by agent
     */
    public function getByAgent($agentId)
    {
        $sql = "SELECT DISTINCT c.*
                FROM customers c
                INNER JOIN pigmy_collections pc ON c.id = pc.customer_id
                WHERE pc.agent_id = ?
                ORDER BY c.name";
        
        return Database::fetchAll($sql, [$agentId]);
    }

    /**
     * Search customers
     */
    public function search($keyword)
    {
        $sql = "SELECT * FROM customers 
                WHERE (name LIKE ? OR account_no LIKE ? OR mobile LIKE ?)
                AND status = 'active'
                LIMIT 50";
        
        $searchTerm = "%{$keyword}%";
        return Database::fetchAll($sql, [$searchTerm, $searchTerm, $searchTerm]);
    }
}

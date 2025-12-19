<?php
/**
 * Base Model Class
 * All models should extend this class
 */

class Model
{
    protected $table;
    protected $primaryKey = 'id';

    /**
     * Find record by ID
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? LIMIT 1";
        return Database::fetchOne($sql, [$id]);
    }

    /**
     * Find all records
     */
    public function all($orderBy = null)
    {
        $sql = "SELECT * FROM {$this->table}";
        
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        return Database::fetchAll($sql);
    }

    /**
     * Find records with condition
     */
    public function where($column, $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = ?";
        return Database::fetchAll($sql, [$value]);
    }

    /**
     * Find one record with condition
     */
    public function whereOne($column, $value)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1";
        return Database::fetchOne($sql, [$value]);
    }

    /**
     * Insert a record
     */
    public function insert($data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        
        return Database::insert($sql, array_values($data));
    }

    /**
     * Update a record
     */
    public function update($id, $data)
    {
        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = "{$column} = ?";
        }
        $setString = implode(', ', $sets);
        
        $sql = "UPDATE {$this->table} SET {$setString} WHERE {$this->primaryKey} = ?";
        
        $params = array_values($data);
        $params[] = $id;
        
        Database::query($sql, $params);
        return true;
    }

    /**
     * Delete a record
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        Database::query($sql, [$id]);
        return true;
    }

    /**
     * Count records
     */
    public function count($where = null, $params = [])
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        
        $result = Database::fetchOne($sql, $params);
        return $result['count'] ?? 0;
    }

    /**
     * Execute custom query
     */
    public function query($sql, $params = [])
    {
        return Database::query($sql, $params);
    }
}

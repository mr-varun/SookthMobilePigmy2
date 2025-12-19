<?php
/**
 * Database Class
 * Handles database connections and queries
 */
class Database
{
    private static $connection = null;

    /**
     * Connect to database
     */
    public static function connect()
    {
        if (self::$connection === null) {
            $config = require CONFIG_PATH . '/database.php';
            
            self::$connection = new mysqli(
                $config['host'],
                $config['username'],
                $config['password'],
                $config['database'],
                $config['port']
            );

            if (self::$connection->connect_error) {
                die("Database Connection Failed: " . self::$connection->connect_error);
            }

            // Set charset
            self::$connection->set_charset($config['charset']);
        }

        return self::$connection;
    }

    /**
     * Get connection instance
     */
    public static function getConnection()
    {
        if (self::$connection === null) {
            self::connect();
        }
        return self::$connection;
    }

    /**
     * Execute a query
     */
    public static function query($sql, $params = [])
    {
        $conn = self::getConnection();
        
        if (empty($params)) {
            return $conn->query($sql);
        }

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }

        if (!empty($params)) {
            $types = '';
            $values = [];
            
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
                $values[] = $param;
            }

            $stmt->bind_param($types, ...$values);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        return $result;
    }

    /**
     * Fetch all results
     */
    public static function fetchAll($sql, $params = [])
    {
        $result = self::query($sql, $params);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Fetch single result
     */
    public static function fetchOne($sql, $params = [])
    {
        $result = self::query($sql, $params);
        return $result ? $result->fetch_assoc() : null;
    }

    /**
     * Insert and return last insert ID
     */
    public static function insert($sql, $params = [])
    {
        self::query($sql, $params);
        return self::getConnection()->insert_id;
    }

    /**
     * Close connection
     */
    public static function close()
    {
        if (self::$connection) {
            self::$connection->close();
            self::$connection = null;
        }
    }
}

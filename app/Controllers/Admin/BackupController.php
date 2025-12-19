<?php
/**
 * Admin Backup Controller
 * Handles database backup operations
 */

class BackupController extends Controller
{
    public function __construct()
    {
        if (!Session::isLoggedIn() || Session::getUserRole() !== 'admin') {
            Session::flash('error', 'Please login as admin');
            $this->redirect('admin/login');
        }
    }

    public function index()
    {
        $backupDir = STORAGE_PATH . '/backups/';
        $backups = [];

        if (is_dir($backupDir)) {
            $files = scandir($backupDir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                    $backups[] = [
                        'name' => $file,
                        'size' => filesize($backupDir . $file),
                        'date' => filemtime($backupDir . $file)
                    ];
                }
            }
        }

        // Sort by date descending
        usort($backups, function($a, $b) {
            return $b['date'] - $a['date'];
        });

        echo $this->view('admin.backup.index', [
            'pageTitle' => 'Database Backup',
            'backups' => $backups
        ]);
    }

    public function create()
    {
        if ($this->isPost()) {
            $config = require CONFIG_PATH . '/database.php';
            $backupDir = STORAGE_PATH . '/backups/';
            
            // Create backup directory if not exists
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filepath = $backupDir . $filename;

            try {
                // Create database backup using PHP
                $pdo = new PDO(
                    "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']};charset={$config['charset']}",
                    $config['username'],
                    $config['password']
                );
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $backup = "-- Database Backup\n";
                $backup .= "-- Date: " . date('Y-m-d H:i:s') . "\n";
                $backup .= "-- Database: {$config['database']}\n\n";
                $backup .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

                // Get all tables
                $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

                foreach ($tables as $table) {
                    // Drop table statement
                    $backup .= "-- Table: $table\n";
                    $backup .= "DROP TABLE IF EXISTS `$table`;\n\n";

                    // Create table statement
                    $createTable = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
                    $backup .= $createTable['Create Table'] . ";\n\n";

                    // Get table data
                    $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (!empty($rows)) {
                        foreach ($rows as $row) {
                            $values = array_map(function($value) use ($pdo) {
                                return $value === null ? 'NULL' : $pdo->quote($value);
                            }, array_values($row));
                            
                            $backup .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
                        }
                        $backup .= "\n";
                    }
                }

                $backup .= "SET FOREIGN_KEY_CHECKS=1;\n";

                // Write backup to file
                file_put_contents($filepath, $backup);

                if (file_exists($filepath) && filesize($filepath) > 0) {
                    Session::flash('success', 'Backup created successfully: ' . $filename);
                } else {
                    Session::flash('error', 'Failed to create backup file');
                }

            } catch (Exception $e) {
                Session::flash('error', 'Backup failed: ' . $e->getMessage());
            }
        }

        $this->redirect('admin/backup');
    }
}

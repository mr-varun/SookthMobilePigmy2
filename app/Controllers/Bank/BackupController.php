<?php
/**
 * Bank Backup Controller
 */

class BackupController extends Controller
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

        usort($backups, function($a, $b) {
            return $b['date'] - $a['date'];
        });

        echo $this->view('bank.backup.index', [
            'pageTitle' => 'Database Backup',
            'backups' => $backups
        ]);
    }
}

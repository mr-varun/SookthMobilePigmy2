<?php
/**
 * License Management Helper Functions
 * Check and display license status warnings
 */

/**
 * Check license status for a branch
 */
function checkLicenseStatus($branch_code) {
    $today = date('Y-m-d');
    
    $license_row = Database::fetchOne(
        "SELECT licence_key, status, expiry_date FROM licence_management WHERE branch_code = ?",
        [$branch_code]
    );
    
    if ($license_row) {
        $license_status = $license_row['status'];
        $expiry_date = $license_row['expiry_date'];
        
        // Calculate days until expiry
        $expiry_timestamp = strtotime($expiry_date);
        $today_timestamp = strtotime($today);
        $days_until_expiry = ($expiry_timestamp - $today_timestamp) / (60 * 60 * 24);
        
        // Return license info
        return [
            'is_active' => ($license_status == 1 && $today <= $expiry_date),
            'is_expired' => ($license_status == 0 || $today > $expiry_date),
            'days_until_expiry' => ceil($days_until_expiry),
            'expiry_date' => $expiry_date,
            'is_expiring_soon' => ($days_until_expiry <= 7 && $days_until_expiry > 0),
            'status' => $license_status
        ];
    }
    
    return [
        'is_active' => false,
        'is_expired' => true,
        'days_until_expiry' => -1,
        'expiry_date' => null,
        'is_expiring_soon' => false,
        'status' => 0
    ];
}

/**
 * Display license warning banner HTML
 */
function displayLicenseWarningBanner($days_until_expiry) {
    if ($days_until_expiry > 0 && $days_until_expiry <= 7) {
        $day_text = ($days_until_expiry == 1) ? 'day' : 'days';
        
        return '
        <div class="license-banner" style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%); color: white; padding: 15px 20px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 15px; font-weight: 500; box-shadow: 0px 4px 12px rgba(255, 152, 0, 0.3);">
            <i class="fas fa-exclamation-triangle" style="font-size: 24px;"></i>
            <div style="flex: 1;">
                <strong>⚠️ License Expiring Soon!</strong> Your license will expire in ' . $days_until_expiry . ' ' . $day_text . '. Please contact your administrator.
            </div>
        </div>';
    }
    
    return "";
}

-- ============================================================================
-- Migration Script: temp.sql to mobile_pigmy.sql
-- ============================================================================
-- Purpose: Migrate from temp.sql schema to mobile_pigmy.sql schema
-- Date: December 19, 2025
-- Database: mobile_pigmy
-- 
-- This script will:
-- 1. Add missing columns to existing tables
-- 2. Create new tables (branch_settings, pin_reset_tokens)
-- 3. Modify column definitions to match new schema
-- 4. Add missing indexes and keys
-- 5. Update column names where changed
-- ============================================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+05:30";

-- ============================================================================
-- STEP 1: BACKUP REMINDER
-- ============================================================================
-- IMPORTANT: Create a backup before running this migration!
-- Command: mysqldump -u root -p mobile_pigmy > backup_before_migration.sql
-- ============================================================================

USE `mobile_pigmy`;

-- ============================================================================
-- STEP 2: ALTER EXISTING TABLES
-- ============================================================================

-- ----------------------------------------------------------------------------
-- Table: accounts
-- Changes: PRIMARY KEY and UNIQUE KEY should already exist from temp.sql
-- Note: Skipping as temp.sql already has these keys defined
-- ----------------------------------------------------------------------------
-- Keys already exist in temp.sql:
-- PRIMARY KEY (`id`)
-- UNIQUE KEY `branch_agent_account_unique` (`branch_code`, `agent_code`, `account_number`)

-- ----------------------------------------------------------------------------
-- Table: admin
-- Changes: PRIMARY KEY and UNIQUE KEYS should already exist from temp.sql
-- Note: Skipping as temp.sql already has these keys defined
-- ----------------------------------------------------------------------------
-- Keys already exist in temp.sql:
-- PRIMARY KEY (`admin_id`)
-- UNIQUE KEY `username` (`username`)
-- UNIQUE KEY `email` (`email`)

-- ----------------------------------------------------------------------------
-- Table: agent
-- Changes: Add status column and index for better performance
-- ----------------------------------------------------------------------------
-- Check if status column exists before adding
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'mobile_pigmy' 
  AND TABLE_NAME = 'agent' 
  AND COLUMN_NAME = 'status';

SET @query = IF(@col_exists = 0,
  'ALTER TABLE `agent` 
   ADD COLUMN `status` TINYINT(1) NOT NULL DEFAULT 1 COMMENT ''1=Active, 0=Disabled'' AFTER `pin_changed`,
   ADD KEY `idx_agent_status` (`status`)',
  'SELECT "Column status already exists in agent table" AS notice');

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ----------------------------------------------------------------------------
-- Table: branch
-- Changes: Add created_at timestamp column
-- ----------------------------------------------------------------------------
-- Check if created_at column exists before adding
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'mobile_pigmy' 
  AND TABLE_NAME = 'branch' 
  AND COLUMN_NAME = 'created_at';

SET @query = IF(@col_exists = 0,
  'ALTER TABLE `branch` 
   ADD COLUMN `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT ''Branch creation timestamp'' AFTER `manager_password`',
  'SELECT "Column created_at already exists in branch table" AS notice');

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ----------------------------------------------------------------------------
-- Table: transactions
-- Changes: 
-- 1. Rename 'account_name' to 'customer_name'
-- 2. Rename 'date' to 'transaction_date'
-- 3. Rename 'time' to 'transaction_time'
-- 4. Rename 'status' to 'is_resent' and change type
-- 5. Add indexes for better performance
-- ----------------------------------------------------------------------------

-- Check if old columns exist before migration
SET @has_old_columns = 0;
SELECT COUNT(*) INTO @has_old_columns
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'mobile_pigmy' 
  AND TABLE_NAME = 'transactions' 
  AND COLUMN_NAME IN ('account_name', 'date', 'time', 'status')
LIMIT 1;

-- Only migrate if old columns exist
-- If new columns already exist, skip this step
SET @has_new_columns = 0;
SELECT COUNT(*) INTO @has_new_columns
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'mobile_pigmy' 
  AND TABLE_NAME = 'transactions' 
  AND COLUMN_NAME = 'customer_name';

-- Perform migration only if we have old columns and not new ones
-- This is a complex operation, so we'll do it conditionally

-- Step 1: Add new columns if they don't exist
ALTER TABLE `transactions`
  ADD COLUMN IF NOT EXISTS `customer_name` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL AFTER `account_number`,
  ADD COLUMN IF NOT EXISTS `transaction_date` DATE NOT NULL AFTER `customer_name`,
  ADD COLUMN IF NOT EXISTS `transaction_time` TIME NOT NULL AFTER `transaction_date`,
  ADD COLUMN IF NOT EXISTS `is_resent` TINYINT(1) DEFAULT 0 AFTER `amount`;

-- Step 2: Copy data from old columns to new columns (if old columns exist)
UPDATE `transactions` SET 
  `customer_name` = COALESCE(`account_name`, `customer_name`),
  `transaction_date` = COALESCE(`date`, `transaction_date`),
  `transaction_time` = COALESCE(`time`, `transaction_time`),
  `is_resent` = COALESCE(IF(`status` > 0, 1, 0), `is_resent`)
WHERE EXISTS (
  SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS 
  WHERE TABLE_SCHEMA = 'mobile_pigmy' 
    AND TABLE_NAME = 'transactions' 
    AND COLUMN_NAME = 'account_name'
);

-- Step 3: Drop old columns if they exist
SET @drop_account_name = (
  SELECT IF(COUNT(*) > 0,
    'ALTER TABLE `transactions` DROP COLUMN `account_name`',
    'SELECT "Column account_name does not exist" AS notice')
  FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = 'mobile_pigmy' 
    AND TABLE_NAME = 'transactions'
    AND COLUMN_NAME = 'account_name'
);
PREPARE stmt FROM @drop_account_name;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @drop_date = (
  SELECT IF(COUNT(*) > 0,
    'ALTER TABLE `transactions` DROP COLUMN `date`',
    'SELECT "Column date does not exist" AS notice')
  FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = 'mobile_pigmy' 
    AND TABLE_NAME = 'transactions'
    AND COLUMN_NAME = 'date'
);
PREPARE stmt FROM @drop_date;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @drop_time = (
  SELECT IF(COUNT(*) > 0,
    'ALTER TABLE `transactions` DROP COLUMN `time`',
    'SELECT "Column time does not exist" AS notice')
  FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = 'mobile_pigmy' 
    AND TABLE_NAME = 'transactions'
    AND COLUMN_NAME = 'time'
);
PREPARE stmt FROM @drop_time;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @drop_status = (
  SELECT IF(COUNT(*) > 0,
    'ALTER TABLE `transactions` DROP COLUMN `status`',
    'SELECT "Column status does not exist" AS notice')
  FROM INFORMATION_SCHEMA.COLUMNS
  WHERE TABLE_SCHEMA = 'mobile_pigmy' 
    AND TABLE_NAME = 'transactions'
    AND COLUMN_NAME = 'status'
);
PREPARE stmt FROM @drop_status;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Step 4: Add indexes for better performance (if they don't exist)
ALTER TABLE `transactions`
  ADD KEY IF NOT EXISTS `branch_agent` (`branch_code`, `agent_code`),
  ADD KEY IF NOT EXISTS `account_number` (`account_number`),
  ADD KEY IF NOT EXISTS `transaction_date` (`transaction_date`);

-- ----------------------------------------------------------------------------
-- Table: backuptransaction
-- Changes: 
-- 1. Rename 'date' to 'transaction_date' (if needed)
-- 2. Rename 'time' to 'transaction_time' (if needed)
-- 3. Rename 'account_name' to 'customer_name' (if needed)
-- Note: Check if backuptransaction needs similar changes
-- ----------------------------------------------------------------------------

-- Check if backuptransaction has old column names and update if necessary
-- Uncomment the following if backuptransaction needs migration

-- ALTER TABLE `backuptransaction`
--   ADD COLUMN `customer_name` VARCHAR(100) COLLATE utf8mb4_unicode_ci NOT NULL AFTER `account_number`,
--   ADD COLUMN `transaction_date` DATE NOT NULL AFTER `customer_name`,
--   ADD COLUMN `transaction_time` TIME NOT NULL AFTER `transaction_date`;
--
-- UPDATE `backuptransaction` SET 
--   `customer_name` = `account_name`,
--   `transaction_date` = `date`,
--   `transaction_time` = `time`;
--
-- ALTER TABLE `backuptransaction`
--   DROP COLUMN `account_name`,
--   DROP COLUMN `date`,
--   DROP COLUMN `time`;

-- ============================================================================
-- STEP 3: CREATE NEW TABLES
-- ============================================================================

-- ----------------------------------------------------------------------------
-- Table: branch_settings
-- Purpose: Store branch-specific settings (printer, SMS, WhatsApp)
-- Status: NEW TABLE in mobile_pigmy.sql
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `branch_settings` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `branch_code` VARCHAR(20) NOT NULL,
  `printer_support` TINYINT(1) NOT NULL DEFAULT 0,
  `text_message` TINYINT(1) NOT NULL DEFAULT 0,
  `whatsapp_message` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branch_code` (`branch_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default settings for existing branches
INSERT INTO `branch_settings` (`branch_code`, `printer_support`, `text_message`, `whatsapp_message`)
SELECT `branch_code`, 0, 0, 0
FROM `branch`
WHERE `branch_code` NOT IN (SELECT `branch_code` FROM `branch_settings`);

-- ----------------------------------------------------------------------------
-- Table: pin_reset_tokens
-- Purpose: Store PIN reset tokens for agents (security feature)
-- Status: NEW TABLE in mobile_pigmy.sql
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `pin_reset_tokens` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `agent_code` VARCHAR(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_code` VARCHAR(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` VARCHAR(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiry` DATETIME NOT NULL,
  `used` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `agent_code` (`agent_code`),
  KEY `branch_code` (`branch_code`),
  KEY `expiry` (`expiry`),
  KEY `idx_token_used` (`token`, `used`),
  KEY `idx_agent_branch` (`agent_code`, `branch_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- STEP 4: DATA VALIDATION (Optional but Recommended)
-- ============================================================================

-- Verify that all agents have a status value
SELECT COUNT(*) AS agents_without_status 
FROM `agent` 
WHERE `status` IS NULL;

-- Verify that all transactions have been migrated properly
SELECT COUNT(*) AS transactions_with_old_columns
FROM `transactions`
WHERE `transaction_date` IS NULL OR `transaction_time` IS NULL;

-- Verify that all branches have settings
SELECT b.branch_code, b.branch_name, 
       CASE WHEN bs.id IS NULL THEN 'Missing Settings' ELSE 'Settings OK' END AS status
FROM `branch` b
LEFT JOIN `branch_settings` bs ON b.branch_code = bs.branch_code;

-- ============================================================================
-- STEP 5: UPDATE STATISTICS (Optional)
-- ============================================================================

-- Analyze tables for better query performance
ANALYZE TABLE `accounts`;
ANALYZE TABLE `admin`;
ANALYZE TABLE `agent`;
ANALYZE TABLE `backuptransaction`;
ANALYZE TABLE `branch`;
ANALYZE TABLE `branch_settings`;
ANALYZE TABLE `licence_management`;
ANALYZE TABLE `pin_reset_tokens`;
ANALYZE TABLE `transactions`;

-- ============================================================================
-- MIGRATION COMPLETE
-- ============================================================================

SELECT 'Migration completed successfully!' AS status,
       NOW() AS completed_at;

-- ============================================================================
-- POST-MIGRATION CHECKLIST
-- ============================================================================
-- [ ] Verify all tables exist
-- [ ] Verify all columns are present
-- [ ] Verify data integrity
-- [ ] Test application login
-- [ ] Test transaction creation
-- [ ] Test reports generation
-- [ ] Create fresh backup after successful migration
-- ============================================================================

-- ============================================================================
-- ROLLBACK SCRIPT (In case of issues)
-- ============================================================================
-- If migration fails, restore from backup:
-- mysql -u root -p mobile_pigmy < backup_before_migration.sql
-- ============================================================================

-- ============================================================================
-- NOTES FOR DEVELOPERS
-- ============================================================================
-- 1. The temp.sql schema lacks:
--    - branch_settings table (for printer/SMS/WhatsApp settings)
--    - pin_reset_tokens table (for secure PIN reset functionality)
--    - status column in agent table (for enabling/disabling agents)
--    - created_at column in branch table (for audit trail)
--    - Performance indexes on transactions table
--
-- 2. Column name changes in transactions table:
--    - account_name → customer_name (more accurate naming)
--    - date → transaction_date (clearer purpose)
--    - time → transaction_time (clearer purpose)
--    - status → is_resent (boolean flag instead of int status)
--
-- 3. Benefits of new schema:
--    - Better performance with added indexes
--    - Improved security with pin_reset_tokens
--    - Better audit trail with timestamps
--    - Branch-specific settings support
--    - Clearer column naming conventions
--
-- 4. Storage Engine Change:
--    - branch_settings uses InnoDB (for foreign key support in future)
--    - pin_reset_tokens uses InnoDB (for referential integrity)
--    - Other tables remain MyISAM (for current compatibility)
--
-- 5. Testing Recommendations:
--    - Test with a copy of production database first
--    - Verify all application features after migration
--    - Monitor performance for 24-48 hours
--    - Keep backup for at least 7 days
-- ============================================================================

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Dec 19, 2025 at 08:32 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+05:30";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mobile_pigmy`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
CREATE TABLE IF NOT EXISTS `accounts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_old_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `account_mobile` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_opening_date` date DEFAULT NULL,
  `account_new_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `branch_agent_account_unique` (`branch_code`,`agent_code`,`account_number`)
) ENGINE=MyISAM AUTO_INCREMENT=2274 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `admin_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_name`, `username`, `password`, `email`, `otp`, `otp_expiry`) VALUES
(1, 'Varun Hosakoti', 'VARUN', 'Varun#2025', 'hosakotivarun@gmail.com', '295254', '2025-11-28 11:36:48');

-- --------------------------------------------------------

--
-- Table structure for table `agent`
--

DROP TABLE IF EXISTS `agent`;
CREATE TABLE IF NOT EXISTS `agent` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pass123',
  `agent_mobile` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pin` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '123456',
  `pin_changed` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=Active, 0=Disabled',
  PRIMARY KEY (`id`),
  UNIQUE KEY `agent_mobile` (`agent_mobile`),
  UNIQUE KEY `agent_email` (`agent_email`),
  UNIQUE KEY `branch_code` (`branch_code`,`agent_code`),
  KEY `idx_agent_status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `backuptransaction`
--

DROP TABLE IF EXISTS `backuptransaction`;
CREATE TABLE IF NOT EXISTS `backuptransaction` (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

DROP TABLE IF EXISTS `branch`;
CREATE TABLE IF NOT EXISTS `branch` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manager_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manager_mobile` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manager_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manager_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pass123',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Branch creation timestamp',
  PRIMARY KEY (`id`),
  UNIQUE KEY `branch_code` (`branch_code`),
  UNIQUE KEY `manager_mobile` (`manager_mobile`),
  UNIQUE KEY `manager_email` (`manager_email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branch_settings`
--

DROP TABLE IF EXISTS `branch_settings`;
CREATE TABLE IF NOT EXISTS `branch_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `branch_code` varchar(20) NOT NULL,
  `printer_support` tinyint(1) NOT NULL DEFAULT '0',
  `text_message` tinyint(1) NOT NULL DEFAULT '0',
  `whatsapp_message` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branch_code` (`branch_code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `licence_management`
--

DROP TABLE IF EXISTS `licence_management`;
CREATE TABLE IF NOT EXISTS `licence_management` (
  `id` int NOT NULL AUTO_INCREMENT,
  `licence_key` varchar(12) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `reg_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expiry_date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pin_reset_tokens`
--

DROP TABLE IF EXISTS `pin_reset_tokens`;
CREATE TABLE IF NOT EXISTS `pin_reset_tokens` (
  `id` int NOT NULL AUTO_INCREMENT,
  `agent_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiry` datetime NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `agent_code` (`agent_code`),
  KEY `branch_code` (`branch_code`),
  KEY `expiry` (`expiry`),
  KEY `idx_token_used` (`token`,`used`),
  KEY `idx_agent_branch` (`agent_code`,`branch_code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `transaction_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agent_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_date` date NOT NULL,
  `transaction_time` time NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `is_resent` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_id` (`transaction_id`),
  KEY `branch_agent` (`branch_code`,`agent_code`),
  KEY `account_number` (`account_number`),
  KEY `transaction_date` (`transaction_date`)
) ENGINE=MyISAM AUTO_INCREMENT=139 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

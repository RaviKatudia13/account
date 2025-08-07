-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2025 at 03:30 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `payment_mode_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `ifsc_code` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `upi_id` varchar(255) DEFAULT NULL,
  `holder_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `type`, `payment_mode_id`, `account_number`, `ifsc_code`, `bank_name`, `branch`, `upi_id`, `holder_name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'RaviKatudia', 'bank', 1, '202513052006', 'UNI0001201411', 'Union Bank of India', 'Gandhi Road', NULL, 'RaviKatudia', NULL, 1, '2025-07-07 11:09:34', '2025-07-07 11:09:34'),
(2, 'Ravi', 'upi', 2, NULL, NULL, NULL, NULL, 'ravikatudia@icici', 'Katudia Ravi', NULL, 1, '2025-07-07 11:09:54', '2025-07-07 11:09:54'),
(3, 'Cash', 'cash', 3, NULL, NULL, NULL, NULL, NULL, 'CashAccount', NULL, 1, '2025-07-07 11:10:09', '2025-07-07 11:10:09');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Client', '2025-07-07 11:07:22', '2025-07-07 11:07:22');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `gst_registered` tinyint(1) NOT NULL DEFAULT 0,
  `gstin` varchar(255) DEFAULT NULL,
  `payment_mode` varchar(255) DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `name`, `company_name`, `mobile`, `email`, `address`, `gst_registered`, `gstin`, `payment_mode`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'Client', 'LTTRBX', '8849913479', 'Client@gmail.com', 'E-1214 GG 11', 0, NULL, NULL, 1, '2025-07-07 11:08:01', '2025-07-07 11:08:01');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `number` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `join_date` date NOT NULL,
  `employee_category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `number`, `address`, `designation`, `join_date`, `employee_category_id`, `created_at`, `updated_at`) VALUES
(1, 'Employee', '8849913479', 'E-1214 GG 11', 'CEO', '2006-05-13', 1, '2025-07-07 11:12:58', '2025-07-07 11:12:58');

-- --------------------------------------------------------

--
-- Table structure for table `employee_categories`
--

CREATE TABLE `employee_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_categories`
--

INSERT INTO `employee_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Employee', '2025-07-07 11:07:34', '2025-07-07 11:07:34');

-- --------------------------------------------------------

--
-- Table structure for table `employee_due`
--

CREATE TABLE `employee_due` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_due`
--

INSERT INTO `employee_due` (`id`, `employee_id`, `date`, `total_amount`, `paid_amount`, `remaining_amount`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-07-07', 70000.00, 49000.00, 21000.00, NULL, '2025-07-07 11:13:12', '2025-07-07 12:11:55'),
(2, 1, '2025-07-07', 5000.00, 0.00, 5000.00, NULL, '2025-07-07 11:23:57', '2025-07-07 11:23:57'),
(3, 1, '2025-07-07', 74000.00, 0.00, 74000.00, NULL, '2025-07-07 11:29:04', '2025-07-07 11:29:04'),
(4, 1, '2025-07-07', 13000.00, 0.00, 13000.00, NULL, '2025-07-07 12:40:21', '2025-07-07 12:40:21');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `income_expenses`
--

CREATE TABLE `income_expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `emp_vendor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `emp_vendor_type` enum('employee','vendor') NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `inc_exp_category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `income_expenses`
--

INSERT INTO `income_expenses` (`id`, `type`, `emp_vendor_id`, `emp_vendor_type`, `date`, `amount`, `inc_exp_category_id`, `created_at`, `updated_at`) VALUES
(1, 'income', 1, 'vendor', '2025-07-07', 45000.00, 1, '2025-07-07 11:13:51', '2025-07-07 11:13:51'),
(2, 'expense', 1, 'employee', '2025-07-07', 10000.00, 2, '2025-07-07 11:14:06', '2025-07-07 11:14:06'),
(3, 'income', 2, 'vendor', '2025-07-07', 57000.00, 1, '2025-07-07 12:49:53', '2025-07-07 12:49:53'),
(4, 'expense', 1, 'employee', '2025-07-07', 47000.00, 2, '2025-07-07 12:50:07', '2025-07-07 12:50:07');

-- --------------------------------------------------------

--
-- Table structure for table `inc_exp_categories`
--

CREATE TABLE `inc_exp_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inc_exp_categories`
--

INSERT INTO `inc_exp_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Income', '2025-07-07 11:07:39', '2025-07-07 11:07:39'),
(2, 'Expense', '2025-07-07 11:07:42', '2025-07-07 11:07:42');

-- --------------------------------------------------------

--
-- Table structure for table `internal_transfer`
--

CREATE TABLE `internal_transfer` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `debit_account` varchar(255) NOT NULL,
  `credit_account` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `internal_transfer`
--

INSERT INTO `internal_transfer` (`id`, `debit_account`, `credit_account`, `amount`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Ravi', 'RaviKatudia', 785000.00, NULL, '2025-07-07 11:10:44', '2025-07-07 11:10:44');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `invoice_date` date NOT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`items`)),
  `subtotal` decimal(12,2) NOT NULL,
  `gst_type` enum('Non-GST','GST','IGST') NOT NULL DEFAULT 'GST',
  `gst_amount` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `status` enum('Paid','Due','Partial') NOT NULL DEFAULT 'Due',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `client_id`, `invoice_number`, `invoice_date`, `items`, `subtotal`, `gst_type`, `gst_amount`, `total`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'INV-20250707-001', '2025-07-07', '[{\"description\":\"Test Entry\",\"rate\":\"50700\",\"gst_type\":\"GST\",\"gst_percent\":\"18\",\"amount\":\"59826.00\"}]', 50700.00, 'GST', 9126.00, 59826.00, 'Due', 'Payment Terms: Net 30 days from the date of invoice.', '2025-07-07 11:08:23', '2025-07-07 11:08:23');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_06_25_000000_create_categories_table', 1),
(6, '2025_06_26_102909_create_clients_table', 1),
(7, '2025_06_26_124457_create_invoices_table', 1),
(8, '2025_06_27_000000_create_payments_table', 1),
(9, '2025_06_28_083050_update_invoices_status_enum', 1),
(10, '2025_06_28_154646_update_invoices_gst_type_enum', 1),
(11, '2025_06_30_154046_add_gst_registered_to_clients_table', 1),
(12, '2025_07_01_000000_create_payment_methods_table', 1),
(13, '2025_07_01_000001_add_payment_method_id_to_users_table', 1),
(14, '2025_07_01_000002_create_vendor_categories_table', 1),
(15, '2025_07_01_000003_create_vendors_table', 1),
(16, '2025_07_02_000001_create_employee_categories_table', 1),
(17, '2025_07_02_000002_create_employees_table', 1),
(18, '2025_07_03_000001_create_inc_exp_categories_table', 1),
(19, '2025_07_03_000002_create_income_expenses_table', 1),
(20, '2025_07_03_175142_add_expense_id_to_payments_table', 1),
(21, '2025_07_05_000000_create_vendor_due_table', 1),
(22, '2025_07_05_000001_create_employee_due_table', 1),
(23, '2025_07_05_000002_add_ids_to_payments_table', 1),
(24, '2025_07_05_000003_add_type_to_payments_table', 1),
(25, '2025_07_05_000004_add_payment_method_id_to_payments_table', 1),
(26, '2025_07_05_115237_add_internal_transfer_to_payments_table', 1),
(27, '2025_07_05_120414_create_accounts_table', 1),
(28, '2025_07_05_120929_add_account_id_to_payments_table', 1),
(29, '2025_07_05_121529_add_payment_mode_id_to_accounts_table', 1),
(30, '2025_07_06_000000_create_internal_transfer_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` int(11) DEFAULT NULL,
  `internal_transfer` tinyint(1) NOT NULL DEFAULT 0,
  `invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vendor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_date` date NOT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `expense_id` bigint(20) UNSIGNED DEFAULT NULL,
  `recorded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `type`, `internal_transfer`, `invoice_id`, `vendor_id`, `employee_id`, `amount`, `payment_date`, `payment_mode`, `account_id`, `remarks`, `expense_id`, `recorded_by`, `created_at`, `updated_at`) VALUES
(1, 2, 1, NULL, NULL, NULL, 785000.00, '2025-07-07', '2', 2, 'Internal Transfer - Debit: ', NULL, 1, '2025-07-07 11:10:44', '2025-07-07 11:10:44'),
(2, 1, 1, NULL, NULL, NULL, 785000.00, '2025-07-07', '1', 1, 'Internal Transfer - Credit: ', NULL, 1, '2025-07-07 11:10:44', '2025-07-07 11:10:44'),
(3, 2, 0, NULL, NULL, 1, 7982.00, '2025-07-07', '1', 1, NULL, NULL, 1, '2025-07-07 11:24:34', '2025-07-07 11:24:34'),
(4, 2, 0, NULL, NULL, 1, 7018.00, '2025-07-07', '1', 1, NULL, NULL, 1, '2025-07-07 11:26:37', '2025-07-07 11:26:37'),
(5, 2, 0, NULL, 1, NULL, 6000.00, '2025-07-07', '1', 1, 'This is Test', NULL, 1, '2025-07-07 11:50:57', '2025-07-07 11:50:57'),
(6, 2, 0, NULL, NULL, 1, 34000.00, '2025-07-07', '2', 2, NULL, NULL, 1, '2025-07-07 12:11:55', '2025-07-07 12:11:55'),
(7, 2, 0, NULL, 1, NULL, 3000.00, '2025-07-07', '3', 3, NULL, NULL, 1, '2025-07-07 12:43:08', '2025-07-07 12:43:08');

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Bank', NULL, '2025-07-07 11:09:01', '2025-07-07 11:09:01'),
(2, 'UPI', NULL, '2025-07-07 11:09:06', '2025-07-07 11:09:06'),
(3, 'Cash', NULL, '2025-07-07 11:09:10', '2025-07-07 11:09:10');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `payment_method_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Ravi', 'ravikatudia@gmail.com', NULL, '$2y$12$/aSWzPJHgk/4ty9Fw/rTk.zRlpkzrmmJCTxrKWpt/7lSz0iEpPcr.', NULL, NULL, '2025-07-07 11:07:16', '2025-07-07 11:07:16');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `vendor_category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `address`, `phone`, `vendor_category_id`, `created_at`, `updated_at`) VALUES
(1, 'Vendor', 'E-1214 GG 11', '8849913479', 1, '2025-07-07 11:11:48', '2025-07-07 11:11:48'),
(2, 'MeetPatel', 'E-1214 GG 11', '8849913479', 1, '2025-07-07 12:44:32', '2025-07-07 12:44:32');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_categories`
--

CREATE TABLE `vendor_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_categories`
--

INSERT INTO `vendor_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Vendor', '2025-07-07 11:07:28', '2025-07-07 11:07:28');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_due`
--

CREATE TABLE `vendor_due` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_due`
--

INSERT INTO `vendor_due` (`id`, `vendor_id`, `date`, `total_amount`, `paid_amount`, `remaining_amount`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, '2025-07-08', 78000.00, 0.00, 78000.00, NULL, '2025-07-07 11:12:22', '2025-07-07 11:12:22'),
(2, 1, '2025-07-07', 8000.00, 8000.00, 0.00, NULL, '2025-07-07 11:12:33', '2025-07-07 12:43:08'),
(3, 1, '2025-07-07', 13000.00, 1000.00, 12000.00, NULL, '2025-07-07 12:40:03', '2025-07-07 12:43:08'),
(4, 2, '2025-07-07', 1000.00, 0.00, 1000.00, NULL, '2025-07-07 12:45:01', '2025-07-07 12:45:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `accounts_payment_mode_id_foreign` (`payment_mode_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `clients_category_id_foreign` (`category_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employees_employee_category_id_foreign` (`employee_category_id`);

--
-- Indexes for table `employee_categories`
--
ALTER TABLE `employee_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_due`
--
ALTER TABLE `employee_due`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_due_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `income_expenses`
--
ALTER TABLE `income_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `income_expenses_inc_exp_category_id_foreign` (`inc_exp_category_id`);

--
-- Indexes for table `inc_exp_categories`
--
ALTER TABLE `inc_exp_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `internal_transfer`
--
ALTER TABLE `internal_transfer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_client_id_foreign` (`client_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_expense_id_foreign` (`expense_id`),
  ADD KEY `payments_account_id_foreign` (`account_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendors_vendor_category_id_foreign` (`vendor_category_id`);

--
-- Indexes for table `vendor_categories`
--
ALTER TABLE `vendor_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_due`
--
ALTER TABLE `vendor_due`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_due_vendor_id_foreign` (`vendor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_categories`
--
ALTER TABLE `employee_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `employee_due`
--
ALTER TABLE `employee_due`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `income_expenses`
--
ALTER TABLE `income_expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `inc_exp_categories`
--
ALTER TABLE `inc_exp_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `internal_transfer`
--
ALTER TABLE `internal_transfer`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vendor_categories`
--
ALTER TABLE `vendor_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendor_due`
--
ALTER TABLE `vendor_due`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_payment_mode_id_foreign` FOREIGN KEY (`payment_mode_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `clients`
--
ALTER TABLE `clients`
  ADD CONSTRAINT `clients_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_employee_category_id_foreign` FOREIGN KEY (`employee_category_id`) REFERENCES `employee_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_due`
--
ALTER TABLE `employee_due`
  ADD CONSTRAINT `employee_due_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `income_expenses`
--
ALTER TABLE `income_expenses`
  ADD CONSTRAINT `income_expenses_inc_exp_category_id_foreign` FOREIGN KEY (`inc_exp_category_id`) REFERENCES `inc_exp_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `income_expenses` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `vendors_vendor_category_id_foreign` FOREIGN KEY (`vendor_category_id`) REFERENCES `vendor_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vendor_due`
--
ALTER TABLE `vendor_due`
  ADD CONSTRAINT `vendor_due_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

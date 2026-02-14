-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 15, 2026 at 01:38 AM
-- Server version: 11.4.10-MariaDB
-- PHP Version: 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gimmhost_cafeqr`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('cafe-qr-ordering-cache-c14c53f9f3e9830cdf657aed88feb9d367959958', 'i:23;', 1771094262),
('cafe-qr-ordering-cache-c14c53f9f3e9830cdf657aed88feb9d367959958:timer', 'i:1771094262;', 1771094262),
('cafe-qr-ordering-cache-menu_categories', 'O:39:\"Illuminate\\Database\\Eloquent\\Collection\":2:{s:8:\"\0*\0items\";a:4:{i:0;O:19:\"App\\Models\\Category\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:4:\"Kopi\";s:10:\"sort_order\";s:1:\"1\";s:9:\"is_active\";s:1:\"1\";s:16:\"close_order_time\";s:8:\"02:00:00\";s:10:\"created_at\";s:19:\"2026-02-03 06:08:52\";s:10:\"updated_at\";s:19:\"2026-02-14 17:43:59\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";s:1:\"1\";s:4:\"name\";s:4:\"Kopi\";s:10:\"sort_order\";s:1:\"1\";s:9:\"is_active\";s:1:\"1\";s:16:\"close_order_time\";s:8:\"02:00:00\";s:10:\"created_at\";s:19:\"2026-02-03 06:08:52\";s:10:\"updated_at\";s:19:\"2026-02-14 17:43:59\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:10:\"sort_order\";i:2;s:9:\"is_active\";i:3;s:16:\"close_order_time\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:1;O:19:\"App\\Models\\Category\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:8:\"Non-Kopi\";s:10:\"sort_order\";s:1:\"2\";s:9:\"is_active\";s:1:\"1\";s:16:\"close_order_time\";s:8:\"02:00:00\";s:10:\"created_at\";s:19:\"2026-02-03 06:08:52\";s:10:\"updated_at\";s:19:\"2026-02-14 17:43:59\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";s:1:\"2\";s:4:\"name\";s:8:\"Non-Kopi\";s:10:\"sort_order\";s:1:\"2\";s:9:\"is_active\";s:1:\"1\";s:16:\"close_order_time\";s:8:\"02:00:00\";s:10:\"created_at\";s:19:\"2026-02-03 06:08:52\";s:10:\"updated_at\";s:19:\"2026-02-14 17:43:59\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:10:\"sort_order\";i:2;s:9:\"is_active\";i:3;s:16:\"close_order_time\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:2;O:19:\"App\\Models\\Category\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:5:\"Snack\";s:10:\"sort_order\";s:1:\"3\";s:9:\"is_active\";s:1:\"1\";s:16:\"close_order_time\";s:8:\"22:30:00\";s:10:\"created_at\";s:19:\"2026-02-03 06:08:52\";s:10:\"updated_at\";s:19:\"2026-02-14 17:43:59\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";s:1:\"3\";s:4:\"name\";s:5:\"Snack\";s:10:\"sort_order\";s:1:\"3\";s:9:\"is_active\";s:1:\"1\";s:16:\"close_order_time\";s:8:\"22:30:00\";s:10:\"created_at\";s:19:\"2026-02-03 06:08:52\";s:10:\"updated_at\";s:19:\"2026-02-14 17:43:59\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:10:\"sort_order\";i:2;s:9:\"is_active\";i:3;s:16:\"close_order_time\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}i:3;O:19:\"App\\Models\\Category\":33:{s:13:\"\0*\0connection\";s:5:\"mysql\";s:8:\"\0*\0table\";s:10:\"categories\";s:13:\"\0*\0primaryKey\";s:2:\"id\";s:10:\"\0*\0keyType\";s:3:\"int\";s:12:\"incrementing\";b:1;s:7:\"\0*\0with\";a:0:{}s:12:\"\0*\0withCount\";a:0:{}s:19:\"preventsLazyLoading\";b:0;s:10:\"\0*\0perPage\";i:15;s:6:\"exists\";b:1;s:18:\"wasRecentlyCreated\";b:0;s:28:\"\0*\0escapeWhenCastingToString\";b:0;s:13:\"\0*\0attributes\";a:7:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:7:\"Dessert\";s:10:\"sort_order\";s:1:\"4\";s:9:\"is_active\";s:1:\"1\";s:16:\"close_order_time\";s:8:\"22:30:00\";s:10:\"created_at\";s:19:\"2026-02-03 06:08:52\";s:10:\"updated_at\";s:19:\"2026-02-14 17:43:59\";}s:11:\"\0*\0original\";a:7:{s:2:\"id\";s:1:\"4\";s:4:\"name\";s:7:\"Dessert\";s:10:\"sort_order\";s:1:\"4\";s:9:\"is_active\";s:1:\"1\";s:16:\"close_order_time\";s:8:\"22:30:00\";s:10:\"created_at\";s:19:\"2026-02-03 06:08:52\";s:10:\"updated_at\";s:19:\"2026-02-14 17:43:59\";}s:10:\"\0*\0changes\";a:0:{}s:11:\"\0*\0previous\";a:0:{}s:8:\"\0*\0casts\";a:1:{s:9:\"is_active\";s:7:\"boolean\";}s:17:\"\0*\0classCastCache\";a:0:{}s:21:\"\0*\0attributeCastCache\";a:0:{}s:13:\"\0*\0dateFormat\";N;s:10:\"\0*\0appends\";a:0:{}s:19:\"\0*\0dispatchesEvents\";a:0:{}s:14:\"\0*\0observables\";a:0:{}s:12:\"\0*\0relations\";a:0:{}s:10:\"\0*\0touches\";a:0:{}s:27:\"\0*\0relationAutoloadCallback\";N;s:26:\"\0*\0relationAutoloadContext\";N;s:10:\"timestamps\";b:1;s:13:\"usesUniqueIds\";b:0;s:9:\"\0*\0hidden\";a:0:{}s:10:\"\0*\0visible\";a:0:{}s:11:\"\0*\0fillable\";a:4:{i:0;s:4:\"name\";i:1;s:10:\"sort_order\";i:2;s:9:\"is_active\";i:3;s:16:\"close_order_time\";}s:10:\"\0*\0guarded\";a:1:{i:0;s:1:\"*\";}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}', 1771094298),
('cafe-qr-ordering-cache-setting_cafe_is_open', 's:1:\"1\";', 1771094298);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cafe_tables`
--

CREATE TABLE `cafe_tables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `table_no` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cafe_tables`
--

INSERT INTO `cafe_tables` (`id`, `table_no`, `name`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Meja 1', 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(2, 2, 'Meja 2', 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(3, 3, 'Meja 3', 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(4, 4, 'Meja 4', 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(5, 5, 'Meja 5', 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(6, 6, 'Meja 6', 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(7, 7, 'Meja 7', 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(8, 8, 'Meja 8', 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(9, 9, 'meja 9', 1, '2026-02-02 23:37:21', '2026-02-02 23:37:21');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `close_order_time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `sort_order`, `is_active`, `close_order_time`, `created_at`, `updated_at`) VALUES
(1, 'Kopi', 1, 1, '02:00:00', '2026-02-02 23:08:52', '2026-02-14 10:43:59'),
(2, 'Non-Kopi', 2, 1, '02:00:00', '2026-02-02 23:08:52', '2026-02-14 10:43:59'),
(3, 'Snack', 3, 1, '22:30:00', '2026-02-02 23:08:52', '2026-02-14 10:43:59'),
(4, 'Dessert', 4, 1, '22:30:00', '2026-02-02 23:08:52', '2026-02-14 10:43:59');

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
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_03_000001_create_cafe_tables', 1),
(5, '2026_02_03_000002_create_table_tokens', 1),
(6, '2026_02_03_000003_create_categories_products', 1),
(7, '2026_02_03_000004_create_orders', 1),
(8, '2026_02_03_000005_create_payments', 1),
(9, '2026_02_03_000006_create_order_feedback', 1),
(10, '2026_02_03_000007_add_is_admin_to_users', 1),
(11, '2026_02_03_000008_create_modifiers', 1),
(12, '2026_02_03_000009_add_fields_to_products', 1),
(13, '2026_02_05_000001_add_orders_indexes', 2),
(14, '2026_02_15_000001_add_close_order_time_to_categories', 3),
(15, '2026_02_15_000002_create_settings_table', 4),
(17, '2026_02_15_000003_add_product_review_to_feedback', 5);

-- --------------------------------------------------------

--
-- Table structure for table `mod_groups`
--

CREATE TABLE `mod_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('SINGLE','MULTIPLE') NOT NULL DEFAULT 'SINGLE',
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mod_groups`
--

INSERT INTO `mod_groups` (`id`, `name`, `type`, `is_required`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Size', 'SINGLE', 1, 1, 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(2, 'Sugar Level', 'SINGLE', 0, 2, 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(3, 'Ice Level', 'SINGLE', 0, 3, 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(4, 'Add-ons', 'MULTIPLE', 0, 4, 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52');

-- --------------------------------------------------------

--
-- Table structure for table `mod_options`
--

CREATE TABLE `mod_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `mod_group_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price_modifier` bigint(20) NOT NULL DEFAULT 0,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mod_options`
--

INSERT INTO `mod_options` (`id`, `mod_group_id`, `name`, `price_modifier`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(4, 2, 'Normal Sugar', 0, 1, 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(5, 2, 'Less Sugar', 0, 2, 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(6, 2, 'No Sugar', 0, 3, 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(7, 3, 'Normal Ice', 0, 1, 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(8, 3, 'Less Ice', 0, 2, 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(9, 3, 'No Ice', 0, 3, 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(10, 4, 'Extra Shot', 5000, 1, 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(11, 4, 'Oat Milk', 8000, 2, 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(12, 4, 'Vanilla Syrup', 5000, 3, 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(13, 4, 'Caramel Syrup', 5000, 4, 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(14, 4, 'Whipped Cream', 3000, 5, 1, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(15, 1, 'Normal', 0, 1, 1, '2026-02-14 10:25:33', '2026-02-14 10:25:33');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_code` varchar(32) NOT NULL,
  `table_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `fulfillment_type` enum('DINE_IN','PICKUP') NOT NULL DEFAULT 'DINE_IN',
  `order_status` enum('DITERIMA','DIPROSES','READY','SELESAI','DIBATALKAN') NOT NULL DEFAULT 'DITERIMA',
  `payment_status` enum('UNPAID','PENDING','PAID','FAILED','EXPIRED','REFUNDED') NOT NULL DEFAULT 'UNPAID',
  `subtotal` bigint(20) UNSIGNED NOT NULL,
  `tax_amount` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `service_amount` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `discount_amount` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `grand_total` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_code`, `table_id`, `customer_name`, `fulfillment_type`, `order_status`, `payment_status`, `subtotal`, `tax_amount`, `service_amount`, `discount_amount`, `grand_total`, `created_at`, `updated_at`) VALUES
(12, 'A-20260203-JQQQ24', 8, 'GUS', 'DINE_IN', 'SELESAI', 'PAID', 1000, 0, 0, 0, 1000, '2026-02-03 01:36:48', '2026-02-03 01:45:51'),
(13, 'A-20260203-PXFFGO', 1, 'Ay', 'DINE_IN', 'SELESAI', 'PAID', 1000, 0, 0, 0, 1000, '2026-02-03 01:56:03', '2026-02-03 01:57:51'),
(14, 'A-20260203-A3NP2P', 2, 'BOS', 'DINE_IN', 'SELESAI', 'PAID', 23000, 0, 0, 0, 23000, '2026-02-03 02:18:12', '2026-02-03 02:21:25'),
(17, 'A-20260203-YTR1OS', 1, 'Gimm', 'DINE_IN', 'DITERIMA', 'PENDING', 27000, 0, 0, 0, 27000, '2026-02-03 03:00:03', '2026-02-03 03:00:06'),
(19, 'A-20260207-MVNO8U', 1, 'Lamar', 'DINE_IN', 'SELESAI', 'PAID', 1000, 0, 0, 0, 1000, '2026-02-07 05:16:55', '2026-02-07 05:21:30'),
(21, 'A-20260208-OYHOLQ', 1, 'aas', 'DINE_IN', 'SELESAI', 'PAID', 25000, 0, 0, 0, 25000, '2026-02-08 04:30:38', '2026-02-13 22:44:10'),
(30, 'A-20260214-EV0XHL', 3, 'gimM', 'DINE_IN', 'SELESAI', 'PAID', 1000, 0, 0, 0, 1000, '2026-02-14 01:02:53', '2026-02-14 01:12:32'),
(31, 'A-20260214-RBA29H', 3, 'gim', 'DINE_IN', 'SELESAI', 'PAID', 25000, 0, 0, 0, 25000, '2026-02-14 01:12:50', '2026-02-14 01:48:49'),
(33, 'A-20260214-OCADND', 1, 'ahmad', 'DINE_IN', 'SELESAI', 'PAID', 27000, 0, 0, 0, 27000, '2026-02-14 01:46:22', '2026-02-14 01:48:47'),
(35, 'A-20260214-GKOSIY', 1, 'gimM', 'DINE_IN', 'SELESAI', 'PAID', 40000, 0, 0, 0, 40000, '2026-02-14 01:58:43', '2026-02-14 01:59:26'),
(36, 'A-20260214-TKHWUZ', 1, 'AAA', 'DINE_IN', 'SELESAI', 'PAID', 13000, 0, 0, 0, 13000, '2026-02-14 01:59:52', '2026-02-14 02:01:30'),
(37, 'A-20260214-43V71C', 1, 'ahmad', 'DINE_IN', 'SELESAI', 'PAID', 42000, 0, 0, 0, 42000, '2026-02-14 02:11:01', '2026-02-14 02:11:55'),
(38, 'A-20260215-YDMARV', 2, 'gim', 'DINE_IN', 'SELESAI', 'PAID', 56000, 0, 0, 0, 56000, '2026-02-14 18:14:08', '2026-02-14 18:14:38'),
(39, 'A-20260215-AXIIBK', 2, 'AAA', 'DINE_IN', 'SELESAI', 'PAID', 30000, 0, 0, 0, 30000, '2026-02-14 18:30:23', '2026-02-14 18:36:49');

-- --------------------------------------------------------

--
-- Table structure for table `order_feedback`
--

CREATE TABLE `order_feedback` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_item_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rating` tinyint(4) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `status` enum('VISIBLE','HIDDEN') NOT NULL DEFAULT 'VISIBLE',
  `is_flagged` tinyint(1) NOT NULL DEFAULT 0,
  `admin_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_feedback`
--

INSERT INTO `order_feedback` (`id`, `order_id`, `product_id`, `order_item_id`, `rating`, `comment`, `status`, `is_flagged`, `admin_note`, `created_at`, `updated_at`) VALUES
(1, 39, 3, 40, 4, 'MANTAP', 'VISIBLE', 0, NULL, '2026-02-14 18:37:13', '2026-02-14 18:37:13');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `unit_price` bigint(20) UNSIGNED NOT NULL,
  `qty` int(10) UNSIGNED NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `line_total` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `unit_price`, `qty`, `note`, `line_total`, `created_at`, `updated_at`) VALUES
(12, 12, 13, 'Cookies', 1000, 1, NULL, 1000, '2026-02-03 01:36:48', '2026-02-03 01:36:48'),
(13, 13, 14, 'Tempe Goreng', 1000, 1, NULL, 1000, '2026-02-03 01:56:03', '2026-02-03 01:56:03'),
(14, 14, 7, 'Hot Chocolate', 23000, 1, NULL, 23000, '2026-02-03 02:18:12', '2026-02-03 02:18:12'),
(17, 17, 6, 'Matcha Latte', 27000, 1, NULL, 27000, '2026-02-03 03:00:03', '2026-02-03 03:00:03'),
(19, 19, 13, 'Cookies', 1000, 1, NULL, 1000, '2026-02-07 05:16:55', '2026-02-07 05:16:55'),
(21, 21, 3, 'Caffe Latte', 25000, 1, NULL, 25000, '2026-02-08 04:30:38', '2026-02-08 04:30:38'),
(30, 30, 13, 'Cookies', 1000, 1, NULL, 1000, '2026-02-14 01:02:53', '2026-02-14 01:02:53'),
(31, 31, 3, 'Caffe Latte', 25000, 1, NULL, 25000, '2026-02-14 01:12:50', '2026-02-14 01:12:50'),
(33, 33, 6, 'Matcha Latte', 27000, 1, NULL, 27000, '2026-02-14 01:46:22', '2026-02-14 01:46:22'),
(35, 35, 11, 'Cheesecake', 40000, 1, NULL, 40000, '2026-02-14 01:58:43', '2026-02-14 01:58:43'),
(36, 36, 5, 'Espresso', 13000, 1, NULL, 13000, '2026-02-14 01:59:52', '2026-02-14 01:59:52'),
(37, 37, 3, 'Caffe Latte', 25000, 1, NULL, 25000, '2026-02-14 02:11:01', '2026-02-14 02:11:01'),
(38, 37, 1, 'Americano', 17000, 1, NULL, 17000, '2026-02-14 02:11:01', '2026-02-14 02:11:01'),
(39, 38, 2, 'Cappuccino', 28000, 2, 'pakai doa', 56000, '2026-02-14 18:14:08', '2026-02-14 18:14:08'),
(40, 39, 3, 'Caffe Latte', 30000, 1, NULL, 30000, '2026-02-14 18:30:23', '2026-02-14 18:30:23');

-- --------------------------------------------------------

--
-- Table structure for table `order_item_mods`
--

CREATE TABLE `order_item_mods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_item_id` bigint(20) UNSIGNED NOT NULL,
  `mod_option_id` bigint(20) UNSIGNED NOT NULL,
  `mod_group_name` varchar(255) NOT NULL,
  `mod_option_name` varchar(255) NOT NULL,
  `price_modifier` bigint(20) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_item_mods`
--

INSERT INTO `order_item_mods` (`id`, `order_item_id`, `mod_option_id`, `mod_group_name`, `mod_option_name`, `price_modifier`, `created_at`, `updated_at`) VALUES
(14, 39, 15, 'Size', 'Normal', 0, '2026-02-14 18:14:08', '2026-02-14 18:14:08'),
(15, 40, 15, 'Size', 'Normal', 0, '2026-02-14 18:30:23', '2026-02-14 18:30:23');

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
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `gateway` varchar(30) NOT NULL DEFAULT 'ipaymu',
  `status` varchar(20) NOT NULL DEFAULT 'CREATED',
  `gateway_session_id` varchar(255) DEFAULT NULL,
  `gateway_url` varchar(255) DEFAULT NULL,
  `gateway_trx_id` varchar(255) DEFAULT NULL,
  `raw_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_response`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `gateway`, `status`, `gateway_session_id`, `gateway_url`, `gateway_trx_id`, `raw_response`, `created_at`, `updated_at`) VALUES
(12, 12, 'ipaymu', 'PAID', 'b563659f-1a9e-40c3-9f05-53e920be3634', 'https://sandbox-payment.ipaymu.com/#/b563659f-1a9e-40c3-9f05-53e920be3634', '194168', '{\"Status\":200,\"Success\":true,\"Message\":\"Success\",\"Data\":{\"SessionID\":\"b563659f-1a9e-40c3-9f05-53e920be3634\",\"Url\":\"https:\\/\\/sandbox-payment.ipaymu.com\\/#\\/b563659f-1a9e-40c3-9f05-53e920be3634\"}}', '2026-02-03 01:36:49', '2026-02-03 01:45:13'),
(13, 13, 'ipaymu', 'PAID', '29486aac-a8fb-49e0-803a-b460708cddf4', 'https://sandbox-payment.ipaymu.com/#/29486aac-a8fb-49e0-803a-b460708cddf4', '194172', '{\"Status\":200,\"Success\":true,\"Message\":\"Success\",\"Data\":{\"SessionID\":\"29486aac-a8fb-49e0-803a-b460708cddf4\",\"Url\":\"https:\\/\\/sandbox-payment.ipaymu.com\\/#\\/29486aac-a8fb-49e0-803a-b460708cddf4\"}}', '2026-02-03 01:56:04', '2026-02-03 01:56:39'),
(14, 14, 'ipaymu', 'PAID', 'a7fdaee0-b7fb-437e-8d44-20a30bda8d5e', 'https://sandbox-payment.ipaymu.com/#/a7fdaee0-b7fb-437e-8d44-20a30bda8d5e', '194175', '{\"Status\":200,\"Success\":true,\"Message\":\"Success\",\"Data\":{\"SessionID\":\"a7fdaee0-b7fb-437e-8d44-20a30bda8d5e\",\"Url\":\"https:\\/\\/sandbox-payment.ipaymu.com\\/#\\/a7fdaee0-b7fb-437e-8d44-20a30bda8d5e\"}}', '2026-02-03 02:18:13', '2026-02-03 02:18:53'),
(17, 17, 'ipaymu', 'PENDING', 'c5469fe7-6dc6-4ed6-9afd-2a15de77e8c3', 'https://sandbox-payment.ipaymu.com/#/c5469fe7-6dc6-4ed6-9afd-2a15de77e8c3', NULL, '{\"Status\":200,\"Success\":true,\"Message\":\"Success\",\"Data\":{\"SessionID\":\"c5469fe7-6dc6-4ed6-9afd-2a15de77e8c3\",\"Url\":\"https:\\/\\/sandbox-payment.ipaymu.com\\/#\\/c5469fe7-6dc6-4ed6-9afd-2a15de77e8c3\"}}', '2026-02-03 03:00:05', '2026-02-03 03:00:06'),
(19, 19, 'ipaymu', 'PAID', '60b977fa-b736-4724-891d-f23563749e5f', 'https://sandbox-payment.ipaymu.com/#/60b977fa-b736-4724-891d-f23563749e5f', '194636', '{\"Status\":200,\"Success\":true,\"Message\":\"Success\",\"Data\":{\"SessionID\":\"60b977fa-b736-4724-891d-f23563749e5f\",\"Url\":\"https:\\/\\/sandbox-payment.ipaymu.com\\/#\\/60b977fa-b736-4724-891d-f23563749e5f\"}}', '2026-02-07 05:16:57', '2026-02-07 05:19:17'),
(21, 21, 'ipaymu', 'PAID', '2de42581-669c-4b00-b74f-62d7dcedda9c', 'https://sandbox-payment.ipaymu.com/#/2de42581-669c-4b00-b74f-62d7dcedda9c', '194699', '{\"Status\":200,\"Success\":true,\"Message\":\"Success\",\"Data\":{\"SessionID\":\"2de42581-669c-4b00-b74f-62d7dcedda9c\",\"Url\":\"https:\\/\\/sandbox-payment.ipaymu.com\\/#\\/2de42581-669c-4b00-b74f-62d7dcedda9c\"}}', '2026-02-08 04:30:38', '2026-02-08 04:31:19'),
(30, 30, 'ipaymu', 'PAID', '5275c661-3ba5-4196-be05-6818cfca376f', 'https://sandbox-payment.ipaymu.com/#/5275c661-3ba5-4196-be05-6818cfca376f', '195627', '{\"Status\":200,\"Success\":true,\"Message\":\"Success\",\"Data\":{\"SessionID\":\"5275c661-3ba5-4196-be05-6818cfca376f\",\"Url\":\"https:\\/\\/sandbox-payment.ipaymu.com\\/#\\/5275c661-3ba5-4196-be05-6818cfca376f\"}}', '2026-02-14 01:02:55', '2026-02-14 01:11:36'),
(31, 31, 'ipaymu', 'PAID', '4a5a14df-2e1b-47cc-8bf8-063eca4e8957', 'https://sandbox-payment.ipaymu.com/#/4a5a14df-2e1b-47cc-8bf8-063eca4e8957', '195629', '{\"Status\":200,\"Success\":true,\"Message\":\"Success\",\"Data\":{\"SessionID\":\"4a5a14df-2e1b-47cc-8bf8-063eca4e8957\",\"Url\":\"https:\\/\\/sandbox-payment.ipaymu.com\\/#\\/4a5a14df-2e1b-47cc-8bf8-063eca4e8957\"}}', '2026-02-14 01:12:50', '2026-02-14 01:16:00'),
(32, 33, 'midtrans', 'PAID', '241e887c-d68c-4f3f-91c7-5f74c7d88206', 'https://app.sandbox.midtrans.com/snap/v4/redirection/241e887c-d68c-4f3f-91c7-5f74c7d88206', '5533ff8b-e138-44d6-b7f5-7907bf4ec627', NULL, '2026-02-14 01:46:22', '2026-02-14 01:48:12'),
(34, 35, 'midtrans', 'PAID', '03d87a5a-8716-4850-b937-84bb442c3609', 'https://app.sandbox.midtrans.com/snap/v4/redirection/03d87a5a-8716-4850-b937-84bb442c3609', 'ec336015-260c-4df6-b7cb-9b91bf863287', NULL, '2026-02-14 01:58:44', '2026-02-14 01:59:05'),
(35, 36, 'midtrans', 'PAID', 'eb7aec0f-5032-4250-aad8-8f54829cae9b', 'https://app.sandbox.midtrans.com/snap/v4/redirection/eb7aec0f-5032-4250-aad8-8f54829cae9b', 'e0195918-0395-4843-a1ec-1520cf55e7a7', NULL, '2026-02-14 01:59:53', '2026-02-14 02:00:13'),
(36, 37, 'midtrans', 'PAID', 'e80c5077-c11e-467a-963a-682cc03f34ae', 'https://app.sandbox.midtrans.com/snap/v4/redirection/e80c5077-c11e-467a-963a-682cc03f34ae', '4141d8e5-ca29-4974-a753-23bbb146fd80', NULL, '2026-02-14 02:11:02', '2026-02-14 02:11:20'),
(37, 38, 'midtrans', 'PAID', '4dee4e05-c62e-45ac-9ab6-b718b95f7ec4', 'https://app.sandbox.midtrans.com/snap/v4/redirection/4dee4e05-c62e-45ac-9ab6-b718b95f7ec4', '940e4da4-f473-4c8a-b2b2-0dd6f32eb9dc', NULL, '2026-02-14 18:14:09', '2026-02-14 18:14:19'),
(38, 39, 'midtrans', 'PAID', '6a170f5f-84f8-466e-b190-da9a17d88941', 'https://app.sandbox.midtrans.com/snap/v4/redirection/6a170f5f-84f8-466e-b190-da9a17d88941', 'afa67f10-3fd0-43a9-83e5-c071631ea296', NULL, '2026-02-14 18:30:23', '2026-02-14 18:30:40');

-- --------------------------------------------------------

--
-- Table structure for table `payment_events`
--

CREATE TABLE `payment_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_id` bigint(20) UNSIGNED NOT NULL,
  `event_type` varchar(30) NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  `is_valid` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_events`
--

INSERT INTO `payment_events` (`id`, `payment_id`, `event_type`, `payload`, `is_valid`, `created_at`, `updated_at`) VALUES
(1, 12, 'notify', '{\"trx_id\":\"194168\",\"sid\":\"b563659f-1a9e-40c3-9f05-53e920be3634\",\"reference_id\":\"A-20260203-JQQQ24\",\"status\":\"pending\",\"status_code\":\"0\",\"sub_total\":\"1000\",\"total\":\"1000\",\"amount\":\"1000\",\"fee\":\"7\",\"paid_off\":\"993\",\"created_at\":\"2026-02-03 15:45:07\",\"expired_at\":\"2026-02-04 15:45:07\",\"paid_at\":null,\"settlement_status\":\"unsettle\",\"transaction_status_code\":\"0\",\"is_escrow\":\"0\",\"system_notes\":null,\"via\":\"qris\",\"channel\":\"mpm\",\"payment_no\":null,\"buyer_name\":\"GUS\",\"buyer_email\":\"agimpratamazte@gmail.com\",\"buyer_phone\":\"087786686392\",\"url\":\"https:\\/\\/cafeqr.gimmhost.my.id\\/ipaymu\\/notify\"}', 1, '2026-02-03 01:45:08', '2026-02-03 01:45:08'),
(2, 12, 'notify', '{\"trx_id\":\"194168\",\"sid\":\"b563659f-1a9e-40c3-9f05-53e920be3634\",\"reference_id\":\"A-20260203-JQQQ24\",\"status\":\"berhasil\",\"status_code\":\"1\",\"sub_total\":\"1000\",\"total\":\"1000\",\"amount\":\"1000\",\"fee\":\"7\",\"paid_off\":\"993\",\"created_at\":\"2026-02-03 15:45:07\",\"expired_at\":\"2026-02-04 15:45:07\",\"paid_at\":\"2026-02-03 15:45:12\",\"settlement_status\":\"settled\",\"transaction_status_code\":\"1\",\"is_escrow\":\"0\",\"system_notes\":\"Sandbox notify\",\"via\":\"qris\",\"channel\":\"mpm\",\"payment_no\":null,\"buyer_name\":\"GUS\",\"buyer_email\":\"agimpratamazte@gmail.com\",\"buyer_phone\":\"087786686392\",\"url\":\"https:\\/\\/cafeqr.gimmhost.my.id\\/ipaymu\\/notify\"}', 1, '2026-02-03 01:45:13', '2026-02-03 01:45:13'),
(3, 13, 'notify', '{\"trx_id\":\"194172\",\"sid\":\"29486aac-a8fb-49e0-803a-b460708cddf4\",\"reference_id\":\"A-20260203-PXFFGO\",\"status\":\"pending\",\"status_code\":\"0\",\"sub_total\":\"1000\",\"total\":\"1000\",\"amount\":\"1000\",\"fee\":\"7\",\"paid_off\":\"993\",\"created_at\":\"2026-02-03 15:56:28\",\"expired_at\":\"2026-02-04 15:56:28\",\"paid_at\":null,\"settlement_status\":\"unsettle\",\"transaction_status_code\":\"0\",\"is_escrow\":\"0\",\"system_notes\":null,\"via\":\"qris\",\"channel\":\"mpm\",\"payment_no\":null,\"buyer_name\":\"Ay\",\"buyer_email\":\"agimpratamazte@gmail.com\",\"buyer_phone\":\"087786686392\",\"url\":\"https:\\/\\/cafeqr.gimmhost.my.id\\/ipaymu\\/notify\"}', 1, '2026-02-03 01:56:29', '2026-02-03 01:56:29'),
(4, 13, 'notify', '{\"trx_id\":\"194172\",\"sid\":\"29486aac-a8fb-49e0-803a-b460708cddf4\",\"reference_id\":\"A-20260203-PXFFGO\",\"status\":\"berhasil\",\"status_code\":\"1\",\"sub_total\":\"1000\",\"total\":\"1000\",\"amount\":\"1000\",\"fee\":\"7\",\"paid_off\":\"993\",\"created_at\":\"2026-02-03 15:56:28\",\"expired_at\":\"2026-02-04 15:56:28\",\"paid_at\":\"2026-02-03 15:56:37\",\"settlement_status\":\"settled\",\"transaction_status_code\":\"1\",\"is_escrow\":\"0\",\"system_notes\":\"Sandbox notify\",\"via\":\"qris\",\"channel\":\"mpm\",\"payment_no\":null,\"buyer_name\":\"Ay\",\"buyer_email\":\"agimpratamazte@gmail.com\",\"buyer_phone\":\"087786686392\",\"url\":\"https:\\/\\/cafeqr.gimmhost.my.id\\/ipaymu\\/notify\"}', 1, '2026-02-03 01:56:39', '2026-02-03 01:56:39'),
(5, 14, 'notify', '{\"trx_id\":\"194175\",\"sid\":\"a7fdaee0-b7fb-437e-8d44-20a30bda8d5e\",\"reference_id\":\"A-20260203-A3NP2P\",\"status\":\"berhasil\",\"status_code\":\"1\",\"sub_total\":\"23000\",\"total\":\"23000\",\"amount\":\"23000\",\"fee\":\"161\",\"paid_off\":\"22839\",\"created_at\":\"2026-02-03 16:18:34\",\"expired_at\":\"2026-02-04 16:18:34\",\"paid_at\":\"2026-02-03 16:18:51\",\"settlement_status\":\"settled\",\"transaction_status_code\":\"1\",\"is_escrow\":\"0\",\"system_notes\":\"Sandbox notify\",\"via\":\"qris\",\"channel\":\"mpm\",\"payment_no\":null,\"buyer_name\":\"BOS\",\"buyer_email\":\"agimpratamazte@gmail.com\",\"buyer_phone\":\"087786686392\",\"url\":\"https:\\/\\/cafeqr.gimmhost.my.id\\/ipaymu\\/notify\"}', 1, '2026-02-03 02:18:53', '2026-02-03 02:18:53'),
(7, 19, 'notify', '{\"trx_id\":\"194636\",\"sid\":\"60b977fa-b736-4724-891d-f23563749e5f\",\"reference_id\":\"A-20260207-MVNO8U\",\"status\":\"berhasil\",\"status_code\":\"1\",\"sub_total\":\"1000\",\"total\":\"1000\",\"amount\":\"1000\",\"fee\":\"7\",\"paid_off\":\"993\",\"created_at\":\"2026-02-07 19:18:24\",\"expired_at\":\"2026-02-08 19:18:24\",\"paid_at\":\"2026-02-07 19:19:11\",\"settlement_status\":\"settled\",\"transaction_status_code\":\"1\",\"is_escrow\":\"0\",\"system_notes\":\"Sandbox notify\",\"via\":\"qris\",\"channel\":\"mpm\",\"payment_no\":null,\"buyer_name\":\"Lamar\",\"buyer_email\":\"kakakakak@gmail.com\",\"buyer_phone\":\"085444661199\",\"url\":\"https:\\/\\/cafeqr.gimmhost.my.id\\/ipaymu\\/notify\"}', 1, '2026-02-07 05:19:16', '2026-02-07 05:19:16'),
(8, 21, 'notify', '{\"trx_id\":\"194699\",\"sid\":\"2de42581-669c-4b00-b74f-62d7dcedda9c\",\"reference_id\":\"A-20260208-OYHOLQ\",\"status\":\"pending\",\"status_code\":\"0\",\"sub_total\":\"25000\",\"total\":\"25000\",\"amount\":\"25000\",\"fee\":\"175\",\"paid_off\":\"24825\",\"created_at\":\"2026-02-08 18:31:04\",\"expired_at\":\"2026-02-09 18:31:04\",\"paid_at\":null,\"settlement_status\":\"unsettle\",\"transaction_status_code\":\"0\",\"is_escrow\":\"0\",\"system_notes\":null,\"via\":\"qris\",\"channel\":\"mpm\",\"payment_no\":null,\"buyer_name\":\"aas\",\"buyer_email\":\"agimpratamazte@gmail.com\",\"buyer_phone\":\"087786686392\",\"url\":\"https:\\/\\/cafeqr.gimmhost.my.id\\/ipaymu\\/notify\"}', 1, '2026-02-08 04:31:06', '2026-02-08 04:31:06'),
(9, 21, 'notify', '{\"trx_id\":\"194699\",\"sid\":\"2de42581-669c-4b00-b74f-62d7dcedda9c\",\"reference_id\":\"A-20260208-OYHOLQ\",\"status\":\"berhasil\",\"status_code\":\"1\",\"sub_total\":\"25000\",\"total\":\"25000\",\"amount\":\"25000\",\"fee\":\"175\",\"paid_off\":\"24825\",\"created_at\":\"2026-02-08 18:31:04\",\"expired_at\":\"2026-02-09 18:31:04\",\"paid_at\":\"2026-02-08 18:31:18\",\"settlement_status\":\"settled\",\"transaction_status_code\":\"1\",\"is_escrow\":\"0\",\"system_notes\":\"Sandbox notify\",\"via\":\"qris\",\"channel\":\"mpm\",\"payment_no\":null,\"buyer_name\":\"aas\",\"buyer_email\":\"agimpratamazte@gmail.com\",\"buyer_phone\":\"087786686392\",\"url\":\"https:\\/\\/cafeqr.gimmhost.my.id\\/ipaymu\\/notify\"}', 1, '2026-02-08 04:31:19', '2026-02-08 04:31:19'),
(16, 30, 'notify', '{\"trx_id\":\"195623\",\"sid\":\"8115c26c-fa54-4510-8e91-b7115aea2080\",\"reference_id\":\"A-20260214-EV0XHL\",\"status\":\"pending\",\"status_code\":\"0\",\"sub_total\":\"1000\",\"total\":\"1000\",\"amount\":\"1000\",\"fee\":\"7\",\"paid_off\":\"993\",\"created_at\":\"2026-02-14 15:03:26\",\"expired_at\":\"2026-02-15 15:03:26\",\"paid_at\":null,\"settlement_status\":\"unsettle\",\"transaction_status_code\":\"0\",\"is_escrow\":\"0\",\"system_notes\":null,\"via\":\"qris\",\"channel\":\"mpm\",\"payment_no\":null,\"buyer_name\":\"gimM\",\"buyer_email\":\"novniv68@gmail.com\",\"buyer_phone\":\"082259671573\",\"url\":\"https:\\/\\/cafeqrv2.gimmhost.my.id\\/ipaymu\\/notify\"}', 0, '2026-02-14 01:03:27', '2026-02-14 01:03:27'),
(17, 30, 'notify', '{\"trx_id\":\"195623\",\"sid\":\"8115c26c-fa54-4510-8e91-b7115aea2080\",\"reference_id\":\"A-20260214-EV0XHL\",\"status\":\"berhasil\",\"status_code\":\"1\",\"sub_total\":\"1000\",\"total\":\"1000\",\"amount\":\"1000\",\"fee\":\"7\",\"paid_off\":\"993\",\"created_at\":\"2026-02-14 15:03:26\",\"expired_at\":\"2026-02-15 15:03:26\",\"paid_at\":\"2026-02-14 15:03:37\",\"settlement_status\":\"settled\",\"transaction_status_code\":\"1\",\"is_escrow\":\"0\",\"system_notes\":\"Sandbox notify\",\"via\":\"qris\",\"channel\":\"mpm\",\"payment_no\":null,\"buyer_name\":\"gimM\",\"buyer_email\":\"novniv68@gmail.com\",\"buyer_phone\":\"082259671573\",\"url\":\"https:\\/\\/cafeqrv2.gimmhost.my.id\\/ipaymu\\/notify\"}', 0, '2026-02-14 01:03:38', '2026-02-14 01:03:38'),
(18, 30, 'notify', '{\"trx_id\":\"195624\",\"sid\":\"054fa5b9-a39c-4298-b6b9-7e424ad3a605\",\"reference_id\":\"A-20260214-EV0XHL\",\"status\":\"pending\",\"status_code\":\"0\",\"sub_total\":\"1000\",\"total\":\"1000\",\"amount\":\"1000\",\"fee\":\"7\",\"paid_off\":\"993\",\"created_at\":\"2026-02-14 15:05:02\",\"expired_at\":\"2026-02-15 15:05:02\",\"paid_at\":null,\"settlement_status\":\"unsettle\",\"transaction_status_code\":\"0\",\"is_escrow\":\"0\",\"system_notes\":null,\"via\":\"qris\",\"channel\":\"mpm\",\"payment_no\":null,\"buyer_name\":\"gimM\",\"buyer_email\":\"novniv68@gmail.com\",\"buyer_phone\":\"082259671573\",\"url\":\"https:\\/\\/cafeqrv2.gimmhost.my.id\\/ipaymu\\/notify\"}', 0, '2026-02-14 01:05:03', '2026-02-14 01:05:03'),
(19, 30, 'notify', '{\"trx_id\":\"195624\",\"sid\":\"054fa5b9-a39c-4298-b6b9-7e424ad3a605\",\"reference_id\":\"A-20260214-EV0XHL\",\"status\":\"berhasil\",\"status_code\":\"1\",\"sub_total\":\"1000\",\"total\":\"1000\",\"amount\":\"1000\",\"fee\":\"7\",\"paid_off\":\"993\",\"created_at\":\"2026-02-14 15:05:02\",\"expired_at\":\"2026-02-15 15:05:02\",\"paid_at\":\"2026-02-14 15:05:50\",\"settlement_status\":\"settled\",\"transaction_status_code\":\"1\",\"is_escrow\":\"0\",\"system_notes\":\"Sandbox notify\",\"via\":\"qris\",\"channel\":\"mpm\",\"payment_no\":null,\"buyer_name\":\"gimM\",\"buyer_email\":\"novniv68@gmail.com\",\"buyer_phone\":\"082259671573\",\"url\":\"https:\\/\\/cafeqrv2.gimmhost.my.id\\/ipaymu\\/notify\"}', 0, '2026-02-14 01:05:51', '2026-02-14 01:05:51'),
(20, 30, 'notify', '{\"trx_id\":\"195627\",\"sid\":\"5275c661-3ba5-4196-be05-6818cfca376f\",\"reference_id\":\"A-20260214-EV0XHL\",\"status\":\"pending\",\"status_code\":\"0\",\"sub_total\":\"1000\",\"total\":\"1000\",\"amount\":\"1000\",\"fee\":\"7\",\"paid_off\":\"993\",\"created_at\":\"2026-02-14 15:11:27\",\"expired_at\":\"2026-02-15 15:11:27\",\"paid_at\":null,\"settlement_status\":\"unsettle\",\"transaction_status_code\":\"0\",\"is_escrow\":\"0\",\"system_notes\":null,\"via\":\"qris\",\"channel\":\"mpm\",\"payment_no\":null,\"buyer_name\":\"gimM\",\"buyer_email\":\"novniv68@gmail.com\",\"buyer_phone\":\"123456789123\",\"url\":\"https:\\/\\/cafeqrv2.gimmhost.my.id\\/ipaymu\\/notify\"}', 0, '2026-02-14 01:11:27', '2026-02-14 01:11:27'),
(21, 30, 'notify', '{\"trx_id\":\"195627\",\"sid\":\"5275c661-3ba5-4196-be05-6818cfca376f\",\"reference_id\":\"A-20260214-EV0XHL\",\"status\":\"berhasil\",\"status_code\":\"1\",\"sub_total\":\"1000\",\"total\":\"1000\",\"amount\":\"1000\",\"fee\":\"7\",\"paid_off\":\"993\",\"created_at\":\"2026-02-14 15:11:27\",\"expired_at\":\"2026-02-15 15:11:27\",\"paid_at\":\"2026-02-14 15:11:35\",\"settlement_status\":\"settled\",\"transaction_status_code\":\"1\",\"is_escrow\":\"0\",\"system_notes\":\"Sandbox notify\",\"via\":\"qris\",\"channel\":\"mpm\",\"payment_no\":null,\"buyer_name\":\"gimM\",\"buyer_email\":\"novniv68@gmail.com\",\"buyer_phone\":\"123456789123\",\"url\":\"https:\\/\\/cafeqrv2.gimmhost.my.id\\/ipaymu\\/notify\"}', 1, '2026-02-14 01:11:36', '2026-02-14 01:11:36'),
(22, 31, 'notify', '{\"trx_id\":\"195629\",\"sid\":\"4a5a14df-2e1b-47cc-8bf8-063eca4e8957\",\"reference_id\":\"A-20260214-RBA29H\",\"status\":\"pending\",\"status_code\":\"0\",\"sub_total\":\"25000\",\"total\":\"25000\",\"amount\":\"25000\",\"fee\":\"175\",\"paid_off\":\"24825\",\"created_at\":\"2026-02-14 15:13:39\",\"expired_at\":\"2026-02-15 15:13:38\",\"paid_at\":null,\"settlement_status\":\"unsettle\",\"transaction_status_code\":\"0\",\"is_escrow\":\"0\",\"system_notes\":null,\"via\":\"qris\",\"channel\":\"mpm\",\"payment_no\":null,\"buyer_name\":\"gim\",\"buyer_email\":\"novniv67@gmail.com\",\"buyer_phone\":\"123456789123\",\"url\":\"https:\\/\\/cafeqrv2.gimmhost.my.id\\/ipaymu\\/notify\"}', 0, '2026-02-14 01:13:41', '2026-02-14 01:13:41'),
(23, 31, 'notify', '{\"trx_id\":\"195629\",\"sid\":\"4a5a14df-2e1b-47cc-8bf8-063eca4e8957\",\"reference_id\":\"A-20260214-RBA29H\",\"status\":\"berhasil\",\"status_code\":\"1\",\"sub_total\":\"25000\",\"total\":\"25000\",\"amount\":\"25000\",\"fee\":\"175\",\"paid_off\":\"24825\",\"created_at\":\"2026-02-14 15:13:39\",\"expired_at\":\"2026-02-15 15:13:38\",\"paid_at\":\"2026-02-14 15:15:59\",\"settlement_status\":\"settled\",\"transaction_status_code\":\"1\",\"is_escrow\":\"0\",\"system_notes\":\"Sandbox notify\",\"via\":\"qris\",\"channel\":\"mpm\",\"payment_no\":null,\"buyer_name\":\"gim\",\"buyer_email\":\"novniv67@gmail.com\",\"buyer_phone\":\"123456789123\",\"url\":\"https:\\/\\/cafeqrv2.gimmhost.my.id\\/ipaymu\\/notify\"}', 1, '2026-02-14 01:16:00', '2026-02-14 01:16:00'),
(24, 32, 'pending', '{\"transaction_type\":\"off-us\",\"transaction_time\":\"2026-02-14 15:46:54\",\"transaction_status\":\"pending\",\"transaction_id\":\"5533ff8b-e138-44d6-b7f5-7907bf4ec627\",\"status_message\":\"midtrans payment notification\",\"status_code\":\"201\",\"signature_key\":\"3c46aae3353b538d4ac87d971651ecd4acfaf2672f3392be6316a33fac3cd2dca10741392a9619cde781498b58060f0284b378631122f731c6baadb7b8319065\",\"payment_type\":\"qris\",\"order_id\":\"A-20260214-OCADND\",\"merchant_id\":\"G637999158\",\"gross_amount\":\"27000.00\",\"fraud_status\":\"accept\",\"expiry_time\":\"2026-02-14 15:56:22\",\"customer_details\":{\"full_name\":\"ahmad\",\"email\":\"guest@cafe.local\"},\"currency\":\"IDR\"}', 1, '2026-02-14 01:46:55', '2026-02-14 01:46:55'),
(25, 32, 'settlement', '{\"transaction_type\":\"on-us\",\"transaction_time\":\"2026-02-14 15:46:54\",\"transaction_status\":\"settlement\",\"transaction_id\":\"5533ff8b-e138-44d6-b7f5-7907bf4ec627\",\"status_message\":\"midtrans payment notification\",\"status_code\":\"200\",\"signature_key\":\"cfd901aa7f1f789c2a010261cccedcddaf933ef942c809391157118f475cacf22d051be50957841f8be91bd55dd0ee6bd6ab69180e77080a1ceb6f2bde7b3d89\",\"settlement_time\":\"2026-02-14 15:48:10\",\"pop_id\":\"12b610a0-273c-444c-81c6-31d89865cfa9\",\"payment_type\":\"qris\",\"order_id\":\"A-20260214-OCADND\",\"merchant_id\":\"G637999158\",\"merchant_cross_reference_id\":\"37da0b5c-a518-4f14-91b7-d7ef81b5565a\",\"issuer\":\"gopay\",\"gross_amount\":\"27000.00\",\"fraud_status\":\"accept\",\"expiry_time\":\"2026-02-14 15:56:22\",\"customer_details\":{\"full_name\":\"ahmad\",\"email\":\"guest@cafe.local\"},\"currency\":\"IDR\",\"acquirer\":\"gopay\"}', 1, '2026-02-14 01:48:12', '2026-02-14 01:48:12'),
(27, 34, 'pending', '{\"transaction_type\":\"off-us\",\"transaction_time\":\"2026-02-14 15:58:52\",\"transaction_status\":\"pending\",\"transaction_id\":\"ec336015-260c-4df6-b7cb-9b91bf863287\",\"status_message\":\"midtrans payment notification\",\"status_code\":\"201\",\"signature_key\":\"002fdd92afd12c5b3f9a8b51469ffb415f450ac8089518a6498e5c9e3e8566143cbb2c7a3e3ec76a7fb420cc0ba146e9f07c429d483299cf12f44134b8f35159\",\"payment_type\":\"qris\",\"order_id\":\"A-20260214-GKOSIY\",\"merchant_id\":\"G637999158\",\"gross_amount\":\"40000.00\",\"fraud_status\":\"accept\",\"expiry_time\":\"2026-02-14 16:08:43\",\"customer_details\":{\"full_name\":\"gimM\",\"email\":\"guest@cafe.local\"},\"currency\":\"IDR\"}', 1, '2026-02-14 01:58:53', '2026-02-14 01:58:53'),
(28, 34, 'settlement', '{\"transaction_type\":\"on-us\",\"transaction_time\":\"2026-02-14 15:58:52\",\"transaction_status\":\"settlement\",\"transaction_id\":\"ec336015-260c-4df6-b7cb-9b91bf863287\",\"status_message\":\"midtrans payment notification\",\"status_code\":\"200\",\"signature_key\":\"06789a71ef2dab987672dbf04997d7fb07ed1e273f7589d0a7fc6f328bf55e363a6f3cdf4bbcc0dfb799b90f94ecf810744fed9ef625799e8c4fb2f7c2df0e81\",\"settlement_time\":\"2026-02-14 15:59:04\",\"pop_id\":\"12b610a0-273c-444c-81c6-31d89865cfa9\",\"payment_type\":\"qris\",\"order_id\":\"A-20260214-GKOSIY\",\"merchant_id\":\"G637999158\",\"merchant_cross_reference_id\":\"37da0b5c-a518-4f14-91b7-d7ef81b5565a\",\"issuer\":\"gopay\",\"gross_amount\":\"40000.00\",\"fraud_status\":\"accept\",\"expiry_time\":\"2026-02-14 16:08:43\",\"customer_details\":{\"full_name\":\"gimM\",\"email\":\"guest@cafe.local\"},\"currency\":\"IDR\",\"acquirer\":\"gopay\"}', 1, '2026-02-14 01:59:05', '2026-02-14 01:59:05'),
(29, 35, 'pending', '{\"transaction_type\":\"off-us\",\"transaction_time\":\"2026-02-14 16:00:04\",\"transaction_status\":\"pending\",\"transaction_id\":\"e0195918-0395-4843-a1ec-1520cf55e7a7\",\"status_message\":\"midtrans payment notification\",\"status_code\":\"201\",\"signature_key\":\"a524be21acf398fd38712f69c671b3d0c3abde6c647cf631dcdac71778d8cf30e07028880ce3c0ea57695616a30a07f8f446632a99ecb0ee8eb9b8060d31efa3\",\"payment_type\":\"qris\",\"order_id\":\"A-20260214-TKHWUZ\",\"merchant_id\":\"G637999158\",\"gross_amount\":\"13000.00\",\"fraud_status\":\"accept\",\"expiry_time\":\"2026-02-14 16:09:52\",\"customer_details\":{\"full_name\":\"AAA\",\"email\":\"guest@cafe.local\"},\"currency\":\"IDR\"}', 1, '2026-02-14 02:00:05', '2026-02-14 02:00:05'),
(30, 35, 'settlement', '{\"transaction_type\":\"on-us\",\"transaction_time\":\"2026-02-14 16:00:04\",\"transaction_status\":\"settlement\",\"transaction_id\":\"e0195918-0395-4843-a1ec-1520cf55e7a7\",\"status_message\":\"midtrans payment notification\",\"status_code\":\"200\",\"signature_key\":\"f8f8f58cf5143f032100620a10dbe5325238e3577731432a12db3a3b912c4407f98c496716a8437e4e8694729ae12c2416c99960181b9d3b50342c2f16a341d6\",\"settlement_time\":\"2026-02-14 16:00:12\",\"pop_id\":\"12b610a0-273c-444c-81c6-31d89865cfa9\",\"payment_type\":\"qris\",\"order_id\":\"A-20260214-TKHWUZ\",\"merchant_id\":\"G637999158\",\"merchant_cross_reference_id\":\"37da0b5c-a518-4f14-91b7-d7ef81b5565a\",\"issuer\":\"gopay\",\"gross_amount\":\"13000.00\",\"fraud_status\":\"accept\",\"expiry_time\":\"2026-02-14 16:09:52\",\"customer_details\":{\"full_name\":\"AAA\",\"email\":\"guest@cafe.local\"},\"currency\":\"IDR\",\"acquirer\":\"gopay\"}', 1, '2026-02-14 02:00:13', '2026-02-14 02:00:13'),
(32, 36, 'pending', '{\"transaction_type\":\"off-us\",\"transaction_time\":\"2026-02-14 16:11:04\",\"transaction_status\":\"pending\",\"transaction_id\":\"4141d8e5-ca29-4974-a753-23bbb146fd80\",\"status_message\":\"midtrans payment notification\",\"status_code\":\"201\",\"signature_key\":\"7280e590923041957d18d3bfae016c917d9860493cbcc417d71069302c085eb7e0ecbffef7d9f99c4551ec30d36b0f4c34166dd70aeb8ad3fe67c52a0400af63\",\"payment_type\":\"qris\",\"order_id\":\"A-20260214-43V71C\",\"merchant_id\":\"G637999158\",\"gross_amount\":\"42000.00\",\"fraud_status\":\"accept\",\"expiry_time\":\"2026-02-14 16:21:01\",\"customer_details\":{\"full_name\":\"ahmad\",\"email\":\"guest@cafe.local\"},\"currency\":\"IDR\"}', 1, '2026-02-14 02:11:06', '2026-02-14 02:11:06'),
(33, 36, 'settlement', '{\"transaction_type\":\"on-us\",\"transaction_time\":\"2026-02-14 16:11:04\",\"transaction_status\":\"settlement\",\"transaction_id\":\"4141d8e5-ca29-4974-a753-23bbb146fd80\",\"status_message\":\"midtrans payment notification\",\"status_code\":\"200\",\"signature_key\":\"708b5fd7da1221e0da06bbcf6e7d28ac0bfa119d8675ae8e24208932419fead15cddde440875583fdf3405a64a94a273f689338a1f4e3faf50c180c79337202c\",\"settlement_time\":\"2026-02-14 16:11:19\",\"pop_id\":\"12b610a0-273c-444c-81c6-31d89865cfa9\",\"payment_type\":\"qris\",\"order_id\":\"A-20260214-43V71C\",\"merchant_id\":\"G637999158\",\"merchant_cross_reference_id\":\"37da0b5c-a518-4f14-91b7-d7ef81b5565a\",\"issuer\":\"gopay\",\"gross_amount\":\"42000.00\",\"fraud_status\":\"accept\",\"expiry_time\":\"2026-02-14 16:21:01\",\"customer_details\":{\"full_name\":\"ahmad\",\"email\":\"guest@cafe.local\"},\"currency\":\"IDR\",\"acquirer\":\"gopay\"}', 1, '2026-02-14 02:11:20', '2026-02-14 02:11:20'),
(34, 37, 'pending', '{\"transaction_type\":\"off-us\",\"transaction_time\":\"2026-02-15 01:14:12\",\"transaction_status\":\"pending\",\"transaction_id\":\"940e4da4-f473-4c8a-b2b2-0dd6f32eb9dc\",\"status_message\":\"midtrans payment notification\",\"status_code\":\"201\",\"signature_key\":\"d3996c4322b80502eadd0fe51302beddabb91302a3e32d137fb4f007a2f71e1593fe49db6029c8cadd13545b1b9c6192648ba36b76ed44bff88708e331f0e9e7\",\"payment_type\":\"qris\",\"order_id\":\"A-20260215-YDMARV\",\"merchant_id\":\"G637999158\",\"gross_amount\":\"56000.00\",\"fraud_status\":\"accept\",\"expiry_time\":\"2026-02-15 01:24:08\",\"customer_details\":{\"full_name\":\"gim\",\"email\":\"guest@cafe.local\"},\"currency\":\"IDR\"}', 1, '2026-02-14 18:14:14', '2026-02-14 18:14:14'),
(35, 37, 'settlement', '{\"transaction_type\":\"on-us\",\"transaction_time\":\"2026-02-15 01:14:12\",\"transaction_status\":\"settlement\",\"transaction_id\":\"940e4da4-f473-4c8a-b2b2-0dd6f32eb9dc\",\"status_message\":\"midtrans payment notification\",\"status_code\":\"200\",\"signature_key\":\"a0db6527769e99b511aae34df0f9558c82b123f89e4680691f75410d7ee1e95a27ccaf9fc2f6129b2d97a041002adbcf03cbe72b2524aa6b9662341776afc54c\",\"settlement_time\":\"2026-02-15 01:14:18\",\"pop_id\":\"12b610a0-273c-444c-81c6-31d89865cfa9\",\"payment_type\":\"qris\",\"order_id\":\"A-20260215-YDMARV\",\"merchant_id\":\"G637999158\",\"merchant_cross_reference_id\":\"37da0b5c-a518-4f14-91b7-d7ef81b5565a\",\"issuer\":\"gopay\",\"gross_amount\":\"56000.00\",\"fraud_status\":\"accept\",\"expiry_time\":\"2026-02-15 01:24:08\",\"customer_details\":{\"full_name\":\"gim\",\"email\":\"guest@cafe.local\"},\"currency\":\"IDR\",\"acquirer\":\"gopay\"}', 1, '2026-02-14 18:14:19', '2026-02-14 18:14:19'),
(36, 38, 'pending', '{\"transaction_type\":\"off-us\",\"transaction_time\":\"2026-02-15 01:30:26\",\"transaction_status\":\"pending\",\"transaction_id\":\"afa67f10-3fd0-43a9-83e5-c071631ea296\",\"status_message\":\"midtrans payment notification\",\"status_code\":\"201\",\"signature_key\":\"baf3b436a6a0e0c323b0b13fea3e2cd3b3317d6d953b887bf1d2eacd53efeffa082d1308923f7d87d73f2b80dd6eb2f66bfd5c3e38de308aed4f52ade96ca557\",\"payment_type\":\"qris\",\"order_id\":\"A-20260215-AXIIBK\",\"merchant_id\":\"G637999158\",\"gross_amount\":\"30000.00\",\"fraud_status\":\"accept\",\"expiry_time\":\"2026-02-15 01:40:23\",\"customer_details\":{\"full_name\":\"AAA\",\"email\":\"guest@cafe.local\"},\"currency\":\"IDR\"}', 1, '2026-02-14 18:30:26', '2026-02-14 18:30:26'),
(37, 38, 'settlement', '{\"transaction_type\":\"on-us\",\"transaction_time\":\"2026-02-15 01:30:26\",\"transaction_status\":\"settlement\",\"transaction_id\":\"afa67f10-3fd0-43a9-83e5-c071631ea296\",\"status_message\":\"midtrans payment notification\",\"status_code\":\"200\",\"signature_key\":\"a6a7f0c17aa2dfd73df367e33aafc7c0b95a9a847503f7ab805c9b466d8f80f58293ceffedcec5c178739429e9dcd1aab2a16c1e387ba0fff18f719e4823a3be\",\"settlement_time\":\"2026-02-15 01:30:39\",\"pop_id\":\"12b610a0-273c-444c-81c6-31d89865cfa9\",\"payment_type\":\"qris\",\"order_id\":\"A-20260215-AXIIBK\",\"merchant_id\":\"G637999158\",\"merchant_cross_reference_id\":\"37da0b5c-a518-4f14-91b7-d7ef81b5565a\",\"issuer\":\"gopay\",\"gross_amount\":\"30000.00\",\"fraud_status\":\"accept\",\"expiry_time\":\"2026-02-15 01:40:23\",\"customer_details\":{\"full_name\":\"AAA\",\"email\":\"guest@cafe.local\"},\"currency\":\"IDR\",\"acquirer\":\"gopay\"}', 1, '2026-02-14 18:30:40', '2026-02-14 18:30:40');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `base_price` bigint(20) UNSIGNED NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_sold_out` tinyint(1) NOT NULL DEFAULT 0,
  `is_best_seller` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `description`, `base_price`, `image_url`, `is_active`, `is_sold_out`, `is_best_seller`, `created_at`, `updated_at`) VALUES
(1, 1, 'Americano', 'americano-vkfv', 'Espresso dengan air panas, bold dan rich', 22000, 'https://nnc-media.netralnews.com/2025/10/IMG-Netral-News-User-16161-NWCRQ847IS-Thumbnail-L.jpg', 1, 0, 1, '2026-02-02 23:08:52', '2026-02-02 23:13:19'),
(2, 1, 'Cappuccino', 'cappuccino-ddbe', 'Espresso dengan susu steamed dan foam tebal', 28000, 'https://www.acouplecooks.com/wp-content/uploads/2020/10/how-to-make-cappuccino-005.jpg', 1, 0, 1, '2026-02-02 23:08:52', '2026-02-02 23:13:36'),
(3, 1, 'Caffe Latte', 'caffe-latte-yzvl', 'Espresso dengan susu steamed yang lembut', 30000, 'https://www.nescafe.com/id/sites/default/files/2023-08/Fakta-Menarik-Seputar-Caff_-Latte%2C-Kopi-Susu-ala-Italia-desktop.jpeg', 1, 0, 0, '2026-02-02 23:08:52', '2026-02-02 23:13:48'),
(4, 1, 'Mocha', 'mocha-u1k5', 'Perpaduan espresso, coklat, dan susu', 32000, 'https://gatherforbread.com/wp-content/uploads/2014/10/Dark-Chocolate-Mocha-Square.jpg', 1, 0, 0, '2026-02-02 23:08:52', '2026-02-02 23:14:08'),
(5, 1, 'Espresso', 'espresso-s8yp', 'Single shot espresso murni', 18000, 'https://www.sharmispassions.com/wp-content/uploads/2012/07/espresso-coffee-recipe04-500x500.jpg', 1, 0, 0, '2026-02-02 23:08:52', '2026-02-02 23:14:26'),
(6, 2, 'Matcha Latte', 'matcha-latte-uvkd', 'Green tea premium dengan susu segar', 32000, 'https://www.ohhowcivilized.com/wp-content/uploads/iced-matcha-latte-recipe.jpg', 1, 0, 1, '2026-02-02 23:08:52', '2026-02-02 23:14:42'),
(7, 2, 'Hot Chocolate', 'hot-chocolate-xx2m', 'Coklat premium dengan susu hangat', 28000, 'https://wholefully.com/wp-content/uploads/2017/06/red-wine-hot-chocolate-2-800x1200.jpg', 1, 0, 0, '2026-02-02 23:08:52', '2026-02-02 23:14:58'),
(9, 3, 'French Fries', 'french-fries-qlt7', 'Kentang goreng crispy dengan saus', 25000, 'https://thecozycook.com/wp-content/uploads/2018/10/Homemade-French-Fry-Recipe-.jpg', 1, 0, 0, '2026-02-02 23:08:52', '2026-02-02 23:15:14'),
(11, 4, 'Cheesecake', 'cheesecake-1jbk', 'New York style cheesecake', 40000, 'https://www.onceuponachef.com/images/2017/12/cheesecake.jpg', 1, 0, 0, '2026-02-02 23:08:52', '2026-02-02 23:15:29'),
(12, 4, 'Chocolate Brownie', 'chocolate-brownie-owkw', 'Brownie dengan ice cream vanilla', 35000, 'https://i0.wp.com/cookingwithbry.com/wp-content/uploads/chocolate-brownies-recipe.png?resize=720%2C720&ssl=1', 1, 0, 0, '2026-02-02 23:08:52', '2026-02-02 23:15:46'),
(13, 3, 'Cookies', 'cookies-bbm2', 'Cookies', 1000, 'https://assets.bonappetit.com/photos/5ca534485e96521ff23b382b/1:1/w_2560%2Cc_limit/chocolate-chip-cookie.jpg', 1, 0, 0, '2026-02-02 23:43:44', '2026-02-02 23:43:44'),
(14, 3, 'Tempe Goreng', 'tempe-goreng-8u3u', NULL, 1000, 'https://cdn.yummy.co.id/content-images/images/20230622/fHaK3v61mmKMWYwzVoSSX21meKqgEsNP-31363837343232343632d41d8cd98f00b204e9800998ecf8427e.jpg?x-oss-process=image/resize,w_388,h_388,m_fixed,x-oss-process=image/format,webp', 1, 0, 0, '2026-02-03 00:07:28', '2026-02-03 00:07:28');

-- --------------------------------------------------------

--
-- Table structure for table `product_mod_groups`
--

CREATE TABLE `product_mod_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `mod_group_id` bigint(20) UNSIGNED NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_mod_groups`
--

INSERT INTO `product_mod_groups` (`id`, `product_id`, `mod_group_id`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(2, 1, 2, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(3, 1, 3, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(4, 1, 4, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(5, 2, 1, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(6, 2, 2, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(7, 2, 3, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(8, 2, 4, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(9, 3, 1, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(10, 3, 2, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(11, 3, 3, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(12, 3, 4, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(13, 4, 1, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(14, 4, 2, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(15, 4, 3, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(16, 4, 4, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(17, 5, 1, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(18, 5, 2, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(19, 5, 3, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(20, 5, 4, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(21, 6, 1, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(22, 6, 2, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(23, 6, 3, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(24, 7, 1, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(25, 7, 2, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(26, 7, 3, 0, '2026-02-02 23:08:52', '2026-02-02 23:08:52');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4xw0DtuahQjiF7kgErChHY5FvomW4GPD82MQeBFF', NULL, '147.139.173.83', 'Veritrans', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiSzhjSzlUSDM3OWhycXNiTHVVWFpRdUwybnViTXRieGhKZDY4bUZneCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771093826),
('EavSFMlmMd09ZVXAEA6vDQCupg8caUw2zhM31Bje', NULL, '147.139.132.215', 'Veritrans', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoib1Jaa1NXV042M3RZbllJM3p1aUJnZVhmN2lEd25KeHhmS0luOGlMWiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771093840),
('kxhCGnv0zkRX0nufzBKMbINuinAJIUbnrPbfGUTH', NULL, '36.74.118.13', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoib2lZMjNSMk5HaUFBVElyeFZlNVJVQmpZQ0hPam11SG5HYXZ6dUFOcCI7czoxMzoiY2FmZV90YWJsZV9pZCI7aToyO3M6MTM6ImNhZmVfdGFibGVfbm8iO3M6MToiMiI7czoxNjoiY2FmZV90YWJsZV90b2tlbiI7czozMjoiWVp5cWh1U0Y5ZTlBMDVRcGFkemNvZlhDWWV0aGhzN0UiO3M6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjQxOiJodHRwczovL2NhZmVxcnYyLmdpbW1ob3N0Lm15LmlkL2NhZmUvbWVudSI7czo1OiJyb3V0ZSI7czo5OiJjYWZlLm1lbnUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1771094269),
('nlhkyrBz69Ey6rc4Y50W9NCjevIt0ZTvCeYB4lHB', NULL, '147.139.213.108', 'Veritrans', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiYkRyV1lZZFVKQ0VlNnR5SjY4aXhpakgwcXNlSUtVSkJsWXRJeEd3ZSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771092859),
('Thnf6F1Jx4bTGguPfWMRJCsb2d8VL3qgi4bXKgv7', NULL, '147.139.209.91', 'Veritrans', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiVE0zRThOVVpJR1gxTmZNdDl1NWZwY25hMjQzM2NzaDdROXZudGppWSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1771092854),
('xeqOqP0rHdfe5aZTXHNlrW4CgD0K4MccwYGqsGdH', 1, '36.74.126.127', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUW5rNjFGblpDMTA5djRNb2RUNG5VeUxiQW9yVk5PMjZVTUFIVkJGaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTE6Imh0dHBzOi8vY2FmZXFydjIuZ2ltbWhvc3QubXkuaWQvYWRtaW4vbWVudS9wcm9kdWN0cyI7czo1OiJyb3V0ZSI7czoxNDoiYWRtaW4ucHJvZHVjdHMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1771094288),
('znF5wltgRtICVyor6QqmsfIBDbLokEzqF1ECvjln', NULL, '117.103.171.4', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiME1CYnl0aEtveUpXclBIQTdTU3ZKOXEwQnJLQ0JOZW9OV1VTQUZORSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHBzOi8vY2FmZXFydjIuZ2ltbWhvc3QubXkuaWQvYWRtaW4vbG9naW4iO3M6NToicm91dGUiO3M6MTE6ImFkbWluLmxvZ2luIjt9fQ==', 1771089855);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'cafe_is_open', '1', '2026-02-14 11:05:08', '2026-02-14 11:05:08');

-- --------------------------------------------------------

--
-- Table structure for table `table_tokens`
--

CREATE TABLE `table_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `table_id` bigint(20) UNSIGNED NOT NULL,
  `token` varchar(64) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `rotated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `table_tokens`
--

INSERT INTO `table_tokens` (`id`, `table_id`, `token`, `is_active`, `rotated_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'LBaqTF7In12b6CizwduB0K5hVLnwK16x', 0, '2026-02-02 23:16:46', '2026-02-02 23:08:52', '2026-02-02 23:16:46'),
(2, 2, 'YZyqhuSF9e9A05QpadzcofXCYethhs7E', 1, NULL, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(3, 3, 'PhaeKiZgAJECxbaAnGEgfCOE1JoOXK6g', 1, NULL, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(4, 4, 'd8uwf75ekwt0FGDgvfnrd84OWrG8T8pP', 1, NULL, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(5, 5, 'ieLva5BPUHa96TrtCGJQGWhXcHsDLGbJ', 1, NULL, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(6, 6, '3RFcFP0sT5ClZxg6yXrLtzDozW98v7gY', 1, NULL, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(7, 7, '4zW3HzWUb6G4XRNCEfI8tAKs4JugVsol', 1, NULL, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(8, 8, 'wMLjWRoWzYyep7yaYl5M5xjNTdw9LbsA', 1, NULL, '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(9, 1, 'oK7Ukn4uT73aBFmbPO0hySoLtKe7GaKH', 0, '2026-02-02 23:27:31', '2026-02-02 23:16:46', '2026-02-02 23:27:31'),
(10, 1, 'fbh0H60IsOzbCv2yiBnM4KFu4lR1RLwU', 1, NULL, '2026-02-02 23:27:31', '2026-02-02 23:27:31'),
(11, 9, 'UlmmhnxfI1VphPMQ4XwxABLxuZkUJAYV', 1, NULL, '2026-02-02 23:37:21', '2026-02-02 23:37:21');

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
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_admin`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@cafeqr.testing', NULL, '$2y$12$DnuS9KgXoAYX6PeUMqBEE.lUR.N4GOGu9YGUTvJTNACz0d3PMK8o.', 1, '6Ywyc4iPTHCYsAYG2O20ZczFFq8Od6mp2OzMPPFyn7BcSszTDvGcWL7BEEZl', '2026-02-02 23:08:52', '2026-02-02 23:08:52'),
(2, 'Admin', 'admin@local.test', NULL, '$2y$12$XyCMkB1olNNgAMdPW5ZSheMzcqcTT5p59lC7dVLKdK131O4wdjA.W', 1, NULL, '2026-02-10 01:28:18', '2026-02-10 01:28:18'),
(3, 'Admin 2', 'admin2@example.com', NULL, '$2y$12$iWt09hpGbelbRrIc8ibL7uxZ.Yz7tsUU44lAhqZKcNWo967c8RKE.', 1, NULL, '2026-02-10 01:28:19', '2026-02-10 01:28:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `cafe_tables`
--
ALTER TABLE `cafe_tables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cafe_tables_table_no_unique` (`table_no`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mod_groups`
--
ALTER TABLE `mod_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mod_options`
--
ALTER TABLE `mod_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mod_options_mod_group_id_foreign` (`mod_group_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_code_unique` (`order_code`),
  ADD KEY `orders_status_created_index` (`order_status`,`created_at`),
  ADD KEY `orders_payment_created_index` (`payment_status`,`created_at`),
  ADD KEY `orders_table_created_index` (`table_id`,`created_at`);

--
-- Indexes for table `order_feedback`
--
ALTER TABLE `order_feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_feedback_order_id_foreign` (`order_id`),
  ADD KEY `order_feedback_product_id_foreign` (`product_id`),
  ADD KEY `order_feedback_order_item_id_foreign` (`order_item_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `order_item_mods`
--
ALTER TABLE `order_item_mods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_item_mods_order_item_id_foreign` (`order_item_id`),
  ADD KEY `order_item_mods_mod_option_id_foreign` (`mod_option_id`);

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
  ADD UNIQUE KEY `payments_order_id_unique` (`order_id`);

--
-- Indexes for table `payment_events`
--
ALTER TABLE `payment_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_events_payment_id_foreign` (`payment_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indexes for table `product_mod_groups`
--
ALTER TABLE `product_mod_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_mod_groups_product_id_mod_group_id_unique` (`product_id`,`mod_group_id`),
  ADD KEY `product_mod_groups_mod_group_id_foreign` (`mod_group_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `table_tokens`
--
ALTER TABLE `table_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `table_tokens_token_unique` (`token`),
  ADD KEY `table_tokens_table_id_foreign` (`table_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cafe_tables`
--
ALTER TABLE `cafe_tables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `mod_groups`
--
ALTER TABLE `mod_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mod_options`
--
ALTER TABLE `mod_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `order_feedback`
--
ALTER TABLE `order_feedback`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `order_item_mods`
--
ALTER TABLE `order_item_mods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `payment_events`
--
ALTER TABLE `payment_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `product_mod_groups`
--
ALTER TABLE `product_mod_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `table_tokens`
--
ALTER TABLE `table_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `mod_options`
--
ALTER TABLE `mod_options`
  ADD CONSTRAINT `mod_options_mod_group_id_foreign` FOREIGN KEY (`mod_group_id`) REFERENCES `mod_groups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `cafe_tables` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_feedback`
--
ALTER TABLE `order_feedback`
  ADD CONSTRAINT `order_feedback_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_feedback_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_feedback_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_item_mods`
--
ALTER TABLE `order_item_mods`
  ADD CONSTRAINT `order_item_mods_mod_option_id_foreign` FOREIGN KEY (`mod_option_id`) REFERENCES `mod_options` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_item_mods_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_events`
--
ALTER TABLE `payment_events`
  ADD CONSTRAINT `payment_events_payment_id_foreign` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_mod_groups`
--
ALTER TABLE `product_mod_groups`
  ADD CONSTRAINT `product_mod_groups_mod_group_id_foreign` FOREIGN KEY (`mod_group_id`) REFERENCES `mod_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_mod_groups_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `table_tokens`
--
ALTER TABLE `table_tokens`
  ADD CONSTRAINT `table_tokens_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `cafe_tables` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

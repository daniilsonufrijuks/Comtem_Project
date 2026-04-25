-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:4306
-- Generation Time: Mar 14, 2026 at 12:31 PM
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
-- Database: `comex`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`, `created_at`, `updated_at`) VALUES
(1, 'admin@super.com', '$2y$12$Vs.GZy91Oggy0jsYLXBFiOtSW3RRF1Q1YJPO2gD3i49ua2f0p3Cli', '2025-04-27 06:02:46', '2025-04-27 06:02:46');

-- --------------------------------------------------------

--
-- Table structure for table `auctions`
--

CREATE TABLE `auctions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `starting_bid` decimal(8,2) NOT NULL,
  `img` varchar(255) NOT NULL,
  `start_time` date DEFAULT NULL,
  `end_time` date DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `auctions`
--

INSERT INTO `auctions` (`id`, `name`, `description`, `starting_bid`, `img`, `start_time`, `end_time`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Mac 1990', 'Mac 1990 pc', 10000.00, 'images/front/1748171783_1739113880_1738502092_maco.jpeg', '2025-05-16', '2025-05-31', 3, '2025-05-25 08:16:23', '2025-05-25 08:16:23');

-- --------------------------------------------------------

--
-- Table structure for table `bids`
--

CREATE TABLE `bids` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `item_id` bigint(20) UNSIGNED NOT NULL,
  `bid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bids`
--

INSERT INTO `bids` (`id`, `user_id`, `item_id`, `bid_amount`, `created_at`, `updated_at`) VALUES
(1, 10, 1, 1.00, '2025-08-31 07:44:01', '2025-08-31 07:44:01'),
(2, 10, 1, 1.00, '2025-08-31 07:44:29', '2025-08-31 07:44:29');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `admin_id`, `created_at`, `updated_at`, `name`, `description`, `file_path`, `img`) VALUES
(1, 1, '2025-06-27 10:45:49', '2025-06-27 10:45:49', 'Intro to Computers', 'A simple guide to understanding computers.', '/files/pc.pdf', '/files/pc.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `body`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Nice shop', NULL, '2025-06-27 09:40:43', '2025-06-27 09:40:43'),
(2, 'Thank you', NULL, '2025-06-27 09:46:35', '2025-06-27 09:46:35'),
(3, 'Very very nice shop', NULL, '2025-07-10 11:36:42', '2025-07-10 11:36:42'),
(4, 'Thank you!', NULL, '2025-08-26 05:15:35', '2025-08-26 05:15:35'),
(5, 'I found it!', 10, '2025-08-31 08:14:49', '2025-08-31 08:14:49'),
(6, 'Very good shop!', 11, '2025-12-16 15:09:29', '2025-12-16 15:09:29'),
(7, 'Nice store!', NULL, '2026-02-07 12:55:14', '2026-02-07 12:55:14');

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
-- Table structure for table `families`
--

CREATE TABLE `families` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `family_name` varchar(255) NOT NULL,
  `invitation_code` varchar(50) NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stripe_customer_id` varchar(255) DEFAULT NULL,
  `stripe_payment_method_id` varchar(255) DEFAULT NULL,
  `card_last_four` varchar(4) DEFAULT NULL,
  `card_brand` varchar(50) DEFAULT NULL,
  `max_members` int(11) DEFAULT 5,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `families`
--

INSERT INTO `families` (`id`, `family_name`, `invitation_code`, `parent_id`, `stripe_customer_id`, `stripe_payment_method_id`, `card_last_four`, `card_brand`, `max_members`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Mikes', '1584BFE7', 14, 'cus_TtjtnydvyUCa0x', NULL, NULL, NULL, 5, 1, '2026-01-31 12:53:22', '2026-02-01 06:53:35'),
(2, 'Smith', '10326109', 1, NULL, NULL, NULL, NULL, 5, 1, '2026-02-01 09:57:33', '2026-02-01 09:57:33'),
(3, 'Johns', '7BFF26BA', 19, 'cus_Tw58G9dhmDXAQG', NULL, NULL, NULL, 5, 1, '2026-02-07 12:57:32', '2026-02-07 12:59:04'),
(4, 'family', 'E1C30B0A', 21, 'cus_U4irWhbaMj1lQl', NULL, NULL, NULL, 5, 1, '2026-03-02 14:33:05', '2026-03-02 14:34:09');

-- --------------------------------------------------------

--
-- Table structure for table `family_payment_methods`
--

CREATE TABLE `family_payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `family_id` bigint(20) UNSIGNED NOT NULL,
  `stripe_payment_method_id` varchar(255) NOT NULL,
  `stripe_customer_id` varchar(255) NOT NULL,
  `card_last_four` varchar(4) NOT NULL,
  `card_brand` varchar(50) NOT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `added_by_user_id` bigint(20) UNSIGNED NOT NULL,
  `added_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `family_payment_methods`
--

INSERT INTO `family_payment_methods` (`id`, `family_id`, `stripe_payment_method_id`, `stripe_customer_id`, `card_last_four`, `card_brand`, `is_default`, `added_by_user_id`, `added_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'pm_1SvwOwCGF8xpxcU7VShd8kWH', 'cus_TtjtnydvyUCa0x', '8431', 'amex', 1, 14, NULL, '2026-02-01 06:53:36', '2026-02-01 06:53:36'),
(2, 3, 'pm_1SyCyKCGF8xpxcU7lLKyVKqc', 'cus_Tw58G9dhmDXAQG', '8431', 'amex', 1, 19, NULL, '2026-02-07 12:59:05', '2026-02-07 12:59:05'),
(3, 4, 'pm_1T6ZPsCGF8xpxcU72JfYipJ4', 'cus_U4irWhbaMj1lQl', '4105', 'discover', 1, 21, NULL, '2026-03-02 14:34:11', '2026-03-02 14:34:11');

-- --------------------------------------------------------

--
-- Table structure for table `family_transactions`
--

CREATE TABLE `family_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `family_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `stripe_payment_intent_id` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) DEFAULT 'USD',
  `description` text DEFAULT NULL,
  `status` enum('succeeded','failed','pending') DEFAULT 'pending',
  `payment_method_used` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `family_transactions`
--

INSERT INTO `family_transactions` (`id`, `family_id`, `user_id`, `order_id`, `stripe_payment_intent_id`, `amount`, `currency`, `description`, `status`, `payment_method_used`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 1, 17, NULL, 'pi_3SvwVVCGF8xpxcU712PZhsGz', 1000.00, 'USD', 'Purchase from cart', 'succeeded', 'pm_1SvwOwCGF8xpxcU7VShd8kWH', '\"{\\\"card_last_four\\\":\\\"8431\\\",\\\"card_brand\\\":\\\"amex\\\",\\\"items\\\":[{\\\"id\\\":44,\\\"name\\\":\\\"MSI Gaming Laptop Pro\\\",\\\"price\\\":1000,\\\"quantity\\\":1,\\\"description\\\":\\\"MSI Gaming Laptop:\\\\r\\\\n                                Intel Core i9 processor, 4 Ghz, 16 cores, 24 threads\\\\r\\\\n                                NVIDIA RTX 3060 8 Gb\\\\r\\\\n                                RAM Fury 16 Gb 3200 Mhz\\\\r\\\\n                                SSD 2 TB\\\",\\\"image\\\":\\\"images\\\\\\/front\\\\\\/msilp.jpg\\\",\\\"category\\\":\\\"Laptop\\\",\\\"total_price\\\":1000,\\\"shipping_address\\\":\\\"Maskavas 4\\\"}]}\"', '2026-02-01 06:59:50', '2026-02-01 06:59:50'),
(2, 1, 17, NULL, 'pi_3Svwh1CGF8xpxcU71vbIqABy', 80.00, 'USD', 'Purchase from cart', 'succeeded', 'pm_1SvwOwCGF8xpxcU7VShd8kWH', '\"{\\\"card_last_four\\\":\\\"8431\\\",\\\"card_brand\\\":\\\"amex\\\",\\\"items\\\":[{\\\"id\\\":14,\\\"name\\\":\\\"Kingston RAM 3200 MHz 32GB\\\",\\\"price\\\":80,\\\"quantity\\\":1,\\\"description\\\":\\\"Kingston RAM 3200 MHz 32GB 1 stick\\\",\\\"image\\\":\\\"images\\\\\\/front\\\\\\/kingstonram.jpg\\\",\\\"category\\\":\\\"Component\\\",\\\"total_price\\\":80,\\\"shipping_address\\\":\\\"Maskavas 4\\\"}]}\"', '2026-02-01 07:11:44', '2026-02-01 07:11:44'),
(3, 1, 17, NULL, 'pi_3SvwnACGF8xpxcU70F5SwoeO', 200.00, 'USD', 'Purchase from cart', 'succeeded', 'pm_1SvwOwCGF8xpxcU7VShd8kWH', '\"{\\\"card_last_four\\\":\\\"8431\\\",\\\"card_brand\\\":\\\"amex\\\",\\\"items\\\":[{\\\"id\\\":50,\\\"name\\\":\\\"Leo Table Pro\\\",\\\"price\\\":200,\\\"quantity\\\":1,\\\"description\\\":\\\"Leo Table Pro 1.5 x 1 x 2 m\\\",\\\"image\\\":\\\"images\\\\\\/front\\\\\\/1752158103_table.png\\\",\\\"category\\\":\\\"Furniture\\\",\\\"total_price\\\":200,\\\"shipping_address\\\":\\\"Maskavas 4\\\"}]}\"', '2026-02-01 07:18:05', '2026-02-01 07:18:05'),
(4, 1, 17, NULL, 'pi_3SvwnvCGF8xpxcU70qUanDgu', 200.00, 'USD', 'Purchase from cart', 'succeeded', 'pm_1SvwOwCGF8xpxcU7VShd8kWH', '\"{\\\"card_last_four\\\":\\\"8431\\\",\\\"card_brand\\\":\\\"amex\\\",\\\"items\\\":[{\\\"id\\\":50,\\\"name\\\":\\\"Leo Table Pro\\\",\\\"price\\\":200,\\\"quantity\\\":1,\\\"description\\\":\\\"Leo Table Pro 1.5 x 1 x 2 m\\\",\\\"image\\\":\\\"images\\\\\\/front\\\\\\/1752158103_table.png\\\",\\\"category\\\":\\\"Furniture\\\",\\\"total_price\\\":200,\\\"shipping_address\\\":\\\"Maskavas 4\\\"}]}\"', '2026-02-01 07:18:52', '2026-02-01 07:18:52'),
(5, 1, 17, NULL, 'pi_3SvwuYCGF8xpxcU70Dk0oeot', 203.00, 'USD', 'Purchase from cart', 'succeeded', 'pm_1SvwOwCGF8xpxcU7VShd8kWH', '\"{\\\"card_last_four\\\":\\\"8431\\\",\\\"card_brand\\\":\\\"amex\\\",\\\"items\\\":[{\\\"id\\\":50,\\\"name\\\":\\\"Leo Table Pro\\\",\\\"price\\\":200,\\\"quantity\\\":1,\\\"description\\\":\\\"Leo Table Pro 1.5 x 1 x 2 m\\\",\\\"image\\\":\\\"images\\\\\\/front\\\\\\/1752158103_table.png\\\",\\\"category\\\":\\\"Furniture\\\",\\\"total_price\\\":200,\\\"shipping_address\\\":\\\"Maskavas 4\\\"}]}\"', '2026-02-01 07:25:43', '2026-02-01 07:25:43'),
(6, 1, 17, 36, 'pi_3Svx5yCGF8xpxcU71ANLNL2c', 53.00, 'USD', 'Purchase from cart', 'succeeded', 'pm_1SvwOwCGF8xpxcU7VShd8kWH', '\"{\\\"card_last_four\\\":\\\"8431\\\",\\\"card_brand\\\":\\\"amex\\\",\\\"items\\\":[{\\\"id\\\":48,\\\"name\\\":\\\"Sony Studio Wireless\\\",\\\"price\\\":50,\\\"quantity\\\":1,\\\"description\\\":\\\"Sony Studio Pro Headphones\\\",\\\"image\\\":\\\"images\\\\\\/front\\\\\\/1750924746_sony.png\\\",\\\"category\\\":\\\"Peripheral\\\",\\\"total_price\\\":50,\\\"shipping_address\\\":\\\"Maskavas 4\\\"}]}\"', '2026-02-01 07:37:30', '2026-02-01 07:37:30'),
(7, 3, 19, 39, 'pi_3SyCyrCGF8xpxcU71E1HohG6', 1943.00, 'USD', 'Purchase from cart', 'succeeded', 'pm_1SyCyKCGF8xpxcU7lLKyVKqc', '\"{\\\"card_last_four\\\":\\\"8431\\\",\\\"card_brand\\\":\\\"amex\\\",\\\"items\\\":[{\\\"id\\\":33,\\\"name\\\":\\\"MSI Gaming Station\\\",\\\"price\\\":970,\\\"quantity\\\":2,\\\"description\\\":\\\"MSI Gaming Station: MSI Gaming Station: Intel Core i7 processor, 5 Ghz NVIDIA RTX 3080 8 Gb RAM Fury 32 Gb 3200 Mhz SSD 1 TB HDD 2 TB Power Supply Be quiet 700 W\\\",\\\"image\\\":\\\"images\\\\\\/front\\\\\\/1748188525_pc.png\\\",\\\"category\\\":\\\"Pc\\\",\\\"total_price\\\":1940,\\\"shipping_address\\\":\\\"Marbel street 9\\\"}]}\"', '2026-02-07 12:59:30', '2026-02-07 12:59:30'),
(8, 3, 20, 42, 'pi_3SyD6PCGF8xpxcU71nxfRE6o', 53.00, 'USD', 'Purchase from cart', 'succeeded', 'pm_1SyCyKCGF8xpxcU7lLKyVKqc', '\"{\\\"card_last_four\\\":\\\"8431\\\",\\\"card_brand\\\":\\\"amex\\\",\\\"items\\\":[{\\\"id\\\":12,\\\"name\\\":\\\"Kingston RAM 3200 MHz 8GB\\\",\\\"price\\\":50,\\\"quantity\\\":1,\\\"description\\\":\\\"Kingston RAM 3200 MHz 8GB 1 stick\\\",\\\"image\\\":\\\"images\\\\\\/front\\\\\\/kingstonram.jpg\\\",\\\"category\\\":\\\"Component\\\",\\\"total_price\\\":50,\\\"shipping_address\\\":\\\"Marbel street 9\\\"}]}\"', '2026-02-07 13:07:18', '2026-02-07 13:07:18'),
(9, 3, 20, 43, 'pi_3SyD7pCGF8xpxcU70IN0FHhc', 803.00, 'USD', 'Purchase from cart', 'succeeded', 'pm_1SyCyKCGF8xpxcU7lLKyVKqc', '\"{\\\"card_last_four\\\":\\\"8431\\\",\\\"card_brand\\\":\\\"amex\\\",\\\"items\\\":[{\\\"id\\\":35,\\\"name\\\":\\\"Google Pixel 9 Pro\\\",\\\"price\\\":800,\\\"quantity\\\":1,\\\"description\\\":\\\"Google pixel 9 Pro phone\\\\r\\\\n512 Gb Rom\\\\r\\\\n12 Gb RAM\\\\r\\\\nSnapdragon Elite G CPU\\\\r\\\\nAdreno 890 GPU\\\\r\\\\n7000 Mah Battery\\\\r\\\\nType C\\\",\\\"image\\\":\\\"images\\\\\\/front\\\\\\/1750755864_phones.png\\\",\\\"category\\\":\\\"Phone\\\",\\\"total_price\\\":800,\\\"shipping_address\\\":\\\"Marbel street 9\\\"}]}\"', '2026-02-07 13:08:46', '2026-02-07 13:08:46'),
(10, 4, 22, 49, 'pi_3T6Za5CGF8xpxcU71SOAIq5r', 103.00, 'USD', 'Purchase from cart', 'succeeded', 'pm_1T6ZPsCGF8xpxcU72JfYipJ4', '\"{\\\"card_last_four\\\":\\\"4105\\\",\\\"card_brand\\\":\\\"discover\\\",\\\"items\\\":[{\\\"id\\\":40,\\\"name\\\":\\\"Custom 60%\\\",\\\"price\\\":50,\\\"quantity\\\":2,\\\"description\\\":\\\"Custom 60% keyboard, red switches, wireless\\\",\\\"image\\\":\\\"images\\\\\\/front\\\\\\/1750755707_keyboard.png\\\",\\\"category\\\":\\\"Peripheral\\\",\\\"total_price\\\":100,\\\"shipping_address\\\":\\\"Skuju iela 19\\\"}]}\"', '2026-03-02 14:44:28', '2026-03-02 14:44:28'),
(11, 4, 22, 50, 'pi_3T6ZemCGF8xpxcU70xa0RvN6', 453.99, 'USD', 'Purchase from cart', 'succeeded', 'pm_1T6ZPsCGF8xpxcU72JfYipJ4', '\"{\\\"card_last_four\\\":\\\"4105\\\",\\\"card_brand\\\":\\\"discover\\\",\\\"items\\\":[{\\\"id\\\":12,\\\"name\\\":\\\"Kingston RAM 3200 MHz 8GB\\\",\\\"price\\\":50,\\\"quantity\\\":2,\\\"description\\\":\\\"Kingston RAM 3200 MHz 8GB 1 stick\\\",\\\"image\\\":\\\"images\\\\\\/front\\\\\\/kingstonram.jpg\\\",\\\"category\\\":\\\"Component\\\",\\\"total_price\\\":100,\\\"shipping_address\\\":\\\"Skuju iela 19\\\"},{\\\"id\\\":54,\\\"name\\\":\\\"Asus Ergo Table\\\",\\\"price\\\":350.99,\\\"quantity\\\":1,\\\"description\\\":\\\"Asus Ergo Table 1.7 x 1.2 x 2 m\\\",\\\"image\\\":\\\"images\\\\\\/front\\\\\\/1770477093_1752158103_table.png\\\",\\\"category\\\":\\\"Furniture\\\",\\\"total_price\\\":350.99,\\\"shipping_address\\\":\\\"Skuju iela 19\\\"}]}\"', '2026-03-02 14:49:19', '2026-03-02 14:49:19'),
(12, 4, 22, 51, 'pi_3T6a9TCGF8xpxcU71EwkPt2d', 603.00, 'USD', 'Purchase from cart', 'succeeded', 'pm_1T6ZPsCGF8xpxcU72JfYipJ4', '\"{\\\"card_last_four\\\":\\\"4105\\\",\\\"card_brand\\\":\\\"discover\\\",\\\"items\\\":[{\\\"id\\\":15,\\\"name\\\":\\\"MSI Gaming Laptop\\\",\\\"price\\\":600,\\\"quantity\\\":1,\\\"description\\\":\\\"MSI Gaming Laptop:\\\\n                                Intel Core i7 processor, 4 Ghz, 12 cores, 24 threads\\\\n                                NVIDIA RTX 3060 6 Gb\\\\n                                RAM Fury 16 Gb 3200 Mhz\\\\n                                SSD 1 TB\\\",\\\"image\\\":\\\"images\\\\\\/front\\\\\\/msilp.jpg\\\",\\\"category\\\":\\\"Laptop\\\",\\\"total_price\\\":600,\\\"shipping_address\\\":\\\"Skuju iela 19\\\"}]}\"', '2026-03-02 15:21:04', '2026-03-02 15:21:04'),
(13, 4, 21, 60, 'pi_3T8HI0CGF8xpxcU710TC2Hpc', 4553.00, 'USD', 'Purchase from cart', 'succeeded', 'pm_1T6ZPsCGF8xpxcU72JfYipJ4', '\"{\\\"card_last_four\\\":\\\"4105\\\",\\\"card_brand\\\":\\\"discover\\\"}\"', '2026-03-07 07:36:52', '2026-03-07 07:36:52'),
(14, 4, 22, 62, 'pi_3T8HN2CGF8xpxcU715sBPIiG', 453.99, 'USD', 'Purchase from cart', 'succeeded', 'pm_1T6ZPsCGF8xpxcU72JfYipJ4', '\"{\\\"card_last_four\\\":\\\"4105\\\",\\\"card_brand\\\":\\\"discover\\\"}\"', '2026-03-07 07:42:04', '2026-03-07 07:42:04'),
(15, 4, 22, 63, 'pi_3T8IkHCGF8xpxcU71rfGgONm', 1204.65, 'USD', 'Purchase from cart', 'succeeded', 'pm_1T6ZPsCGF8xpxcU72JfYipJ4', '\"{\\\"card_last_four\\\":\\\"4105\\\",\\\"card_brand\\\":\\\"discover\\\"}\"', '2026-03-07 09:10:15', '2026-03-07 09:10:15');

-- --------------------------------------------------------

--
-- Table structure for table `goods_orders`
--

CREATE TABLE `goods_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `status` enum('pending','processing','completed','cancelled') NOT NULL DEFAULT 'pending',
  `name` varchar(255) NOT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `goods_orders`
--

INSERT INTO `goods_orders` (`id`, `order_id`, `product_id`, `quantity`, `status`, `name`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 1, 'pending', 'MSI Gaming Laptop', 700.00, '2025-04-27 06:03:44', '2025-04-27 06:03:44'),
(2, 2, NULL, 1, 'pending', 'Gigabyte Laptop', 900.00, '2025-04-27 06:04:40', '2025-04-27 06:04:40'),
(3, 2, NULL, 1, 'pending', 'Intel Core i3', 50.00, '2025-04-27 06:04:40', '2025-04-27 06:04:40'),
(4, 3, NULL, 1, 'pending', 'Kingston RAM 3200 MHz 8GB', 50.00, '2025-05-15 07:43:02', '2025-05-15 07:43:02'),
(5, 4, NULL, 1, 'pending', 'MSI Gaming Station', 700.00, '2025-05-25 08:17:17', '2025-05-25 08:17:17'),
(6, 5, NULL, 1, 'pending', 'MSI Gaming Station', 750.00, '2025-06-05 04:16:02', '2025-06-05 04:16:02'),
(7, 5, NULL, 1, 'pending', 'Nvidia RTX 3080', 500.00, '2025-06-05 04:16:03', '2025-06-05 04:16:03'),
(8, 6, NULL, 1, 'pending', 'MSI Gaming Station', 970.00, '2025-06-05 04:29:26', '2025-06-05 04:29:26'),
(9, 7, NULL, 1, 'pending', 'Kingston RAM 3200 MHz 8GB', 50.00, '2025-06-05 04:33:49', '2025-06-05 04:33:49'),
(10, 8, NULL, 1, 'pending', 'Google Pixel 9 Pro', 800.00, '2025-06-24 07:35:45', '2025-06-24 07:35:45'),
(11, 9, NULL, 1, 'pending', 'Google Pixel 9 Pro', 800.00, '2025-06-24 07:37:05', '2025-06-24 07:37:05'),
(12, 10, NULL, 1, 'pending', 'Google Pixel 9 Pro', 800.00, '2025-06-24 07:40:09', '2025-06-24 07:40:09'),
(13, 11, NULL, 1, 'pending', 'Google Pixel 9 Pro', 800.00, '2025-06-24 07:42:00', '2025-06-24 07:42:00'),
(14, 12, NULL, 1, 'pending', 'Custom 60%', 100.00, '2025-06-24 07:46:09', '2025-06-24 07:46:09'),
(15, 13, NULL, 1, 'pending', 'Custom 60%', 100.00, '2025-06-25 11:54:49', '2025-06-25 11:54:49'),
(16, 13, NULL, 1, 'pending', 'Custom 60%', 50.00, '2025-06-25 11:54:49', '2025-06-25 11:54:49'),
(17, 14, NULL, 1, 'pending', 'Custom 60%', 100.00, '2025-06-25 12:01:51', '2025-06-25 12:01:51'),
(18, 14, NULL, 1, 'pending', 'Custom 60%', 50.00, '2025-06-25 12:01:51', '2025-06-25 12:01:51'),
(19, 14, NULL, 1, 'pending', 'Gigabyte Laptop', 900.00, '2025-06-25 12:01:51', '2025-06-25 12:01:51'),
(20, 15, NULL, 1, 'pending', 'Intel Core i5', 150.00, '2025-08-31 05:09:56', '2025-08-31 05:09:56'),
(21, 15, NULL, 1, 'pending', 'MSI Gaming Station', 970.00, '2025-08-31 05:09:56', '2025-08-31 05:09:56'),
(22, 15, NULL, 1, 'pending', 'Custom 60%', 50.00, '2025-08-31 05:09:56', '2025-08-31 05:09:56'),
(23, 15, NULL, 1, 'pending', 'Sony Studio', 70.00, '2025-08-31 05:09:56', '2025-08-31 05:09:56'),
(24, 15, NULL, 1, 'pending', 'Logitech G Pro', 120.00, '2025-08-31 05:09:56', '2025-08-31 05:09:56'),
(25, 16, NULL, 1, 'pending', 'Play Station 5', 350.00, '2025-10-05 10:36:56', '2025-10-05 10:36:56'),
(27, 18, NULL, 1, 'pending', 'MSI Gaming Laptop Pro', 1000.00, '2026-01-17 10:45:31', '2026-01-17 10:45:31'),
(28, 18, NULL, 1, 'pending', 'Play Station 5 Pro', 400.00, '2026-01-17 10:45:31', '2026-01-17 10:45:31'),
(29, 18, NULL, 1, 'pending', 'MSI Gaming Laptop Lite', 500.00, '2026-01-17 10:45:31', '2026-01-17 10:45:31'),
(30, 19, NULL, 1, 'pending', 'Google Pixel 9 Pro', 800.00, '2026-01-17 12:37:17', '2026-01-17 12:37:17'),
(31, 20, NULL, 1, 'pending', 'Google Pixel 9 Pro', 800.00, '2026-01-17 13:04:22', '2026-01-17 13:04:22'),
(32, 21, NULL, 1, 'pending', 'Google Pixel 9 Pro', 800.00, '2026-01-17 13:12:19', '2026-01-17 13:12:19'),
(33, 22, NULL, 1, 'pending', 'Gigabyte Laptop', 900.00, '2026-01-17 13:15:45', '2026-01-17 13:15:45'),
(34, 23, NULL, 1, 'pending', 'Gigabyte Laptop', 900.00, '2026-01-17 13:17:32', '2026-01-17 13:17:32'),
(35, 24, NULL, 1, 'pending', 'MSI Gaming Station', 750.00, '2026-01-17 13:19:29', '2026-01-17 13:19:29'),
(36, 25, NULL, 1, 'pending', 'MSI Gaming Station', 750.00, '2026-01-17 13:22:46', '2026-01-17 13:22:46'),
(37, 25, NULL, 1, 'pending', 'MSI Gaming Laptop Pro', 1000.00, '2026-01-17 13:22:46', '2026-01-17 13:22:46'),
(38, 26, NULL, 1, 'pending', 'MSI Gaming Laptop', 600.00, '2026-01-31 12:55:01', '2026-01-31 12:55:01'),
(39, 27, NULL, 1, 'pending', 'MSI Gaming Laptop', 600.00, '2026-01-31 12:55:59', '2026-01-31 12:55:59'),
(40, 28, NULL, 1, 'pending', 'MSI Gaming Laptop', 600.00, '2026-01-31 12:57:13', '2026-01-31 12:57:13'),
(41, 29, NULL, 1, 'pending', 'MSI Gaming Laptop', 600.00, '2026-01-31 12:59:51', '2026-01-31 12:59:51'),
(42, 30, NULL, 1, 'pending', 'MSI Gaming Laptop', 600.00, '2026-01-31 13:23:34', '2026-01-31 13:23:34'),
(43, 31, NULL, 1, 'pending', 'Gigabyte Laptop', 900.00, '2026-01-31 13:26:01', '2026-01-31 13:26:01'),
(44, 32, NULL, 1, 'pending', 'MSI Gaming Laptop Pro', 1000.00, '2026-02-01 06:58:31', '2026-02-01 06:58:31'),
(45, 33, NULL, 1, 'pending', 'MSI Gaming Laptop Pro', 1000.00, '2026-02-01 06:59:51', '2026-02-01 06:59:51'),
(46, 34, NULL, 1, 'pending', 'MSI Gaming Laptop Pro', 1000.00, '2026-02-01 07:01:10', '2026-02-01 07:01:10'),
(47, 35, NULL, 1, 'pending', 'Kingston RAM 3200 MHz 32GB', 80.00, '2026-02-01 07:11:49', '2026-02-01 07:11:49'),
(48, 36, NULL, 1, 'completed', 'Sony Studio Wireless', 50.00, '2026-02-01 07:37:30', '2026-02-01 07:37:30'),
(49, 37, NULL, 1, 'pending', 'Google Pixel 9 Pro', 800.00, '2026-02-01 07:40:52', '2026-02-01 07:40:52'),
(50, 37, NULL, 1, 'pending', 'Custom 60%', 100.00, '2026-02-01 07:40:52', '2026-02-01 07:40:52'),
(51, 38, NULL, 1, 'pending', 'Play Station 5', 350.00, '2026-02-01 07:44:56', '2026-02-01 07:44:56'),
(52, 39, NULL, 1, 'completed', 'MSI Gaming Station', 970.00, '2026-02-07 12:59:30', '2026-02-07 12:59:30'),
(53, 40, NULL, 1, 'pending', 'Kingston RAM 3200 MHz 8GB', 50.00, '2026-02-07 13:03:54', '2026-02-07 13:03:54'),
(54, 41, NULL, 1, 'pending', 'Kingston RAM 3200 MHz 8GB', 50.00, '2026-02-07 13:05:05', '2026-02-07 13:05:05'),
(55, 42, NULL, 1, 'completed', 'Kingston RAM 3200 MHz 8GB', 50.00, '2026-02-07 13:07:18', '2026-02-07 13:07:18'),
(56, 43, NULL, 1, 'completed', 'Google Pixel 9 Pro', 800.00, '2026-02-07 13:08:46', '2026-02-07 13:08:46'),
(57, 44, NULL, 1, 'processing', 'Asus Ergo Table', 350.99, '2026-02-14 08:16:25', '2026-03-02 15:55:24'),
(58, 45, NULL, 1, 'pending', 'Power Cable', 20.00, '2026-02-14 09:20:04', '2026-02-14 09:20:04'),
(59, 46, NULL, 1, 'pending', 'Power Cable', 20.00, '2026-02-14 09:34:25', '2026-02-14 09:34:25'),
(60, 47, NULL, 1, 'pending', 'Power Cable', 20.00, '2026-03-02 14:35:55', '2026-03-02 14:35:55'),
(61, 47, NULL, 1, 'pending', 'MSI Gaming Station', 970.00, '2026-03-02 14:35:55', '2026-03-02 14:35:55'),
(62, 48, NULL, 1, 'pending', 'Power Cable', 20.00, '2026-03-02 14:43:34', '2026-03-02 14:43:34'),
(63, 48, NULL, 1, 'pending', 'MSI Gaming Station', 970.00, '2026-03-02 14:43:34', '2026-03-02 14:43:34'),
(64, 49, NULL, 1, 'completed', 'Custom 60%', 50.00, '2026-03-02 14:44:28', '2026-03-02 14:44:28'),
(65, 50, NULL, 1, 'completed', 'Kingston RAM 3200 MHz 8GB', 50.00, '2026-03-02 14:49:19', '2026-03-02 14:49:19'),
(66, 50, NULL, 1, 'completed', 'Asus Ergo Table', 350.99, '2026-03-02 14:49:19', '2026-03-02 14:49:19'),
(67, 51, NULL, 1, 'completed', 'MSI Gaming Laptop', 600.00, '2026-03-02 15:21:04', '2026-03-02 15:21:04'),
(68, 52, NULL, 1, 'pending', 'MSI Gaming Laptop', 600.00, '2026-03-02 15:21:09', '2026-03-02 15:21:09'),
(69, 53, NULL, 1, 'pending', 'Gigabyte Laptop', 900.00, '2026-03-02 15:22:43', '2026-03-02 15:22:43'),
(70, 54, NULL, 1, 'pending', 'Play Station 5', 1053.00, '2026-03-02 15:30:20', '2026-03-02 15:30:20'),
(71, 55, NULL, 1, 'pending', 'Play Station 5', 2053.00, '2026-03-02 15:32:52', '2026-03-02 15:32:52'),
(72, 55, NULL, 1, 'pending', 'MSI Gaming Laptop Lite', 2053.00, '2026-03-02 15:32:52', '2026-03-02 15:32:52'),
(73, 56, NULL, 1, 'pending', 'Play Station 5', 1050.00, '2026-03-02 15:40:55', '2026-03-02 15:40:55'),
(74, 56, NULL, 1, 'pending', 'MSI Gaming Laptop Lite', 1000.00, '2026-03-02 15:40:55', '2026-03-02 15:40:55'),
(75, 57, 52, 3, 'cancelled', 'Play Station 5', 1050.00, '2026-03-02 15:45:16', '2026-03-02 15:56:51'),
(76, 57, 45, 2, 'cancelled', 'MSI Gaming Laptop Lite', 1000.00, '2026-03-02 15:45:16', '2026-03-02 15:56:51'),
(77, 58, 12, 1, 'pending', 'Kingston RAM 3200 MHz 8GB', 50.00, '2026-03-07 07:26:02', '2026-03-07 07:26:02'),
(78, 58, 35, 2, 'pending', 'Google Pixel 9 Pro', 1600.00, '2026-03-07 07:26:02', '2026-03-07 07:26:02'),
(79, 58, 33, 1, 'pending', 'MSI Gaming Station', 970.00, '2026-03-07 07:26:02', '2026-03-07 07:26:02'),
(80, 59, 7, 1, 'pending', 'Adata SSD 1 TB', 70.00, '2026-03-07 07:33:03', '2026-03-07 07:33:03'),
(81, 59, 2, 2, 'pending', 'Intel Core i7', 400.00, '2026-03-07 07:33:03', '2026-03-07 07:33:03'),
(82, 59, 9, 2, 'pending', 'Nvidia RTX 3060', 600.00, '2026-03-07 07:33:03', '2026-03-07 07:33:03'),
(83, 60, 33, 2, 'pending', 'MSI Gaming Station', 1940.00, '2026-03-07 07:36:52', '2026-03-07 07:36:52'),
(84, 60, 16, 3, 'pending', 'Asus Gaming Laptop', 2610.00, '2026-03-07 07:36:52', '2026-03-07 07:36:52'),
(85, 61, 44, 1, 'pending', 'MSI Gaming Laptop Pro', 1000.00, '2026-03-07 07:40:38', '2026-03-07 07:40:38'),
(86, 62, 40, 2, 'pending', 'Custom 60%', 100.00, '2026-03-07 07:42:04', '2026-03-07 07:42:04'),
(87, 62, 54, 1, 'pending', 'Asus Ergo Table', 350.99, '2026-03-07 07:42:04', '2026-03-07 07:42:04'),
(88, 63, 53, 3, 'pending', 'Play Station 5 Pro', 1201.65, '2026-03-07 09:10:15', '2026-03-07 09:10:15');

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
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_address` text DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_intent_id` varchar(255) DEFAULT NULL,
  `ordered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `status`, `total`, `shipping_address`, `payment_method`, `payment_intent_id`, `ordered_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'pending', 700.00, NULL, NULL, NULL, NULL, '2025-04-27 06:03:44', '2025-04-27 06:03:44'),
(2, 1, 'pending', 950.00, NULL, NULL, NULL, NULL, '2025-04-27 06:04:40', '2025-04-27 06:04:40'),
(3, 2, 'pending', 50.00, NULL, NULL, NULL, NULL, '2025-05-15 07:43:02', '2025-05-15 07:43:02'),
(4, 3, 'pending', 700.00, NULL, NULL, NULL, NULL, '2025-05-25 08:17:17', '2025-05-25 08:17:17'),
(5, 8, 'pending', 1250.00, NULL, NULL, NULL, NULL, '2025-06-05 04:16:02', '2025-06-05 04:16:02'),
(6, 8, 'pending', 970.00, NULL, NULL, NULL, NULL, '2025-06-05 04:29:26', '2025-06-05 04:29:26'),
(7, 8, 'pending', 50.00, NULL, NULL, NULL, NULL, '2025-06-05 04:33:49', '2025-06-05 04:33:49'),
(8, 9, 'pending', 800.00, NULL, NULL, NULL, NULL, '2025-06-24 07:35:45', '2025-06-24 07:35:45'),
(9, 9, 'pending', 800.00, NULL, NULL, NULL, NULL, '2025-06-24 07:37:05', '2025-06-24 07:37:05'),
(10, 9, 'pending', 800.00, NULL, NULL, NULL, NULL, '2025-06-24 07:40:09', '2025-06-24 07:40:09'),
(11, 9, 'pending', 800.00, NULL, NULL, NULL, NULL, '2025-06-24 07:42:00', '2025-06-24 07:42:00'),
(12, 9, 'pending', 100.00, NULL, NULL, NULL, NULL, '2025-06-24 07:46:09', '2025-06-24 07:46:09'),
(13, 9, 'pending', 150.00, NULL, NULL, NULL, NULL, '2025-06-25 11:54:49', '2025-06-25 11:54:49'),
(14, 9, 'pending', 1050.00, NULL, NULL, NULL, NULL, '2025-06-25 12:01:51', '2025-06-25 12:01:51'),
(15, 10, 'pending', 1360.00, NULL, NULL, NULL, NULL, '2025-08-31 05:09:56', '2025-08-31 05:09:56'),
(16, 11, 'pending', 700.05, NULL, NULL, NULL, NULL, '2025-10-05 10:36:56', '2025-12-06 11:23:38'),
(18, 12, 'pending', 2903.00, NULL, NULL, NULL, NULL, '2026-01-17 10:45:30', '2026-01-17 10:45:30'),
(19, 12, 'pending', 803.00, NULL, NULL, NULL, NULL, '2026-01-17 12:37:17', '2026-01-17 12:37:17'),
(20, 12, 'pending', 803.00, NULL, NULL, NULL, NULL, '2026-01-17 13:04:22', '2026-01-17 13:04:22'),
(21, 12, 'pending', 803.00, NULL, NULL, NULL, NULL, '2026-01-17 13:12:19', '2026-01-17 13:12:19'),
(22, 12, 'pending', 903.00, NULL, NULL, NULL, NULL, '2026-01-17 13:15:45', '2026-01-17 13:15:45'),
(23, 13, 'pending', 903.00, NULL, NULL, NULL, NULL, '2026-01-17 13:17:32', '2026-01-17 13:17:32'),
(24, 12, 'completed', 753.00, NULL, NULL, NULL, NULL, '2026-01-17 13:19:29', '2026-01-25 07:19:23'),
(25, 13, 'completed', 1753.00, NULL, NULL, NULL, NULL, '2026-01-17 13:22:46', '2026-01-25 07:18:33'),
(26, 14, 'pending', 603.00, NULL, NULL, NULL, NULL, '2026-01-31 12:55:01', '2026-01-31 12:55:01'),
(27, 14, 'pending', 603.00, NULL, NULL, NULL, NULL, '2026-01-31 12:55:59', '2026-01-31 12:55:59'),
(28, 14, 'pending', 603.00, NULL, NULL, NULL, NULL, '2026-01-31 12:57:13', '2026-01-31 12:57:13'),
(29, 14, 'pending', 603.00, NULL, NULL, NULL, NULL, '2026-01-31 12:59:51', '2026-01-31 12:59:51'),
(30, 14, 'pending', 603.00, NULL, NULL, NULL, NULL, '2026-01-31 13:23:34', '2026-01-31 13:23:34'),
(31, 15, 'pending', 903.00, NULL, NULL, NULL, NULL, '2026-01-31 13:26:01', '2026-01-31 13:26:01'),
(32, 17, 'pending', 1003.00, NULL, NULL, NULL, NULL, '2026-02-01 06:58:31', '2026-02-01 06:58:31'),
(33, 17, 'pending', 1003.00, NULL, NULL, NULL, NULL, '2026-02-01 06:59:51', '2026-02-01 06:59:51'),
(34, 17, 'pending', 1003.00, NULL, NULL, NULL, NULL, '2026-02-01 07:01:10', '2026-02-01 07:01:10'),
(35, 17, 'pending', 83.00, NULL, NULL, NULL, NULL, '2026-02-01 07:11:49', '2026-02-01 07:11:49'),
(36, 17, 'completed', 53.00, 'Maskavas 4', 'family_card', 'pi_3Svx5yCGF8xpxcU71ANLNL2c', NULL, '2026-02-01 07:37:30', '2026-02-01 07:37:30'),
(37, 11, 'pending', 1703.00, 'Valdemara 5', 'stripe', NULL, NULL, '2026-02-01 07:40:52', '2026-02-01 07:40:52'),
(38, 11, 'completed', 353.00, 'Valdemara 5', 'stripe', NULL, NULL, '2026-02-01 07:44:56', '2026-02-01 09:58:11'),
(39, 19, 'completed', 1943.00, 'Marbel street 9', 'family_card', 'pi_3SyCyrCGF8xpxcU71E1HohG6', NULL, '2026-02-07 12:59:30', '2026-02-07 12:59:30'),
(40, 20, 'pending', 53.00, 'Marbel street 9', 'stripe', NULL, NULL, '2026-02-07 13:03:54', '2026-02-07 13:03:54'),
(41, 20, 'completed', 53.00, 'Marbel street 9', 'stripe', NULL, NULL, '2026-02-07 13:05:05', '2026-02-07 13:12:10'),
(42, 20, 'completed', 53.00, 'Marbel street 9', 'family_card', 'pi_3SyD6PCGF8xpxcU71nxfRE6o', NULL, '2026-02-07 13:07:18', '2026-02-07 13:07:18'),
(43, 20, 'completed', 803.00, 'Marbel street 9', 'family_card', 'pi_3SyD7pCGF8xpxcU70IN0FHhc', NULL, '2026-02-07 13:08:46', '2026-02-07 13:08:46'),
(44, 11, 'pending', 353.99, 'Valdemara 5', 'stripe', NULL, NULL, '2026-02-14 08:16:25', '2026-02-14 08:16:25'),
(45, 11, 'pending', 23.00, 'Valdemara 5', 'stripe', NULL, NULL, '2026-02-14 09:20:04', '2026-02-14 09:20:04'),
(46, 11, 'completed', 23.00, 'Valdemara 5', 'stripe', 'pi_3T0h7eCGF8xpxcU70jokXD4o', NULL, '2026-02-14 09:34:25', '2026-02-14 09:34:54'),
(47, 22, 'pending', 993.00, 'Skuju iela 19', 'stripe', NULL, NULL, '2026-03-02 14:35:55', '2026-03-02 14:35:55'),
(48, 22, 'pending', 993.00, 'Skuju iela 19', 'stripe', NULL, NULL, '2026-03-02 14:43:34', '2026-03-02 14:43:34'),
(49, 22, 'completed', 103.00, 'Skuju iela 19', 'family_card', 'pi_3T6Za5CGF8xpxcU71SOAIq5r', NULL, '2026-03-02 14:44:28', '2026-03-02 14:44:28'),
(50, 22, 'completed', 453.99, 'Skuju iela 19', 'family_card', 'pi_3T6ZemCGF8xpxcU70xa0RvN6', NULL, '2026-03-02 14:49:19', '2026-03-02 14:49:19'),
(51, 22, 'completed', 603.00, 'Skuju iela 19', 'family_card', 'pi_3T6a9TCGF8xpxcU71EwkPt2d', NULL, '2026-03-02 15:21:04', '2026-03-02 15:21:04'),
(52, 22, 'pending', 603.00, 'Skuju iela 19', 'stripe', NULL, NULL, '2026-03-02 15:21:09', '2026-03-02 15:21:09'),
(53, 11, 'completed', 903.00, 'Valdemara 5', 'stripe', 'pi_3T6aBPCGF8xpxcU708eYWFPC', NULL, '2026-03-02 15:22:43', '2026-03-02 15:23:07'),
(54, 11, 'completed', 1053.00, 'Valdemara 5', 'stripe', 'pi_3T6aIkCGF8xpxcU71MiahuCT', NULL, '2026-03-02 15:30:20', '2026-03-02 15:30:42'),
(55, 11, 'completed', 2053.00, 'Valdemara 5', 'stripe', 'pi_3T6aL8CGF8xpxcU70THUOKfh', NULL, '2026-03-02 15:32:52', '2026-03-02 15:33:09'),
(56, 11, 'completed', 2053.00, 'Valdemara 5', 'stripe', 'pi_3T6aSvCGF8xpxcU70zqB8P6k', NULL, '2026-03-02 15:40:55', '2026-03-02 15:41:13'),
(57, 11, 'cancelled', 2053.00, 'Valdemara 5', 'stripe', 'pi_3T6aX9CGF8xpxcU71aqJ2ddb', NULL, '2026-03-02 15:45:16', '2026-03-02 15:56:51'),
(58, 21, 'pending', 2623.00, 'Skuju iela 19', 'stripe', NULL, NULL, '2026-03-07 07:26:02', '2026-03-07 07:26:02'),
(59, 21, 'pending', 1073.00, 'Skuju iela 19', 'stripe', NULL, NULL, '2026-03-07 07:33:03', '2026-03-07 07:33:03'),
(60, 21, 'completed', 4553.00, 'Skuju iela 19', 'family_card', 'pi_3T8HI0CGF8xpxcU710TC2Hpc', NULL, '2026-03-07 07:36:52', '2026-03-07 07:36:52'),
(61, 21, 'pending', 1003.00, 'Skuju iela 19', 'stripe', NULL, NULL, '2026-03-07 07:40:38', '2026-03-07 07:40:38'),
(62, 22, 'completed', 453.99, 'Skuju iela 19', 'family_card', 'pi_3T8HN2CGF8xpxcU715sBPIiG', NULL, '2026-03-07 07:42:04', '2026-03-07 07:42:04'),
(63, 22, 'completed', 1204.65, 'Skuju iela 19', 'family_card', 'pi_3T8IkHCGF8xpxcU71rfGgONm', NULL, '2026-03-07 09:10:15', '2026-03-07 09:10:15');

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
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(255) NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `image`, `category`, `admin_id`, `created_at`, `updated_at`) VALUES
(1, 'Intel Core i5F', 105.00, 'Intel Core i5 processor, 5 Ghz', 'images/front/inteli5.png', 'Component', 1, '2025-04-27 06:02:47', '2025-04-27 06:02:47'),
(2, 'Intel Core i7', 200.00, 'Intel Core i7 processor, 6 Ghz', 'images/front/inteli7.jpg', 'Component', 1, '2025-04-27 06:02:47', '2025-04-27 06:02:47'),
(3, 'Intel Core i5', 150.00, 'Intel Core i5 processor, 6 Ghz', 'images/front/inteli5.png', 'Component', 1, '2025-04-27 06:02:47', '2025-04-27 06:02:47'),
(4, 'Intel Core i9', 400.00, 'Intel Core i9 processor, 7 Ghz', 'images/front/inteli9.jpg', 'Component', 1, '2025-04-27 06:02:47', '2025-04-27 06:02:47'),
(5, 'Intel Core i9', 450.00, 'Intel Core i9 processor, 9 Ghz', 'images/front/inteli9.jpg', 'Component', 1, '2025-04-27 06:02:47', '2025-04-27 06:02:47'),
(6, 'Intel Core i3', 50.00, 'Intel Core i3 processor, 3 Ghz', 'images/front/inteli3.jpg', 'Component', 1, '2025-04-27 06:02:47', '2025-04-27 06:02:47'),
(7, 'Adata SSD 1 TB', 70.00, 'Adata SSD 1 TB DISK', 'images/front/adata.jpg', 'Component', 1, '2025-04-27 06:02:47', '2025-04-27 06:02:47'),
(8, 'WD SSD 1 TB', 90.00, 'WD SSD 1 TB DISK', 'images/front/adata.jpg', 'Component', 1, '2025-04-27 06:02:47', '2025-04-27 06:02:47'),
(9, 'Nvidia RTX 3060', 300.00, 'Nvidia RTX 3060 6GB', 'images/front/3060.jpg', 'Component', 1, '2025-04-27 06:02:47', '2025-04-27 06:02:47'),
(10, 'Nvidia RTX 3080', 500.00, 'Nvidia RTX 3080 12 GB', 'images/front/3080.jpg', 'Component', 1, '2025-04-27 06:02:47', '2025-04-27 06:02:47'),
(11, 'Nvidia RTX 3090', 700.00, 'Nvidia RTX 3090 12 GB', 'images/front/3090.jpg', 'Component', 1, '2025-04-27 06:02:47', '2025-04-27 06:02:47'),
(12, 'Kingston RAM 3200 MHz 8GB', 50.00, 'Kingston RAM 3200 MHz 8GB 1 stick', 'images/front/kingstonram.jpg', 'Component', 1, '2025-04-27 06:02:47', '2025-04-27 06:02:47'),
(13, 'Kingston RAM 3200 MHz 16GB', 70.00, 'Kingston RAM 3200 MHz 16GB 1 stick', 'images/front/kingstonram.jpg', 'Component', 1, '2025-04-27 06:02:47', '2025-04-27 06:02:47'),
(14, 'Kingston RAM 3200 MHz 32GB', 80.00, 'Kingston RAM 3200 MHz 32GB 1 stick', 'images/front/kingstonram.jpg', 'Component', 1, '2025-04-27 06:02:47', '2025-04-27 06:02:47'),
(15, 'MSI Gaming Laptop', 600.00, 'MSI Gaming Laptop:\n                                Intel Core i7 processor, 4 Ghz, 12 cores, 24 threads\n                                NVIDIA RTX 3060 6 Gb\n                                RAM Fury 16 Gb 3200 Mhz\n                                SSD 1 TB', 'images/front/msilp.jpg', 'Laptop', 1, '2025-04-27 06:02:47', '2025-05-25 12:56:28'),
(16, 'Asus Gaming Laptop', 870.00, 'Asus Gaming Laptop:\n                                Intel Core i9 processor, 7 Ghz, 12 cores, 24 threads\n                                NVIDIA RTX 3080 6 Gb\n                                RAM Fury 16 Gb 3200 Mhz\n                                SSD 1 TB', 'images/front/asusgl.jpg', 'Laptop', 1, '2025-04-27 06:02:47', '2025-04-27 06:02:47'),
(17, 'Gigabyte Laptop', 900.00, 'Gigabyte Laptop:\n                                Intel Core i9 processor, 7 Ghz, 12 cores, 24 threads\n                                RAM Fury 32 Gb 3200 Mhz\n                                SSD 1 TB', 'images/front/giglp.jpg', 'Laptop', 1, '2025-04-27 06:02:47', '2025-04-27 06:02:47'),
(18, 'MSI Gaming Station', 750.00, 'MSI Gaming Station:\n                                MSI Gaming Station:\n                                Intel Core i5 processor, 5 Ghz\n                                NVIDIA RTX 3060 8 Gb\n                                RAM Fury 32 Gb 3200 Mhz\n                                SSD 1 TB\n                                HDD 2 TB\n                                Power Supply Be quiet 700 W', 'images/front/pc.png', 'Pc', 1, '2025-04-27 06:02:47', '2025-05-25 12:56:52'),
(33, 'MSI Gaming Station', 970.00, 'MSI Gaming Station: MSI Gaming Station: Intel Core i7 processor, 5 Ghz NVIDIA RTX 3080 8 Gb RAM Fury 32 Gb 3200 Mhz SSD 1 TB HDD 2 TB Power Supply Be quiet 700 W', 'images/front/1748188525_pc.png', 'Pc', 1, '2025-05-25 12:55:25', '2025-05-25 12:59:02'),
(34, 'Custom 60%', 100.00, 'Custom 60% keyboard, mechanical, red switches, wireless', 'images/front/1750755707_keyboard.png', 'Peripheral', 1, '2025-06-24 06:01:47', '2025-06-24 06:01:47'),
(35, 'Google Pixel 9 Pro', 800.00, 'Google pixel 9 Pro phone\r\n512 Gb Rom\r\n12 Gb RAM\r\nSnapdragon Elite G CPU\r\nAdreno 890 GPU\r\n7000 Mah Battery\r\nType C', 'images/front/1750755864_phones.png', 'Phone', 1, '2025-06-24 06:04:24', '2025-06-24 06:04:24'),
(36, 'Custom 60%', 150.00, 'Custom 60% keyboard, mechanical, blue switches, wireless', 'images/front/1750755707_keyboard.png', 'Peripheral', 1, '2025-06-24 06:01:47', '2025-06-24 06:01:47'),
(37, 'Custom 60%', 150.00, 'Custom 60% keyboard, mechanical, green switches, wireless', 'images/front/1750755707_keyboard.png', 'Peripheral', 1, '2025-06-24 06:01:47', '2025-06-24 06:01:47'),
(38, 'Custom 70%', 150.00, 'Custom 70% keyboard, mechanical, red switches, wireless', 'images/front/1750755707_keyboard.png', 'Peripheral', 1, '2025-06-24 06:01:47', '2025-06-24 06:01:47'),
(39, 'Custom 60%', 200.00, 'Custom 60% keyboard, magnetic switches, wireless', 'images/front/1750755707_keyboard.png', 'Peripheral', 1, '2025-06-24 06:01:47', '2025-06-24 06:01:47'),
(40, 'Custom 60%', 50.00, 'Custom 60% keyboard, red switches, wireless', 'images/front/1750755707_keyboard.png', 'Peripheral', 1, '2025-06-24 06:01:47', '2025-06-24 06:01:47'),
(41, 'Custom 60%', 30.00, 'Custom 60% keyboard, red switches, type-c cable', 'images/front/1750755707_keyboard.png', 'Peripheral', 1, '2025-06-24 06:01:47', '2025-06-24 06:01:47'),
(42, 'Google Pixel 9 Ultra', 900.00, 'Google pixel 9 Ultra phone\r\n512 Gb Rom\r\n16 Gb RAM\r\nSnapdragon Elite G CPU\r\nAdreno 890 GPU\r\n7500 Mah Battery\r\nType C', 'images/front/1750755864_phones.png', 'Phone', 1, '2025-06-24 06:04:24', '2025-06-24 06:04:24'),
(43, 'Google Pixel 9', 700.00, 'Google pixel 9 phone\r\n256 Gb Rom\r\n8 Gb RAM\r\nSnapdragon Elite G CPU\r\nAdreno 890 GPU\r\n5000 Mah Battery\r\nType C', 'images/front/1750755864_phones.png', 'Phone', 1, '2025-06-24 06:04:24', '2025-06-24 06:04:24'),
(44, 'MSI Gaming Laptop Pro', 1000.00, 'MSI Gaming Laptop:\r\n                                Intel Core i9 processor, 4 Ghz, 16 cores, 24 threads\r\n                                NVIDIA RTX 3060 8 Gb\r\n                                RAM Fury 16 Gb 3200 Mhz\r\n                                SSD 2 TB', 'images/front/msilp.jpg', 'Laptop', 1, '2025-04-27 06:02:47', '2025-05-25 12:56:28'),
(45, 'MSI Gaming Laptop Lite', 500.00, 'MSI Gaming Laptop:\r\n                                Intel Core i5 processor, 4 Ghz, 10 cores, 20 threads\r\n                                NVIDIA RTX 3060 4 Gb\r\n                                RAM Fury 16 Gb 3200 Mhz\r\n                                SSD 2 TB', 'images/front/msilp.jpg', 'Laptop', 1, '2025-04-27 06:02:47', '2025-05-25 12:56:28'),
(46, 'Sony Studio', 70.00, 'Sony Studio Pro Headphones', 'images/front/1750924746_sony.png', 'Peripheral', 1, '2025-06-26 04:59:06', '2025-06-26 04:59:06'),
(47, 'Sony Studio Ultra', 90.00, 'Sony Studio Pro Headphones', 'images/front/1750924746_sony.png', 'Peripheral', 1, '2025-06-26 04:59:06', '2025-06-26 04:59:06'),
(48, 'Sony Studio Wireless', 50.00, 'Sony Studio Pro Headphones', 'images/front/1750924746_sony.png', 'Peripheral', 1, '2025-06-26 04:59:06', '2025-06-26 04:59:06'),
(49, 'Logitech G Pro', 120.00, 'Logitech G Pro Mouse', 'images/front/1750924976_logi.png', 'Peripheral', 1, '2025-06-26 05:02:56', '2025-06-26 05:02:56'),
(50, 'Leo Table Pro', 200.00, 'Leo Table Pro 1.5 x 1 x 2 m', 'images/front/1752158103_table.png', 'Furniture', 1, '2025-07-10 11:35:03', '2025-07-10 11:35:03'),
(51, 'Power Cable', 20.00, 'Power Cable for power supplies from 200 to 700 w', 'images/front/1752158167_cables.png', 'Cable', 1, '2025-07-10 11:36:07', '2025-07-10 11:36:07'),
(52, 'Play Station 5', 350.00, 'Play Station 5 Slim 1 TB AMD', 'images/front/1756645594_games.png', 'Games', 1, '2025-08-31 10:06:34', '2025-08-31 10:06:34'),
(53, 'Play Station 5 Pro', 400.55, 'Play Station 5 Pro 1 TB AMD', 'images/front/1765026545_1756645594_games.png', 'Games', 1, '2025-12-06 11:09:05', '2026-02-01 09:57:49'),
(54, 'Asus Ergo Table', 350.99, 'Asus Ergo Table 1.7 x 1.2 x 2 m', 'images/front/1770477093_1752158103_table.png', 'Furniture', 1, '2026-02-07 13:11:33', '2026-02-07 13:11:33'),
(55, 'Nvidia RTX 5060', 1100.99, 'Nvidia RTX 5060 10 GB, DLSS 4', 'images/front/1770477254_3090.jpg', 'Component', 1, '2026-02-07 13:14:14', '2026-02-07 13:14:14'),
(56, 'Nvidia RTX 5090', 1400.00, 'Nvidia RTX 5090 16 GB, DLSS 4', 'images/front/1772877398_3090.jpg', 'Component', 1, '2026-03-07 07:56:38', '2026-03-07 07:56:38');

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
('uTCxihehCuS31e1fd4NF17sZ2QYP9B9TuyjCN5tZ', 22, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiejhlSXowOUpZWDJMTjU0bXluY0VWRXJiVXNUaHp3NmNWYXRwbE1pYSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Njc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0cy9sYXB0b3BzP3ByaWNlX21heD0xMDAwMDAmcHJpY2VfbWluPTAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyMjtzOjUyOiJsb2dpbl9hZG1pbl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1772883822),
('zY2EV5u1d4ZydiRUEg4qUzSoMPfksBL9UXyporWm', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNWxpejY0OGlwT09GVXlOYVU5V0dqb01OMWlNM2ZzeXc4VVJQR3NwMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Njc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9kdWN0cy9sYXB0b3BzP3ByaWNlX21heD0xMDAwMDAmcHJpY2VfbWluPTAiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1773487621);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `family_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role` enum('parent','child','standalone') DEFAULT 'child',
  `is_family_admin` tinyint(1) DEFAULT 0,
  `can_use_family_card` tinyint(1) DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `awards` int(11) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `family_id`, `role`, `is_family_admin`, `can_use_family_card`, `name`, `email`, `email_verified_at`, `address`, `awards`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 2, 'parent', 0, 0, 'johndoe', 'johndoe@gmail.com', NULL, NULL, NULL, '$2y$12$CxAVn/AIQsjPZiFKG5GFu.fEx4cO8wOWH2f7ZAlXqJMZRi7AV9irC', NULL, '2025-04-27 06:03:14', '2026-02-01 09:57:33'),
(2, NULL, 'standalone', 0, 0, 'igor', 'igor@gmail.com', NULL, NULL, NULL, '$2y$12$e2g1Owb1styaeIdPe/m92OpEaAHyXenGMcA8YNHrxFabGpi/JjKby', NULL, '2025-05-15 07:39:42', '2025-05-15 07:39:42'),
(3, NULL, 'standalone', 0, 0, 'rvt', 'rvt@gmail.com', NULL, NULL, NULL, '$2y$12$jPxR/QjacCOyr6dv.sJEVezgN.170e2O9ElLtewTj77K9n2GkFikS', NULL, '2025-05-25 08:15:11', '2025-05-25 08:15:11'),
(4, NULL, 'standalone', 0, 0, 'donufrijuks', 'donufrijuks@gmail.com', NULL, NULL, NULL, '$2y$12$FiXc4Mb/i/NtMXokjqI/.eBGodARvWNcBbhEsUwy/80dAnFJ.BgC.', NULL, '2025-06-05 03:44:41', '2025-06-05 03:44:41'),
(5, NULL, 'standalone', 0, 0, 'freemen1094', 'freemen1094@gmail.com', NULL, NULL, NULL, '$2y$12$O.k/JwU8ylbUlmulbfzYTOJ6XRE/HNoHZq3sBnWN/EaKvuT.qTUqa', NULL, '2025-06-05 03:47:49', '2025-06-05 03:47:49'),
(6, NULL, 'standalone', 0, 0, 'dhl', 'dhl@gmail.com', NULL, NULL, NULL, '$2y$12$Gbds2d0rYJELIAipqmh/nuRcjgnwvp7bA9eWOwuLDG8wG7OzfckRq', NULL, '2025-06-05 03:49:50', '2025-06-05 03:49:50'),
(7, NULL, 'standalone', 0, 0, 'asink', 'asink@gmail.com', NULL, NULL, NULL, '$2y$12$p6m2CmKtItJSN6Z2KdiAJ.Uuuw9s9YU9/sW6RZgPE52CLjdj6wiF6', NULL, '2025-06-05 04:05:10', '2025-12-06 11:23:25'),
(8, NULL, 'standalone', 0, 0, 'kms', 'kms@gmail.com', NULL, NULL, NULL, '$2y$12$MpXpo4yDiAq3PJMAFlyp/eWYmH1TCEhgch0mR0.vjRQ2GRu0p381.', NULL, '2025-06-05 04:10:37', '2025-06-05 04:10:37'),
(9, NULL, 'standalone', 0, 0, 'g', 'g@gmail.com', NULL, NULL, NULL, '$2y$12$scke6DlNfV7ydwerRaOodejJCv.DpT0zGoM9Azrm7dvvQ/IagPHgG', NULL, '2025-06-24 07:35:37', '2025-06-24 07:35:37'),
(10, NULL, 'standalone', 0, 0, 'mYn', 'my@gmail.com', NULL, NULL, NULL, '$2y$12$eMrlPNDO.K3hEEgi/fbmU../9WRqHr5Jx.9MW8Nflyk.DspnF65Zi', NULL, '2025-08-31 05:07:47', '2025-08-31 06:40:22'),
(11, NULL, 'standalone', 0, 0, 'man', 'man@gmail.com', NULL, 'Valdemara 5', 9, '$2y$12$U2oL8EUVePDWFHsR2g3COepYZKuFWFhbcpBRSpbUNi9KvLRtoKXCa', NULL, '2025-10-05 10:36:32', '2026-02-14 08:15:41'),
(12, NULL, 'standalone', 0, 0, 'man2', 'man2@gmail.com', NULL, 'Valdemara 3', 12, '$2y$12$oUaHDhdMJkEOOR1dr7.0DuGV6sEn04wJ7gPCedoc0InrFqmaSIq/K', NULL, '2026-01-17 08:49:11', '2026-01-17 09:12:43'),
(13, NULL, 'standalone', 0, 0, 'man3', 'man3@gmail.com', NULL, 'Skuju 5', 16, '$2y$12$FZO4d7XT6TMB05XcrmCLmuvrq7xxOvlk7ukfWr7S0v5zt.4yYX4Zi', NULL, '2026-01-17 13:14:25', '2026-01-28 15:32:31'),
(14, 1, 'parent', 1, 1, 'family', 'family@gmail.com', NULL, 'Maskavas 4', 10, '$2y$12$OGEIrL74Kbe6/v94mdeSKejR7e3uobLUb2oSgOj53kolv7vwzxJOa', NULL, '2026-01-31 12:53:22', '2026-01-31 13:04:15'),
(15, 1, 'child', 0, 1, 'family1', 'family1@gmail.com', NULL, 'Maskavas 5', NULL, '$2y$12$e/CqmIMJIWHxw7E/gNotGOH38sy.KF6Y1Y0xlCl7Qjh2gv1HCaxwO', NULL, '2026-01-31 13:24:59', '2026-02-01 06:53:46'),
(16, NULL, 'standalone', 0, 0, 'man5', 'man5@gmail.com', NULL, 'Valdemara 9b', 11, '$2y$12$TIWTteJ/nKm15K02OjVaOeT7ZS8jgyD7tc1cOxRFgQenqotklx1R6', NULL, '2026-01-31 13:37:35', '2026-01-31 13:38:17'),
(17, 1, 'child', 0, 1, 'family2', 'family2@gmail.com', NULL, 'Maskavas 4', NULL, '$2y$12$4.luQcet71XcjoN7nrZNX.R1JX1qEzs2GHYfZicOskyVPgR3G6F0K', NULL, '2026-02-01 06:57:54', '2026-02-01 06:59:10'),
(18, NULL, 'standalone', 0, 0, 'Mike', 'mike@gmail.com', NULL, NULL, 0, '$2y$12$OA3eC4tdWqixS.v8fJ8Qr.gArnhxtMVZM3CSuzkupQZg3s5b1OzQq', NULL, '2026-02-01 10:07:23', '2026-02-01 10:07:23'),
(19, 3, 'parent', 1, 1, 'johns', 'johns@gmail.com', NULL, 'Marbel street 9', 7, '$2y$12$yPsqlLGgbsy2PtAe8OJxqOoz.cwa5Qqal5qj46/iZ5XBB9U6RC9KK', NULL, '2026-02-07 12:57:32', '2026-02-07 13:21:17'),
(20, 3, 'child', 0, 1, 'mikee', 'mikee@gmail.com', NULL, 'Marbel street 9', NULL, '$2y$12$LDC6kW.IFLg8jqBMGrAmP.ZXRJGBI5TcG/znnHKdztZ5VC9FMSrtq', NULL, '2026-02-07 13:01:31', '2026-02-07 13:02:27'),
(21, 4, 'parent', 1, 1, 'family3', 'family3@gmail.com', NULL, 'Skuju iela 19', NULL, '$2y$12$xvYQnsAbsDjSiah5gaNv3usyaeCnaLu0bJl0yxCbczLAd.VgqKp1W', NULL, '2026-03-02 14:33:05', '2026-03-02 14:33:05'),
(22, 4, 'child', 0, 1, 'smith1', 'smith1@gmail.com', NULL, 'Skuju iela 19', NULL, '$2y$12$0CSTyLtti0ZXQTcQsq/ul.nNDNWVNw10J4XHx/DB6uVI8JrgKjruK', NULL, '2026-03-02 14:35:07', '2026-03-02 14:42:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `auctions`
--
ALTER TABLE `auctions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auctions_user_id_foreign` (`user_id`);

--
-- Indexes for table `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bids_user_id_foreign` (`user_id`),
  ADD KEY `bids_item_id_foreign` (`item_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `books_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `families`
--
ALTER TABLE `families`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invitation_code` (`invitation_code`),
  ADD KEY `idx_families_invitation_code` (`invitation_code`),
  ADD KEY `idx_families_parent_id` (`parent_id`),
  ADD KEY `idx_families_stripe_customer` (`stripe_customer_id`);

--
-- Indexes for table `family_payment_methods`
--
ALTER TABLE `family_payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_family_payment` (`family_id`,`stripe_payment_method_id`),
  ADD KEY `added_by_user_id` (`added_by_user_id`),
  ADD KEY `idx_family_payment_methods_family` (`family_id`),
  ADD KEY `idx_family_payment_methods_default` (`family_id`,`is_default`),
  ADD KEY `idx_family_payment_methods_stripe` (`stripe_payment_method_id`);

--
-- Indexes for table `family_transactions`
--
ALTER TABLE `family_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_family_transactions_family` (`family_id`),
  ADD KEY `idx_family_transactions_user` (`user_id`),
  ADD KEY `idx_family_transactions_created` (`created_at`),
  ADD KEY `idx_family_transactions_status` (`status`),
  ADD KEY `idx_family_transactions_stripe` (`stripe_payment_intent_id`),
  ADD KEY `fk_family_transactions_order` (`order_id`);

--
-- Indexes for table `goods_orders`
--
ALTER TABLE `goods_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_orders_order_id_foreign` (`order_id`),
  ADD KEY `fk_goods_orders_product_id` (`product_id`);

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `idx_users_family_id` (`family_id`),
  ADD KEY `idx_users_family_role` (`family_id`,`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `auctions`
--
ALTER TABLE `auctions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bids`
--
ALTER TABLE `bids`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `families`
--
ALTER TABLE `families`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `family_payment_methods`
--
ALTER TABLE `family_payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `family_transactions`
--
ALTER TABLE `family_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `goods_orders`
--
ALTER TABLE `goods_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `auctions`
--
ALTER TABLE `auctions`
  ADD CONSTRAINT `auctions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `bids_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `auctions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bids_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `families`
--
ALTER TABLE `families`
  ADD CONSTRAINT `families_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `family_payment_methods`
--
ALTER TABLE `family_payment_methods`
  ADD CONSTRAINT `family_payment_methods_ibfk_1` FOREIGN KEY (`family_id`) REFERENCES `families` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `family_payment_methods_ibfk_2` FOREIGN KEY (`added_by_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `family_transactions`
--
ALTER TABLE `family_transactions`
  ADD CONSTRAINT `family_transactions_ibfk_1` FOREIGN KEY (`family_id`) REFERENCES `families` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `family_transactions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_family_transactions_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `goods_orders`
--
ALTER TABLE `goods_orders`
  ADD CONSTRAINT `fk_goods_orders_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `goods_orders_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_family` FOREIGN KEY (`family_id`) REFERENCES `families` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:4306
-- Время создания: Апр 06 2025 г., 14:09
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `comex`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admins`
--

CREATE TABLE `admins` (
                          `id` bigint(20) UNSIGNED NOT NULL,
                          `name` varchar(255) NOT NULL,
                          `password` varchar(255) NOT NULL,
                          `created_at` timestamp NULL DEFAULT NULL,
                          `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`, `created_at`, `updated_at`) VALUES
    (3, 'admin@super.com', '$2y$12$QQ9rCXrMkUgdqciwJZ8QmuII/CW4GqD2r2xbsr7HKZn7j7JPCtc0W', '2025-01-17 12:02:06', '2025-01-17 12:02:06');

-- --------------------------------------------------------

--
-- Структура таблицы `auctions`
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
-- Дамп данных таблицы `auctions`
--

INSERT INTO `auctions` (`id`, `name`, `description`, `starting_bid`, `img`, `start_time`, `end_time`, `user_id`, `created_at`, `updated_at`) VALUES
    (1, 'Mac 1990', 'Mac 1990 unique', 20000.00, 'images/front/1739113880_1738502092_maco.jpeg', '2025-02-09', '2025-08-31', 1, '2025-02-09 13:11:20', '2025-02-09 13:11:20');

-- --------------------------------------------------------

--
-- Структура таблицы `bids`
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
-- Дамп данных таблицы `bids`
--

INSERT INTO `bids` (`id`, `user_id`, `item_id`, `bid_amount`, `created_at`, `updated_at`) VALUES
    (1, 1, 1, 1.00, '2025-02-10 12:26:17', '2025-02-10 12:26:17');

-- --------------------------------------------------------

--
-- Структура таблицы `books`
--

CREATE TABLE `books` (
                         `id` bigint(20) UNSIGNED NOT NULL,
                         `created_at` timestamp NULL DEFAULT NULL,
                         `updated_at` timestamp NULL DEFAULT NULL,
                         `name` varchar(255) NOT NULL,
                         `description` text NOT NULL,
                         `file_path` varchar(255) NOT NULL,
                         `img` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `cache`
--

CREATE TABLE `cache` (
                         `key` varchar(255) NOT NULL,
                         `value` mediumtext NOT NULL,
                         `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `cache_locks`
--

CREATE TABLE `cache_locks` (
                               `key` varchar(255) NOT NULL,
                               `owner` varchar(255) NOT NULL,
                               `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `failed_jobs`
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
-- Структура таблицы `gpt_traces`
--

CREATE TABLE `gpt_traces` (
                              `id` bigint(20) UNSIGNED NOT NULL,
                              `class` varchar(255) NOT NULL,
                              `input` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`input`)),
                              `model_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`model_response`)),
                              `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta`)),
                              `attributes` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attributes`)),
                              `deleted_at` timestamp NULL DEFAULT NULL,
                              `created_at` timestamp NULL DEFAULT NULL,
                              `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `jobs`
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
-- Структура таблицы `job_batches`
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
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
                              `id` int(10) UNSIGNED NOT NULL,
                              `migration` varchar(255) NOT NULL,
                              `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
                                                          (8, '0001_01_01_000000_create_users_table', 1),
                                                          (9, '0001_01_01_000001_create_cache_table', 1),
                                                          (10, '0001_01_01_000002_create_jobs_table', 1),
                                                          (11, '2024_11_26_132631_create_products_table', 1),
                                                          (17, '2024_12_03_125041_create_orders_table', 2),
                                                          (18, '2025_01_16_153853_create_admins_table', 3),
                                                          (19, '2024_07_20_000000_create_gpt_traces_table', 4),
                                                          (24, '2025_01_19_161211_create_auctions_table', 5),
                                                          (25, '2025_02_09_144714_create_bids_table', 6),
                                                          (27, '2025_02_26_164946_create_books_table', 7),
                                                          (28, '2025_03_05_134141_create_poincluded_table', 8),
                                                          (29, '2025_04_05_083849_create_goods_orders_table', 9);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
                          `id` bigint(20) UNSIGNED NOT NULL,
                          `user_id` bigint(20) UNSIGNED NOT NULL,
                          `status` varchar(255) NOT NULL DEFAULT 'pending',
                          `total` decimal(10,2) NOT NULL DEFAULT 0.00,
                          `ordered_at` timestamp NULL DEFAULT NULL,
                          `created_at` timestamp NULL DEFAULT NULL,
                          `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `status`, `total`, `ordered_at`, `created_at`, `updated_at`) VALUES
                                                                                                        (27, 1, 'pending', 1300.00, NULL, '2025-04-06 07:44:52', '2025-04-06 07:44:52'),
                                                                                                        (28, 1, 'pending', 2000.00, NULL, '2025-04-06 08:14:16', '2025-04-06 08:14:16'),
                                                                                                        (29, 1, 'pending', 90.00, NULL, '2025-04-06 08:15:00', '2025-04-06 08:15:00'),
                                                                                                        (30, 1, 'pending', 2700.00, NULL, '2025-04-06 09:08:36', '2025-04-06 09:08:36');

-- --------------------------------------------------------

--
-- Структура таблицы `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
                                         `email` varchar(255) NOT NULL,
                                         `token` varchar(255) NOT NULL,
                                         `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `poincluded`
--

CREATE TABLE `poincluded` (
                              `id` bigint(20) UNSIGNED NOT NULL,
                              `order_id` bigint(20) UNSIGNED NOT NULL,
                              `product_id` bigint(20) UNSIGNED NOT NULL,
                              `quantity` int(11) NOT NULL,
                              `price` decimal(8,2) NOT NULL,
                              `created_at` timestamp NULL DEFAULT NULL,
                              `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
                            `id` bigint(20) UNSIGNED NOT NULL,
                            `name` varchar(255) NOT NULL,
                            `price` decimal(8,2) DEFAULT NULL,
                            `description` text DEFAULT NULL,
                            `image` varchar(255) DEFAULT NULL,
                            `category` varchar(255) NOT NULL,
                            `created_at` timestamp NULL DEFAULT NULL,
                            `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `image`, `category`, `created_at`, `updated_at`) VALUES
                                                                                                                   (1, 'Intel Core i5', 100.00, 'Intel Core i5 processor, 5 Ghz', 'images/front/inteli5.png', 'Component', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (2, 'Intel Core i7', 300.00, 'Intel Core i7 processor, 7 Ghz', 'images/front/inteli7.jpg', 'Component', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (3, 'Intel Core i9', 400.00, 'Intel Core i9 processor, 9 Ghz', 'images/front/inteli9.jpg', 'Component', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (4, 'Intel Core i5', 155.00, 'Intel Core i5 processor, 5.5 Ghz', 'images/front/inteli5.png', 'Component', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (5, 'Intel Core i3', 50.00, 'Intel Core i4 processor, 4 Ghz', 'images/front/inteli3.jpg', 'Component', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (6, 'MSI Gaming Station', 700.00, 'MSI Gaming Station:\r\nIntel Core i5 processor, 5 Ghz\r\nNVIDIA RTX 3060 8 Gb\r\nRAM Fury 32 Gb 3200 Mhz\r\nSSD 1 TB\r\nHDD 2 TB\r\nPower Supply Be quiet 700 W', 'images/front/pc.png', 'Pc', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (7, 'MSI Gaming Laptop', 700.00, 'MSI Gaming Laptop:\r\nIntel Core i7 processor, 4 Ghz, 12 cores, 24 threads\r\nNVIDIA RTX 3060 6 Gb\r\nRAM Fury 16 Gb 3200 Mhz\r\nSSD 1 TB', 'images/front/msilp.jpg', 'Laptop', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (8, 'Gigabyte Gaming Laptop', 600.00, 'Gigabyte Gaming Laptop:\r\nIntel Core i5 processor, 4 Ghz, 10 cores, 20 threads\r\nNVIDIA RTX 3050 6 Gb\r\nRAM Fury 32 Gb 3200 Mhz\r\nSSD 500 GB', 'images/front/giglp.jpg', 'Laptop', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (9, 'Asus Gaming Laptop', 900.00, 'Asus Gaming Laptop:\r\nIntel Core i9 processor, 6 Ghz, 12 cores, 24 threads\r\nRAM Fury 16 Gb 3200 Mhz\r\nSSD 1 TB', 'images/front/asusgl.jpg', 'Laptop', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (10, 'Macbook Laptop', 1100.00, 'Macbook Laptop:\r\nM3 processor\r\nRAM 16 Gb 3200 Mhz\r\nSSD 1 TB', 'images/front/mcbook.jpg', 'Laptop', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (11, 'Nvidia RTX 3060 ', 400.00, 'Nvidia RTX 3060 6 Gb', 'images/front/3060.jpg', 'Component', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (12, 'Nvidia RTX 3080 TI', 600.00, 'Nvidia RTX 3080 TI 8 Gb', 'images/front/3080.jpg', 'Component', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (13, 'Nvidia RTX 3090 SUPER', 800.00, 'Nvidia RTX 3090 SUPER 12 Gb', 'images/front/3090.jpg', 'Component', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (14, 'Kingston 3200 Mhz 16 Gb', 50.00, 'Kingston 3200 Mhz 16 Gb RAM 1 stick', 'images/front/kingstonram.jpg', 'Component', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (15, 'Kingston 3200 Mhz 8 Gb', 30.00, 'Kingston 3200 Mhz 8 Gb RAM 1 stick', 'images/front/kingstonram.jpg', 'Component', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (16, 'Kingston 3200 Mhz 32 Gb', 70.00, 'Kingston 3200 Mhz 32 Gb RAM 1 stick', 'images/front/kingstonram.jpg', 'Component', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (17, 'ADATA SSD 1 TB', 80.00, 'ADATA SSD 1 TB DISK', 'images/front/adata.jpg', 'Component', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (18, 'WD SSD 1 TB', 80.00, 'WD SSD 1 TB DISK', 'images/front/wd.jpg', 'Component', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (19, 'Be quiet! Power Supply 700 W', 100.00, 'Be quiet! Power Supply 700 W Bronze', 'images/front/bequietps.jpg', 'Component', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (20, 'Be quiet! Power Supply 500 W', 80.00, 'Be quiet! Power Supply 500 W Bronze', 'images/front/bequietps.jpg', 'Component', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (21, 'Be quiet! Power Supply 300 W', 80.00, 'Be quiet! Power Supply 300 W Gold', 'images/front/bequietps.jpg', 'Component', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (22, 'MSI Gaming Station PRO', 1200.00, 'MSI Gaming Station:\r\nIntel Core i7 processor, 5 Ghz, 14 cores, 28 threads\r\nNVIDIA RTX 3060 SUPER 10 Gb\r\nRAM Fury 32 Gb 6400 Mhz\r\nSSD 1 TB\r\nHDD 4 TB\r\nPower Supply Be quiet 800 W', 'images/front/pc.png', 'Pc', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (23, 'Asus Gaming Station PRO', 1500.00, 'Asus Gaming Station:\r\nIntel Core i9 processor, 5 Ghz, 16 cores, 32 threads\r\nNVIDIA RTX 3090 SUPER 12 Gb\r\nRAM Fury 32 Gb 6400 Mhz\r\nSSD 2 TB\r\nHDD 6 TB\r\nPower Supply Be quiet 800 W', 'images/front/pc.png', 'Pc', '2024-11-26 12:13:56', '2024-11-26 12:13:56'),
                                                                                                                   (24, 'Intel Core i5F', 105.00, 'Intel Core i5 processor, 6 Ghz', 'images/front/inteli5.png', 'Component', '2024-12-08 12:41:37', '2024-12-08 12:41:37'),
                                                                                                                   (25, 'Intel Core i5F', 110.00, 'Intel Core i5 processor, 7 Ghz', 'images/front/inteli5.png', 'Component', '2024-12-08 12:41:37', '2024-12-08 12:41:37'),
                                                                                                                   (26, 'Intel i5', 200.00, 'Intel i5 processor, 12 cores, 24 threads, 4 ai, 5 Ghz', 'images/front/inteli5.png', 'Component', '2025-01-17 14:04:51', '2025-01-17 14:04:51'),
                                                                                                                   (27, 'Intel i5', 200.00, 'Intel i5 processor, 12 cores, 24 threads, 4 ai, 7 Ghz', 'images/front/inteli5.png', 'Component', '2025-01-17 14:07:04', '2025-01-17 14:07:04'),
                                                                                                                   (38, 'Intel Core i5F', 105.00, 'Intel Core i5 processor, 5 Ghz', 'images/front/inteli5.png', 'Component', '2025-01-19 14:19:05', '2025-01-19 14:19:05'),
                                                                                                                   (39, 'Intel Core i7', 200.00, 'Intel Core i7 processor, 6 Ghz', 'images/front/inteli7.jpg', 'Component', '2025-01-19 14:19:05', '2025-01-19 14:19:05'),
                                                                                                                   (40, 'Intel Core i5', 150.00, 'Intel Core i5 processor, 6 Ghz', 'images/front/inteli5.png', 'Component', '2025-01-19 14:19:05', '2025-01-19 14:19:05'),
                                                                                                                   (41, 'Intel Core i9', 400.00, 'Intel Core i9 processor, 7 Ghz', 'images/front/inteli9.jpg', 'Component', '2025-01-19 14:19:05', '2025-01-19 14:19:05'),
                                                                                                                   (42, 'Intel Core i9', 450.00, 'Intel Core i9 processor, 9 Ghz', 'images/front/inteli9.jpg', 'Component', '2025-01-19 14:19:05', '2025-01-19 14:19:05'),
                                                                                                                   (43, 'Intel Core i3', 50.00, 'Intel Core i3 processor, 3 Ghz', 'images/front/inteli3.jpg', 'Component', '2025-01-19 14:19:05', '2025-01-19 14:19:05'),
                                                                                                                   (44, 'Adata SSD 1 TB', 70.00, 'Adata SSD 1 TB DISK', 'images/front/adata.jpg', 'Component', '2025-01-19 14:19:05', '2025-01-19 14:19:05'),
                                                                                                                   (45, 'WD SSD 1 TB', 90.00, 'WD SSD 1 TB DISK', 'images/front/adata.jpg', 'Component', '2025-01-19 14:19:05', '2025-01-19 14:19:05'),
                                                                                                                   (46, 'Nvidia RTX 3060', 300.00, 'Nvidia RTX 3060 6GB', 'images/front/3060.jpg', 'Component', '2025-01-19 14:19:05', '2025-01-19 14:19:05'),
                                                                                                                   (47, 'Nvidia RTX 3080', 500.00, 'Nvidia RTX 3080 12 GB', 'images/front/3080.jpg', 'Component', '2025-01-19 14:19:05', '2025-01-19 14:19:05'),
                                                                                                                   (48, 'Nvidia RTX 3090', 700.00, 'Nvidia RTX 3090 12 GB', 'images/front/3090.jpg', 'Component', '2025-01-19 14:19:05', '2025-01-19 14:19:05'),
                                                                                                                   (49, 'Kingston RAM 3200 MHz 8GB', 50.00, 'Kingston RAM 3200 MHz 8GB 1 stick', 'images/front/kingstonram.jpg', 'Component', '2025-01-19 14:19:05', '2025-01-19 14:19:05'),
                                                                                                                   (50, 'Kingston RAM 3200 MHz 16GB', 70.00, 'Kingston RAM 3200 MHz 16GB 1 stick', 'images/front/kingstonram.jpg', 'Component', '2025-01-19 14:19:05', '2025-01-19 14:19:05'),
                                                                                                                   (51, 'Kingston RAM 3200 MHz 32GB', 80.00, 'Kingston RAM 3200 MHz 32GB 1 stick', 'images/front/kingstonram.jpg', 'Component', '2025-01-19 14:19:05', '2025-01-19 14:19:05'),
                                                                                                                   (52, 'MSI Gaming Laptop', 600.00, 'MSI Gaming Laptop:\n                                Intel Core i7 processor, 4 Ghz, 12 cores, 24 threads\n                                NVIDIA RTX 3060 6 Gb\n                                RAM Fury 16 Gb 3200 Mhz\n                                SSD 1 TB', 'images/front/msilp.jpg', 'Laptop', '2025-01-19 14:19:05', '2025-01-19 14:19:05'),
                                                                                                                   (53, 'Asus Gaming Laptop', 700.00, 'Asus Gaming Laptop:\n                                Intel Core i9 processor, 7 Ghz, 12 cores, 24 threads\n                                NVIDIA RTX 3080 6 Gb\n                                RAM Fury 16 Gb 3200 Mhz\n                                SSD 1 TB', 'images/front/asusgl.jpg', 'Laptop', '2025-01-19 14:19:05', '2025-01-19 14:19:05'),
                                                                                                                   (54, 'Gigabyte Laptop', 900.00, 'Gigabyte Laptop:\n                                Intel Core i9 processor, 7 Ghz, 12 cores, 24 threads\n                                RAM Fury 32 Gb 3200 Mhz\n                                SSD 1 TB', 'images/front/giglp.jpg', 'Laptop', '2025-01-19 14:19:05', '2025-01-19 14:19:05'),
                                                                                                                   (55, 'MSI Gaming Station', 700.00, 'MSI Gaming Station:\n                                MSI Gaming Station:\n                                Intel Core i5 processor, 5 Ghz\n                                NVIDIA RTX 3060 8 Gb\n                                RAM Fury 32 Gb 3200 Mhz\n                                SSD 1 TB\n                                HDD 2 TB\n                                Power Supply Be quiet 700 W', 'images/front/pc.png', 'Pc', '2025-01-19 14:19:05', '2025-01-19 14:19:05');

-- --------------------------------------------------------

--
-- Структура таблицы `sessions`
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
-- Дамп данных таблицы `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
    ('uiWkL4QcZYWvjGiflYi6v4DcmCP7YoezlPY6lj60', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTFVSRGQ1OERaMm4wdGtPQkdJbnN5b3VFbE1BT1hIZ3d2M3F2MWs2QSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jYXJ0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1743941316);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
                         `id` bigint(20) UNSIGNED NOT NULL,
                         `name` varchar(255) NOT NULL,
                         `email` varchar(255) NOT NULL,
                         `email_verified_at` timestamp NULL DEFAULT NULL,
                         `password` varchar(255) NOT NULL,
                         `remember_token` varchar(100) DEFAULT NULL,
                         `created_at` timestamp NULL DEFAULT NULL,
                         `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
                                                                                                                               (1, 'John Doe', 'john.doe@example.com', NULL, '$2y$12$UKj.h6bxGIjH.TwpbHTLIuKVkUnje/q2C1aRaBcFhNllVjrdzHrr6', NULL, '2024-11-26 12:12:42', '2024-11-26 12:12:42'),
                                                                                                                               (2, 'Jane Smith', 'jane.smith@example.com', NULL, '$2y$12$tS0xtdhHknxzNgRgY5az8eDbXSvlRdczXpKrpvEl/02Gi8/4Tvjaq', NULL, '2024-11-26 12:12:42', '2024-11-26 12:12:42'),
                                                                                                                               (4, 'adm', 'adm@gmail.com', NULL, '$2y$12$h.756gbVwWT4mRklzJ2kouJKh8swH0IuUHW4oqpqO/Pvxfr6SYLYO', NULL, '2024-12-06 12:30:02', '2024-12-06 12:30:02'),
                                                                                                                               (5, 'dan', 'dan@gmail.com', NULL, '$2y$12$UOB9iD8EGtllrWIbgRpWr.8T02wwZ/zpdOoLc0vSAg9v375hT5ayi', NULL, '2024-12-12 13:11:11', '2024-12-12 13:11:11'),
                                                                                                                               (6, 'mark.cen', 'mark.cen@gmail.com', NULL, '$2y$12$ujiQhEPVsCzLwoooigaFieuEVNv7Wv.9KbXipQquLuN5Oa9R2dFSy', NULL, '2024-12-14 07:00:10', '2024-12-14 07:00:10'),
                                                                                                                               (11, 'freemen1094', 'freemen1094@gmail.com', NULL, '$2y$12$1cCyuXff5fpObd.UQ7X1s.PRfqiEh9L.z.YxxmQs2akML2/gzI.xG', NULL, '2024-12-15 11:43:06', '2024-12-15 11:43:06'),
                                                                                                                               (12, 'igor', 'igor@gmail.com', NULL, '$2y$12$i7sjyzGXrnfTlh2nj4cYn.fbP6QuEmiMLdVJiOZMU/qHmml/DuiiO', NULL, '2025-01-04 11:51:23', '2025-01-04 11:51:23'),
                                                                                                                               (13, 'fim', 'fim@gmail.com', NULL, '$2y$12$5iry20RRDdsYLSvmARMQIOUu2kRNT/B35z6ZGxP95wTOhgn8IItwm', NULL, '2025-01-04 11:53:30', '2025-01-04 11:53:30'),
                                                                                                                               (14, 'sukapidorgej', 'sukapidorgej@rvt.lv', NULL, '$2y$12$s9El6fGBwy19FXS9tMJZ4ensIQdApcFpDQlTwDj6QtRGDByDU/wPC', NULL, '2025-01-22 09:01:06', '2025-01-22 09:01:06'),
                                                                                                                               (15, 'admin_petuh', 'admin_petuh@gmail.com', NULL, '$2y$12$xLaIhb6.4.6xBaQi6sAgs.HxRl264ss1vLIfAVhMsOQnOz6wwNqZC', NULL, '2025-01-22 09:05:18', '2025-01-22 09:05:18'),
                                                                                                                               (16, 'tak', 'tak@gmail.com', NULL, '$2y$12$H4WUWLv1w7rAw/kLKZaT2eyHUnm92DnS06vperg9u8w7NiYk0CvIC', NULL, '2025-02-09 13:13:13', '2025-02-09 13:13:13'),
                                                                                                                               (17, 'me', 'me@gmail.com', NULL, '$2y$12$OPfiFSUMntBp0NlvKwnimOEEx1mtkL95xENXDEHm8EWf4Qf3kqMRm', NULL, '2025-02-26 14:38:55', '2025-02-26 14:38:55'),
                                                                                                                               (18, 'mek', 'mek@gmail.com', NULL, '$2y$12$lyRgyGGR5yco80gqMSR1vOF93Zm85HIYrCqP/LMFpknLC2kDBhxka', NULL, '2025-02-26 14:40:39', '2025-02-26 14:40:39'),
                                                                                                                               (19, 'geek', 'geek@gmail.com', NULL, '$2y$12$dj20foHFpNMpNYD4fd6ebudIwJcdFz3MQKFfhmMthsMDKOJ//7Sji', NULL, '2025-03-23 07:35:58', '2025-03-23 07:35:58');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admins`
--
ALTER TABLE `admins`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_name_unique` (`name`);

--
-- Индексы таблицы `auctions`
--
ALTER TABLE `auctions`
    ADD PRIMARY KEY (`id`),
  ADD KEY `auctions_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `bids`
--
ALTER TABLE `bids`
    ADD PRIMARY KEY (`id`),
  ADD KEY `bids_user_id_foreign` (`user_id`),
  ADD KEY `bids_item_id_foreign` (`item_id`);

--
-- Индексы таблицы `books`
--
ALTER TABLE `books`
    ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `cache`
--
ALTER TABLE `cache`
    ADD PRIMARY KEY (`key`);

--
-- Индексы таблицы `cache_locks`
--
ALTER TABLE `cache_locks`
    ADD PRIMARY KEY (`key`);

--
-- Индексы таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Индексы таблицы `gpt_traces`
--
ALTER TABLE `gpt_traces`
    ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `jobs`
--
ALTER TABLE `jobs`
    ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Индексы таблицы `job_batches`
--
ALTER TABLE `job_batches`
    ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
    ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
    ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Индексы таблицы `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
    ADD PRIMARY KEY (`email`);

--
-- Индексы таблицы `poincluded`
--
ALTER TABLE `poincluded`
    ADD PRIMARY KEY (`id`),
  ADD KEY `poincluded_order_id_foreign` (`order_id`),
  ADD KEY `poincluded_product_id_foreign` (`product_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
    ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sessions`
--
ALTER TABLE `sessions`
    ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admins`
--
ALTER TABLE `admins`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `auctions`
--
ALTER TABLE `auctions`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `bids`
--
ALTER TABLE `bids`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `books`
--
ALTER TABLE `books`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `gpt_traces`
--
ALTER TABLE `gpt_traces`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `jobs`
--
ALTER TABLE `jobs`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
    MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `poincluded`
--
ALTER TABLE `poincluded`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `auctions`
--
ALTER TABLE `auctions`
    ADD CONSTRAINT `auctions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `bids`
--
ALTER TABLE `bids`
    ADD CONSTRAINT `bids_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `auctions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bids_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
    ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `poincluded`
--
ALTER TABLE `poincluded`
    ADD CONSTRAINT `poincluded_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `poincluded_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

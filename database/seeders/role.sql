-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2023 at 09:40 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bss`
--

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_title` varchar(355) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent` int(11) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id`, `title`, `route_title`, `parent`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'مدیرسایت', '', 0, NULL, '2022-04-04 19:30:00', NULL),
(2, 'مشاور', 'tarho_Barname_manager_index', 0, NULL, '2022-04-04 19:30:00', NULL),
(3, 'کارفرما', 'employer_index', 0, NULL, '2022-04-04 19:30:00', NULL),
(4, 'ناظر', 'Supervisor_index', 0, NULL, '2022-04-04 19:30:00', NULL),
(5, 'مدیرعامل', 'main_manager_index', 0, NULL, '2022-04-04 19:30:00', NULL),
(6, 'مدیر مالی', 'maliManager_index', 23, NULL, '2022-04-04 19:30:00', NULL),
(7, 'کاربر حقوقی', 'legalUser_index', 0, NULL, '2022-04-04 19:30:00', NULL),
(8, 'کاربر حقیقی', 'realUser_index', 0, NULL, '2022-04-04 19:30:00', NULL),
(9, 'کارشناس', 'expert_index', 2, NULL, '2023-01-15 08:15:12', NULL),
(10, 'پرسنل عادی', 'personnel_index', 0, NULL, '2022-12-28 12:32:53', NULL),
(11, 'معاون هماهنگ کننده', 'deputy_coordinator', 0, NULL, '2023-01-10 07:01:54', NULL),
(12, 'رییس مرکز عملیات ویژه', 'head_special_operation_center', 0, NULL, '2023-01-10 07:07:19', NULL),
(13, 'رییس مرکز گفتمان سازی', 'head_discourse_creation', 0, NULL, '2023-01-10 07:09:13', NULL),
(14, 'رییس مرکز نوآوری اجتماعی', 'head_innovation', 0, NULL, '2023-01-10 07:10:28', NULL),
(15, 'رییس مرکز پیشرفت منطقه ای', 'head_development', 0, NULL, '2023-01-10 07:11:22', NULL),
(16, 'مدیر پشتیبانی', 'support_manager_index', 23, NULL, '2023-01-10 07:13:42', NULL),
(17, 'کارشناس پشتیبانی', 'support_expert_index', 16, NULL, '2023-01-10 07:14:30', NULL),
(18, 'مدیر روابط عمومی', 'relations_manager_index', 23, NULL, '2023-01-10 07:18:07', NULL),
(19, 'کارشناس ارشد مرکز عملیات ویژه', 'special_expert', 12, NULL, '2023-01-10 07:20:33', NULL),
(20, 'کارشناس مرکز گفتمان سازی', 'discourse_expert', 13, NULL, '2023-01-10 07:20:33', NULL),
(21, 'کارشناس مرکز نوآوری', 'innovation_expert', 14, NULL, '2023-01-10 07:21:35', NULL),
(22, 'کارشناس مالی', 'mali_expert', 6, NULL, '2023-01-10 07:27:10', NULL),
(23, 'معاون طرح و برنامه', 'deputy_plan_program_index', 5, NULL, '2023-01-15 11:19:32', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

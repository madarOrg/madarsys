-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.40 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for madarsys
CREATE DATABASE IF NOT EXISTS `madarsys` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `madarsys`;

-- Dumping structure for table madarsys.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للمستخدم',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم المستخدم الكامل',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'البريد الإلكتروني للمستخدم',
  `email_verified_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ التحقق من البريد الإلكتروني',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'كلمة المرور المشفرة',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رمز التذكر لتسجيل الدخول التلقائي',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء الحساب',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي أنشأ هذا الحساب',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للحساب',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي عدّل الحساب',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف الفرع المرتبط بالمستخدم',
  `status` int NOT NULL DEFAULT '1' COMMENT 'حالة المستخدم',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_branch_id_foreign` (`branch_id`),
  CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.users: ~7 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `created_user`, `updated_at`, `updated_user`, `branch_id`, `status`) VALUES
	(5, 'Admin User', 'admin@example.com', NULL, '$2y$12$jnUXvk3V9ScczuVkKifvDe1j67QtFwUETal7cER2Im3r2b3sBBh9q', NULL, '2025-02-18 01:32:17', NULL, '2025-02-18 01:32:17', NULL, 2, 1),
	(6, 'Representative User', 'representative@example.com', NULL, '$2y$12$xWl20NDFhkeajRE1R63l/.fh/fAC/Ap7BeSK7QkzDAXPeKZBy.6BC', NULL, '2025-02-18 01:32:17', NULL, '2025-02-18 01:32:17', NULL, 2, 1),
	(7, 'Storekeeper User', 'storekeeper@example.com', NULL, '$2y$12$cvDMVj/bqhkxemZ.FgIFkep7jTAbc.Ew5GbZ4e1iqenElRd7ua1qC', NULL, '2025-02-18 01:32:17', NULL, '2025-02-18 01:32:17', NULL, 2, 1),
	(12, 'Accountant User', 'acountant@example.com', NULL, '$2y$12$maT0LFXz2xHMJPvQHmcaCu0/77fwWePeIvvQ85QWeawu2HfokSgN2', NULL, '2025-04-14 12:21:21', 5, '2025-04-15 04:55:41', 12, 2, 1),
	(14, 'ShippingClerk', 'ShippingClerk@example.com', NULL, '$2y$12$P9KZs/rZqDFKQTlGp9JoOu/GhUBcQfTV9n2V1/qYR4tyLYvwZT.5O', NULL, '2025-04-15 04:58:19', 5, '2025-04-15 04:58:19', 5, 2, 1),
	(15, 'Warehouse Manager', 'WarehouseManager@example.com', NULL, '$2y$12$thbJ8rq6d7nbk0RjIi8LR.vMxcyeKHtYz4TxbWzYvzROUfuiD4ORG', NULL, '2025-04-15 05:05:31', 5, '2025-04-15 05:05:31', 5, 2, 1),
	(16, 'test1', 'test11@example.com', NULL, '$2y$12$LpoT4fdflLD3Mqif1Ht6ZOHq6L1O.DUc6HY04dMnf4qL4IqqXE8Ry', NULL, '2025-04-18 23:28:29', 5, '2025-04-18 23:28:29', 5, 2, 1);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

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

-- Dumping structure for table madarsys.branches
CREATE TABLE IF NOT EXISTS `branches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرف الفرع (رقم فريد)',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم الفرع',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'عنوان الفرع',
  `contact_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'معلومات الاتصال',
  `company_id` bigint unsigned NOT NULL COMMENT 'معرف الشركة المرتبطة بالفرع',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت الإنشاء',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت التحديث',
  PRIMARY KEY (`id`),
  UNIQUE KEY `branches_id_company_id_unique` (`id`,`company_id`),
  KEY `branches_company_id_foreign` (`company_id`),
  CONSTRAINT `branches_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.brands
CREATE TABLE IF NOT EXISTS `brands` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم العلامة التجارية',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رمز مميز للعلامة',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'وصف العلامة التجارية',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ السجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي قام بتحديث السجل',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرف الفرع المرتبط',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'المفتاح الخاص بالقفل',
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'مالك القفل',
  `expiration` int NOT NULL COMMENT 'مدة انتهاء القفل (بالثواني)',
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرف الفئة (رقم فريد)',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم الفئة',
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رمز الفئة',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'وصف الفئة',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت الإنشاء',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ الفئة',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت التحديث',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي قام بالتحديث',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرف الفرع المرتبط بالفئة',
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  UNIQUE KEY `categories_code_unique` (`code`),
  KEY `categories_branch_id_foreign` (`branch_id`),
  CONSTRAINT `categories_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.companies
CREATE TABLE IF NOT EXISTS `companies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرف الشركة (رقم فريد)',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم الشركة',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'شعار الشركة',
  `phone_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رقم الهاتف',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'البريد الإلكتروني',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'عنوان الشركة',
  `additional_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'معلومات إضافية',
  `settings` json DEFAULT NULL COMMENT 'الإعدادات الخاصة بالشركة',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت الإنشاء',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ الشركة',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت التحديث',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي قام بالتحديث',
  PRIMARY KEY (`id`),
  UNIQUE KEY `companies_phone_number_unique` (`phone_number`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.currencies
CREATE TABLE IF NOT EXISTS `currencies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرف العملة (رقم فريد)',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم العملة',
  `code` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رمز العملة',
  `symbol` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رمز العملة (مثال: $)',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت الإنشاء',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت التحديث',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.departments
CREATE TABLE IF NOT EXISTS `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للقسم',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم القسم',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'وصف القسم',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'حالة تفعيل القسم',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي أنشأ السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي عدّل السجل آخر مرة',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف الفرع المرتبط بالقسم',
  PRIMARY KEY (`id`),
  KEY `departments_branch_id_foreign` (`branch_id`),
  CONSTRAINT `departments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للفشل',
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'المعرّف العالمي الموحد للوظيفة',
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم الاتصال الذي استُخدم لتشغيل الوظيفة',
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم قائمة الانتظار التي كانت الوظيفة فيها',
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'محتوى الوظيفة الكامل (بيانات التنفيذ)',
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'نص الاستثناء الذي تسبب في فشل الوظيفة',
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'تاريخ ووقت فشل الوظيفة',
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.inventory
CREATE TABLE IF NOT EXISTS `inventory` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للسجل',
  `warehouse_id` bigint unsigned NOT NULL COMMENT 'معرّف المستودع المرتبط',
  `product_id` bigint unsigned NOT NULL COMMENT 'معرّف المنتج المرتبط',
  `quantity` int NOT NULL COMMENT 'الكمية الحالية',
  `unit_price` decimal(10,2) NOT NULL COMMENT 'السعر للوحدة',
  `total_value` decimal(15,2) NOT NULL COMMENT 'الرصيد التراكمي (الكمية * السعر)',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  `updated_user` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_warehouse_id_foreign` (`warehouse_id`),
  KEY `inventory_product_id_foreign` (`product_id`),
  CONSTRAINT `inventory_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.inventory_audits
CREATE TABLE IF NOT EXISTS `inventory_audits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد لسجل الجرد',
  `inventory_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'كود الجرد الفريد',
  `inventory_type` tinyint unsigned NOT NULL COMMENT 'نوع الجرد (1=دوري, 2=مفاجئ, 3=سنوي, 4=شهري)',
  `start_date` datetime DEFAULT NULL COMMENT 'تاريخ بدء الجرد',
  `end_date` datetime DEFAULT NULL COMMENT 'تاريخ انتهاء الجرد',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT 'حالة الجرد (1=معلق, 2=جاري, 3=مكتمل, 4=متأخر)',
  `expected_products_count` int DEFAULT NULL COMMENT 'عدد المنتجات المتوقع جردها',
  `counted_products_count` int DEFAULT NULL COMMENT 'عدد المنتجات التي تم جردها',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'ملاحظات حول الجرد',
  `branch_id` bigint unsigned NOT NULL COMMENT 'معرّف الفرع المرتبط بالجرد',
  `created_user` bigint unsigned NOT NULL COMMENT 'المستخدم الذي أنشأ الجرد',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'المستخدم الذي قام بآخر تحديث للجرد',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  PRIMARY KEY (`id`),
  UNIQUE KEY `inventory_audits_inventory_code_unique` (`inventory_code`),
  KEY `inventory_audits_branch_id_foreign` (`branch_id`),
  KEY `inventory_audits_created_user_foreign` (`created_user`),
  KEY `inventory_audits_updated_user_foreign` (`updated_user`),
  CONSTRAINT `inventory_audits_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inventory_audits_created_user_foreign` FOREIGN KEY (`created_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inventory_audits_updated_user_foreign` FOREIGN KEY (`updated_user`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.inventory_audit_users
CREATE TABLE IF NOT EXISTS `inventory_audit_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للسجل',
  `inventory_audit_id` bigint unsigned NOT NULL COMMENT 'معرّف الجرد المرتبط',
  `user_id` bigint unsigned NOT NULL COMMENT 'المستخدم المسؤول عن الجرد',
  `branch_id` bigint unsigned NOT NULL COMMENT 'معرّف الفرع المرتبط بالجرد',
  `created_user` bigint unsigned NOT NULL COMMENT 'المستخدم الذي أدخل السجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'المستخدم الذي قام بآخر تحديث',
  `operation_type` tinyint unsigned NOT NULL DEFAULT '1' COMMENT 'نوع العملية: 1 = جرد، 2 = تسوية',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  PRIMARY KEY (`id`),
  KEY `inventory_audit_users_inventory_audit_id_foreign` (`inventory_audit_id`),
  KEY `inventory_audit_users_user_id_foreign` (`user_id`),
  KEY `inventory_audit_users_branch_id_foreign` (`branch_id`),
  KEY `inventory_audit_users_created_user_foreign` (`created_user`),
  KEY `inventory_audit_users_updated_user_foreign` (`updated_user`),
  CONSTRAINT `inventory_audit_users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inventory_audit_users_created_user_foreign` FOREIGN KEY (`created_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inventory_audit_users_inventory_audit_id_foreign` FOREIGN KEY (`inventory_audit_id`) REFERENCES `inventory_audits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inventory_audit_users_updated_user_foreign` FOREIGN KEY (`updated_user`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `inventory_audit_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.inventory_audit_warehouses
CREATE TABLE IF NOT EXISTS `inventory_audit_warehouses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للسجل',
  `inventory_audit_id` bigint unsigned NOT NULL COMMENT 'معرّف الجرد المرتبط',
  `warehouse_id` bigint unsigned NOT NULL COMMENT 'معرّف المستودع المرتبط',
  `branch_id` bigint unsigned NOT NULL COMMENT 'معرّف الفرع المرتبط بالجرد',
  `created_user` bigint unsigned NOT NULL COMMENT 'المستخدم الذي أدخل السجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'المستخدم الذي قام بآخر تحديث',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  PRIMARY KEY (`id`),
  KEY `inventory_audit_warehouses_inventory_audit_id_foreign` (`inventory_audit_id`),
  KEY `inventory_audit_warehouses_warehouse_id_foreign` (`warehouse_id`),
  KEY `inventory_audit_warehouses_branch_id_foreign` (`branch_id`),
  KEY `inventory_audit_warehouses_created_user_foreign` (`created_user`),
  KEY `inventory_audit_warehouses_updated_user_foreign` (`updated_user`),
  CONSTRAINT `inventory_audit_warehouses_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inventory_audit_warehouses_created_user_foreign` FOREIGN KEY (`created_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inventory_audit_warehouses_inventory_audit_id_foreign` FOREIGN KEY (`inventory_audit_id`) REFERENCES `inventory_audits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `inventory_audit_warehouses_updated_user_foreign` FOREIGN KEY (`updated_user`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `inventory_audit_warehouses_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.inventory_products
CREATE TABLE IF NOT EXISTS `inventory_products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للمنتج داخل المستودع',
  `batch_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رقم الدفعة للمنتج',
  `production_date` date DEFAULT NULL COMMENT 'تاريخ الإنتاج',
  `expiration_date` date DEFAULT NULL COMMENT 'تاريخ انتهاء الصلاحية',
  `product_id` bigint unsigned NOT NULL COMMENT 'المعرّف الفريد للمنتج',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'المعرّف الفريد للفرع',
  `warehouse_id` bigint unsigned NOT NULL COMMENT 'المعرّف الفريد للمستودع',
  `storage_area_id` bigint unsigned NOT NULL COMMENT 'المعرّف الفريد لمنطقة التخزين',
  `location_id` bigint unsigned DEFAULT NULL COMMENT 'المعرّف الفريد لموقع التخزين',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'رقم المستخدم الذي قام بإضافة هذا السجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'رقم المستخدم الذي قام بآخر تحديث لهذا السجل',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `inventory_transaction_item_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '0' COMMENT 'كمية المنتج الحالية في المستودع',
  `temporary_transfer_expiry_date` timestamp NULL DEFAULT NULL COMMENT 'تاريخ انتهاء النقل المؤقت',
  `distribution_type` tinyint NOT NULL DEFAULT (1) COMMENT 'نوع التوزيع',
  `unit_id` bigint unsigned DEFAULT NULL COMMENT 'المعرّف الفريد للوحدة',
  `unit_product_id` bigint unsigned DEFAULT NULL COMMENT 'المعرّف الفريد للوحدة المرتبطة بالمنتج',
  `converted_quantity` decimal(10,4) DEFAULT NULL COMMENT 'الكمية المحولة حسب الوحدة',
  `price` decimal(20,6) DEFAULT NULL COMMENT 'سعر المنتج في المستودع',
  PRIMARY KEY (`id`),
  KEY `inventory_products_product_id_foreign` (`product_id`),
  KEY `inventory_products_branch_id_foreign` (`branch_id`),
  KEY `inventory_products_warehouse_id_foreign` (`warehouse_id`),
  KEY `inventory_products_storage_area_id_foreign` (`storage_area_id`),
  KEY `inventory_products_location_id_foreign` (`location_id`),
  KEY `inventory_products_inventory_transaction_item_id_foreign` (`inventory_transaction_item_id`),
  KEY `idx_inventory_product_id` (`id`),
  CONSTRAINT `inventory_products_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_products_inventory_transaction_item_id_foreign` FOREIGN KEY (`inventory_transaction_item_id`) REFERENCES `inventory_transaction_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_products_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `warehouse_locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_products_storage_area_id_foreign` FOREIGN KEY (`storage_area_id`) REFERENCES `warehouse_storage_areas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_products_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.inventory_requests
CREATE TABLE IF NOT EXISTS `inventory_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرف الفريد لطلب المخزون',
  `transaction_type_id` bigint unsigned NOT NULL COMMENT 'نوع العملية المرتبطة بالطلب',
  `request_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'تاريخ الطلب',
  `partner_id` bigint unsigned DEFAULT NULL COMMENT 'المورد أو العميل المرتبط بالطلب',
  `department_id` bigint unsigned DEFAULT NULL COMMENT 'القسم الذي قدم الطلب',
  `warehouse_id` bigint unsigned NOT NULL COMMENT 'المستودع الذي سيتم تنفيذ الطلب منه',
  `status` enum('قيد الانتظار','موافق عليه','مرفوض','مكتمل') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'حالة الطلب: 1 قيد الانتظار، 2 موافق عليه، 3 مرفوض، 4 مكتمل',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'ملاحظات إضافية',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'الفرع المرتبط بالطلب',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'المستخدم الذي أنشأ الطلب',
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'آخر مستخدم قام بالتعديل',
  PRIMARY KEY (`id`),
  KEY `inventory_requests_transaction_type_id_foreign` (`transaction_type_id`),
  KEY `inventory_requests_partner_id_foreign` (`partner_id`),
  KEY `inventory_requests_department_id_foreign` (`department_id`),
  KEY `inventory_requests_warehouse_id_foreign` (`warehouse_id`),
  KEY `inventory_requests_branch_id_foreign` (`branch_id`),
  CONSTRAINT `inventory_requests_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_requests_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_requests_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_requests_transaction_type_id_foreign` FOREIGN KEY (`transaction_type_id`) REFERENCES `transaction_types` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `inventory_requests_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='جدول خاص بطلبات المخزون، يحتوي على معلومات الطلب مثل نوع العملية، الجهة الطالبة، المستودع، والحالة.';

-- Data exporting was unselected.

-- Dumping structure for table madarsys.inventory_request_details
CREATE TABLE IF NOT EXISTS `inventory_request_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرف الفريد لتفاصيل الطلب',
  `inventory_request_id` bigint unsigned NOT NULL COMMENT 'الطلب الرئيسي المرتبط بهذه التفاصيل',
  `product_id` bigint unsigned NOT NULL COMMENT 'المنتج المطلوب',
  `quantity` int NOT NULL COMMENT 'عدد الوحدات المطلوبة',
  `unit_price` decimal(10,2) DEFAULT NULL COMMENT 'السعر لكل وحدة',
  `total` decimal(15,2) DEFAULT NULL COMMENT 'المجموع الكلي للتكلفة',
  `warehouse_location_id` bigint unsigned DEFAULT NULL COMMENT 'موقع التخزين داخل المستودع',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'الفرع المرتبط بالطلب',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_request_details_inventory_request_id_foreign` (`inventory_request_id`),
  KEY `inventory_request_details_product_id_foreign` (`product_id`),
  KEY `inventory_request_details_warehouse_location_id_foreign` (`warehouse_location_id`),
  KEY `inventory_request_details_branch_id_foreign` (`branch_id`),
  CONSTRAINT `inventory_request_details_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_request_details_inventory_request_id_foreign` FOREIGN KEY (`inventory_request_id`) REFERENCES `inventory_requests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_request_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_request_details_warehouse_location_id_foreign` FOREIGN KEY (`warehouse_location_id`) REFERENCES `warehouse_locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='جدول يحتوي على تفاصيل الطلبات المخزنية، حيث يتم تسجيل المنتجات المطلوبة وكمياتها وأسعارها ومواقع التخزين.';

-- Data exporting was unselected.

-- Dumping structure for table madarsys.inventory_transactions
CREATE TABLE IF NOT EXISTS `inventory_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرف الفريد للعملية المخزنية',
  `transaction_type_id` bigint unsigned NOT NULL COMMENT 'نوع العملية (إضافة، إخراج، تعديل)',
  `sub_type_id` bigint unsigned DEFAULT NULL COMMENT 'النوع الفرعي للعملية',
  `effect` tinyint DEFAULT NULL COMMENT 'تأثير العملية على المخزون (+ زيادة، - نقصان، 0 محايدة)',
  `transaction_date` timestamp NOT NULL COMMENT 'تاريخ تنفيذ العملية',
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'مرجع العملية إن وجد',
  `partner_id` bigint unsigned DEFAULT NULL COMMENT 'المورد أو العميل المرتبط بالعملية',
  `department_id` bigint unsigned DEFAULT NULL COMMENT 'القسم الذي قام بالعملية',
  `warehouse_id` bigint unsigned NOT NULL COMMENT 'المستودع الذي حدثت فيه العملية',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `branch_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `inventory_request_id` bigint unsigned DEFAULT NULL,
  `secondary_warehouse_id` bigint unsigned DEFAULT NULL COMMENT 'المستودع الثانوي المستخدم إن وجد',
  `status` int NOT NULL DEFAULT '0' COMMENT 'حالة العملية المخزنية',
  PRIMARY KEY (`id`),
  KEY `inventory_transactions_transaction_type_id_foreign` (`transaction_type_id`),
  KEY `inventory_transactions_partner_id_foreign` (`partner_id`),
  KEY `inventory_transactions_department_id_foreign` (`department_id`),
  KEY `inventory_transactions_warehouse_id_foreign` (`warehouse_id`),
  KEY `inventory_transactions_branch_id_foreign` (`branch_id`),
  KEY `inventory_transactions_inventory_request_id_foreign` (`inventory_request_id`),
  KEY `inventory_transactions_secondary_warehouse_id_foreign` (`secondary_warehouse_id`),
  KEY `inventory_transactions_sub_type_id_foreign` (`sub_type_id`),
  CONSTRAINT `inventory_transactions_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transactions_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transactions_inventory_request_id_foreign` FOREIGN KEY (`inventory_request_id`) REFERENCES `inventory_requests` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transactions_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transactions_secondary_warehouse_id_foreign` FOREIGN KEY (`secondary_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transactions_sub_type_id_foreign` FOREIGN KEY (`sub_type_id`) REFERENCES `inventory_transaction_subtypes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transactions_transaction_type_id_foreign` FOREIGN KEY (`transaction_type_id`) REFERENCES `transaction_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_transactions_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=865 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='جدول يسجل الحركات المخزنية التي تتم على المنتجات، مثل الإدخال، الإخراج، والتعديلات على المخزون.';

-- Data exporting was unselected.

-- Dumping structure for table madarsys.inventory_transaction_items
CREATE TABLE IF NOT EXISTS `inventory_transaction_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرف الفريد لتفاصيل العنصر في العملية المخزنية',
  `inventory_transaction_id` bigint unsigned NOT NULL COMMENT 'المعرف المرتبط بالعملية المخزنية',
  `product_id` bigint unsigned NOT NULL COMMENT 'المنتج المتأثر بالعملية',
  `batch_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'كود الدفعة الخاصة بالمنتج',
  `quantity` int NOT NULL COMMENT 'عدد الوحدات المتأثرة',
  `unit_id` bigint unsigned DEFAULT NULL COMMENT 'الوحدة المستخدمة',
  `unit_product_id` bigint unsigned DEFAULT NULL,
  `converted_quantity` decimal(10,4) DEFAULT NULL COMMENT 'الكمية المحولة حسب الوحدة',
  `unit_prices` decimal(15,2) DEFAULT NULL COMMENT 'سعر الوحدة',
  `total` decimal(20,6) DEFAULT NULL COMMENT 'الإجمالي بعد الحساب',
  `warehouse_location_id` bigint unsigned DEFAULT NULL COMMENT 'الموقع داخل المستودع',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'الفرع المرتبط بالعملية',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `target_warehouse_id` bigint unsigned DEFAULT NULL COMMENT 'المستودع المستهدف في عمليات التحويل',
  `converted_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `production_date` date DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `source_warehouse_id` bigint unsigned DEFAULT NULL COMMENT 'المستودع المصدر في عمليات التحويل',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT 'حالة العنصر في العملية: 1 = قيد التنفيذ، 2 = مكتملة، 3 = مُلغاة',
  `result` tinyint unsigned NOT NULL DEFAULT '1' COMMENT 'نتيجة الجرد: 1 = مطابقة، 2 = تلف، 3 = فقدان، 4 = نقل',
  `expected_audit_quantity` int DEFAULT NULL COMMENT 'الكمية المتوقعة من الجرد',
  `batch_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رقم الدفعة من جدول inventory_products',
  `reference_item_id` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `inventory_transaction_items_product_id_foreign` (`product_id`),
  KEY `inventory_transaction_items_unit_id_foreign` (`unit_id`),
  KEY `inventory_transaction_items_unit_product_id_foreign` (`unit_product_id`),
  KEY `inventory_transaction_items_warehouse_location_id_foreign` (`warehouse_location_id`),
  KEY `inventory_transaction_items_branch_id_foreign` (`branch_id`),
  KEY `inventory_transaction_items_target_warehouse_id_foreign` (`target_warehouse_id`),
  KEY `idx_transaction_item_product_id` (`inventory_transaction_id`),
  CONSTRAINT `inventory_transaction_items_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transaction_items_inventory_transaction_id_foreign` FOREIGN KEY (`inventory_transaction_id`) REFERENCES `inventory_transactions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_transaction_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_transaction_items_target_warehouse_id_foreign` FOREIGN KEY (`target_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transaction_items_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transaction_items_unit_product_id_foreign` FOREIGN KEY (`unit_product_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transaction_items_warehouse_location_id_foreign` FOREIGN KEY (`warehouse_location_id`) REFERENCES `warehouse_locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=606 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='جدول يحتوي على تفاصيل العناصر في كل عملية مخزنية، بما في ذلك المنتجات، الكميات، المواقع، والتكاليف.';

-- Data exporting was unselected.

-- Dumping structure for table madarsys.inventory_transaction_subtypes
CREATE TABLE IF NOT EXISTS `inventory_transaction_subtypes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للسجل',
  `transaction_type_id` bigint unsigned NOT NULL COMMENT 'معرّف نوع المعاملة المرتبط',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم نوع المعاملة',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'وصف نوع المعاملة',
  `branch_id` bigint unsigned DEFAULT '2' COMMENT 'معرّف الفرع المرتبط',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'المستخدم الذي أنشأ السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  PRIMARY KEY (`id`),
  KEY `inventory_transaction_subtypes_transaction_type_id_foreign` (`transaction_type_id`),
  KEY `inventory_transaction_subtypes_branch_id_foreign` (`branch_id`),
  KEY `inventory_transaction_subtypes_created_user_foreign` (`created_user`),
  CONSTRAINT `inventory_transaction_subtypes_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transaction_subtypes_created_user_foreign` FOREIGN KEY (`created_user`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transaction_subtypes_transaction_type_id_foreign` FOREIGN KEY (`transaction_type_id`) REFERENCES `transaction_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.inventory_update_errors
CREATE TABLE IF NOT EXISTS `inventory_update_errors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرف الفريد لخطأ التحديث',
  `inventory_transaction_item_id` bigint unsigned NOT NULL COMMENT 'العنصر المرتبط بالخطأ',
  `product_id` bigint unsigned NOT NULL COMMENT 'المنتج الذي حدث فيه الخطأ',
  `warehouse_id` bigint unsigned NOT NULL COMMENT 'المستودع المرتبط بالخطأ',
  `quantity` int NOT NULL COMMENT 'الكمية التي تسببت في الخطأ',
  `error_message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'وصف الخطأ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='جدول يسجل الأخطاء التي تحدث أثناء تحديث المخزون، مثل نقص الكمية أو مشاكل في البيانات.';

-- Data exporting was unselected.

-- Dumping structure for table madarsys.invoices
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `production_date` date DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `invoice_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `partner_id` bigint unsigned NOT NULL,
  `payment_type_id` bigint unsigned NOT NULL DEFAULT '1',
  `invoice_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `check_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '1 for sale, 2 for purchase invoice',
  `inventory_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف المخزون المرتبط بالفاتورة',
  `warehouse_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستودع المرتبط بالفاتورة',
  `inventory_transaction_id` bigint unsigned DEFAULT NULL,
  `currency_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف العملة المرتبطة بالفاتورة',
  `exchange_rate` decimal(10,4) DEFAULT NULL COMMENT 'سعر الصرف للعملة المرتبطة بالفاتورة',
  `department_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف القسم المرتبط بالفاتورة',
  `branch_id` bigint unsigned DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `discount_amount` decimal(10,2) DEFAULT '0.00',
  `discount_type` int DEFAULT NULL,
  `discount_percentage` decimal(8,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_partner_id_foreign` (`partner_id`),
  KEY `invoices_payment_type_id_foreign` (`payment_type_id`),
  KEY `invoices_branch_id_foreign` (`branch_id`),
  KEY `invoices_created_user_foreign` (`created_user`),
  KEY `invoices_updated_user_foreign` (`updated_user`),
  KEY `invoices_inventory_transaction_id_foreign` (`inventory_transaction_id`),
  CONSTRAINT `invoices_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_created_user_foreign` FOREIGN KEY (`created_user`) REFERENCES `users` (`id`),
  CONSTRAINT `invoices_inventory_transaction_id_foreign` FOREIGN KEY (`inventory_transaction_id`) REFERENCES `inventory_transactions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_payment_type_id_foreign` FOREIGN KEY (`payment_type_id`) REFERENCES `payment_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_updated_user_foreign` FOREIGN KEY (`updated_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='يخزن بيانات الفواتير مثل الفواتير المبيعات والمشتريات';

-- Data exporting was unselected.

-- Dumping structure for table madarsys.invoice_items
CREATE TABLE IF NOT EXISTS `invoice_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرّف العنصر في الفاتورة',
  `invoice_id` bigint unsigned NOT NULL COMMENT 'معرّف الفاتورة المرتبط بها العنصر',
  `product_id` bigint unsigned NOT NULL COMMENT 'معرّف المنتج المرتبط بالعنصر',
  `quantity` int NOT NULL COMMENT 'الكمية المشتراة من المنتج',
  `price` decimal(10,2) NOT NULL COMMENT 'سعر المنتج لكل وحدة',
  `unit_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف الوحدة المرتبطة بالعنصر (مثل قطعة، كيلو، ...)',
  `subtotal` decimal(10,2) NOT NULL COMMENT 'إجمالي المبلغ للبند في الفاتورة (الكمية × السعر)',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت إنشاء السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت آخر تحديث للسجل',
  PRIMARY KEY (`id`),
  KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  KEY `invoice_items_product_id_foreign` (`product_id`),
  KEY `invoice_items_unit_id_foreign` (`unit_id`),
  CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoice_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoice_items_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'معرف المهمة',
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم قائمة الانتظار التي توجد بها المهمة',
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'بيانات المهمة (الحمولة)',
  `attempts` tinyint unsigned NOT NULL COMMENT 'عدد المحاولات التي تم تنفيذها لتشغيل المهمة',
  `reserved_at` int unsigned DEFAULT NULL COMMENT 'وقت حجز المهمة (إذا كانت محجوزة)',
  `available_at` int unsigned NOT NULL COMMENT 'وقت توفر المهمة للتنفيذ',
  `created_at` int unsigned NOT NULL COMMENT 'وقت إنشاء المهمة',
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='يخزن معلومات عن المهام المجدولة في النظام';

-- Data exporting was unselected.

-- Dumping structure for table madarsys.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'معرف الدفعة (Batch)',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم الدفعة',
  `total_jobs` int NOT NULL COMMENT 'إجمالي عدد المهام في الدفعة',
  `pending_jobs` int NOT NULL COMMENT 'عدد المهام المعلقة في الدفعة',
  `failed_jobs` int NOT NULL COMMENT 'عدد المهام الفاشلة في الدفعة',
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'معرفات المهام الفاشلة في الدفعة',
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'خيارات إضافية تتعلق بالدفعة',
  `cancelled_at` int DEFAULT NULL COMMENT 'تاريخ ووقت إلغاء الدفعة',
  `created_at` int NOT NULL COMMENT 'تاريخ ووقت إنشاء الدفعة',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ الدفعة',
  `finished_at` int DEFAULT NULL COMMENT 'تاريخ ووقت الانتهاء من الدفعة',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.manufacturing_countries
CREATE TABLE IF NOT EXISTS `manufacturing_countries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم البلد المصنع',
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رمز البلد مثل SA أو EG',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'وصف إضافي للبلد',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ السجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي قام بتحديث السجل',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرف الفرع المرتبط',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `manufacturing_countries_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.modules
CREATE TABLE IF NOT EXISTS `modules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرف الوحدة البرمجية',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'اسم الوحدة البرمجية',
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'مفتاح الوحدة البرمجية',
  `scope_level` enum('company','branch','warehouse') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'مستوى النطاق: الشركة، الفرع، المخزن',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت الإنشاء',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ الوحدة',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت آخر تحديث',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي قام بآخر تحديث',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرف الفرع',
  PRIMARY KEY (`id`),
  UNIQUE KEY `modules_key_unique` (`key`),
  KEY `modules_branch_id_foreign` (`branch_id`),
  CONSTRAINT `modules_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='يخزن معلومات عن الوحدات البرمجية في النظام';

-- Data exporting was unselected.

-- Dumping structure for table madarsys.module_actions
CREATE TABLE IF NOT EXISTS `module_actions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرف الإجراء',
  `module_id` bigint unsigned NOT NULL COMMENT 'معرف الوحدة البرمجية',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'اسم الإجراء',
  `action_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'مفتاح الإجراء',
  `route` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'المسار المرتبط بالإجراء',
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'أيقونة الإجراء',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت الإنشاء',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ الإجراء',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت آخر تحديث',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي قام بآخر تحديث',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرف الفرع',
  PRIMARY KEY (`id`),
  KEY `module_actions_module_id_foreign` (`module_id`),
  KEY `module_actions_branch_id_foreign` (`branch_id`),
  CONSTRAINT `module_actions_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `module_actions_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='يخزن الإجراءات المتاحة للوحدات البرمجية مثل إضافة أو تعديل أو حذف';

-- Data exporting was unselected.

-- Dumping structure for table madarsys.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='يخزن الإشعارات المرسلة للمستخدمين';

-- Data exporting was unselected.

-- Dumping structure for table madarsys.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرف الطلب',
  `type` enum('buy','sell') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'نوع الطلب (شراء/بيع)',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرف الفرع المرتبط بالطلب',
  `status` enum('pending','confirmed','completed','canceled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'حالة الطلب',
  `payment_type_id` bigint unsigned DEFAULT NULL COMMENT 'معرف نوع الدفع المرتبط بالطلب',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ الطلب',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي قام بتحديث الطلب',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت إنشاء الطلب',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت آخر تحديث للطلب',
  PRIMARY KEY (`id`),
  KEY `orders_branch_id_foreign` (`branch_id`),
  KEY `orders_payment_type_id_foreign` (`payment_type_id`),
  KEY `orders_created_user_foreign` (`created_user`),
  KEY `orders_updated_user_foreign` (`updated_user`),
  CONSTRAINT `orders_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_created_user_foreign` FOREIGN KEY (`created_user`) REFERENCES `users` (`id`),
  CONSTRAINT `orders_payment_type_id_foreign` FOREIGN KEY (`payment_type_id`) REFERENCES `payment_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_updated_user_foreign` FOREIGN KEY (`updated_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.order_details
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرف تفاصيل الطلب',
  `order_id` bigint unsigned NOT NULL COMMENT 'معرف الطلب المرتبط بتفاصيل الطلب',
  `product_id` bigint unsigned NOT NULL COMMENT 'معرف المنتج المرتبط بتفاصيل الطلب',
  `quantity` int NOT NULL COMMENT 'الكمية المطلوبة من المنتج',
  `price` decimal(10,2) NOT NULL COMMENT 'سعر الوحدة للمنتج',
  `total_price` decimal(10,2) NOT NULL COMMENT 'السعر الإجمالي لتفاصيل الطلب',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت إنشاء تفاصيل الطلب',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت آخر تحديث لتفاصيل الطلب',
  PRIMARY KEY (`id`),
  KEY `order_details_order_id_foreign` (`order_id`),
  KEY `order_details_product_id_foreign` (`product_id`),
  CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.partners
CREATE TABLE IF NOT EXISTS `partners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرف الشريك',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم الشريك',
  `type` bigint unsigned NOT NULL COMMENT 'نوع الشريك',
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'اسم الشخص المسؤول للتواصل',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رقم الهاتف للشريك',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'البريد الإلكتروني للشريك',
  `address` text COLLATE utf8mb4_unicode_ci COMMENT 'عنوان الشريك',
  `tax_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رقم الضريبة للشريك',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'حالة الشريك (نشط/غير نشط)',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت آخر تحديث للسجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي قام بتحديث السجل',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرف الفرع المرتبط بالشريك',
  PRIMARY KEY (`id`),
  UNIQUE KEY `partners_email_unique` (`email`),
  UNIQUE KEY `partners_tax_number_unique` (`tax_number`),
  KEY `partners_type_foreign` (`type`),
  KEY `partners_branch_id_foreign` (`branch_id`),
  CONSTRAINT `partners_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `partners_type_foreign` FOREIGN KEY (`type`) REFERENCES `partner_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.partner_types
CREATE TABLE IF NOT EXISTS `partner_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرف نوع الشريك',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم نوع الشريك',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت آخر تحديث للسجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي قام بتحديث السجل',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرف الفرع المرتبط بنوع الشريك',
  PRIMARY KEY (`id`),
  UNIQUE KEY `partner_types_name_unique` (`name`),
  KEY `partner_types_branch_id_foreign` (`branch_id`),
  CONSTRAINT `partner_types_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'البريد الإلكتروني للمستخدم الذي طلب إعادة تعيين كلمة المرور',
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رمز التحقق لإعادة تعيين كلمة المرور',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت إنشاء الرمز',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ الرمز',
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.payment_types
CREATE TABLE IF NOT EXISTS `payment_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم نوع الدفع',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'رقم المستخدم الذي قام بإضافة هذا السجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'رقم المستخدم الذي قام بآخر تحديث لهذا السجل',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_types_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للصلاحية',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم الصلاحية (مثال: عرض الفواتير)',
  `module_id` bigint unsigned NOT NULL COMMENT 'معرف الوحدة المرتبطة بالصلاحية (مثل: الفواتير، المنتجات)',
  `module_action_id` bigint unsigned NOT NULL COMMENT 'معرف الإجراء المرتبط بالصلاحية (مثل: عرض، تعديل)',
  `permission_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'المفتاح الفريد المستخدم للتعرف على الصلاحية في الكود',
  `scope_level` enum('company','branch','warehouse') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'company' COMMENT 'مستوى الصلاحية: شركة، فرع، أو مستودع',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تحديث للسجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي حدّث السجل آخر مرة',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرف الفرع المرتبط بالصلاحية (إن وجد)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`),
  UNIQUE KEY `permissions_permission_key_unique` (`permission_key`),
  KEY `permissions_module_id_foreign` (`module_id`),
  KEY `permissions_module_action_id_foreign` (`module_action_id`),
  KEY `permissions_branch_id_foreign` (`branch_id`),
  CONSTRAINT `permissions_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `permissions_module_action_id_foreign` FOREIGN KEY (`module_action_id`) REFERENCES `module_actions` (`id`),
  CONSTRAINT `permissions_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.posts
CREATE TABLE IF NOT EXISTS `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للتدوينة',
  `user_id` bigint unsigned NOT NULL COMMENT 'معرف المستخدم صاحب التدوينة',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'عنوان التدوينة',
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'محتوى التدوينة',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء التدوينة',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للتدوينة',
  PRIMARY KEY (`id`),
  KEY `posts_user_id_foreign` (`user_id`),
  CONSTRAINT `posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للمنتج',
  `brand_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف البراند',
  `manufacturing_country_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف بلد الصنع',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم المنتج',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رابط صورة المنتج',
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'وصف تفصيلي للمنتج',
  `ingredients` text COLLATE utf8mb4_unicode_ci COMMENT 'المكونات',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'ملاحظات إضافية',
  `category_id` bigint unsigned NOT NULL COMMENT 'معرّف الفئة التي ينتمي إليها المنتج',
  `supplier_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف المورد (شريك)',
  `barcode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رمز الباركود الخاص بالمنتج',
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رمز التخزين الداخلي SKU',
  `purchase_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'سعر الشراء للمنتج',
  `selling_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'سعر البيع للمنتج',
  `stock_quantity` int unsigned NOT NULL DEFAULT '0' COMMENT 'الكمية الحالية في المخزون',
  `min_stock_level` int unsigned NOT NULL DEFAULT '1' COMMENT 'الحد الأدنى للمخزون قبل التنبيه',
  `max_stock_level` int unsigned DEFAULT NULL COMMENT 'الحد الأقصى المسموح به في المخزون',
  `unit_id` bigint unsigned NOT NULL COMMENT 'وحدة القياس المرتبطة بالمنتج',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'حالة تفعيل المنتج',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تحديث',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي عدّل السجل',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف الفرع الذي يتبع له المنتج',
  `tax` decimal(5,2) DEFAULT NULL COMMENT 'نسبة الضريبة المضافة',
  `discount` decimal(5,2) DEFAULT NULL COMMENT 'نسبة الخصم المطبقة',
  `supplier_contact` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'معلومات التواصل مع المورد',
  `purchase_date` date DEFAULT NULL COMMENT 'تاريخ شراء المنتج',
  `manufacturing_date` date DEFAULT NULL COMMENT 'تاريخ التصنيع',
  `expiration_date` date DEFAULT NULL COMMENT 'تاريخ انتهاء الصلاحية',
  `last_updated` date DEFAULT NULL COMMENT 'تاريخ آخر تحديث للبيانات',
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  UNIQUE KEY `products_barcode_unique` (`barcode`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_supplier_id_foreign` (`supplier_id`),
  KEY `products_unit_id_foreign` (`unit_id`),
  KEY `products_branch_id_foreign` (`branch_id`),
  KEY `products_brand_id_foreign` (`brand_id`),
  KEY `products_manufacturing_country_id_foreign` (`manufacturing_country_id`),
  CONSTRAINT `products_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_manufacturing_country_id_foreign` FOREIGN KEY (`manufacturing_country_id`) REFERENCES `manufacturing_countries` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `partners` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.request_types
CREATE TABLE IF NOT EXISTS `request_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم نوع الطلب',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'وصف لنوع الطلب',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `request_types_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.return_orders
CREATE TABLE IF NOT EXISTS `return_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد لسجل عملية الإرجاع',
  `return_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رقم عملية الإرجاع',
  `customer_id` bigint unsigned NOT NULL COMMENT 'معرّف العميل الذي قام بعملية الإرجاع',
  `return_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'سبب الإرجاع من قِبل العميل',
  `return_date` date NOT NULL COMMENT 'تاريخ تنفيذ عملية الإرجاع',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  PRIMARY KEY (`id`),
  UNIQUE KEY `return_orders_return_number_unique` (`return_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.return_order_items
CREATE TABLE IF NOT EXISTS `return_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد لكل عنصر إرجاع',
  `return_order_id` bigint unsigned NOT NULL COMMENT 'معرّف الإرجاع المرتبط بهذه العنصر',
  `product_id` bigint unsigned NOT NULL COMMENT 'معرّف المنتج الذي تم إرجاعه',
  `quantity` int NOT NULL COMMENT 'الكمية التي تم إرجاعها من المنتج',
  `status` enum('قبول الإرجاع','إرجاع للمخزون','إرسال للصيانة','تصنيف كمنتج تالف','منتهي الصلاحية') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'تصنيف كمنتج تالف' COMMENT 'الحالة التي تم اتخاذها بعد الإرجاع',
  `Is_Send` int NOT NULL DEFAULT '0' COMMENT 'هل تم إرسال المنتج لاتخاذ الإجراء المناسب (1 نعم / 0 لا)',
  PRIMARY KEY (`id`),
  KEY `return_order_items_return_order_id_foreign` (`return_order_id`),
  KEY `return_order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `return_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `return_order_items_return_order_id_foreign` FOREIGN KEY (`return_order_id`) REFERENCES `return_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.return_suppliers
CREATE TABLE IF NOT EXISTS `return_suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد لسجل المورد الذي يستقبل الإرجاع',
  `return_order_id` bigint unsigned NOT NULL COMMENT 'معرّف عملية الإرجاع التي تم إجراء الإرجاع لها',
  `product_id` bigint unsigned NOT NULL COMMENT 'معرّف المنتج الذي يتم إرجاعه من العميل',
  `supplier_id` bigint unsigned NOT NULL COMMENT 'معرّف المورد الذي سيتم إرسال المنتج إليه',
  `quantity` int NOT NULL COMMENT 'الكمية التي سيتم إرجاعها إلى المورد',
  `status` enum('قبول الإرجاع','إرجاع للمخزون','إرسال للصيانة','تصنيف كمنتج تالف','منتهي الصلاحية') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'تصنيف كمنتج تالف' COMMENT 'حالة الإرجاع للمنتج',
  `Is_Send` int NOT NULL DEFAULT '0' COMMENT 'تم إرسال المنتج للمورد أو لا (0: لا، 1: نعم)',
  `return_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'سبب الإرجاع من المورد',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  PRIMARY KEY (`id`),
  KEY `return_suppliers_return_order_id_foreign` (`return_order_id`),
  KEY `return_suppliers_product_id_foreign` (`product_id`),
  KEY `return_suppliers_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `return_suppliers_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `return_suppliers_return_order_id_foreign` FOREIGN KEY (`return_order_id`) REFERENCES `return_suppliers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `return_suppliers_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.return_suppliers_orders
CREATE TABLE IF NOT EXISTS `return_suppliers_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint unsigned NOT NULL,
  `status` enum('قيد المراجعة','قيد التوصيل','تم الاستلام') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'قيد المراجعة',
  `return_reason` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `return_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `return_suppliers_orders_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `return_suppliers_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.return_suppliers_order_items
CREATE TABLE IF NOT EXISTS `return_suppliers_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد لعنصر طلب إرجاع المورد',
  `return_supplier_order_id` bigint unsigned NOT NULL COMMENT 'معرّف طلب إرجاع المورد المرتبط بهذا العنصر',
  `product_id` bigint unsigned NOT NULL COMMENT 'معرّف المنتج الذي يتم إرجاعه من المورد',
  `quantity` int NOT NULL DEFAULT '1' COMMENT 'الكمية المعادة من المنتج',
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'حالة العنصر في طلب إرجاع المورد',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  PRIMARY KEY (`id`),
  KEY `return_suppliers_order_items_return_supplier_order_id_foreign` (`return_supplier_order_id`),
  KEY `return_suppliers_order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `return_suppliers_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `return_suppliers_order_items_return_supplier_order_id_foreign` FOREIGN KEY (`return_supplier_order_id`) REFERENCES `return_suppliers_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.role_branch
CREATE TABLE IF NOT EXISTS `role_branch` (
  `role_id` bigint unsigned NOT NULL COMMENT 'معرّف الدور المرتبط بهذا العنصر',
  `branch_id` bigint unsigned NOT NULL COMMENT 'معرّف الفرع المرتبط بهذا العنصر',
  `company_id` bigint unsigned NOT NULL COMMENT 'معرّف الشركة المرتبط بهذا العنصر',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي أنشأ السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي عدل السجل',
  KEY `role_branch_role_id_foreign` (`role_id`),
  KEY `role_branch_company_id_foreign` (`company_id`),
  KEY `role_branch_branch_id_company_id_foreign` (`branch_id`,`company_id`),
  CONSTRAINT `role_branch_branch_id_company_id_foreign` FOREIGN KEY (`branch_id`, `company_id`) REFERENCES `branches` (`id`, `company_id`) ON DELETE CASCADE,
  CONSTRAINT `role_branch_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_branch_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_branch_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.role_company
CREATE TABLE IF NOT EXISTS `role_company` (
  `role_id` bigint unsigned NOT NULL COMMENT 'معرّف الدور المرتبط بالشركة',
  `company_id` bigint unsigned NOT NULL COMMENT 'معرّف الشركة المرتبط بهذا الدور',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي أنشأ السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي عدل السجل',
  KEY `role_company_role_id_foreign` (`role_id`),
  KEY `role_company_company_id_foreign` (`company_id`),
  CONSTRAINT `role_company_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_company_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.role_permissions
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للحق في الدور',
  `role_id` bigint unsigned NOT NULL COMMENT 'معرّف الدور المرتبط بهذا الحق',
  `permission_id` bigint unsigned NOT NULL COMMENT 'معرّف الصلاحية المرتبطة بهذا الدور',
  `can_view` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'السماح بالعرض',
  `can_create` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'السماح بالإضافة',
  `can_update` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'السماح بالتعديل',
  `can_delete` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'السماح بالحذف',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'حالة الصلاحية المرتبطة بالدور',
  `status_updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تحديث لحالة الصلاحية',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي أنشأ السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي عدل السجل',
  PRIMARY KEY (`id`),
  KEY `role_permissions_role_id_foreign` (`role_id`),
  KEY `role_permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=835 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.role_user
CREATE TABLE IF NOT EXISTS `role_user` (
  `user_id` bigint unsigned NOT NULL COMMENT 'معرّف المستخدم المرتبط بهذا الدور',
  `role_id` bigint unsigned NOT NULL COMMENT 'معرّف الدور المرتبط بهذا المستخدم',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي أنشأ السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي عدل السجل',
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.role_warehouse
CREATE TABLE IF NOT EXISTS `role_warehouse` (
  `role_id` bigint unsigned NOT NULL COMMENT 'معرّف الدور المرتبط بالمستودع',
  `warehouse_id` bigint unsigned NOT NULL COMMENT 'معرّف المستودع المرتبط بالدور',
  `branch_id` bigint unsigned NOT NULL COMMENT 'معرّف الفرع المرتبط بهذا الدور والمستودع',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي أنشأ السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي عدل السجل',
  KEY `role_warehouse_role_id_foreign` (`role_id`),
  KEY `role_warehouse_warehouse_id_foreign` (`warehouse_id`),
  KEY `role_warehouse_branch_id_foreign` (`branch_id`),
  CONSTRAINT `role_warehouse_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_warehouse_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_warehouse_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'المعرّف الفريد للجلسة',
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم المرتبط بهذه الجلسة',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'عنوان الـ IP للمستخدم الذي قام بتسجيل الدخول',
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'بيانات المتصفح أو الجهاز المستخدم للجلسة',
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'بيانات الجلسة المشفرة',
  `last_activity` int NOT NULL COMMENT 'وقت آخر نشاط في الجلسة',
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للإعداد',
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'المفتاح الفريد للإعداد',
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'قيمة الإعداد المرتبطة بالمفتاح',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف الفرع المرتبط بهذا الإعداد',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي قام بإنشاء السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي قام بتعديل السجل',
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`),
  KEY `settings_branch_id_foreign` (`branch_id`),
  CONSTRAINT `settings_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.shipments
CREATE TABLE IF NOT EXISTS `shipments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للشحنة',
  `shipment_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رقم الشحنة',
  `shipment_date` date NOT NULL COMMENT 'تاريخ الشحنة',
  `quantity` int NOT NULL COMMENT 'الكمية المرسلة',
  `status` enum('pending','shipped','delivered') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'حالة الشحنة',
  `product_id` bigint unsigned NOT NULL COMMENT 'معرّف المنتج المرتبط بالشحنة',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  PRIMARY KEY (`id`),
  KEY `shipments_product_id_foreign` (`product_id`),
  CONSTRAINT `shipments_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.telescope_entries
CREATE TABLE IF NOT EXISTS `telescope_entries` (
  `sequence` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `family_hash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `should_display_on_index` tinyint(1) NOT NULL DEFAULT '1',
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`sequence`),
  UNIQUE KEY `telescope_entries_uuid_unique` (`uuid`),
  KEY `telescope_entries_batch_id_index` (`batch_id`),
  KEY `telescope_entries_family_hash_index` (`family_hash`),
  KEY `telescope_entries_created_at_index` (`created_at`),
  KEY `telescope_entries_type_should_display_on_index_index` (`type`,`should_display_on_index`)
) ENGINE=InnoDB AUTO_INCREMENT=820267 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.telescope_entries_tags
CREATE TABLE IF NOT EXISTS `telescope_entries_tags` (
  `entry_uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`entry_uuid`,`tag`),
  KEY `telescope_entries_tags_tag_index` (`tag`),
  CONSTRAINT `telescope_entries_tags_entry_uuid_foreign` FOREIGN KEY (`entry_uuid`) REFERENCES `telescope_entries` (`uuid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.telescope_monitoring
CREATE TABLE IF NOT EXISTS `telescope_monitoring` (
  `tag` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.transaction_types
CREATE TABLE IF NOT EXISTS `transaction_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد لنوع العملية المخزنية',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم نوع العملية المخزنية',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'وصف لنوع العملية',
  `effect` tinyint NOT NULL DEFAULT '1' COMMENT 'تحديد ما إذا كانت العملية المخزنية إضافة (1) أو خصم (-1) أو محايدة (0)',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'المعرف المرجعي للفرع المرتبط',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'المستخدم الذي أنشأ السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'المستخدم الذي عدّل السجل آخر مرة',
  `inventory_movement_count` int NOT NULL DEFAULT '1' COMMENT 'عدد الحركات المخزنية لهذا النوع',
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_types_name_unique` (`name`),
  KEY `transaction_types_branch_id_foreign` (`branch_id`),
  CONSTRAINT `transaction_types_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.units
CREATE TABLE IF NOT EXISTS `units` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للوحدة',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم الوحدة',
  `parent_unit_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف الوحدة الأب في حال كانت الوحدة مشتقة من وحدة أخرى',
  `conversion_factor` decimal(10,4) DEFAULT NULL COMMENT 'معامل التحويل إلى الوحدة الأب، مثل 1000 للغرام مقابل الكيلوغرام',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'المعرف المرجعي للفرع المرتبط بالوحدة',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي أنشأ السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي عدّل السجل آخر مرة',
  PRIMARY KEY (`id`),
  UNIQUE KEY `units_name_unique` (`name`),
  KEY `units_parent_unit_id_foreign` (`parent_unit_id`),
  KEY `units_branch_id_foreign` (`branch_id`),
  CONSTRAINT `units_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `units_parent_unit_id_foreign` FOREIGN KEY (`parent_unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.warehouses
CREATE TABLE IF NOT EXISTS `warehouses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للمستودع',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم المستودع',
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رمز المستودع الفريد',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'عنوان المستودع',
  `contact_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'معلومات التواصل للمستودع',
  `branch_id` bigint unsigned NOT NULL COMMENT 'معرّف الفرع المرتبط بالمستودع',
  `supervisor_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف المشرف على المستودع (إن وجد)',
  `latitude` decimal(10,7) DEFAULT NULL COMMENT 'خط العرض لموقع المستودع',
  `longitude` decimal(10,7) DEFAULT NULL COMMENT 'خط الطول لموقع المستودع',
  `area` float DEFAULT NULL COMMENT 'المساحة الإجمالية للمستودع بالأمتار المربعة',
  `capacity` double DEFAULT NULL COMMENT 'السعة التخزينية القصوى للمستودع',
  `is_smart` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'هل المستودع ذكي؟ 1 نعم، 0 لا',
  `has_security_system` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'هل يوجد نظام أمني؟',
  `has_cctv` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'هل يوجد نظام كاميرات مراقبة؟',
  `is_integrated_with_wms` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'هل المستودع مدمج مع نظام WMS؟',
  `last_maintenance` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر صيانة للمستودع',
  `has_automated_systems` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'هل يحتوي المستودع على أنظمة مؤتمتة؟',
  `temperature` double DEFAULT NULL COMMENT 'درجة الحرارة داخل المستودع',
  `humidity` double DEFAULT NULL COMMENT 'نسبة الرطوبة داخل المستودع',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'تحديد ما إذا كان المستودع متاحًا أم مغلقًا',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي أنشأ السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي عدّل السجل',
  PRIMARY KEY (`id`),
  UNIQUE KEY `warehouses_code_unique` (`code`),
  KEY `warehouses_branch_id_foreign` (`branch_id`),
  KEY `warehouses_supervisor_id_foreign` (`supervisor_id`),
  CONSTRAINT `warehouses_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `warehouses_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.warehouse_category_warehouse
CREATE TABLE IF NOT EXISTS `warehouse_category_warehouse` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للسجل',
  `warehouse_id` bigint unsigned NOT NULL COMMENT 'معرّف المستودع المرتبط',
  `warehouse_category_id` bigint unsigned NOT NULL COMMENT 'معرّف التصنيف المرتبط بالمستودع',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي أنشأ السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي عدّل السجل',
  PRIMARY KEY (`id`),
  KEY `warehouse_category_warehouse_warehouse_id_foreign` (`warehouse_id`),
  KEY `warehouse_category_warehouse_warehouse_category_id_foreign` (`warehouse_category_id`),
  CONSTRAINT `warehouse_category_warehouse_warehouse_category_id_foreign` FOREIGN KEY (`warehouse_category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `warehouse_category_warehouse_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.warehouse_locations
CREATE TABLE IF NOT EXISTS `warehouse_locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد لموقع التخزين',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'المعرّف الفرعي المرتبط بالموقع',
  `warehouse_id` bigint unsigned NOT NULL COMMENT 'المستودع الذي يحتوي هذا الموقع',
  `storage_area_id` bigint unsigned NOT NULL COMMENT 'منطقة التخزين ضمن المستودع',
  `aisle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'الممر داخل منطقة التخزين',
  `rack` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رمز الرف',
  `shelf` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رمز الرفوف',
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'الموقع الدقيق أو الخانة داخل الرف',
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'الباركود الفريد لموقع التخزين',
  `is_occupied` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'هل الموقع مشغول أم لا (1 = نعم، 0 = لا)',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'ملاحظات إضافية حول الموقع',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ الإنشاء',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم المنشئ',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ التحديث الأخير',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي قام بالتعديل',
  `rack_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رمز مخصص للرف الكامل لتسهيل التتبع',
  PRIMARY KEY (`id`),
  UNIQUE KEY `warehouse_locations_barcode_unique` (`barcode`),
  UNIQUE KEY `warehouse_locations_rack_code_unique` (`rack_code`),
  KEY `warehouse_locations_warehouse_id_foreign` (`warehouse_id`),
  KEY `warehouse_locations_storage_area_id_foreign` (`storage_area_id`),
  KEY `warehouse_locations_branch_id_foreign` (`branch_id`),
  CONSTRAINT `warehouse_locations_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `warehouse_locations_storage_area_id_foreign` FOREIGN KEY (`storage_area_id`) REFERENCES `warehouse_storage_areas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `warehouse_locations_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.warehouse_reports
CREATE TABLE IF NOT EXISTS `warehouse_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد لتقرير المستودع',
  `warehouse_id` bigint unsigned NOT NULL COMMENT 'معرّف المستودع المرتبط بالتقرير',
  `report_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'نوع التقرير (مثل: حركة، جرد، ...)',
  `report_data` json NOT NULL COMMENT 'بيانات التقرير بصيغة JSON',
  `report_date` datetime NOT NULL COMMENT 'تاريخ إصدار التقرير',
  `generated_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم أو معرّف من أنشأ التقرير',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'المستخدم الذي أنشأ السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'المستخدم الذي قام بآخر تعديل',
  PRIMARY KEY (`id`),
  KEY `warehouse_reports_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `warehouse_reports_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.warehouse_storage_areas
CREATE TABLE IF NOT EXISTS `warehouse_storage_areas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد لمنطقة التخزين',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف الفرع المرتبطة به منطقة التخزين',
  `warehouse_id` bigint unsigned NOT NULL COMMENT 'معرّف المستودع المرتبط بمنطقة التخزين',
  `area_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم منطقة التخزين',
  `area_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'نوع منطقة التخزين (مثل: مبردة، جافة، إلخ)',
  `capacity` int NOT NULL COMMENT 'الطاقة الاستيعابية القصوى لمنطقة التخزين',
  `current_occupancy` int NOT NULL DEFAULT '0' COMMENT 'عدد الوحدات المخزنة حاليًا في المنطقة',
  `zone_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف المنطقة الجغرافية داخل المستودع إن وُجد',
  `storage_conditions` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'شروط التخزين الخاصة (درجة حرارة، رطوبة، إلخ)',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'المستخدم الذي أنشأ السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'المستخدم الذي قام بآخر تعديل',
  PRIMARY KEY (`id`),
  KEY `warehouse_storage_areas_warehouse_id_foreign` (`warehouse_id`),
  KEY `warehouse_storage_areas_zone_id_foreign` (`zone_id`),
  KEY `warehouse_storage_areas_branch_id_foreign` (`branch_id`),
  CONSTRAINT `warehouse_storage_areas_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `warehouse_storage_areas_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `warehouse_storage_areas_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `zones` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

-- Dumping structure for table madarsys.zones
CREATE TABLE IF NOT EXISTS `zones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `warehouse_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `capacity` int NOT NULL DEFAULT '0',
  `current_occupancy` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `zones_name_unique` (`name`),
  UNIQUE KEY `zones_code_unique` (`code`),
  KEY `zones_warehouse_id_foreign` (`warehouse_id`),
  KEY `zones_branch_id_foreign` (`branch_id`),
  CONSTRAINT `zones_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `zones_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data exporting was unselected.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

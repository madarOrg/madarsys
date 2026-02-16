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
DROP DATABASE IF EXISTS `madarsys`;
CREATE DATABASE IF NOT EXISTS `madarsys` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `madarsys`;

-- Dumping structure for table madarsys.branches
DROP TABLE IF EXISTS `branches`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.branches: ~5 rows (approximately)
DELETE FROM `branches`;
INSERT INTO `branches` (`id`, `name`, `address`, `contact_info`, `company_id`, `created_at`, `updated_at`) VALUES
	(2, 'فرع 1', 'عنوان الفرع 1', 'معلومات الاتصال للفرع 1', 1, '2025-02-18 01:32:16', '2025-02-18 01:32:16'),
	(3, 'فرع 2', 'عنوان الفرع 2', 'معلومات الاتصال للفرع 2', 3, '2025-02-18 01:32:16', '2025-02-18 01:32:16'),
	(4, 'فرع 3', 'عنوان الفرع 3', 'معلومات الاتصال للفرع 3', 3, '2025-02-18 01:32:16', '2025-02-18 01:32:16'),
	(5, 'فرع 4', 'عنوان الفرع 4', 'معلومات الاتصال للفرع 4', 3, '2025-02-18 01:32:16', '2025-02-18 01:32:16'),
	(6, 'فرع 5', 'عنوان الفرع 5', 'معلومات الاتصال للفرع 5', 3, '2025-02-18 01:32:16', '2025-02-18 01:32:16');

-- Dumping database structure for madarsys
DROP DATABASE IF EXISTS `madarsys`;
CREATE DATABASE IF NOT EXISTS `madarsys` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `madarsys`;

-- Dumping structure for table madarsys.transaction_types
DROP TABLE IF EXISTS `transaction_types`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.transaction_types: ~14 rows (approximately)
DELETE FROM `transaction_types`;
INSERT INTO `transaction_types` (`id`, `name`, `description`, `effect`, `branch_id`, `created_at`, `created_user`, `updated_at`, `updated_user`, `inventory_movement_count`) VALUES
	(1, 'شراء', 'عملية شراء المنتجات من الموردين وإضافتها إلى المخزون.', 1, 2, NULL, NULL, NULL, NULL, 1),
	(2, 'إرجاع من عميل', 'إرجاع المنتجات من العملاء إلى المخزون بعد البيع.', 1, 2, NULL, NULL, NULL, NULL, 1),
	(3, 'إنتاج داخلي', 'إضافة المنتجات المصنَّعة داخليًا إلى المخزون.', 1, 2, NULL, NULL, NULL, NULL, 1),
	(4, 'صرف', 'صرف المنتجات للأقسام أو الجهات الداخلية داخل الشركة.', -1, 2, NULL, NULL, NULL, NULL, 1),
	(5, 'تحويل مخزني', 'نقل المنتجات بين المستودعات أو الأرفف المختلفة.', 0, 2, NULL, NULL, NULL, NULL, 2),
	(6, 'إرجاع لمورد', 'إرجاع المنتجات إلى المورد بسبب التلف أو الخطأ في الطلبية.', 1, 2, NULL, NULL, NULL, NULL, 1),
	(7, 'بيع', 'بيع المنتجات للعملاء وإخراجها من المخزون.', -1, 2, NULL, NULL, NULL, NULL, 1),
	(8, 'جرد مخزني', 'عملية تدقيق لمقارنة الكمية الفعلية مع الكمية المسجلة في النظام.', 0, 2, NULL, NULL, NULL, NULL, 1),
	(9, 'تلف', 'تسجيل المنتجات التالفة وإخراجها من المخزون.', -1, 2, NULL, NULL, NULL, NULL, 1),
	(10, 'سرقة', 'تسجيل نقص المخزون الناتج عن فقدان غير مبرر أو سرقة.', -1, 2, NULL, NULL, NULL, NULL, 1),
	(11, 'تعديل يدوي', 'تصحيح المخزون يدويًا بسبب أخطاء تسجيل سابقة.', 0, 2, NULL, NULL, NULL, NULL, 1),
	(12, 'حجز المخزون', 'تحديد كمية من المخزون لطلبية قبل تنفيذ عملية البيع.', -1, 2, NULL, NULL, NULL, NULL, 1),
	(13, 'انتهاء الصلاحية', 'إخراج المنتجات من المخزون بسبب انتهاء فترة صلاحيتها.', -1, 2, NULL, NULL, NULL, NULL, 1),
	(14, 'استرجاع من الإنتاج', 'إرجاع المواد الخام غير المستخدمة إلى المخزون بعد التصنيع.', 1, 2, NULL, NULL, NULL, NULL, 1);

-- Dumping structure for table madarsys.brands
DROP TABLE IF EXISTS `brands`;
CREATE TABLE IF NOT EXISTS `brands` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم العلامة التجارية',
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رمز مميز للعلامة',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'وصف العلامة التجارية',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ السجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي قام بتحديث السجل',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرف الفرع المرتبط',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brands_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.brands: ~3 rows (approximately)
DELETE FROM `brands`;
INSERT INTO `brands` (`id`, `name`, `code`, `description`, `created_user`, `updated_user`, `branch_id`, `created_at`, `updated_at`) VALUES
	(1, 'Brand A', 'BR-A', 'العلامة التجارية A', NULL, NULL, NULL, '2025-04-09 23:14:15', '2025-04-09 23:14:15'),
	(2, 'Brand B', 'BR-B', 'العلامة التجارية B', NULL, NULL, NULL, '2025-04-09 23:14:15', '2025-04-09 23:14:15'),
	(3, 'Brand C', 'BR-C', 'العلامة التجارية C', NULL, NULL, NULL, '2025-04-09 23:14:15', '2025-04-09 23:14:15');

-- Dumping structure for table madarsys.cache
DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.cache: ~2 rows (approximately)
DELETE FROM `cache`;
INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
	('navbar_links_cc86c03d66472498e702874a61191a80', 'O:29:"Illuminate\\Support\\Collection":2:{s:8:"\0*\0items";a:12:{i:0;a:4:{s:4:"text";s:31:"إدارة المستخدمين";s:3:"key";s:16:"مستخدمين";s:4:"href";s:1:"#";s:8:"children";O:29:"Illuminate\\Support\\Collection":2:{s:8:"\0*\0items";a:5:{i:0;a:3:{s:4:"text";s:44:"إدارة بيانات المستخدمين";s:4:"href";s:6:"/users";s:4:"icon";s:28:"fa fa-user-edit text-red-500";}i:1;a:3:{s:4:"text";s:42:"إدارة أدوار المستخدمين";s:4:"href";s:6:"/roles";s:4:"icon";s:28:"fa fa-user-edit text-red-500";}i:2;a:3:{s:4:"text";s:44:"منح الأذونات والصلاحيات";s:4:"href";s:17:"/role-permissions";s:4:"icon";s:25:"fa fa-key text-orange-500";}i:3;a:3:{s:4:"text";s:25:"اضافة صلاحيات";s:4:"href";s:24:"/role-permissions/create";s:4:"icon";s:30:"fa fa-chart-pie text-green-500";}i:4;a:3:{s:4:"text";s:21:"اعدادت عامة";s:4:"href";s:9:"/settings";s:4:"icon";s:31:"fa fa-check-circle text-red-500";}}s:28:"\0*\0escapeWhenCastingToString";b:0;}}i:1;a:4:{s:4:"text";s:49:"إدارة الشركات و المستودعات";s:3:"key";s:28:"شركاتومستودعات";s:4:"href";s:1:"#";s:8:"children";O:29:"Illuminate\\Support\\Collection":2:{s:8:"\0*\0items";a:6:{i:0;a:3:{s:4:"text";s:32:"إضافة مستودع جديد";s:4:"href";s:18:"/warehouses/create";s:4:"icon";s:25:"fa fa-plus text-green-500";}i:1;a:3:{s:4:"text";s:31:"قائمة المستودعات";s:4:"href";s:11:"/warehouses";s:4:"icon";s:24:"fa fa-list text-blue-500";}i:2;a:3:{s:4:"text";s:26:"إضافة فرع جديد";s:4:"href";s:16:"/branches/create";s:4:"icon";s:25:"fa fa-plus text-green-500";}i:3;a:3:{s:4:"text";s:23:"قائمة الفروع";s:4:"href";s:9:"/branches";s:4:"icon";s:24:"fa fa-list text-blue-500";}i:4;a:3:{s:4:"text";s:25:"قائمة الشركات";s:4:"href";s:10:"/companies";s:4:"icon";s:24:"fa fa-list text-blue-500";}i:5;a:3:{s:4:"text";s:30:"إضافة شركة جديدة";s:4:"href";s:17:"/companies/create";s:4:"icon";s:25:"fa fa-plus text-green-500";}}s:28:"\0*\0escapeWhenCastingToString";b:0;}}i:2;a:4:{s:4:"text";s:25:"إدارة المخزون";s:3:"key";s:10:"مخزون";s:4:"href";s:1:"#";s:8:"children";O:29:"Illuminate\\Support\\Collection":2:{s:8:"\0*\0items";a:4:{i:0;a:3:{s:4:"text";s:42:"قائمة الحركات المخزنية";s:4:"href";s:23:"/inventory/transactions";s:4:"icon";s:24:"fa fa-edit text-blue-500";}i:1;a:3:{s:4:"text";s:32:"إضافة حركة مخزنية";s:4:"href";s:30:"/inventory/transactions/create";s:4:"icon";s:25:"fa fa-plus text-green-500";}i:2;a:3:{s:4:"text";s:53:"إدارة المنتجات في المستودعات";s:4:"href";s:19:"/inventory-products";s:4:"icon";s:32:"fa fa-arrows-alt text-purple-500";}i:3;a:3:{s:4:"text";s:36:"مراقبة حالة المخزون";s:4:"href";s:17:"/inventory-review";s:4:"icon";s:23:"fa fa-eye text-teal-500";}}s:28:"\0*\0escapeWhenCastingToString";b:0;}}i:3;a:4:{s:4:"text";s:25:"إدارة الشحنات";s:3:"key";s:10:"شحنات";s:4:"href";s:1:"#";s:8:"children";O:29:"Illuminate\\Support\\Collection":2:{s:8:"\0*\0items";a:3:{i:0;a:3:{s:4:"text";s:27:"استلام الشحنات";s:4:"href";s:18:"/shipments/receive";s:4:"icon";s:26:"fa fa-inbox text-green-500";}i:1;a:3:{s:4:"text";s:25:"إرسال الشحنات";s:4:"href";s:15:"/shipments/send";s:4:"icon";s:31:"fa fa-paper-plane text-blue-500";}i:2;a:3:{s:4:"text";s:36:"متابعة حالة الشحنات";s:4:"href";s:16:"/shipments/track";s:4:"icon";s:33:"fa fa-map-marker-alt text-red-500";}}s:28:"\0*\0escapeWhenCastingToString";b:0;}}i:4;a:4:{s:4:"text";s:27:"إدارة الفواتير";s:3:"key";s:12:"فواتير";s:4:"href";s:1:"#";s:8:"children";O:29:"Illuminate\\Support\\Collection":2:{s:8:"\0*\0items";a:4:{i:0;a:3:{s:4:"text";s:50:"انشاء فاتورة شراء من الطلب";s:4:"href";s:25:"/invoices/purchase-orders";s:4:"icon";s:32:"fa fa-check-circle text-teal-500";}i:1;a:3:{s:4:"text";s:40:"إنشاء فواتير المبيعات";s:4:"href";s:14:"/invoices/sale";s:4:"icon";s:29:"fa fa-receipt text-yellow-500";}i:2;a:3:{s:4:"text";s:42:"إنشاء فواتير المشتريات";s:4:"href";s:19:"/invoices/purchases";s:4:"icon";s:34:"fa fa-shopping-cart text-green-500";}i:3;a:3:{s:4:"text";s:47:"انشاء فاتورة بيع من الطلب";s:4:"href";s:22:"/invoices/sales-orders";s:4:"icon";s:31:"fa fa-info-circle text-blue-500";}}s:28:"\0*\0escapeWhenCastingToString";b:0;}}i:5;a:4:{s:4:"text";s:25:"إدارة الشركاء";s:3:"key";s:10:"شركاء";s:4:"href";s:1:"#";s:8:"children";O:29:"Illuminate\\Support\\Collection":2:{s:8:"\0*\0items";a:2:{i:0;a:3:{s:4:"text";s:25:"ادارة الشركاء";s:4:"href";s:9:"/partners";s:4:"icon";s:31:"fa fa-plus-square text-blue-500";}i:1;a:3:{s:4:"text";s:28:"إضافة شريك جديد";s:4:"href";s:16:"/partners/create";s:4:"icon";s:25:"fa fa-plus text-green-500";}}s:28:"\0*\0escapeWhenCastingToString";b:0;}}i:6;a:4:{s:4:"text";s:46:"إدارة العوائد (المرتجعات)";s:3:"key";s:26:"عوائد(مرتجعات)";s:4:"href";s:1:"#";s:8:"children";O:29:"Illuminate\\Support\\Collection":2:{s:8:"\0*\0items";a:2:{i:0;a:3:{s:4:"text";s:31:"معالجة المرتجعات";s:4:"href";s:16:"/returns/process";s:4:"icon";s:26:"fa fa-cogs text-yellow-500";}i:1;a:3:{s:4:"text";s:46:"إرسال المرتجعات للموردين";s:4:"href";s:17:"/returns/supplier";s:4:"icon";s:35:"fa fa-truck-loading text-purple-500";}}s:28:"\0*\0escapeWhenCastingToString";b:0;}}i:7;a:4:{s:4:"text";s:25:"إدارة الطلبات";s:3:"key";s:10:"طلبات";s:4:"href";s:1:"#";s:8:"children";O:29:"Illuminate\\Support\\Collection":2:{s:8:"\0*\0items";a:2:{i:0;a:3:{s:4:"text";s:26:"إضافة طلب جديد";s:4:"href";s:14:"/orders/create";s:4:"icon";s:22:"fa-solid fa-calculator";}i:1;a:3:{s:4:"text";s:25:"قائمة الطلبات";s:4:"href";s:7:"/orders";s:4:"icon";s:34:"fa fa-clipboard-check text-red-500";}}s:28:"\0*\0escapeWhenCastingToString";b:0;}}i:8;a:4:{s:4:"text";s:27:"إدارة المنتجات";s:3:"key";s:27:"إدارة المنتجات";s:4:"href";s:1:"#";s:8:"children";O:29:"Illuminate\\Support\\Collection":2:{s:8:"\0*\0items";a:5:{i:0;a:3:{s:4:"text";s:27:"ترميز المنتجات";s:4:"href";s:9:"/products";s:4:"icon";s:30:"fas fa-barcode text-purple-500";}i:1;a:3:{s:4:"text";s:25:"فئات المنتجات";s:4:"href";s:11:"/categories";s:4:"icon";s:27:"fas fa-folder text-blue-500";}i:2;a:3:{s:4:"text";s:14:"الوحدات";s:4:"href";s:6:"/units";s:4:"icon";s:28:"fa-solid fa-ruler-horizontal";}i:3;a:3:{s:4:"text";s:33:"العلامات التجارية";s:4:"href";s:7:"/brands";s:4:"icon";s:31:"fa fa-check-circle text-red-500";}i:4;a:3:{s:4:"text";s:17:"بلد الصنع";s:4:"href";s:24:"/manufacturing_countries";s:4:"icon";s:17:"fa-solid fa-globe";}}s:28:"\0*\0escapeWhenCastingToString";b:0;}}i:9;a:4:{s:4:"text";s:27:"إنشاء التقارير";s:3:"key";s:22:"إنشاءتقارير";s:4:"href";s:1:"#";s:8:"children";O:29:"Illuminate\\Support\\Collection":2:{s:8:"\0*\0items";a:7:{i:0;a:3:{s:4:"text";s:76:"تقرير المنتجات المقاربة لإنتهاء الصلاحية";s:4:"href";s:25:"/reports/expired-products";s:4:"icon";s:35:"fa fa-clipboard-list text-green-500";}i:1;a:3:{s:4:"text";s:35:"تقرير طلب المنتجات ";s:4:"href";s:24:"/reports/search-products";s:4:"icon";s:33:"fa fa-shipping-fast text-blue-500";}i:2;a:3:{s:4:"text";s:38:"تقرير موردي المنتجات";s:4:"href";s:24:"/reports/search-partners";s:4:"icon";s:30:"fa fa-file-alt text-orange-500";}i:3;a:3:{s:4:"text";s:63:"تقرير المنتجات المنتهية  الصلاحية ";s:4:"href";s:29:"/reports/get-expired-products";s:4:"icon";s:30:"fa-solid fa-paste text-red-500";}i:4;a:3:{s:4:"text";s:42:"تقرير الحركات المخزنية";s:4:"href";s:31:"/reports/inventory-transactions";s:4:"icon";s:27:"fa-solid fa-diagram-project";}i:5;a:3:{s:4:"text";s:27:"تقرير المنتجات";s:4:"href";s:22:"/reports/product-stock";s:4:"icon";s:25:"fa-solid fa-bars-progress";}i:6;a:3:{s:4:"text";s:25:"تقرير الشركاء";s:4:"href";s:17:"/reports/partners";s:4:"icon";s:25:"fa fa-tasks text-blue-500";}}s:28:"\0*\0escapeWhenCastingToString";b:0;}}i:10;a:4:{s:4:"text";s:28:"لوحة تحكم الجرد";s:3:"key";s:10:"الجرد";s:4:"href";s:1:"#";s:8:"children";O:29:"Illuminate\\Support\\Collection":2:{s:8:"\0*\0items";a:2:{i:0;a:3:{s:4:"text";s:30:"سجل عمليات الجرد";s:4:"href";s:16:"/inventory/audit";s:4:"icon";s:32:"fa fa-address-card text-blue-500";}i:1;a:3:{s:4:"text";s:37:"إضافة جرد مخزني جديد";s:4:"href";s:23:"/inventory/audit/create";s:4:"icon";s:25:"fa fa-plus text-green-500";}}s:28:"\0*\0escapeWhenCastingToString";b:0;}}i:11;a:4:{s:4:"text";s:42:"إدارة الأوامر المخزنية";s:3:"key";s:27:"أوامر المخزنية";s:4:"href";s:1:"#";s:8:"children";O:29:"Illuminate\\Support\\Collection":2:{s:8:"\0*\0items";a:5:{i:0;a:3:{s:4:"text";s:51:"إدارة أوامر الشراء المخزنية";s:4:"href";s:16:"/purchase-orders";s:4:"icon";s:31:"fa-solid fa-arrow-up-short-wide";}i:1;a:3:{s:4:"text";s:47:"إدارة أوامر الصرف المخزني";s:4:"href";s:13:"/sales-orders";s:4:"icon";s:33:"fa-solid fa-arrow-up-wide-short\r\n";}i:2;a:3:{s:4:"text";s:47:"التحقق من الطلبات الموكدة";s:4:"href";s:23:"/orders/check-confirmed";s:4:"icon";s:30:"fa fa-user-plus text-green-500";}i:3;a:3:{s:4:"text";s:38:"أوامر الصرف المعتمدة";s:4:"href";s:22:"/invoices/sales-orders";s:4:"icon";s:25:"fa fa-tasks text-blue-500";}i:4;a:3:{s:4:"text";s:30:"الطلبات المعلقة";s:4:"href";s:25:"/orders/pending-approval\n";s:4:"icon";s:30:"fa fa-comments text-purple-500";}}s:28:"\0*\0escapeWhenCastingToString";b:0;}}}s:28:"\0*\0escapeWhenCastingToString";b:0;}', 1747935338),
	('user_permissions_5', 'a:47:{i:0;s:28:"مستخدمين.users.index";i:1;s:22:"مستخدمين.roles";i:2;s:28:"مستخدمين.permissions";i:3;s:25:"مستخدمين.activity";i:4;s:35:"شركاتومستودعات.create";i:5;s:39:"شركاتومستودعات.warehouses";i:6;s:38:"شركاتومستودعات.locations";i:7;s:42:"شركاتومستودعات.storage-areas";i:8;s:37:"شركاتومستودعات.security";i:9;s:34:"شركاتومستودعات.audit";i:10;s:37:"شركاتومستودعات.settings";i:11;s:45:"شركاتومستودعات.companies.create";i:12;s:14:"مخزون.add";i:13;s:28:"مخزون.update-quantities";i:14;s:21:"مخزون.distribute";i:15;s:18:"مخزون.monitor";i:16;s:19:"مخزون.validate";i:17;s:18:"شحنات.receive";i:18;s:15:"شحنات.send";i:19;s:70:"انشاء فاتورة من الطلب..invoices.create-from-order\n";i:20;s:18:"فواتير.sales";i:21;s:22:"فواتير.purchases";i:22;s:15:"parchasr-orders";i:23;s:17:"شركاء.create";i:24;s:16:"شركاء.track";i:25;s:34:"عوائد(مرتجعات).process";i:26;s:35:"عوائد(مرتجعات).supplier";i:27;s:16:"موردين.add";i:28;s:18:"موردين.track";i:29;s:29:"تتبعكميات.warehouses";i:30;s:25:"تتبعكميات.update";i:31;s:70:"إنشاءتقاريرالمقاربهعلىالانتهاء.inventory";i:32;s:46:"إنشاءتقاريرحدالطلب.inventory";i:33;s:58:"إنشاءتقاريرمورديالمنتحات.inventory";i:34;s:43:"عملاءومحلات.customers-stores.add";i:35;s:46:"عملاءومحلات.customers-stores.orders";i:36;s:56:"التحقق من الطلبات .orders.check-confirmed";i:37;s:58:".invoicessales-ordersاوامرالصرف المعتمدة";i:38;s:55:"الطلبات المعلقة.orders.pending-approval\n";i:39;s:50:"انشاءتقاريرالمنتهيات.inventory";i:40;s:21:"تقارير.invetory";i:41;s:23:" تقارير.inventory";i:42;s:0:"";i:43;s:19:"شركاء.partener";i:44;s:22:"منتجات.brand.add";i:45;s:44:"بلدالصنع.manufacturing_countries.add";i:46;s:68:"انشاء فاتورة بيع من الطلب.invoice.seles-orders";}', 1747935338);

-- Dumping structure for table madarsys.cache_locks
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'المفتاح الخاص بالقفل',
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'مالك القفل',
  `expiration` int NOT NULL COMMENT 'مدة انتهاء القفل (بالثواني)',
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.cache_locks: ~0 rows (approximately)
DELETE FROM `cache_locks`;

-- Dumping structure for table madarsys.categories
DROP TABLE IF EXISTS `categories`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.categories: ~15 rows (approximately)
DELETE FROM `categories`;
INSERT INTO `categories` (`id`, `name`, `code`, `description`, `created_at`, `created_user`, `updated_at`, `updated_user`, `branch_id`) VALUES
	(1, 'إلكترونيات', 'ELEC', NULL, '2025-02-18 11:50:16', NULL, '2025-02-23 05:14:15', 5, 2),
	(2, 'ملابس', 'CLOTH', 'ملابس رجالية ونسائية وأطفال.', '2025-02-18 11:50:16', NULL, '2025-02-18 11:50:16', NULL, 2),
	(3, 'أثاث', 'FURN', 'الأثاث المنزلي والمكتبي.', '2025-02-18 11:50:16', NULL, '2025-02-18 11:50:16', NULL, 2),
	(4, 'مواد غذائية', 'FOOD', 'المواد الغذائية والمنتجات الاستهلاكية.', '2025-02-18 11:50:16', NULL, '2025-02-18 11:50:16', NULL, 2),
	(5, 'معدات صناعية', 'INDUST', 'المعدات الثقيلة والمستلزمات الصناعية.', '2025-02-18 11:50:16', NULL, '2025-02-18 11:50:16', NULL, 2),
	(7, 'المنتجات الطبية', 'MEDICAL', 'مستلزمات طبية (كمامات، قفازات),أدوية ,معدات طبية,أدوات تعقيم', '2025-04-09 20:43:08', 5, '2025-04-09 20:43:08', 5, 2),
	(8, 'قطع الغيار', 'SPARE-PARTS', 'سيارات ,آلات صناعية,أجهزة كهربائية', '2025-04-09 20:44:40', 5, '2025-04-09 20:44:40', 5, 2),
	(9, 'المواد الجافة', 'DRY-GOODS', 'منتجات غذائية غير قابلة للتلف بسهولة وتُخزن في مكان جاف', '2025-04-09 21:34:22', 5, '2025-04-09 21:34:22', 5, 2),
	(10, 'المواد المعلبة', 'CANNED-FOODS', 'منتجات محفوظة في عبوات معدنية أو زجاجية ولها مدة صلاحية طويلة', '2025-04-09 21:34:53', 5, '2025-04-09 21:34:53', 5, 2),
	(11, 'المنتجات المجمدة', 'FROZEN-ITEMS', 'منتجات محفوظة بالتجميد للحفاظ على صلاحيتها وجودتها', '2025-04-09 21:35:28', 5, '2025-04-09 21:35:28', 5, 2),
	(12, 'المنتجات المبردة', 'CHILLED-PRODUCTS', 'منتجات تتطلب حفظها في درجة حرارة منخفضة', '2025-04-09 21:38:34', 5, '2025-04-09 21:38:34', 5, 2),
	(13, 'المشروبات', 'BEVERAGES', 'سوائل معدّة للشرب مثل العصائر، المياه، والمشروبات الغازية', '2025-04-09 21:46:16', 5, '2025-04-09 21:46:16', 5, 2),
	(14, 'الزيوت والسمن', 'OILS-FATS', 'دهون للطهي مثل الزيوت النباتية والسمن', '2025-04-09 21:46:53', 5, '2025-04-09 21:46:53', 5, 2),
	(15, 'الحلويات والوجبات الخفيفة', 'SNACKS-SWEETS', 'منتجات للتحلية أو التسلية، مثل البسكويت والشوكولاتة.', '2025-04-09 21:47:24', 5, '2025-04-09 21:47:24', 5, 2),
	(16, 'الألبان ومشتقاتها', 'DAIRY', 'منتجات مثل الحليب، الجبن، اللبن، الزبدة، والقشطة.', '2025-04-09 21:48:00', 5, '2025-04-09 21:48:00', 5, 2);

-- Dumping structure for table madarsys.companies
DROP TABLE IF EXISTS `companies`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.companies: ~3 rows (approximately)
DELETE FROM `companies`;
INSERT INTO `companies` (`id`, `name`, `logo`, `phone_number`, `email`, `address`, `additional_info`, `settings`, `created_at`, `created_user`, `updated_at`, `updated_user`) VALUES
	(1, 'شركة المدارات', 'logos/3yNPiHLpLYKhPEPHJE14DPRcYPf3eP4hEb1hZ9LT.gif', '1234567890', 'company@example.com', 'صنعاء-اليمن', NULL, NULL, '2025-02-18 01:27:51', NULL, '2025-04-14 04:23:57', 5),
	(3, 'شركة المدارات', 'path_to_logo', '123456997890', 'company@example.com', 'عنوان الشركة', 'معلومات إضافية', '"{\\"key\\":\\"value\\"}"', '2025-02-18 01:32:16', NULL, '2025-02-18 01:32:16', NULL),
	(5, 'مدار', NULL, '0987654321', 'tt1@example.com', NULL, NULL, NULL, '2025-02-26 16:01:28', 5, '2025-02-26 16:01:28', 5);

-- Dumping structure for table madarsys.currencies
DROP TABLE IF EXISTS `currencies`;
CREATE TABLE IF NOT EXISTS `currencies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرف العملة (رقم فريد)',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم العملة',
  `code` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رمز العملة',
  `symbol` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رمز العملة (مثال: $)',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت الإنشاء',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت التحديث',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.currencies: ~16 rows (approximately)
DELETE FROM `currencies`;
INSERT INTO `currencies` (`id`, `name`, `code`, `symbol`, `created_at`, `updated_at`) VALUES
	(1, 'ريال يمني', '01', 'RY', NULL, NULL),
	(3, 'الريال السعودي', 'SAR', '﷼', '2025-04-13 01:31:06', '2025-04-13 01:31:06'),
	(4, 'الدرهم الإماراتي', 'AED', 'د.إ', '2025-04-13 01:31:06', '2025-04-13 01:31:06'),
	(5, 'الدينار الكويتي', 'KWD', 'د.ك', '2025-04-13 01:31:06', '2025-04-13 01:31:06'),
	(6, 'الريال العماني', 'OMR', '﷼', '2025-04-13 01:31:06', '2025-04-13 01:31:06'),
	(7, 'الريال القطري', 'QAR', '﷼', '2025-04-13 01:31:06', '2025-04-13 01:31:06'),
	(8, 'الدينار البحريني', 'BHD', '.د.ب', '2025-04-13 01:31:06', '2025-04-13 01:31:06'),
	(9, 'الدولار الأمريكي', 'USD', '$', '2025-04-13 01:31:06', '2025-04-13 01:31:06'),
	(10, 'اليورو', 'EUR', '€', '2025-04-13 01:31:06', '2025-04-13 01:31:06'),
	(11, 'الجنيه الإسترليني', 'GBP', '£', '2025-04-13 01:31:06', '2025-04-13 01:31:06'),
	(12, 'الين الياباني', 'JPY', '¥', '2025-04-13 01:31:06', '2025-04-13 01:31:06'),
	(13, 'الدولار الكندي', 'CAD', 'C$', '2025-04-13 01:31:06', '2025-04-13 01:31:06'),
	(14, 'الدولار الأسترالي', 'AUD', 'A$', '2025-04-13 01:31:06', '2025-04-13 01:31:06'),
	(15, 'الفرنك السويسري', 'CHF', 'CHF', '2025-04-13 01:31:06', '2025-04-13 01:31:06'),
	(16, 'اليوان الصيني', 'CNY', '¥', '2025-04-13 01:31:06', '2025-04-13 01:31:06'),
	(17, 'الروبية الهندية', 'INR', '₹', '2025-04-13 01:31:06', '2025-04-13 01:31:06');

-- Dumping structure for table madarsys.departments
DROP TABLE IF EXISTS `departments`;
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

-- Dumping data for table madarsys.departments: ~0 rows (approximately)
DELETE FROM `departments`;

-- Dumping structure for table madarsys.failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للفشل',
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'المعرّف العالمي الموحد للوظيفة',
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم الاتصال الذي استُخدم لتشغيل الوظيفة',
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم قائمة الانتظار التي كانت الوظيفة فيها',
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'محتوى الوظيفة الكامل (بيانات التنفيذ)',
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'نص الاستثناء الذي تسبب في فشل الوظيفة',
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'تاريخ ووقت فشل الوظيفة',
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.failed_jobs: ~9 rows (approximately)
DELETE FROM `failed_jobs`;
INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
	(1, '40c1e673-1702-4598-8d8a-e2572415969e', 'redis', 'default', '{"uuid":"40c1e673-1702-4598-8d8a-e2572415969e","timeout":null,"id":"KM4nJLProaXsMQqielYSrCeBywrUEAIK","backoff":null,"displayName":"App\\\\Notifications\\\\UserNotification","maxTries":null,"failOnTimeout":false,"maxExceptions":null,"retryUntil":null,"job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","data":{"command":"O:48:\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\":3:{s:11:\\"notifiables\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":5:{s:5:\\"class\\";s:15:\\"App\\\\Models\\\\User\\";s:2:\\"id\\";a:1:{i:0;i:6;}s:9:\\"relations\\";a:0:{}s:10:\\"connection\\";s:5:\\"mysql\\";s:15:\\"collectionClass\\";N;}s:12:\\"notification\\";O:34:\\"App\\\\Notifications\\\\UserNotification\\":2:{s:7:\\"\\u0000*\\u0000data\\";a:13:{s:7:\\"message\\";s:53:\\"تم حفظ عملية جديدة في مستودعك\\";s:4:\\"type\\";s:10:\\"restocking\\";s:8:\\"priority\\";i:1;s:8:\\"due_date\\";O:13:\\"Carbon\\\\Carbon\\":3:{s:4:\\"date\\";s:26:\\"2025-03-05 06:25:40.147656\\";s:13:\\"timezone_type\\";i:3;s:8:\\"timezone\\";s:3:\\"UTC\\";}s:7:\\"is_read\\";b:0;s:10:\\"product_id\\";N;s:20:\\"inventory_request_id\\";N;s:8:\\"quantity\\";N;s:6:\\"status\\";s:3:\\"new\\";s:13:\\"department_id\\";N;s:12:\\"warehouse_id\\";s:1:\\"1\\";s:10:\\"created_at\\";s:19:\\"2025-03-04 06:25:40\\";s:10:\\"updated_at\\";s:19:\\"2025-03-04 06:25:40\\";}s:2:\\"id\\";s:36:\\"65e4671d-af71-4f4f-becc-5f21ed99c346\\";}s:8:\\"channels\\";a:1:{i:0;s:8:\\"database\\";}}","commandName":"Illuminate\\\\Notifications\\\\SendQueuedNotifications"},"attempts":2}', 'PDOException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'type\' in \'field list\' in C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php:47\nStack trace:\n#0 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(47): PDO->prepare(\'insert into `no...\')\n#1 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(812): Illuminate\\Database\\MySqlConnection->Illuminate\\Database\\{closure}(\'insert into `no...\', Array)\n#2 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(779): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#3 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#4 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3748): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#5 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2066): Illuminate\\Database\\Query\\Builder->insert(Array)\n#6 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1335): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#7 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1163): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#8 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(371): Illuminate\\Database\\Eloquent\\Model->save()\n#9 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#10 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(368): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#11 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#12 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\UserNotification))\n#13 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'efa3d907-bddd-4...\', Object(App\\Notifications\\UserNotification), \'database\')\n#14 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#15 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#16 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#17 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#18 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#19 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#20 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#21 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#22 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#23 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(126): Illuminate\\Container\\Container->call(Array)\n#24 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#25 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#26 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#27 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#28 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#29 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#31 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#32 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\RedisJob), Array)\n#33 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#34 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(392): Illuminate\\Queue\\Worker->process(\'redis\', Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(178): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\RedisJob), \'redis\', Object(Illuminate\\Queue\\WorkerOptions))\n#36 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'redis\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#37 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'redis\', \'default\')\n#38 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#39 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#40 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#41 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#42 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#43 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(213): Illuminate\\Container\\Container->call(Array)\n#44 C:\\madarsys\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#45 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(182): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#46 C:\\madarsys\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\madarsys\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\madarsys\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#50 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1208): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#51 C:\\madarsys\\artisan(13): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#52 {main}\n\nNext Illuminate\\Database\\QueryException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'type\' in \'field list\' (Connection: mysql, SQL: insert into `notifications` (`id`, `type`, `data`, `read_at`, `notifiable_id`, `notifiable_type`, `updated_at`, `created_at`) values (65e4671d-af71-4f4f-becc-5f21ed99c346, App\\Notifications\\UserNotification, {"notification_id":13,"message":"\\u062a\\u0645 \\u062d\\u0641\\u0638 \\u0639\\u0645\\u0644\\u064a\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629 \\u0641\\u064a \\u0645\\u0633\\u062a\\u0648\\u062f\\u0639\\u0643","priority":1,"due_date":"2025-03-05T06:25:40.147656Z","status":0,"warehouse_id":"1","department_id":null}, ?, 6, App\\Models\\User, 2025-03-04 06:25:41, 2025-03-04 06:25:41)) in C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php:825\nStack trace:\n#0 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(779): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#1 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#2 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3748): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#3 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2066): Illuminate\\Database\\Query\\Builder->insert(Array)\n#4 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1335): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#5 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1163): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#6 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(371): Illuminate\\Database\\Eloquent\\Model->save()\n#7 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#8 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(368): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#9 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#10 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\UserNotification))\n#11 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'efa3d907-bddd-4...\', Object(App\\Notifications\\UserNotification), \'database\')\n#12 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#13 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#14 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#15 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#16 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#17 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#18 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#19 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#20 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#21 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(126): Illuminate\\Container\\Container->call(Array)\n#22 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#23 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#24 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#25 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#26 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#27 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#28 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#29 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\RedisJob), Array)\n#31 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#32 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(392): Illuminate\\Queue\\Worker->process(\'redis\', Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Queue\\WorkerOptions))\n#33 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(178): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\RedisJob), \'redis\', Object(Illuminate\\Queue\\WorkerOptions))\n#34 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'redis\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'redis\', \'default\')\n#36 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#37 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#38 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#39 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#40 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#41 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(213): Illuminate\\Container\\Container->call(Array)\n#42 C:\\madarsys\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#43 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(182): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#44 C:\\madarsys\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\madarsys\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\madarsys\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1208): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\madarsys\\artisan(13): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#50 {main}', '2025-03-04 14:25:41'),
	(2, 'a5b417a6-b72b-4c9a-8c17-7c2c1acb9383', 'redis', 'default', '{"uuid":"a5b417a6-b72b-4c9a-8c17-7c2c1acb9383","timeout":null,"id":"xOCIoevHOUbkGVtVpmh7EesRvVmWbCet","backoff":null,"displayName":"App\\\\Notifications\\\\UserNotification","maxTries":null,"failOnTimeout":false,"maxExceptions":null,"retryUntil":null,"job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","data":{"command":"O:48:\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\":3:{s:11:\\"notifiables\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":5:{s:5:\\"class\\";s:15:\\"App\\\\Models\\\\User\\";s:2:\\"id\\";a:1:{i:0;i:5;}s:9:\\"relations\\";a:0:{}s:10:\\"connection\\";s:5:\\"mysql\\";s:15:\\"collectionClass\\";N;}s:12:\\"notification\\";O:34:\\"App\\\\Notifications\\\\UserNotification\\":2:{s:7:\\"\\u0000*\\u0000data\\";a:13:{s:7:\\"message\\";s:53:\\"تم حفظ عملية جديدة في مستودعك\\";s:4:\\"type\\";s:10:\\"restocking\\";s:8:\\"priority\\";i:1;s:8:\\"due_date\\";O:13:\\"Carbon\\\\Carbon\\":3:{s:4:\\"date\\";s:26:\\"2025-03-05 06:25:40.400027\\";s:13:\\"timezone_type\\";i:3;s:8:\\"timezone\\";s:3:\\"UTC\\";}s:7:\\"is_read\\";b:0;s:10:\\"product_id\\";N;s:20:\\"inventory_request_id\\";N;s:8:\\"quantity\\";N;s:6:\\"status\\";s:3:\\"new\\";s:13:\\"department_id\\";N;s:12:\\"warehouse_id\\";s:1:\\"1\\";s:10:\\"created_at\\";s:19:\\"2025-03-04 06:25:40\\";s:10:\\"updated_at\\";s:19:\\"2025-03-04 06:25:40\\";}s:2:\\"id\\";s:36:\\"a8d0a299-8eda-434f-b31c-d38f66d1cc41\\";}s:8:\\"channels\\";a:1:{i:0;s:8:\\"database\\";}}","commandName":"Illuminate\\\\Notifications\\\\SendQueuedNotifications"},"attempts":2}', 'PDOException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'type\' in \'field list\' in C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php:47\nStack trace:\n#0 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(47): PDO->prepare(\'insert into `no...\')\n#1 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(812): Illuminate\\Database\\MySqlConnection->Illuminate\\Database\\{closure}(\'insert into `no...\', Array)\n#2 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(779): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#3 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#4 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3748): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#5 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2066): Illuminate\\Database\\Query\\Builder->insert(Array)\n#6 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1335): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#7 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1163): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#8 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(371): Illuminate\\Database\\Eloquent\\Model->save()\n#9 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#10 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(368): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#11 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#12 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\UserNotification))\n#13 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'d2ad1a12-27b8-4...\', Object(App\\Notifications\\UserNotification), \'database\')\n#14 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#15 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#16 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#17 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#18 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#19 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#20 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#21 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#22 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#23 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(126): Illuminate\\Container\\Container->call(Array)\n#24 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#25 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#26 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#27 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#28 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#29 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#31 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#32 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\RedisJob), Array)\n#33 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#34 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(392): Illuminate\\Queue\\Worker->process(\'redis\', Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(178): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\RedisJob), \'redis\', Object(Illuminate\\Queue\\WorkerOptions))\n#36 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'redis\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#37 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'redis\', \'default\')\n#38 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#39 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#40 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#41 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#42 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#43 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(213): Illuminate\\Container\\Container->call(Array)\n#44 C:\\madarsys\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#45 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(182): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#46 C:\\madarsys\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\madarsys\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\madarsys\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#50 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1208): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#51 C:\\madarsys\\artisan(13): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#52 {main}\n\nNext Illuminate\\Database\\QueryException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'type\' in \'field list\' (Connection: mysql, SQL: insert into `notifications` (`id`, `type`, `data`, `read_at`, `notifiable_id`, `notifiable_type`, `updated_at`, `created_at`) values (a8d0a299-8eda-434f-b31c-d38f66d1cc41, App\\Notifications\\UserNotification, {"notification_id":14,"message":"\\u062a\\u0645 \\u062d\\u0641\\u0638 \\u0639\\u0645\\u0644\\u064a\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629 \\u0641\\u064a \\u0645\\u0633\\u062a\\u0648\\u062f\\u0639\\u0643","priority":1,"due_date":"2025-03-05T06:25:40.400027Z","status":0,"warehouse_id":"1","department_id":null}, ?, 5, App\\Models\\User, 2025-03-04 06:25:42, 2025-03-04 06:25:42)) in C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php:825\nStack trace:\n#0 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(779): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#1 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#2 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3748): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#3 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2066): Illuminate\\Database\\Query\\Builder->insert(Array)\n#4 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1335): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#5 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1163): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#6 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(371): Illuminate\\Database\\Eloquent\\Model->save()\n#7 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#8 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(368): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#9 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#10 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\UserNotification))\n#11 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'d2ad1a12-27b8-4...\', Object(App\\Notifications\\UserNotification), \'database\')\n#12 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#13 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#14 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#15 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#16 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#17 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#18 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#19 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#20 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#21 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(126): Illuminate\\Container\\Container->call(Array)\n#22 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#23 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#24 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#25 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#26 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#27 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#28 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#29 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\RedisJob), Array)\n#31 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#32 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(392): Illuminate\\Queue\\Worker->process(\'redis\', Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Queue\\WorkerOptions))\n#33 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(178): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\RedisJob), \'redis\', Object(Illuminate\\Queue\\WorkerOptions))\n#34 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'redis\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'redis\', \'default\')\n#36 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#37 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#38 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#39 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#40 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#41 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(213): Illuminate\\Container\\Container->call(Array)\n#42 C:\\madarsys\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#43 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(182): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#44 C:\\madarsys\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\madarsys\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\madarsys\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1208): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\madarsys\\artisan(13): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#50 {main}', '2025-03-04 14:25:42'),
	(3, 'c45acdd8-91d6-4900-aecc-2758c2d0480e', 'redis', 'default', '{"uuid":"c45acdd8-91d6-4900-aecc-2758c2d0480e","timeout":null,"id":"hek8GyEY7jkb04oCUIBeDPnd83ds25Wa","backoff":null,"displayName":"App\\\\Notifications\\\\UserNotification","maxTries":null,"failOnTimeout":false,"maxExceptions":null,"retryUntil":null,"job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","data":{"command":"O:48:\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\":3:{s:11:\\"notifiables\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":5:{s:5:\\"class\\";s:15:\\"App\\\\Models\\\\User\\";s:2:\\"id\\";a:1:{i:0;i:10;}s:9:\\"relations\\";a:0:{}s:10:\\"connection\\";s:5:\\"mysql\\";s:15:\\"collectionClass\\";N;}s:12:\\"notification\\";O:34:\\"App\\\\Notifications\\\\UserNotification\\":2:{s:7:\\"\\u0000*\\u0000data\\";a:13:{s:7:\\"message\\";s:53:\\"تم حفظ عملية جديدة في مستودعك\\";s:4:\\"type\\";s:10:\\"restocking\\";s:8:\\"priority\\";i:1;s:8:\\"due_date\\";O:13:\\"Carbon\\\\Carbon\\":3:{s:4:\\"date\\";s:26:\\"2025-03-05 06:25:40.404026\\";s:13:\\"timezone_type\\";i:3;s:8:\\"timezone\\";s:3:\\"UTC\\";}s:7:\\"is_read\\";b:0;s:10:\\"product_id\\";N;s:20:\\"inventory_request_id\\";N;s:8:\\"quantity\\";N;s:6:\\"status\\";s:3:\\"new\\";s:13:\\"department_id\\";N;s:12:\\"warehouse_id\\";s:1:\\"1\\";s:10:\\"created_at\\";s:19:\\"2025-03-04 06:25:40\\";s:10:\\"updated_at\\";s:19:\\"2025-03-04 06:25:40\\";}s:2:\\"id\\";s:36:\\"12245af9-d826-4ff7-8702-940d1e08dca1\\";}s:8:\\"channels\\";a:1:{i:0;s:8:\\"database\\";}}","commandName":"Illuminate\\\\Notifications\\\\SendQueuedNotifications"},"attempts":2}', 'PDOException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'type\' in \'field list\' in C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php:47\nStack trace:\n#0 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(47): PDO->prepare(\'insert into `no...\')\n#1 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(812): Illuminate\\Database\\MySqlConnection->Illuminate\\Database\\{closure}(\'insert into `no...\', Array)\n#2 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(779): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#3 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#4 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3748): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#5 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2066): Illuminate\\Database\\Query\\Builder->insert(Array)\n#6 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1335): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#7 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1163): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#8 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(371): Illuminate\\Database\\Eloquent\\Model->save()\n#9 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#10 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(368): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#11 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#12 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\UserNotification))\n#13 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'fd0b46ff-3c6b-4...\', Object(App\\Notifications\\UserNotification), \'database\')\n#14 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#15 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#16 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#17 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#18 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#19 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#20 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#21 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#22 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#23 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(126): Illuminate\\Container\\Container->call(Array)\n#24 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#25 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#26 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#27 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#28 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#29 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#31 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#32 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\RedisJob), Array)\n#33 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#34 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(392): Illuminate\\Queue\\Worker->process(\'redis\', Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(178): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\RedisJob), \'redis\', Object(Illuminate\\Queue\\WorkerOptions))\n#36 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'redis\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#37 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'redis\', \'default\')\n#38 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#39 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#40 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#41 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#42 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#43 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(213): Illuminate\\Container\\Container->call(Array)\n#44 C:\\madarsys\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#45 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(182): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#46 C:\\madarsys\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\madarsys\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\madarsys\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#50 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1208): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#51 C:\\madarsys\\artisan(13): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#52 {main}\n\nNext Illuminate\\Database\\QueryException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'type\' in \'field list\' (Connection: mysql, SQL: insert into `notifications` (`id`, `type`, `data`, `read_at`, `notifiable_id`, `notifiable_type`, `updated_at`, `created_at`) values (12245af9-d826-4ff7-8702-940d1e08dca1, App\\Notifications\\UserNotification, {"notification_id":15,"message":"\\u062a\\u0645 \\u062d\\u0641\\u0638 \\u0639\\u0645\\u0644\\u064a\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629 \\u0641\\u064a \\u0645\\u0633\\u062a\\u0648\\u062f\\u0639\\u0643","priority":1,"due_date":"2025-03-05T06:25:40.404026Z","status":0,"warehouse_id":"1","department_id":null}, ?, 10, App\\Models\\User, 2025-03-04 06:25:42, 2025-03-04 06:25:42)) in C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php:825\nStack trace:\n#0 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(779): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#1 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#2 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3748): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#3 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2066): Illuminate\\Database\\Query\\Builder->insert(Array)\n#4 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1335): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#5 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1163): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#6 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(371): Illuminate\\Database\\Eloquent\\Model->save()\n#7 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#8 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(368): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#9 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#10 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\UserNotification))\n#11 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'fd0b46ff-3c6b-4...\', Object(App\\Notifications\\UserNotification), \'database\')\n#12 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#13 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#14 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#15 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#16 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#17 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#18 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#19 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#20 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#21 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(126): Illuminate\\Container\\Container->call(Array)\n#22 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#23 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#24 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#25 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#26 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#27 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#28 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#29 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\RedisJob), Array)\n#31 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#32 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(392): Illuminate\\Queue\\Worker->process(\'redis\', Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Queue\\WorkerOptions))\n#33 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(178): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\RedisJob), \'redis\', Object(Illuminate\\Queue\\WorkerOptions))\n#34 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'redis\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'redis\', \'default\')\n#36 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#37 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#38 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#39 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#40 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#41 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(213): Illuminate\\Container\\Container->call(Array)\n#42 C:\\madarsys\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#43 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(182): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#44 C:\\madarsys\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\madarsys\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\madarsys\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1208): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\madarsys\\artisan(13): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#50 {main}', '2025-03-04 14:25:42'),
	(4, '29d4dd93-3796-456e-8b9a-31ba0e892055', 'redis', 'default', '{"uuid":"29d4dd93-3796-456e-8b9a-31ba0e892055","timeout":null,"id":"O5j1rs6kz0cWAu6VvLk9zqXqS9gDA4l1","backoff":null,"displayName":"App\\\\Notifications\\\\UserNotification","maxTries":null,"failOnTimeout":false,"maxExceptions":null,"retryUntil":null,"job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","data":{"command":"O:48:\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\":3:{s:11:\\"notifiables\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":5:{s:5:\\"class\\";s:15:\\"App\\\\Models\\\\User\\";s:2:\\"id\\";a:1:{i:0;i:6;}s:9:\\"relations\\";a:0:{}s:10:\\"connection\\";s:5:\\"mysql\\";s:15:\\"collectionClass\\";N;}s:12:\\"notification\\";O:34:\\"App\\\\Notifications\\\\UserNotification\\":2:{s:7:\\"\\u0000*\\u0000data\\";a:13:{s:7:\\"message\\";s:53:\\"تم حفظ عملية جديدة في مستودعك\\";s:4:\\"type\\";s:10:\\"restocking\\";s:8:\\"priority\\";i:1;s:8:\\"due_date\\";O:13:\\"Carbon\\\\Carbon\\":3:{s:4:\\"date\\";s:26:\\"2025-03-05 06:30:01.011536\\";s:13:\\"timezone_type\\";i:3;s:8:\\"timezone\\";s:3:\\"UTC\\";}s:7:\\"is_read\\";b:0;s:10:\\"product_id\\";N;s:20:\\"inventory_request_id\\";N;s:8:\\"quantity\\";N;s:6:\\"status\\";s:3:\\"new\\";s:13:\\"department_id\\";N;s:12:\\"warehouse_id\\";s:1:\\"1\\";s:10:\\"created_at\\";s:19:\\"2025-03-04 06:30:01\\";s:10:\\"updated_at\\";s:19:\\"2025-03-04 06:30:01\\";}s:2:\\"id\\";s:36:\\"34ecbf45-8ca2-4176-bace-5f21687d1c46\\";}s:8:\\"channels\\";a:1:{i:0;s:8:\\"database\\";}}","commandName":"Illuminate\\\\Notifications\\\\SendQueuedNotifications"},"attempts":2}', 'PDOException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'type\' in \'field list\' in C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php:47\nStack trace:\n#0 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(47): PDO->prepare(\'insert into `no...\')\n#1 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(812): Illuminate\\Database\\MySqlConnection->Illuminate\\Database\\{closure}(\'insert into `no...\', Array)\n#2 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(779): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#3 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#4 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3748): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#5 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2066): Illuminate\\Database\\Query\\Builder->insert(Array)\n#6 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1335): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#7 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1163): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#8 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(371): Illuminate\\Database\\Eloquent\\Model->save()\n#9 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#10 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(368): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#11 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#12 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\UserNotification))\n#13 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'1618b57f-a955-4...\', Object(App\\Notifications\\UserNotification), \'database\')\n#14 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#15 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#16 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#17 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#18 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#19 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#20 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#21 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#22 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#23 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(126): Illuminate\\Container\\Container->call(Array)\n#24 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#25 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#26 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#27 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#28 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#29 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#31 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#32 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\RedisJob), Array)\n#33 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#34 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(392): Illuminate\\Queue\\Worker->process(\'redis\', Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(178): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\RedisJob), \'redis\', Object(Illuminate\\Queue\\WorkerOptions))\n#36 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'redis\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#37 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'redis\', \'default\')\n#38 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#39 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#40 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#41 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#42 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#43 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(213): Illuminate\\Container\\Container->call(Array)\n#44 C:\\madarsys\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#45 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(182): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#46 C:\\madarsys\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\madarsys\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\madarsys\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#50 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1208): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#51 C:\\madarsys\\artisan(13): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#52 {main}\n\nNext Illuminate\\Database\\QueryException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'type\' in \'field list\' (Connection: mysql, SQL: insert into `notifications` (`id`, `type`, `data`, `read_at`, `notifiable_id`, `notifiable_type`, `updated_at`, `created_at`) values (34ecbf45-8ca2-4176-bace-5f21687d1c46, App\\Notifications\\UserNotification, {"notification_id":22,"message":"\\u062a\\u0645 \\u062d\\u0641\\u0638 \\u0639\\u0645\\u0644\\u064a\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629 \\u0641\\u064a \\u0645\\u0633\\u062a\\u0648\\u062f\\u0639\\u0643","priority":1,"due_date":"2025-03-05T06:30:01.011536Z","status":0,"warehouse_id":"1","department_id":null}, ?, 6, App\\Models\\User, 2025-03-04 06:30:04, 2025-03-04 06:30:04)) in C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php:825\nStack trace:\n#0 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(779): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#1 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#2 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3748): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#3 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2066): Illuminate\\Database\\Query\\Builder->insert(Array)\n#4 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1335): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#5 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1163): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#6 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(371): Illuminate\\Database\\Eloquent\\Model->save()\n#7 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#8 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(368): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#9 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#10 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\UserNotification))\n#11 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'1618b57f-a955-4...\', Object(App\\Notifications\\UserNotification), \'database\')\n#12 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#13 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#14 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#15 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#16 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#17 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#18 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#19 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#20 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#21 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(126): Illuminate\\Container\\Container->call(Array)\n#22 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#23 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#24 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#25 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#26 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#27 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#28 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#29 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\RedisJob), Array)\n#31 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#32 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(392): Illuminate\\Queue\\Worker->process(\'redis\', Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Queue\\WorkerOptions))\n#33 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(178): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\RedisJob), \'redis\', Object(Illuminate\\Queue\\WorkerOptions))\n#34 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'redis\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'redis\', \'default\')\n#36 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#37 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#38 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#39 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#40 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#41 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(213): Illuminate\\Container\\Container->call(Array)\n#42 C:\\madarsys\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#43 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(182): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#44 C:\\madarsys\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\madarsys\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\madarsys\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1208): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\madarsys\\artisan(13): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#50 {main}', '2025-03-04 14:30:04'),
	(5, '0d14b16b-0e0b-4cd7-afab-1da65b7e0956', 'redis', 'default', '{"uuid":"0d14b16b-0e0b-4cd7-afab-1da65b7e0956","timeout":null,"id":"BNgqUnBzUrrZk7BOstpNVePoq1wWP8yl","backoff":null,"displayName":"App\\\\Notifications\\\\UserNotification","maxTries":null,"failOnTimeout":false,"maxExceptions":null,"retryUntil":null,"job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","data":{"command":"O:48:\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\":3:{s:11:\\"notifiables\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":5:{s:5:\\"class\\";s:15:\\"App\\\\Models\\\\User\\";s:2:\\"id\\";a:1:{i:0;i:5;}s:9:\\"relations\\";a:0:{}s:10:\\"connection\\";s:5:\\"mysql\\";s:15:\\"collectionClass\\";N;}s:12:\\"notification\\";O:34:\\"App\\\\Notifications\\\\UserNotification\\":2:{s:7:\\"\\u0000*\\u0000data\\";a:13:{s:7:\\"message\\";s:53:\\"تم حفظ عملية جديدة في مستودعك\\";s:4:\\"type\\";s:10:\\"restocking\\";s:8:\\"priority\\";i:1;s:8:\\"due_date\\";O:13:\\"Carbon\\\\Carbon\\":3:{s:4:\\"date\\";s:26:\\"2025-03-05 06:30:01.027781\\";s:13:\\"timezone_type\\";i:3;s:8:\\"timezone\\";s:3:\\"UTC\\";}s:7:\\"is_read\\";b:0;s:10:\\"product_id\\";N;s:20:\\"inventory_request_id\\";N;s:8:\\"quantity\\";N;s:6:\\"status\\";s:3:\\"new\\";s:13:\\"department_id\\";N;s:12:\\"warehouse_id\\";s:1:\\"1\\";s:10:\\"created_at\\";s:19:\\"2025-03-04 06:30:01\\";s:10:\\"updated_at\\";s:19:\\"2025-03-04 06:30:01\\";}s:2:\\"id\\";s:36:\\"cb1078a7-1ae8-4bde-b932-ea2cd886d169\\";}s:8:\\"channels\\";a:1:{i:0;s:8:\\"database\\";}}","commandName":"Illuminate\\\\Notifications\\\\SendQueuedNotifications"},"attempts":2}', 'PDOException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'type\' in \'field list\' in C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php:47\nStack trace:\n#0 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(47): PDO->prepare(\'insert into `no...\')\n#1 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(812): Illuminate\\Database\\MySqlConnection->Illuminate\\Database\\{closure}(\'insert into `no...\', Array)\n#2 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(779): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#3 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#4 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3748): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#5 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2066): Illuminate\\Database\\Query\\Builder->insert(Array)\n#6 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1335): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#7 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1163): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#8 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(371): Illuminate\\Database\\Eloquent\\Model->save()\n#9 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#10 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(368): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#11 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#12 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\UserNotification))\n#13 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'2af3dc46-e915-4...\', Object(App\\Notifications\\UserNotification), \'database\')\n#14 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#15 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#16 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#17 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#18 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#19 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#20 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#21 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#22 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#23 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(126): Illuminate\\Container\\Container->call(Array)\n#24 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#25 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#26 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#27 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#28 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#29 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#31 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#32 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\RedisJob), Array)\n#33 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#34 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(392): Illuminate\\Queue\\Worker->process(\'redis\', Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(178): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\RedisJob), \'redis\', Object(Illuminate\\Queue\\WorkerOptions))\n#36 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'redis\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#37 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'redis\', \'default\')\n#38 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#39 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#40 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#41 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#42 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#43 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(213): Illuminate\\Container\\Container->call(Array)\n#44 C:\\madarsys\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#45 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(182): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#46 C:\\madarsys\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\madarsys\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\madarsys\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#50 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1208): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#51 C:\\madarsys\\artisan(13): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#52 {main}\n\nNext Illuminate\\Database\\QueryException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'type\' in \'field list\' (Connection: mysql, SQL: insert into `notifications` (`id`, `type`, `data`, `read_at`, `notifiable_id`, `notifiable_type`, `updated_at`, `created_at`) values (cb1078a7-1ae8-4bde-b932-ea2cd886d169, App\\Notifications\\UserNotification, {"notification_id":23,"message":"\\u062a\\u0645 \\u062d\\u0641\\u0638 \\u0639\\u0645\\u0644\\u064a\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629 \\u0641\\u064a \\u0645\\u0633\\u062a\\u0648\\u062f\\u0639\\u0643","priority":1,"due_date":"2025-03-05T06:30:01.027781Z","status":0,"warehouse_id":"1","department_id":null}, ?, 5, App\\Models\\User, 2025-03-04 06:30:04, 2025-03-04 06:30:04)) in C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php:825\nStack trace:\n#0 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(779): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#1 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#2 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3748): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#3 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2066): Illuminate\\Database\\Query\\Builder->insert(Array)\n#4 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1335): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#5 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1163): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#6 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(371): Illuminate\\Database\\Eloquent\\Model->save()\n#7 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#8 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(368): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#9 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#10 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\UserNotification))\n#11 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'2af3dc46-e915-4...\', Object(App\\Notifications\\UserNotification), \'database\')\n#12 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#13 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#14 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#15 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#16 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#17 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#18 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#19 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#20 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#21 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(126): Illuminate\\Container\\Container->call(Array)\n#22 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#23 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#24 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#25 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#26 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#27 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#28 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#29 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\RedisJob), Array)\n#31 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#32 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(392): Illuminate\\Queue\\Worker->process(\'redis\', Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Queue\\WorkerOptions))\n#33 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(178): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\RedisJob), \'redis\', Object(Illuminate\\Queue\\WorkerOptions))\n#34 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'redis\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'redis\', \'default\')\n#36 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#37 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#38 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#39 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#40 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#41 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(213): Illuminate\\Container\\Container->call(Array)\n#42 C:\\madarsys\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#43 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(182): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#44 C:\\madarsys\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\madarsys\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\madarsys\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1208): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\madarsys\\artisan(13): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#50 {main}', '2025-03-04 14:30:04'),
	(6, 'b8dba86c-7a7b-4aad-abe8-55cae7d90114', 'redis', 'default', '{"uuid":"b8dba86c-7a7b-4aad-abe8-55cae7d90114","timeout":null,"id":"LIMvFEMg9YffxVIjC8p8uwhDayjnRryx","backoff":null,"displayName":"App\\\\Notifications\\\\UserNotification","maxTries":null,"failOnTimeout":false,"maxExceptions":null,"retryUntil":null,"job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","data":{"command":"O:48:\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\":3:{s:11:\\"notifiables\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":5:{s:5:\\"class\\";s:15:\\"App\\\\Models\\\\User\\";s:2:\\"id\\";a:1:{i:0;i:10;}s:9:\\"relations\\";a:0:{}s:10:\\"connection\\";s:5:\\"mysql\\";s:15:\\"collectionClass\\";N;}s:12:\\"notification\\";O:34:\\"App\\\\Notifications\\\\UserNotification\\":2:{s:7:\\"\\u0000*\\u0000data\\";a:13:{s:7:\\"message\\";s:53:\\"تم حفظ عملية جديدة في مستودعك\\";s:4:\\"type\\";s:10:\\"restocking\\";s:8:\\"priority\\";i:1;s:8:\\"due_date\\";O:13:\\"Carbon\\\\Carbon\\":3:{s:4:\\"date\\";s:26:\\"2025-03-05 06:30:01.028765\\";s:13:\\"timezone_type\\";i:3;s:8:\\"timezone\\";s:3:\\"UTC\\";}s:7:\\"is_read\\";b:0;s:10:\\"product_id\\";N;s:20:\\"inventory_request_id\\";N;s:8:\\"quantity\\";N;s:6:\\"status\\";s:3:\\"new\\";s:13:\\"department_id\\";N;s:12:\\"warehouse_id\\";s:1:\\"1\\";s:10:\\"created_at\\";s:19:\\"2025-03-04 06:30:01\\";s:10:\\"updated_at\\";s:19:\\"2025-03-04 06:30:01\\";}s:2:\\"id\\";s:36:\\"0665e18d-e067-4765-95a4-6bd555ab3496\\";}s:8:\\"channels\\";a:1:{i:0;s:8:\\"database\\";}}","commandName":"Illuminate\\\\Notifications\\\\SendQueuedNotifications"},"attempts":2}', 'PDOException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'type\' in \'field list\' in C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php:47\nStack trace:\n#0 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(47): PDO->prepare(\'insert into `no...\')\n#1 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(812): Illuminate\\Database\\MySqlConnection->Illuminate\\Database\\{closure}(\'insert into `no...\', Array)\n#2 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(779): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#3 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#4 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3748): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#5 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2066): Illuminate\\Database\\Query\\Builder->insert(Array)\n#6 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1335): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#7 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1163): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#8 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(371): Illuminate\\Database\\Eloquent\\Model->save()\n#9 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#10 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(368): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#11 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#12 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\UserNotification))\n#13 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'e55a348e-17ac-4...\', Object(App\\Notifications\\UserNotification), \'database\')\n#14 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#15 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#16 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#17 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#18 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#19 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#20 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#21 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#22 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#23 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(126): Illuminate\\Container\\Container->call(Array)\n#24 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#25 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#26 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#27 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#28 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#29 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#31 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#32 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\RedisJob), Array)\n#33 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#34 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(392): Illuminate\\Queue\\Worker->process(\'redis\', Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(178): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\RedisJob), \'redis\', Object(Illuminate\\Queue\\WorkerOptions))\n#36 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'redis\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#37 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'redis\', \'default\')\n#38 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#39 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#40 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#41 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#42 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#43 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(213): Illuminate\\Container\\Container->call(Array)\n#44 C:\\madarsys\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#45 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(182): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#46 C:\\madarsys\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\madarsys\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\madarsys\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#50 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1208): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#51 C:\\madarsys\\artisan(13): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#52 {main}\n\nNext Illuminate\\Database\\QueryException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'type\' in \'field list\' (Connection: mysql, SQL: insert into `notifications` (`id`, `type`, `data`, `read_at`, `notifiable_id`, `notifiable_type`, `updated_at`, `created_at`) values (0665e18d-e067-4765-95a4-6bd555ab3496, App\\Notifications\\UserNotification, {"notification_id":24,"message":"\\u062a\\u0645 \\u062d\\u0641\\u0638 \\u0639\\u0645\\u0644\\u064a\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629 \\u0641\\u064a \\u0645\\u0633\\u062a\\u0648\\u062f\\u0639\\u0643","priority":1,"due_date":"2025-03-05T06:30:01.028765Z","status":0,"warehouse_id":"1","department_id":null}, ?, 10, App\\Models\\User, 2025-03-04 06:30:04, 2025-03-04 06:30:04)) in C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php:825\nStack trace:\n#0 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(779): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#1 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#2 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3748): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#3 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2066): Illuminate\\Database\\Query\\Builder->insert(Array)\n#4 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1335): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#5 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1163): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#6 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(371): Illuminate\\Database\\Eloquent\\Model->save()\n#7 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#8 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(368): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#9 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#10 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\UserNotification))\n#11 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'e55a348e-17ac-4...\', Object(App\\Notifications\\UserNotification), \'database\')\n#12 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#13 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#14 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#15 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#16 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#17 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#18 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#19 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#20 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#21 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(126): Illuminate\\Container\\Container->call(Array)\n#22 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#23 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#24 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#25 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#26 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#27 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#28 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#29 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\RedisJob), Array)\n#31 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#32 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(392): Illuminate\\Queue\\Worker->process(\'redis\', Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Queue\\WorkerOptions))\n#33 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(178): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\RedisJob), \'redis\', Object(Illuminate\\Queue\\WorkerOptions))\n#34 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'redis\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'redis\', \'default\')\n#36 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#37 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#38 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#39 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#40 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#41 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(213): Illuminate\\Container\\Container->call(Array)\n#42 C:\\madarsys\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#43 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(182): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#44 C:\\madarsys\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\madarsys\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\madarsys\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1208): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\madarsys\\artisan(13): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#50 {main}', '2025-03-04 14:30:04'),
	(7, 'd30fb926-2136-451c-a0ed-d57fb6290def', 'redis', 'default', '{"uuid":"d30fb926-2136-451c-a0ed-d57fb6290def","timeout":null,"id":"zAfBausqCeh7FfojRgvxyvFdUmUy1ETf","backoff":null,"displayName":"App\\\\Notifications\\\\UserNotification","maxTries":null,"failOnTimeout":false,"maxExceptions":null,"retryUntil":null,"job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","data":{"command":"O:48:\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\":3:{s:11:\\"notifiables\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":5:{s:5:\\"class\\";s:15:\\"App\\\\Models\\\\User\\";s:2:\\"id\\";a:1:{i:0;i:6;}s:9:\\"relations\\";a:0:{}s:10:\\"connection\\";s:5:\\"mysql\\";s:15:\\"collectionClass\\";N;}s:12:\\"notification\\";O:34:\\"App\\\\Notifications\\\\UserNotification\\":2:{s:7:\\"\\u0000*\\u0000data\\";a:13:{s:7:\\"message\\";s:53:\\"تم حفظ عملية جديدة في مستودعك\\";s:4:\\"type\\";s:10:\\"restocking\\";s:8:\\"priority\\";i:1;s:8:\\"due_date\\";O:13:\\"Carbon\\\\Carbon\\":3:{s:4:\\"date\\";s:26:\\"2025-03-05 06:35:50.084652\\";s:13:\\"timezone_type\\";i:3;s:8:\\"timezone\\";s:3:\\"UTC\\";}s:7:\\"is_read\\";b:0;s:10:\\"product_id\\";N;s:20:\\"inventory_request_id\\";N;s:8:\\"quantity\\";N;s:6:\\"status\\";s:3:\\"new\\";s:13:\\"department_id\\";N;s:12:\\"warehouse_id\\";s:1:\\"1\\";s:10:\\"created_at\\";s:19:\\"2025-03-04 06:35:50\\";s:10:\\"updated_at\\";s:19:\\"2025-03-04 06:35:50\\";}s:2:\\"id\\";s:36:\\"8fcea365-68b9-4678-a688-bec6d4d66cdb\\";}s:8:\\"channels\\";a:1:{i:0;s:8:\\"database\\";}}","commandName":"Illuminate\\\\Notifications\\\\SendQueuedNotifications"},"attempts":2}', 'PDOException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'type\' in \'field list\' in C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php:47\nStack trace:\n#0 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(47): PDO->prepare(\'insert into `no...\')\n#1 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(812): Illuminate\\Database\\MySqlConnection->Illuminate\\Database\\{closure}(\'insert into `no...\', Array)\n#2 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(779): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#3 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#4 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3748): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#5 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2066): Illuminate\\Database\\Query\\Builder->insert(Array)\n#6 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1335): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#7 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1163): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#8 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(371): Illuminate\\Database\\Eloquent\\Model->save()\n#9 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#10 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(368): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#11 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#12 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\UserNotification))\n#13 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'4c629144-cc88-4...\', Object(App\\Notifications\\UserNotification), \'database\')\n#14 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#15 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#16 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#17 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#18 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#19 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#20 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#21 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#22 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#23 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(126): Illuminate\\Container\\Container->call(Array)\n#24 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#25 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#26 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#27 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#28 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#29 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#31 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#32 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\RedisJob), Array)\n#33 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#34 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(392): Illuminate\\Queue\\Worker->process(\'redis\', Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(178): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\RedisJob), \'redis\', Object(Illuminate\\Queue\\WorkerOptions))\n#36 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'redis\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#37 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'redis\', \'default\')\n#38 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#39 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#40 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#41 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#42 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#43 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(213): Illuminate\\Container\\Container->call(Array)\n#44 C:\\madarsys\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#45 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(182): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#46 C:\\madarsys\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\madarsys\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\madarsys\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#50 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1208): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#51 C:\\madarsys\\artisan(13): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#52 {main}\n\nNext Illuminate\\Database\\QueryException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'type\' in \'field list\' (Connection: mysql, SQL: insert into `notifications` (`id`, `type`, `data`, `read_at`, `notifiable_id`, `notifiable_type`, `updated_at`, `created_at`) values (8fcea365-68b9-4678-a688-bec6d4d66cdb, App\\Notifications\\UserNotification, {"notification_id":32,"message":"\\u062a\\u0645 \\u062d\\u0641\\u0638 \\u0639\\u0645\\u0644\\u064a\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629 \\u0641\\u064a \\u0645\\u0633\\u062a\\u0648\\u062f\\u0639\\u0643","priority":1,"due_date":"2025-03-05T06:35:50.084652Z","status":0,"warehouse_id":"1","department_id":null}, ?, 6, App\\Models\\User, 2025-03-04 06:35:51, 2025-03-04 06:35:51)) in C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php:825\nStack trace:\n#0 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(779): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#1 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#2 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3748): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#3 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2066): Illuminate\\Database\\Query\\Builder->insert(Array)\n#4 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1335): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#5 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1163): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#6 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(371): Illuminate\\Database\\Eloquent\\Model->save()\n#7 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#8 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(368): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#9 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#10 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\UserNotification))\n#11 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'4c629144-cc88-4...\', Object(App\\Notifications\\UserNotification), \'database\')\n#12 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#13 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#14 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#15 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#16 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#17 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#18 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#19 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#20 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#21 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(126): Illuminate\\Container\\Container->call(Array)\n#22 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#23 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#24 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#25 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#26 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#27 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#28 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#29 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\RedisJob), Array)\n#31 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#32 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(392): Illuminate\\Queue\\Worker->process(\'redis\', Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Queue\\WorkerOptions))\n#33 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(178): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\RedisJob), \'redis\', Object(Illuminate\\Queue\\WorkerOptions))\n#34 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'redis\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'redis\', \'default\')\n#36 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#37 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#38 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#39 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#40 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#41 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(213): Illuminate\\Container\\Container->call(Array)\n#42 C:\\madarsys\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#43 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(182): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#44 C:\\madarsys\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\madarsys\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\madarsys\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1208): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\madarsys\\artisan(13): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#50 {main}', '2025-03-04 14:35:51'),
	(8, '3858b651-1423-4674-83fa-141735a20859', 'redis', 'default', '{"uuid":"3858b651-1423-4674-83fa-141735a20859","timeout":null,"id":"FAAyVqdAi6LuY7C6vrZyyYAqsDjLyi8d","backoff":null,"displayName":"App\\\\Notifications\\\\UserNotification","maxTries":null,"failOnTimeout":false,"maxExceptions":null,"retryUntil":null,"job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","data":{"command":"O:48:\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\":3:{s:11:\\"notifiables\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":5:{s:5:\\"class\\";s:15:\\"App\\\\Models\\\\User\\";s:2:\\"id\\";a:1:{i:0;i:5;}s:9:\\"relations\\";a:0:{}s:10:\\"connection\\";s:5:\\"mysql\\";s:15:\\"collectionClass\\";N;}s:12:\\"notification\\";O:34:\\"App\\\\Notifications\\\\UserNotification\\":2:{s:7:\\"\\u0000*\\u0000data\\";a:13:{s:7:\\"message\\";s:53:\\"تم حفظ عملية جديدة في مستودعك\\";s:4:\\"type\\";s:10:\\"restocking\\";s:8:\\"priority\\";i:1;s:8:\\"due_date\\";O:13:\\"Carbon\\\\Carbon\\":3:{s:4:\\"date\\";s:26:\\"2025-03-05 06:35:50.098549\\";s:13:\\"timezone_type\\";i:3;s:8:\\"timezone\\";s:3:\\"UTC\\";}s:7:\\"is_read\\";b:0;s:10:\\"product_id\\";N;s:20:\\"inventory_request_id\\";N;s:8:\\"quantity\\";N;s:6:\\"status\\";s:3:\\"new\\";s:13:\\"department_id\\";N;s:12:\\"warehouse_id\\";s:1:\\"1\\";s:10:\\"created_at\\";s:19:\\"2025-03-04 06:35:50\\";s:10:\\"updated_at\\";s:19:\\"2025-03-04 06:35:50\\";}s:2:\\"id\\";s:36:\\"fc43d321-72ad-46ea-8312-62325b06943a\\";}s:8:\\"channels\\";a:1:{i:0;s:8:\\"database\\";}}","commandName":"Illuminate\\\\Notifications\\\\SendQueuedNotifications"},"attempts":2}', 'PDOException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'type\' in \'field list\' in C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php:47\nStack trace:\n#0 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(47): PDO->prepare(\'insert into `no...\')\n#1 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(812): Illuminate\\Database\\MySqlConnection->Illuminate\\Database\\{closure}(\'insert into `no...\', Array)\n#2 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(779): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#3 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#4 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3748): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#5 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2066): Illuminate\\Database\\Query\\Builder->insert(Array)\n#6 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1335): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#7 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1163): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#8 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(371): Illuminate\\Database\\Eloquent\\Model->save()\n#9 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#10 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(368): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#11 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#12 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\UserNotification))\n#13 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'08247cf1-3d91-4...\', Object(App\\Notifications\\UserNotification), \'database\')\n#14 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#15 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#16 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#17 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#18 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#19 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#20 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#21 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#22 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#23 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(126): Illuminate\\Container\\Container->call(Array)\n#24 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#25 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#26 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#27 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#28 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#29 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#31 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#32 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\RedisJob), Array)\n#33 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#34 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(392): Illuminate\\Queue\\Worker->process(\'redis\', Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(178): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\RedisJob), \'redis\', Object(Illuminate\\Queue\\WorkerOptions))\n#36 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'redis\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#37 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'redis\', \'default\')\n#38 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#39 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#40 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#41 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#42 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#43 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(213): Illuminate\\Container\\Container->call(Array)\n#44 C:\\madarsys\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#45 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(182): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#46 C:\\madarsys\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\madarsys\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\madarsys\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#50 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1208): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#51 C:\\madarsys\\artisan(13): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#52 {main}\n\nNext Illuminate\\Database\\QueryException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'type\' in \'field list\' (Connection: mysql, SQL: insert into `notifications` (`id`, `type`, `data`, `read_at`, `notifiable_id`, `notifiable_type`, `updated_at`, `created_at`) values (fc43d321-72ad-46ea-8312-62325b06943a, App\\Notifications\\UserNotification, {"notification_id":33,"message":"\\u062a\\u0645 \\u062d\\u0641\\u0638 \\u0639\\u0645\\u0644\\u064a\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629 \\u0641\\u064a \\u0645\\u0633\\u062a\\u0648\\u062f\\u0639\\u0643","priority":1,"due_date":"2025-03-05T06:35:50.098549Z","status":0,"warehouse_id":"1","department_id":null}, ?, 5, App\\Models\\User, 2025-03-04 06:35:51, 2025-03-04 06:35:51)) in C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php:825\nStack trace:\n#0 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(779): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#1 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#2 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3748): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#3 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2066): Illuminate\\Database\\Query\\Builder->insert(Array)\n#4 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1335): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#5 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1163): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#6 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(371): Illuminate\\Database\\Eloquent\\Model->save()\n#7 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#8 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(368): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#9 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#10 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\UserNotification))\n#11 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'08247cf1-3d91-4...\', Object(App\\Notifications\\UserNotification), \'database\')\n#12 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#13 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#14 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#15 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#16 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#17 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#18 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#19 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#20 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#21 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(126): Illuminate\\Container\\Container->call(Array)\n#22 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#23 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#24 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#25 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#26 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#27 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#28 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#29 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\RedisJob), Array)\n#31 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#32 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(392): Illuminate\\Queue\\Worker->process(\'redis\', Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Queue\\WorkerOptions))\n#33 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(178): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\RedisJob), \'redis\', Object(Illuminate\\Queue\\WorkerOptions))\n#34 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'redis\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'redis\', \'default\')\n#36 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#37 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#38 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#39 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#40 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#41 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(213): Illuminate\\Container\\Container->call(Array)\n#42 C:\\madarsys\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#43 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(182): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#44 C:\\madarsys\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\madarsys\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\madarsys\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1208): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\madarsys\\artisan(13): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#50 {main}', '2025-03-04 14:35:51'),
	(9, '4d9efd19-8685-48e7-a77d-08cbc00e9a2b', 'redis', 'default', '{"uuid":"4d9efd19-8685-48e7-a77d-08cbc00e9a2b","timeout":null,"id":"GJ2WHC77n9AAczEljg1EaElnwhTJn6XC","backoff":null,"displayName":"App\\\\Notifications\\\\UserNotification","maxTries":null,"failOnTimeout":false,"maxExceptions":null,"retryUntil":null,"job":"Illuminate\\\\Queue\\\\CallQueuedHandler@call","data":{"command":"O:48:\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\":3:{s:11:\\"notifiables\\";O:45:\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\":5:{s:5:\\"class\\";s:15:\\"App\\\\Models\\\\User\\";s:2:\\"id\\";a:1:{i:0;i:10;}s:9:\\"relations\\";a:0:{}s:10:\\"connection\\";s:5:\\"mysql\\";s:15:\\"collectionClass\\";N;}s:12:\\"notification\\";O:34:\\"App\\\\Notifications\\\\UserNotification\\":2:{s:7:\\"\\u0000*\\u0000data\\";a:13:{s:7:\\"message\\";s:53:\\"تم حفظ عملية جديدة في مستودعك\\";s:4:\\"type\\";s:10:\\"restocking\\";s:8:\\"priority\\";i:1;s:8:\\"due_date\\";O:13:\\"Carbon\\\\Carbon\\":3:{s:4:\\"date\\";s:26:\\"2025-03-05 06:35:50.099464\\";s:13:\\"timezone_type\\";i:3;s:8:\\"timezone\\";s:3:\\"UTC\\";}s:7:\\"is_read\\";b:0;s:10:\\"product_id\\";N;s:20:\\"inventory_request_id\\";N;s:8:\\"quantity\\";N;s:6:\\"status\\";s:3:\\"new\\";s:13:\\"department_id\\";N;s:12:\\"warehouse_id\\";s:1:\\"1\\";s:10:\\"created_at\\";s:19:\\"2025-03-04 06:35:50\\";s:10:\\"updated_at\\";s:19:\\"2025-03-04 06:35:50\\";}s:2:\\"id\\";s:36:\\"f476b520-d2b7-4533-ab80-406717bd003c\\";}s:8:\\"channels\\";a:1:{i:0;s:8:\\"database\\";}}","commandName":"Illuminate\\\\Notifications\\\\SendQueuedNotifications"},"attempts":2}', 'PDOException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'type\' in \'field list\' in C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php:47\nStack trace:\n#0 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(47): PDO->prepare(\'insert into `no...\')\n#1 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(812): Illuminate\\Database\\MySqlConnection->Illuminate\\Database\\{closure}(\'insert into `no...\', Array)\n#2 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(779): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#3 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#4 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3748): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#5 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2066): Illuminate\\Database\\Query\\Builder->insert(Array)\n#6 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1335): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#7 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1163): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#8 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(371): Illuminate\\Database\\Eloquent\\Model->save()\n#9 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#10 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(368): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#11 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#12 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\UserNotification))\n#13 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'8930478a-d2d8-4...\', Object(App\\Notifications\\UserNotification), \'database\')\n#14 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#15 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#16 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#17 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#18 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#19 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#20 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#21 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#22 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#23 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(126): Illuminate\\Container\\Container->call(Array)\n#24 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#25 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#26 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#27 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#28 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#29 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#31 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#32 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\RedisJob), Array)\n#33 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#34 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(392): Illuminate\\Queue\\Worker->process(\'redis\', Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(178): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\RedisJob), \'redis\', Object(Illuminate\\Queue\\WorkerOptions))\n#36 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'redis\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#37 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'redis\', \'default\')\n#38 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#39 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#40 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#41 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#42 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#43 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(213): Illuminate\\Container\\Container->call(Array)\n#44 C:\\madarsys\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#45 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(182): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#46 C:\\madarsys\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\madarsys\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\madarsys\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#50 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1208): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#51 C:\\madarsys\\artisan(13): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#52 {main}\n\nNext Illuminate\\Database\\QueryException: SQLSTATE[42S22]: Column not found: 1054 Unknown column \'type\' in \'field list\' (Connection: mysql, SQL: insert into `notifications` (`id`, `type`, `data`, `read_at`, `notifiable_id`, `notifiable_type`, `updated_at`, `created_at`) values (f476b520-d2b7-4533-ab80-406717bd003c, App\\Notifications\\UserNotification, {"notification_id":34,"message":"\\u062a\\u0645 \\u062d\\u0641\\u0638 \\u0639\\u0645\\u0644\\u064a\\u0629 \\u062c\\u062f\\u064a\\u062f\\u0629 \\u0641\\u064a \\u0645\\u0633\\u062a\\u0648\\u062f\\u0639\\u0643","priority":1,"due_date":"2025-03-05T06:35:50.099464Z","status":0,"warehouse_id":"1","department_id":null}, ?, 10, App\\Models\\User, 2025-03-04 06:35:51, 2025-03-04 06:35:51)) in C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php:825\nStack trace:\n#0 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Connection.php(779): Illuminate\\Database\\Connection->runQueryCallback(\'insert into `no...\', Array, Object(Closure))\n#1 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\MySqlConnection.php(42): Illuminate\\Database\\Connection->run(\'insert into `no...\', Array, Object(Closure))\n#2 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Query\\Builder.php(3748): Illuminate\\Database\\MySqlConnection->insert(\'insert into `no...\', Array)\n#3 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Builder.php(2066): Illuminate\\Database\\Query\\Builder->insert(Array)\n#4 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1335): Illuminate\\Database\\Eloquent\\Builder->__call(\'insert\', Array)\n#5 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Model.php(1163): Illuminate\\Database\\Eloquent\\Model->performInsert(Object(Illuminate\\Database\\Eloquent\\Builder))\n#6 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(371): Illuminate\\Database\\Eloquent\\Model->save()\n#7 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\helpers.php(399): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->Illuminate\\Database\\Eloquent\\Relations\\{closure}(Object(Illuminate\\Notifications\\DatabaseNotification))\n#8 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany.php(368): tap(Object(Illuminate\\Notifications\\DatabaseNotification), Object(Closure))\n#9 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\Channels\\DatabaseChannel.php(19): Illuminate\\Database\\Eloquent\\Relations\\HasOneOrMany->create(Array)\n#10 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(148): Illuminate\\Notifications\\Channels\\DatabaseChannel->send(Object(App\\Models\\User), Object(App\\Notifications\\UserNotification))\n#11 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(106): Illuminate\\Notifications\\NotificationSender->sendToNotifiable(Object(App\\Models\\User), \'8930478a-d2d8-4...\', Object(App\\Notifications\\UserNotification), \'database\')\n#12 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Traits\\Localizable.php(19): Illuminate\\Notifications\\NotificationSender->Illuminate\\Notifications\\{closure}()\n#13 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\NotificationSender.php(101): Illuminate\\Notifications\\NotificationSender->withLocale(NULL, Object(Closure))\n#14 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\ChannelManager.php(54): Illuminate\\Notifications\\NotificationSender->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#15 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Notifications\\SendQueuedNotifications.php(119): Illuminate\\Notifications\\ChannelManager->sendNow(Object(Illuminate\\Database\\Eloquent\\Collection), Object(App\\Notifications\\UserNotification), Array)\n#16 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Notifications\\SendQueuedNotifications->handle(Object(Illuminate\\Notifications\\ChannelManager))\n#17 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#18 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#19 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#20 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#21 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(126): Illuminate\\Container\\Container->call(Array)\n#22 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#23 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#24 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(130): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#25 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(Illuminate\\Notifications\\SendQueuedNotifications), false)\n#26 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(170): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#27 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(127): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#28 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(125): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#29 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(73): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Notifications\\SendQueuedNotifications))\n#30 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\RedisJob), Array)\n#31 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(442): Illuminate\\Queue\\Jobs\\Job->fire()\n#32 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(392): Illuminate\\Queue\\Worker->process(\'redis\', Object(Illuminate\\Queue\\Jobs\\RedisJob), Object(Illuminate\\Queue\\WorkerOptions))\n#33 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(178): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\RedisJob), \'redis\', Object(Illuminate\\Queue\\WorkerOptions))\n#34 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(149): Illuminate\\Queue\\Worker->daemon(\'redis\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#35 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(132): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'redis\', \'default\')\n#36 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#37 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#38 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(95): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#39 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#40 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(694): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#41 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(213): Illuminate\\Container\\Container->call(Array)\n#42 C:\\madarsys\\vendor\\symfony\\console\\Command\\Command.php(279): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#43 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(182): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#44 C:\\madarsys\\vendor\\symfony\\console\\Application.php(1094): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 C:\\madarsys\\vendor\\symfony\\console\\Application.php(342): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 C:\\madarsys\\vendor\\symfony\\console\\Application.php(193): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#47 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#48 C:\\madarsys\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1208): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#49 C:\\madarsys\\artisan(13): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#50 {main}', '2025-03-04 14:35:51');

-- Dumping structure for table madarsys.inventory
DROP TABLE IF EXISTS `inventory`;
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
  `blocked_quantity` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_warehouse_id_foreign` (`warehouse_id`),
  KEY `inventory_product_id_foreign` (`product_id`),
  CONSTRAINT `inventory_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.inventory: ~7 rows (approximately)
DELETE FROM `inventory`;
INSERT INTO `inventory` (`id`, `warehouse_id`, `product_id`, `quantity`, `unit_price`, `total_value`, `created_at`, `created_user`, `updated_at`, `updated_user`, `blocked_quantity`) VALUES
	(21, 1, 56, 161, 0.00, 0.00, '2025-04-27 02:10:46', 5, '2025-05-01 15:38:39', 5, 183),
	(22, 1, 58, 14, 0.00, 0.00, '2025-04-27 02:19:03', 5, '2025-05-01 16:14:52', 5, 18),
	(23, 1, 101, 10, 0.00, 0.00, '2025-04-27 02:31:34', 5, '2025-04-27 02:31:34', 5, 10),
	(24, 1, 90, 20, 0.00, 0.00, '2025-04-27 02:55:20', 5, '2025-04-27 02:55:20', 5, 20),
	(25, 1, 108, 0, 0.00, 0.00, '2025-04-27 03:00:07', 5, '2025-04-27 03:01:51', 5, 0),
	(26, 2, 80, 10, 0.00, 0.00, '2025-04-29 01:54:42', 5, '2025-04-29 23:28:06', 5, 20),
	(27, 2, 52, 0, 0.00, 0.00, '2025-04-30 01:24:48', 5, '2025-04-30 01:35:26', 5, 0);

-- Dumping structure for table madarsys.inventory_audits
DROP TABLE IF EXISTS `inventory_audits`;
CREATE TABLE IF NOT EXISTS `inventory_audits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد لسجل الجرد',
  `inventory_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'كود الجرد الفريد',
  `inventory_type` tinyint unsigned NOT NULL COMMENT 'نوع الجرد (1=دوري, 2=مفاجئ, 3=سنوي, 4=شهري)',
  `start_date` datetime DEFAULT NULL COMMENT 'تاريخ بدء الجرد',
  `end_date` datetime DEFAULT NULL COMMENT 'تاريخ انتهاء الجرد',
  `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT 'حالة الجرد (1=معلق, 2=جاري, 3=مكتمل, 4=متأخر)',
  `expected_products_count` int DEFAULT NULL COMMENT 'عدد المنتجات المتوقع جردها',
  `counted_products_count` int DEFAULT NULL COMMENT 'عدد المنتجات التي تم جردها',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'ملاحظات حول الجرد',
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.inventory_audits: ~3 rows (approximately)
DELETE FROM `inventory_audits`;
INSERT INTO `inventory_audits` (`id`, `inventory_code`, `inventory_type`, `start_date`, `end_date`, `status`, `expected_products_count`, `counted_products_count`, `notes`, `branch_id`, `created_user`, `updated_user`, `created_at`, `updated_at`) VALUES
	(19, 'audit-20250428-1', 33, '2025-03-29 20:13:00', '2025-05-01 20:13:00', 1, NULL, NULL, NULL, 2, 5, 5, '2025-04-28 14:13:27', '2025-04-28 14:13:27'),
	(20, 'audit-20250429-20', 32, '2025-03-31 21:18:00', '2025-05-02 21:18:00', 1, NULL, NULL, NULL, 2, 5, 5, '2025-04-29 15:18:41', '2025-04-29 15:18:41'),
	(21, 'audit-20250504-21', 34, '2025-05-04 15:36:00', '2025-05-30 15:36:00', 1, NULL, NULL, NULL, 2, 5, 5, '2025-05-04 09:36:46', '2025-05-04 09:36:46');

-- Dumping structure for table madarsys.inventory_audit_users
DROP TABLE IF EXISTS `inventory_audit_users`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.inventory_audit_users: ~3 rows (approximately)
DELETE FROM `inventory_audit_users`;
INSERT INTO `inventory_audit_users` (`id`, `inventory_audit_id`, `user_id`, `branch_id`, `created_user`, `updated_user`, `operation_type`, `created_at`, `updated_at`) VALUES
	(16, 19, 5, 2, 5, 5, 1, '2025-04-28 14:13:27', '2025-04-28 14:13:27'),
	(17, 20, 5, 2, 5, 5, 1, '2025-04-29 15:18:41', '2025-04-29 15:18:41'),
	(18, 21, 5, 2, 5, 5, 1, '2025-05-04 09:36:46', '2025-05-04 09:36:46');

-- Dumping structure for table madarsys.inventory_audit_warehouses
DROP TABLE IF EXISTS `inventory_audit_warehouses`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.inventory_audit_warehouses: ~3 rows (approximately)
DELETE FROM `inventory_audit_warehouses`;
INSERT INTO `inventory_audit_warehouses` (`id`, `inventory_audit_id`, `warehouse_id`, `branch_id`, `created_user`, `updated_user`, `created_at`, `updated_at`) VALUES
	(16, 19, 1, 2, 5, 5, '2025-04-28 14:13:27', '2025-04-28 14:13:27'),
	(17, 20, 2, 2, 5, 5, '2025-04-29 15:18:41', '2025-04-29 15:18:41'),
	(18, 21, 1, 2, 5, 5, '2025-05-04 09:36:46', '2025-05-04 09:36:46');

-- Dumping structure for table madarsys.inventory_products
DROP TABLE IF EXISTS `inventory_products`;
CREATE TABLE IF NOT EXISTS `inventory_products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للمنتج داخل المستودع',
  `item_source_id` bigint unsigned DEFAULT NULL,
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
  `is_printed` tinyint(1) NOT NULL DEFAULT '0',
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
  KEY `inventory_products_item_source_id_foreign` (`item_source_id`),
  CONSTRAINT `inventory_products_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_products_location_id_foreign` FOREIGN KEY (`location_id`) REFERENCES `warehouse_locations` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_products_storage_area_id_foreign` FOREIGN KEY (`storage_area_id`) REFERENCES `warehouse_storage_areas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_products_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.inventory_products: ~17 rows (approximately)
DELETE FROM `inventory_products`;
INSERT INTO `inventory_products` (`id`, `item_source_id`, `batch_number`, `production_date`, `expiration_date`, `product_id`, `branch_id`, `warehouse_id`, `storage_area_id`, `location_id`, `created_user`, `updated_user`, `created_at`, `updated_at`, `inventory_transaction_item_id`, `quantity`, `temporary_transfer_expiry_date`, `distribution_type`, `is_printed`, `unit_id`, `unit_product_id`, `converted_quantity`, `price`) VALUES
	(174, NULL, 'WH11-SKU-60138-250527-000001', '2025-02-09', '2025-05-27', 56, 2, 1, 1, 5, 5, 5, '2025-04-27 02:10:46', '2025-04-27 02:10:46', 83, 80, NULL, 1, 0, 1, 1, 80.0000, NULL),
	(175, NULL, 'WH11-SKU-60138-250527-000002', '2025-02-09', '2025-05-27', 56, 2, 1, 1, 7, 5, 5, '2025-04-27 02:11:01', '2025-04-27 02:11:01', 83, 50, NULL, 1, 0, 1, 1, 50.0000, NULL),
	(176, NULL, 'WH11-SKU-60138-250527-000003', '2025-02-09', '2025-05-27', 56, 2, 1, 4, 5, 5, 5, '2025-04-27 02:11:14', '2025-04-27 02:11:14', 83, 50, NULL, 1, 0, 1, 1, 50.0000, NULL),
	(177, NULL, 'WH11-SKU-60138-250527-000004', '2025-02-09', '2025-05-27', 56, 2, 1, 1, 9, 5, 5, '2025-04-27 02:11:32', '2025-04-27 02:11:32', 83, 5, NULL, 1, 0, 1, 1, 5.0000, NULL),
	(178, NULL, 'WH11-SKU-17684-250601-000001', '2025-02-27', '2025-06-01', 58, 2, 1, 1, 8, 5, 5, '2025-04-27 02:19:03', '2025-04-27 02:19:03', 84, 2, NULL, 1, 0, 2, 1, 20.0000, NULL),
	(179, NULL, 'WH11-SKU-64946-250427-000001', '2025-02-27', '2025-04-27', 101, 2, 1, 3, 6, 5, 5, '2025-04-27 02:31:34', '2025-04-27 02:31:34', 85, 1, NULL, 1, 0, 2, 1, 10.0000, NULL),
	(180, NULL, 'WH11-SKU-77330-000000-000001', NULL, NULL, 90, 2, 1, 4, 8, 5, 5, '2025-04-27 02:55:20', '2025-04-27 02:55:20', 86, 2, NULL, 1, 0, 2, 1, 20.0000, NULL),
	(181, NULL, 'WH11-IND-NO--000102-000000-000002', NULL, NULL, 108, 2, 1, 3, 6, 5, 5, '2025-04-27 03:00:07', '2025-04-27 03:00:07', 87, 1, NULL, 1, 0, 1, 1, 1.0000, NULL),
	(182, 181, 'WH11-IND-NO--000102-000000-000002', NULL, NULL, 108, 2, 1, 3, 6, 5, 5, '2025-04-27 03:01:51', '2025-04-27 03:01:51', 88, -1, NULL, -1, 0, 1, 1, -1.0000, NULL),
	(183, 177, 'WH11-SKU-60138-250527-000004', '2025-02-09', '2025-05-27', 56, 2, 1, 1, 9, 5, 5, '2025-04-27 03:22:39', '2025-04-27 03:22:39', 89, -1, NULL, -1, 0, 1, 1, -1.0000, NULL),
	(184, 178, 'WH11-SKU-17684-250601-000001', '2025-02-27', '2025-06-01', 58, 2, 1, 1, 8, 5, 5, '2025-04-27 03:31:32', '2025-04-27 03:31:32', 90, -1, NULL, -1, 0, 2, 1, -1.0000, NULL),
	(185, NULL, 'WH12-SKU-98789-000000-000001', NULL, NULL, 80, 2, 2, 1, 6, 5, 5, '2025-04-29 01:54:42', '2025-04-29 01:54:42', 182, 10, NULL, 1, 0, 1, 1, 10.0000, NULL),
	(186, NULL, 'WH12-SKU-30508-000000-000002', NULL, NULL, 52, 2, 2, 2, NULL, 5, 5, '2025-04-30 01:24:48', '2025-04-30 01:24:48', 185, 5, NULL, 1, 0, 1, 1, 5.0000, NULL),
	(191, 186, 'WH12-SKU-30508-000000-000002', NULL, NULL, 52, 2, 2, 2, NULL, 5, 5, '2025-04-30 01:35:26', '2025-04-30 01:35:26', 187, -5, NULL, -1, 0, 1, 1, -5.0000, NULL),
	(197, 177, 'WH11-SKU-60138-250527-000004', '2025-02-09', '2025-05-27', 56, 2, 1, 1, 9, 5, 5, '2025-05-01 15:38:39', '2025-05-01 15:38:39', 188, -4, NULL, -1, 0, 1, 1, -4.0000, NULL),
	(198, 176, 'WH11-SKU-60138-250527-000003', '2025-02-09', '2025-05-27', 56, 2, 1, 4, 5, 5, 5, '2025-05-01 15:38:39', '2025-05-01 15:38:39', 188, -19, NULL, -1, 0, 1, 1, -19.0000, NULL),
	(199, 178, 'WH11-SKU-17684-250601-000001', '2025-02-27', '2025-06-01', 58, 2, 1, 1, 8, 5, 5, '2025-05-01 16:14:52', '2025-05-01 16:14:52', 189, -5, NULL, -1, 0, 2, 1, -5.0000, NULL);

-- Dumping structure for table madarsys.inventory_requests
DROP TABLE IF EXISTS `inventory_requests`;
CREATE TABLE IF NOT EXISTS `inventory_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرف الفريد لطلب المخزون',
  `transaction_type_id` bigint unsigned NOT NULL COMMENT 'نوع العملية المرتبطة بالطلب',
  `request_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'تاريخ الطلب',
  `partner_id` bigint unsigned DEFAULT NULL COMMENT 'المورد أو العميل المرتبط بالطلب',
  `department_id` bigint unsigned DEFAULT NULL COMMENT 'القسم الذي قدم الطلب',
  `warehouse_id` bigint unsigned NOT NULL COMMENT 'المستودع الذي سيتم تنفيذ الطلب منه',
  `status` enum('قيد الانتظار','موافق عليه','مرفوض','مكتمل') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'حالة الطلب: 1 قيد الانتظار، 2 موافق عليه، 3 مرفوض، 4 مكتمل',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'ملاحظات إضافية',
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

-- Dumping data for table madarsys.inventory_requests: ~0 rows (approximately)
DELETE FROM `inventory_requests`;

-- Dumping structure for table madarsys.inventory_request_details
DROP TABLE IF EXISTS `inventory_request_details`;
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

-- Dumping data for table madarsys.inventory_request_details: ~0 rows (approximately)
DELETE FROM `inventory_request_details`;

-- Dumping structure for table madarsys.inventory_transactions
DROP TABLE IF EXISTS `inventory_transactions`;
CREATE TABLE IF NOT EXISTS `inventory_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرف الفريد للعملية المخزنية',
  `transaction_type_id` bigint unsigned NOT NULL COMMENT 'نوع العملية (إضافة، إخراج، تعديل)',
  `sub_type_id` bigint unsigned DEFAULT NULL COMMENT 'النوع الفرعي للعملية',
  `effect` tinyint DEFAULT NULL COMMENT 'تأثير العملية على المخزون (+ زيادة، - نقصان، 0 محايدة)',
  `transaction_date` timestamp NOT NULL COMMENT 'تاريخ تنفيذ العملية',
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'مرجع العملية إن وجد',
  `partner_id` bigint unsigned DEFAULT NULL COMMENT 'المورد أو العميل المرتبط بالعملية',
  `department_id` bigint unsigned DEFAULT NULL COMMENT 'القسم الذي قام بالعملية',
  `warehouse_id` bigint unsigned NOT NULL COMMENT 'المستودع الذي حدثت فيه العملية',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `branch_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `inventory_request_id` bigint unsigned DEFAULT NULL,
  `secondary_warehouse_id` bigint unsigned DEFAULT NULL COMMENT 'المستودع الثانوي المستخدم إن وجد',
  `status` int(10) unsigned zerofill NOT NULL DEFAULT '0000000000' COMMENT 'حالة العملية المخزنية',
  `approved_at` timestamp NULL DEFAULT NULL,
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
  CONSTRAINT `inventory_transactions_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transactions_secondary_warehouse_id_foreign` FOREIGN KEY (`secondary_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transactions_sub_type_id_foreign` FOREIGN KEY (`sub_type_id`) REFERENCES `inventory_transaction_subtypes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transactions_transaction_type_id_foreign` FOREIGN KEY (`transaction_type_id`) REFERENCES `transaction_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_transactions_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='جدول يسجل الحركات المخزنية التي تتم على المنتجات، مثل الإدخال، الإخراج، والتعديلات على المخزون.';

-- Dumping data for table madarsys.inventory_transactions: ~20 rows (approximately)
DELETE FROM `inventory_transactions`;
INSERT INTO `inventory_transactions` (`id`, `transaction_type_id`, `sub_type_id`, `effect`, `transaction_date`, `reference`, `partner_id`, `department_id`, `warehouse_id`, `notes`, `branch_id`, `created_at`, `created_user`, `updated_at`, `updated_user`, `inventory_request_id`, `secondary_warehouse_id`, `status`, `approved_at`) VALUES
	(134, 1, NULL, 1, '2025-04-27 01:44:07', 'Pu-Inv-1', 11, NULL, 1, 'تمت إضافة هذه العملية ديناميكيًا بناءً على فاتورة مشتريات رقم: Pu-Inv-1', 2, '2025-04-27 01:44:07', 5, '2025-04-29 05:54:17', 5, NULL, NULL, 0000000001, '2025-04-29 05:54:17'),
	(135, 1, NULL, 1, '2025-04-27 02:09:33', 'Pu-Inv-2', 20, NULL, 1, 'تمت إضافة هذه العملية ديناميكيًا بناءً على فاتورة مشتريات رقم: Pu-Inv-2', 2, '2025-04-27 02:09:33', 5, '2025-04-27 02:10:18', 5, NULL, NULL, 0000000001, '2025-04-27 02:10:18'),
	(136, 1, NULL, 1, '2025-04-27 02:18:16', 'Pu-Inv-3', 1, NULL, 1, 'تمت إضافة هذه العملية ديناميكيًا بناءً على فاتورة مشتريات رقم: Pu-Inv-3', 2, '2025-04-27 02:18:16', 5, '2025-04-27 02:18:45', 5, NULL, NULL, 0000000001, '2025-04-27 02:18:45'),
	(137, 1, NULL, 1, '2025-04-27 02:30:36', 'Pu-Inv-4', 27, NULL, 1, 'تمت إضافة هذه العملية ديناميكيًا بناءً على فاتورة مشتريات رقم: Pu-Inv-4', 2, '2025-04-27 02:30:36', 5, '2025-04-27 02:31:00', 5, NULL, NULL, 0000000001, '2025-04-27 02:31:00'),
	(141, 1, NULL, 1, '2025-04-27 02:54:42', 'Pu-Inv-5', 6, NULL, 1, 'تمت إضافة هذه العملية ديناميكيًا بناءً على فاتورة مشتريات رقم: Pu-Inv-5', 2, '2025-04-27 02:54:42', 5, '2025-04-27 02:55:01', 5, NULL, NULL, 0000000001, '2025-04-27 02:55:01'),
	(142, 1, NULL, 1, '2025-04-27 02:59:41', 'Pu-Inv-6', 13, NULL, 1, 'تمت إضافة هذه العملية ديناميكيًا بناءً على فاتورة مشتريات رقم: Pu-Inv-6', 2, '2025-04-27 02:59:41', 5, '2025-04-27 02:59:48', 5, NULL, NULL, 0000000001, '2025-04-27 02:59:48'),
	(156, 2, NULL, NULL, '2025-04-28 04:28:56', NULL, NULL, NULL, 1, 'خصم مخزون من مرتجع المورد: مورد 5', 2, '2025-04-28 04:28:56', 5, '2025-04-28 04:28:56', 5, NULL, NULL, 0000000000, NULL),
	(157, 2, NULL, NULL, '2025-04-28 04:28:56', NULL, NULL, NULL, 1, 'خصم مخزون من مرتجع المورد: مورد 5', 2, '2025-04-28 04:28:56', 5, '2025-04-28 04:28:56', 5, NULL, NULL, 0000000000, NULL),
	(158, 1, NULL, NULL, '2025-04-28 05:10:08', NULL, NULL, NULL, 1, 'إضافة مخزون من مرتجع العميل: عميل 10', 2, '2025-04-28 05:10:08', 5, '2025-04-28 05:10:08', 5, NULL, NULL, 0000000000, NULL),
	(159, 1, NULL, NULL, '2025-04-28 05:37:18', NULL, NULL, NULL, 1, 'إضافة مخزون من مرتجع العميل: عميل 10', 2, '2025-04-28 05:37:18', 5, '2025-04-28 05:37:18', 5, NULL, NULL, 0000000000, NULL),
	(175, 1, NULL, 1, '2025-04-29 01:52:11', 'Pu-Inv-7', 11, NULL, 2, 'فاتورة شراء مرتبطة بأمر شراء رقم: PO-20250429-0026', 2, '2025-04-29 01:52:11', 5, '2025-04-29 01:54:17', 5, NULL, NULL, 0000000001, '2025-04-29 01:54:17'),
	(178, 1, NULL, 1, '2025-04-30 00:23:59', 'Pu-Inv-7', 7, NULL, 2, 'فاتورة شراء مرتبطة بأمر شراء رقم: PO-20250430-0027', 2, '2025-04-30 00:23:59', 5, '2025-04-30 00:24:17', 5, NULL, NULL, 0000000001, '2025-04-30 00:24:17'),
	(182, 1, NULL, 1, '2025-05-02 13:23:00', 'w3r34', 1, NULL, 1, NULL, 2, '2025-05-02 13:25:03', 5, '2025-05-02 13:25:03', 5, NULL, NULL, 0000000000, NULL),
	(184, 2, NULL, 1, '2025-05-02 13:29:00', '34', 1, NULL, 1, NULL, 2, '2025-05-02 13:30:24', 5, '2025-05-02 13:30:24', 5, NULL, NULL, 0000000000, NULL),
	(186, 1, NULL, NULL, '2025-05-04 08:19:47', NULL, NULL, NULL, 1, 'إضافة مخزون من مرتجع العميل: عميل 4', 2, '2025-05-04 08:19:47', 5, '2025-05-04 08:19:47', 5, NULL, NULL, 0000000000, NULL),
	(187, 1, NULL, NULL, '2025-05-04 08:19:47', NULL, NULL, NULL, 1, 'إضافة مخزون من مرتجع العميل: عميل 4', 2, '2025-05-04 08:19:47', 5, '2025-05-04 08:19:47', 5, NULL, NULL, 0000000000, NULL),
	(188, 1, NULL, NULL, '2025-05-04 08:20:26', NULL, NULL, NULL, 1, 'إضافة مخزون من مرتجع العميل: عميل 5', 2, '2025-05-04 08:20:26', 5, '2025-05-04 08:20:26', 5, NULL, NULL, 0000000000, NULL),
	(190, 2, NULL, NULL, '2025-05-04 10:27:09', NULL, NULL, NULL, 1, 'خصم مخزون من مرتجع المورد: مورد 3', 2, '2025-05-04 10:27:09', 5, '2025-05-04 10:27:09', 5, NULL, NULL, 0000000000, NULL),
	(191, 1, NULL, NULL, '2025-05-04 12:05:29', 'RET-1746371129', 19, NULL, 1, 'إضافة مخزون من مرتجع العميل: عميل 9', 2, '2025-05-04 12:05:29', 5, '2025-05-04 12:05:29', 5, NULL, NULL, 0000000000, NULL),
	(195, 8, 33, 0, '2025-05-19 14:58:40', 'audit-20250428-1', 36, NULL, 1, 'عملية جرد تلقائية مرتبطة بامر جرد رقم: 19', 2, '2025-05-19 14:58:40', 5, '2025-05-19 14:58:40', 5, 19, NULL, 0000000000, NULL);

-- Dumping structure for table madarsys.inventory_transaction_items
DROP TABLE IF EXISTS `inventory_transaction_items`;
CREATE TABLE IF NOT EXISTS `inventory_transaction_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرف الفريد لتفاصيل العنصر في العملية المخزنية',
  `inventory_transaction_id` bigint unsigned NOT NULL COMMENT 'المعرف المرتبط بالعملية المخزنية',
  `product_id` bigint unsigned NOT NULL COMMENT 'المنتج المتأثر بالعملية',
  `batch_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'كود الدفعة الخاصة بالمنتج',
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
  `batch_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رقم الدفعة من جدول inventory_products',
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='جدول يحتوي على تفاصيل العناصر في كل عملية مخزنية، بما في ذلك المنتجات، الكميات، المواقع، والتكاليف.';

-- Dumping data for table madarsys.inventory_transaction_items: ~19 rows (approximately)
DELETE FROM `inventory_transaction_items`;
INSERT INTO `inventory_transaction_items` (`id`, `inventory_transaction_id`, `product_id`, `batch_code`, `quantity`, `unit_id`, `unit_product_id`, `converted_quantity`, `unit_prices`, `total`, `warehouse_location_id`, `branch_id`, `created_at`, `created_user`, `updated_at`, `updated_user`, `target_warehouse_id`, `converted_price`, `production_date`, `expiration_date`, `source_warehouse_id`, `status`, `result`, `expected_audit_quantity`, `batch_number`, `reference_item_id`) VALUES
	(82, 134, 100, '000-SKU-77998-000001', 11, 1, 1, 11.0000, 1.00, 11.000000, NULL, 2, '2025-04-27 01:44:07', 5, '2025-04-27 01:44:07', 5, 1, 1.00, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL),
	(83, 135, 56, '000-SKU-60138-000001', 185, 1, 1, 185.0000, 100.00, 18500.000000, NULL, 2, '2025-04-27 02:09:33', 5, '2025-04-27 02:09:33', 5, 1, 100.00, '2025-02-09', '2025-05-27', NULL, 1, 1, NULL, NULL, NULL),
	(84, 136, 58, '000-SKU-17684-000001', 2, 2, 1, 20.0000, 10000.00, 20000.000000, NULL, 2, '2025-04-27 02:18:16', 5, '2025-04-27 02:18:16', 5, 1, 10000.00, '2025-02-27', '2025-06-01', NULL, 1, 1, NULL, NULL, NULL),
	(85, 137, 101, '000-SKU-64946-000001', 1, 2, 1, 10.0000, 9000.00, 9000.000000, NULL, 2, '2025-04-27 02:30:36', 5, '2025-04-27 02:30:36', 5, 1, 9000.00, '2025-02-27', '2025-04-27', NULL, 1, 1, NULL, NULL, NULL),
	(86, 141, 90, '000-SKU-77330-000001', 2, 2, 1, 20.0000, 100000.00, 200000.000000, NULL, 2, '2025-04-27 02:54:42', 5, '2025-04-27 02:54:42', 5, 1, 100000.00, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL),
	(87, 142, 108, '000-IND-NO--000102-000001', 1, 1, 1, 1.0000, 900.00, 900.000000, NULL, 2, '2025-04-27 02:59:41', 5, '2025-04-27 02:59:41', 5, 1, 900.00, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL),
	(182, 175, 80, '000-SKU-98789-000001', 10, 1, 1, 10.0000, 23.00, 230.000000, NULL, 2, '2025-04-29 01:52:11', 5, '2025-04-29 01:52:11', 5, 2, 23.00, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL),
	(185, 178, 52, '000-SKU-30508-000001', 10, 1, 1, 10.0000, 1000.00, 10000.000000, NULL, 2, '2025-04-30 00:23:59', 5, '2025-04-30 00:23:59', 5, 2, 1000.00, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL),
	(186, 178, 54, '000-SKU-76494-000001', 15, 1, 1, 15.0000, 1200.00, 18000.000000, NULL, 2, '2025-04-30 00:23:59', 5, '2025-04-30 00:23:59', 5, 2, 1200.00, NULL, NULL, NULL, 1, 1, NULL, NULL, NULL),
	(190, 182, 97, '000-SKU-79960-000001', 3, 1, 1, 3.0000, 43.00, 129.000000, NULL, 2, '2025-05-02 13:25:03', 5, '2025-05-02 13:25:03', 5, 1, 129.00, NULL, NULL, NULL, 0, 0, NULL, NULL, 0),
	(191, 184, 92, '000-SKU-02925-000001', 23, 1, 1, 23.0000, 23.00, 529.000000, NULL, 2, '2025-05-02 13:30:24', 5, '2025-05-02 13:30:24', 5, 1, 529.00, NULL, NULL, NULL, 0, 0, NULL, NULL, 0),
	(201, 195, 56, '000-SKU-60138-000001', 80, 1, NULL, NULL, NULL, NULL, NULL, 2, '2025-05-19 14:58:40', 5, '2025-05-19 14:59:40', 5, NULL, 0.00, '2025-02-09', '2025-05-27', NULL, 1, 1, 80, 'WH11-SKU-60138-250527-000001', 0),
	(202, 195, 56, '000-SKU-60138-000002', 50, 1, NULL, NULL, NULL, NULL, NULL, 2, '2025-05-19 14:58:40', 5, '2025-05-19 14:59:40', 5, NULL, 0.00, '2025-02-09', '2025-05-27', NULL, 1, 1, 50, 'WH11-SKU-60138-250527-000002', 0),
	(203, 195, 56, '000-SKU-60138-000003', 20, 1, NULL, NULL, NULL, NULL, NULL, 2, '2025-05-19 14:58:40', 5, '2025-05-19 14:59:40', 5, NULL, 0.00, '2025-02-09', '2025-05-27', NULL, 1, 1, 69, 'WH11-SKU-60138-250527-000003', 0),
	(204, 195, 56, '000-SKU-60138-000004', 10, 1, NULL, NULL, NULL, NULL, NULL, 2, '2025-05-19 14:58:40', 5, '2025-05-19 14:59:40', 5, NULL, 0.00, '2025-02-09', '2025-05-27', NULL, 1, 1, 10, 'WH11-SKU-60138-250527-000004', 0),
	(205, 195, 58, '000-SKU-17684-000001', 27, 1, NULL, NULL, NULL, NULL, NULL, 2, '2025-05-19 14:58:40', 5, '2025-05-19 14:59:40', 5, NULL, 0.00, '2025-02-27', '2025-06-01', NULL, 1, 1, 26, 'WH11-SKU-17684-250601-000001', 0),
	(206, 195, 101, '000-SKU-64946-000001', 11, 1, NULL, NULL, NULL, NULL, NULL, 2, '2025-05-19 14:58:40', 5, '2025-05-19 14:59:40', 5, NULL, 0.00, '2025-02-27', '2025-04-27', NULL, 1, 1, 10, 'WH11-SKU-64946-250427-000001', 0),
	(207, 195, 90, '000-SKU-77330-000001', 0, 1, NULL, NULL, NULL, NULL, NULL, 2, '2025-05-19 14:58:40', 5, '2025-05-19 14:58:40', 5, NULL, 0.00, NULL, NULL, NULL, 1, 1, 20, 'WH11-SKU-77330-000000-000001', 0),
	(208, 195, 108, '000-IND-NO--000102-000001', 0, 1, NULL, NULL, NULL, NULL, NULL, 2, '2025-05-19 14:58:40', 5, '2025-05-19 14:58:40', 5, NULL, 0.00, NULL, NULL, NULL, 1, 1, 2, 'WH11-IND-NO--000102-000000-000002', 0);

-- Dumping structure for table madarsys.inventory_transaction_subtypes
DROP TABLE IF EXISTS `inventory_transaction_subtypes`;
CREATE TABLE IF NOT EXISTS `inventory_transaction_subtypes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للسجل',
  `transaction_type_id` bigint unsigned NOT NULL COMMENT 'معرّف نوع المعاملة المرتبط',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم نوع المعاملة',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'وصف نوع المعاملة',
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.inventory_transaction_subtypes: ~56 rows (approximately)
DELETE FROM `inventory_transaction_subtypes`;
INSERT INTO `inventory_transaction_subtypes` (`id`, `transaction_type_id`, `name`, `description`, `branch_id`, `created_at`, `created_user`, `updated_at`) VALUES
	(1, 1, 'شراء محلي', NULL, 2, NULL, NULL, NULL),
	(2, 1, 'شراء مستورد', NULL, 2, NULL, NULL, NULL),
	(3, 1, 'شراء نقدي', NULL, 2, NULL, NULL, NULL),
	(4, 1, 'شراء آجل', NULL, 2, NULL, NULL, NULL),
	(5, 1, 'عينة مجانية', NULL, 2, NULL, NULL, NULL),
	(6, 2, 'إرجاع بسبب عيب', NULL, 2, NULL, NULL, NULL),
	(7, 2, 'إرجاع زائد عن الحاجة', NULL, 2, NULL, NULL, NULL),
	(8, 2, 'إرجاع بسبب خطأ في الطلب', NULL, 2, NULL, NULL, NULL),
	(9, 2, 'إرجاع ضمن فترة الضمان', NULL, 2, NULL, NULL, NULL),
	(10, 3, 'منتج تام', NULL, 2, NULL, NULL, NULL),
	(11, 3, 'منتج نصف مصنع', NULL, 2, NULL, NULL, NULL),
	(12, 3, 'منتج تجريبي', NULL, 2, NULL, NULL, NULL),
	(13, 4, 'صرف للإنتاج', NULL, 2, NULL, NULL, NULL),
	(14, 4, 'صرف لأغراض الصيانة', NULL, 2, NULL, NULL, NULL),
	(15, 4, 'صرف للتجربة', NULL, 2, NULL, NULL, NULL),
	(16, 4, 'صرف للتالف', NULL, 2, NULL, NULL, NULL),
	(17, 4, 'صرف للموظفين', NULL, 2, NULL, NULL, NULL),
	(18, 5, 'تحويل بين مستودعات', NULL, 2, NULL, NULL, NULL),
	(19, 5, 'تحويل بين أرفف', NULL, 2, NULL, NULL, NULL),
	(20, 5, 'تحويل للصيانة', NULL, 2, NULL, NULL, NULL),
	(21, 5, 'تحويل لفرع آخر', NULL, 2, NULL, NULL, NULL),
	(22, 5, 'تحويل للتجهيز', NULL, 2, NULL, NULL, NULL),
	(23, 6, 'بسبب تلف', NULL, 2, NULL, NULL, NULL),
	(24, 6, 'بسبب عدم مطابقة', NULL, 2, NULL, NULL, NULL),
	(25, 6, 'بسبب نقص جودة', NULL, 2, NULL, NULL, NULL),
	(26, 6, 'بسبب زيادة في الطلبية', NULL, 2, NULL, NULL, NULL),
	(27, 7, 'بيع نقدي', NULL, 2, NULL, NULL, NULL),
	(28, 7, 'بيع آجل', NULL, 2, NULL, NULL, NULL),
	(29, 7, 'بيع جملة', NULL, 2, NULL, NULL, NULL),
	(30, 7, 'بيع تجزئة', NULL, 2, NULL, NULL, NULL),
	(31, 7, 'عرض ترويجي', NULL, 2, NULL, NULL, NULL),
	(32, 8, 'جرد سنوي', NULL, 2, NULL, NULL, NULL),
	(33, 8, 'جرد دوري', NULL, 2, NULL, NULL, NULL),
	(34, 8, 'جرد مفاجئ', NULL, 2, NULL, NULL, NULL),
	(35, 8, 'جرد عند تغيير مسؤول', NULL, 2, NULL, NULL, NULL),
	(36, 8, 'جرد عند النقل', NULL, 2, NULL, NULL, NULL),
	(37, 9, 'تلف أثناء التخزين', NULL, 2, NULL, NULL, NULL),
	(38, 9, 'تلف أثناء النقل', NULL, 2, NULL, NULL, NULL),
	(39, 9, 'تلف بسبب حرارة', NULL, 2, NULL, NULL, NULL),
	(40, 9, 'تلف بسبب ماء', NULL, 2, NULL, NULL, NULL),
	(41, 9, 'تلف نتيجة إهمال', NULL, 2, NULL, NULL, NULL),
	(42, 10, 'سرقة داخلية', NULL, 2, NULL, NULL, NULL),
	(43, 10, 'سرقة خارجية', NULL, 2, NULL, NULL, NULL),
	(44, 10, 'فقدان غير مبرر', NULL, 2, NULL, NULL, NULL),
	(45, 11, 'تعديل خطأ تسجيل', NULL, 2, NULL, NULL, NULL),
	(46, 11, 'تعديل نتيجة جرد', NULL, 2, NULL, NULL, NULL),
	(47, 11, 'تعديل ناتج عن إرجاع سابق', NULL, 2, NULL, NULL, NULL),
	(48, 12, 'حجز لطلبية', NULL, 2, NULL, NULL, NULL),
	(49, 12, 'حجز لمشروع داخلي', NULL, 2, NULL, NULL, NULL),
	(50, 12, 'حجز مؤقت', NULL, 2, NULL, NULL, NULL),
	(51, 13, 'صلاحية منتهية بالمخزن', NULL, 2, NULL, NULL, NULL),
	(52, 13, 'صلاحية منتهية أثناء النقل', NULL, 2, NULL, NULL, NULL),
	(53, 13, 'صلاحية منتهية أثناء العرض', NULL, 2, NULL, NULL, NULL),
	(54, 14, 'فائض مواد خام', NULL, 2, NULL, NULL, NULL),
	(55, 14, 'مواد غير مطابقة', NULL, 2, NULL, NULL, NULL),
	(56, 14, 'فائض بعد تجريب', NULL, 2, NULL, NULL, NULL);

-- Dumping structure for table madarsys.inventory_update_errors
DROP TABLE IF EXISTS `inventory_update_errors`;
CREATE TABLE IF NOT EXISTS `inventory_update_errors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرف الفريد لخطأ التحديث',
  `inventory_transaction_item_id` bigint unsigned NOT NULL COMMENT 'العنصر المرتبط بالخطأ',
  `product_id` bigint unsigned NOT NULL COMMENT 'المنتج الذي حدث فيه الخطأ',
  `warehouse_id` bigint unsigned NOT NULL COMMENT 'المستودع المرتبط بالخطأ',
  `quantity` int NOT NULL COMMENT 'الكمية التي تسببت في الخطأ',
  `error_message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'وصف الخطأ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='جدول يسجل الأخطاء التي تحدث أثناء تحديث المخزون، مثل نقص الكمية أو مشاكل في البيانات.';

-- Dumping data for table madarsys.inventory_update_errors: ~23 rows (approximately)
DELETE FROM `inventory_update_errors`;
INSERT INTO `inventory_update_errors` (`id`, `inventory_transaction_item_id`, `product_id`, `warehouse_id`, `quantity`, `error_message`, `created_at`, `updated_at`) VALUES
	(1, 364, 98, 1, -2, 'خطأ: الكمية في المخزون لا يمكن أن تكون سالبة.', '2025-03-25 05:29:47', '2025-03-25 05:29:47'),
	(2, 364, 98, 1, -2, 'خطأ: الكمية في المخزون لا يمكن أن تكون سالبة.', '2025-03-25 05:54:07', '2025-03-25 05:54:07'),
	(3, 365, 98, 1, 2, 'A non-numeric value encountered', '2025-03-25 06:13:39', '2025-03-25 06:13:39'),
	(4, 365, 98, 1, 2, 'A non-numeric value encountered', '2025-03-25 06:28:24', '2025-03-25 06:28:24'),
	(5, 365, 98, 1, 2, 'A non-numeric value encountered', '2025-03-25 06:32:08', '2025-03-25 06:32:08'),
	(6, 365, 98, 1, 2, 'A non-numeric value encountered', '2025-03-25 06:33:17', '2025-03-25 06:33:17'),
	(7, 365, 98, 1, 2, 'Undefined variable $total_value', '2025-03-25 06:37:42', '2025-03-25 06:37:42'),
	(8, 365, 98, 1, 2, 'A non-numeric value encountered', '2025-03-25 06:40:56', '2025-03-25 06:40:56'),
	(9, 21, 100, 1, -2, 'خطأ: الكمية في المخزون لا يمكن أن تكون سالبة.', '2025-04-24 04:12:55', '2025-04-24 04:12:55'),
	(10, 21, 100, 1, -2, 'خطأ: الكمية في المخزون لا يمكن أن تكون سالبة.', '2025-04-24 04:13:33', '2025-04-24 04:13:33'),
	(11, 21, 100, 1, -2, 'خطأ: الكمية في المخزون لا يمكن أن تكون سالبة.', '2025-04-24 04:23:35', '2025-04-24 04:23:35'),
	(12, 21, 100, 1, -2, 'خطأ: الكمية في المخزون لا يمكن أن تكون سالبة.', '2025-04-24 04:27:54', '2025-04-24 04:27:54'),
	(13, 21, 100, 1, -3, 'خطأ: لا يمكن إخراج منتج غير موجود في المخزون.', '2025-04-24 04:31:20', '2025-04-24 04:31:20'),
	(14, 21, 100, 1, -2, 'خطأ: الكمية في المخزون لا يمكن أن تكون سالبة.', '2025-04-24 05:09:52', '2025-04-24 05:09:52'),
	(15, 21, 100, 1, -2, 'خطأ: الكمية في المخزون لا يمكن أن تكون سالبة.', '2025-04-24 09:24:17', '2025-04-24 09:24:17'),
	(16, 26, 100, 1, -1, 'خطأ: الكمية في المخزون لا يمكن أن تكون سالبة.', '2025-04-24 12:13:55', '2025-04-24 12:13:55'),
	(17, 185, 52, 2, 5, 'Undefined variable $newQuantityBlocked', '2025-04-30 01:25:10', '2025-04-30 01:25:10'),
	(18, 185, 52, 2, 5, 'Undefined variable $newQuantityBlocked', '2025-04-30 01:25:22', '2025-04-30 01:25:22'),
	(19, 185, 52, 2, 5, 'Undefined variable $newQuantityBlocked', '2025-04-30 01:26:11', '2025-04-30 01:26:11'),
	(20, 185, 52, 2, 5, 'Undefined variable $newQuantityBlocked', '2025-04-30 01:26:36', '2025-04-30 01:26:36'),
	(21, 185, 52, 2, 5, 'Undefined variable $newQuantityBlocked', '2025-04-30 01:37:47', '2025-04-30 01:37:47'),
	(22, 185, 52, 2, 5, 'Undefined variable $newQuantityBlocked', '2025-04-30 01:41:36', '2025-04-30 01:41:36'),
	(23, 185, 52, 2, 5, 'Undefined variable $newQuantityBlocked', '2025-04-30 01:42:14', '2025-04-30 01:42:14');

-- Dumping structure for table madarsys.invoices
DROP TABLE IF EXISTS `invoices`;
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `partner_id` bigint unsigned NOT NULL,
  `payment_type_id` bigint unsigned NOT NULL DEFAULT '1',
  `invoice_date` date NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `check_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `order_id` bigint unsigned DEFAULT NULL,
  `purchase_order_id` bigint unsigned DEFAULT NULL,
  `sales_order_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_partner_id_foreign` (`partner_id`),
  KEY `invoices_payment_type_id_foreign` (`payment_type_id`),
  KEY `invoices_branch_id_foreign` (`branch_id`),
  KEY `invoices_created_user_foreign` (`created_user`),
  KEY `invoices_updated_user_foreign` (`updated_user`),
  KEY `invoices_inventory_transaction_id_foreign` (`inventory_transaction_id`),
  KEY `invoices_order_id_foreign` (`order_id`),
  CONSTRAINT `invoices_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_created_user_foreign` FOREIGN KEY (`created_user`) REFERENCES `users` (`id`),
  CONSTRAINT `invoices_inventory_transaction_id_foreign` FOREIGN KEY (`inventory_transaction_id`) REFERENCES `inventory_transactions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `invoices_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_payment_type_id_foreign` FOREIGN KEY (`payment_type_id`) REFERENCES `payment_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoices_updated_user_foreign` FOREIGN KEY (`updated_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='يخزن بيانات الفواتير مثل الفواتير المبيعات والمشتريات';

-- Dumping data for table madarsys.invoices: ~9 rows (approximately)
DELETE FROM `invoices`;
INSERT INTO `invoices` (`id`, `invoice_code`, `partner_id`, `payment_type_id`, `invoice_date`, `total_amount`, `check_number`, `type`, `inventory_id`, `warehouse_id`, `inventory_transaction_id`, `currency_id`, `exchange_rate`, `department_id`, `branch_id`, `created_user`, `updated_user`, `discount_amount`, `discount_type`, `discount_percentage`, `created_at`, `updated_at`, `order_id`, `purchase_order_id`, `sales_order_id`) VALUES
	(128, 'Sa-Inv-1', 11, 1, '2025-04-27', 1.00, '0', 1, 0, 1, NULL, 1, 1.0000, 0, 2, 5, 5, 0.00, 1, 0.00, '2025-04-27 01:30:52', '2025-04-27 01:30:52', NULL, NULL, NULL),
	(129, 'Sa-Inv-2', 11, 1, '2025-04-27', 1.00, '0', 1, 0, 1, NULL, 1, 1.0000, 0, 2, 5, 5, 0.00, 1, 0.00, '2025-04-27 01:32:39', '2025-04-27 01:32:39', NULL, NULL, NULL),
	(130, 'Pu-Inv-1', 11, 1, '2025-04-27', 11.00, '0', 2, 0, 1, 134, 1, 1.0000, 0, 2, 5, 5, 0.00, 1, 0.00, '2025-04-27 01:44:07', '2025-04-27 01:44:07', NULL, NULL, NULL),
	(131, 'Pu-Inv-2', 20, 1, '2025-04-27', 18500.00, '0', 2, 0, 1, 135, 1, 1.0000, 0, 2, 5, 5, 0.00, 1, 0.00, '2025-04-27 02:09:33', '2025-04-27 02:09:33', NULL, NULL, NULL),
	(132, 'Pu-Inv-3', 1, 1, '2025-04-27', 20000.00, '0', 2, 0, 1, 136, 1, 1.0000, 0, 2, 5, 5, 0.00, 1, 0.00, '2025-04-27 02:18:16', '2025-04-27 02:18:16', NULL, NULL, NULL),
	(133, 'Pu-Inv-4', 27, 1, '2025-04-27', 9000.00, '0', 2, 0, 1, 137, 1, 1.0000, 0, 2, 5, 5, 0.00, 1, 0.00, '2025-04-27 02:30:36', '2025-04-27 02:30:36', NULL, NULL, NULL),
	(137, 'Pu-Inv-5', 6, 1, '2025-04-27', 200000.00, '0', 2, 0, 1, 141, 1, 1.0000, 0, 2, 5, 5, 0.00, 1, 0.00, '2025-04-27 02:54:42', '2025-04-27 02:54:42', NULL, NULL, NULL),
	(138, 'Pu-Inv-6', 13, 1, '2025-04-27', 900.00, '0', 2, 0, 1, 142, 1, 1.0000, 0, 2, 5, 5, 0.00, 1, 0.00, '2025-04-27 02:59:41', '2025-04-27 02:59:41', NULL, NULL, NULL),
	(157, 'Pu-Inv-7', 7, 2, '2025-04-30', 28000.00, NULL, 2, NULL, 2, 178, 1, 1.0000, NULL, 2, 5, 5, 0.00, 1, 0.00, '2025-04-30 00:23:59', '2025-04-30 00:23:59', 29, 27, NULL);

-- Dumping structure for table madarsys.invoice_items
DROP TABLE IF EXISTS `invoice_items`;
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
  `production_date` date DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  KEY `invoice_items_product_id_foreign` (`product_id`),
  KEY `invoice_items_unit_id_foreign` (`unit_id`),
  CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoice_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `invoice_items_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.invoice_items: ~10 rows (approximately)
DELETE FROM `invoice_items`;
INSERT INTO `invoice_items` (`id`, `invoice_id`, `product_id`, `quantity`, `price`, `unit_id`, `subtotal`, `created_at`, `updated_at`, `production_date`, `expiration_date`) VALUES
	(139, 128, 100, 1, 1.00, 1, 1.00, '2025-04-27 01:30:52', '2025-04-27 01:30:52', NULL, NULL),
	(140, 129, 100, 1, 1.00, 1, 1.00, '2025-04-27 01:32:39', '2025-04-27 01:32:39', NULL, NULL),
	(141, 130, 100, 11, 1.00, 1, 11.00, '2025-04-27 01:44:07', '2025-04-27 01:44:07', NULL, NULL),
	(142, 131, 56, 185, 100.00, 1, 18500.00, '2025-04-27 02:09:33', '2025-04-27 02:09:33', '2025-02-09', '2025-05-27'),
	(143, 132, 58, 2, 10000.00, 2, 20000.00, '2025-04-27 02:18:16', '2025-04-27 02:18:16', '2025-02-27', '2025-06-01'),
	(144, 133, 101, 1, 9000.00, 2, 9000.00, '2025-04-27 02:30:36', '2025-04-27 02:30:36', '2025-02-27', '2025-04-27'),
	(148, 137, 90, 2, 100000.00, 2, 200000.00, '2025-04-27 02:54:42', '2025-04-27 02:54:42', NULL, NULL),
	(149, 138, 108, 1, 900.00, 1, 900.00, '2025-04-27 02:59:41', '2025-04-27 02:59:41', NULL, NULL),
	(169, 157, 52, 10, 1000.00, 1, 10000.00, '2025-04-30 00:23:59', '2025-04-30 00:23:59', NULL, NULL),
	(170, 157, 54, 15, 1200.00, 1, 18000.00, '2025-04-30 00:23:59', '2025-04-30 00:23:59', NULL, NULL);

-- Dumping structure for table madarsys.jobs
DROP TABLE IF EXISTS `jobs`;
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

-- Dumping data for table madarsys.jobs: ~0 rows (approximately)
DELETE FROM `jobs`;

-- Dumping structure for table madarsys.job_batches
DROP TABLE IF EXISTS `job_batches`;
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

-- Dumping data for table madarsys.job_batches: ~0 rows (approximately)
DELETE FROM `job_batches`;

-- Dumping structure for table madarsys.manufacturing_countries
DROP TABLE IF EXISTS `manufacturing_countries`;
CREATE TABLE IF NOT EXISTS `manufacturing_countries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم البلد المصنع',
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رمز البلد مثل SA أو EG',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'وصف إضافي للبلد',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ السجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي قام بتحديث السجل',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرف الفرع المرتبط',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `manufacturing_countries_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.manufacturing_countries: ~3 rows (approximately)
DELETE FROM `manufacturing_countries`;
INSERT INTO `manufacturing_countries` (`id`, `name`, `code`, `description`, `created_user`, `updated_user`, `branch_id`, `created_at`, `updated_at`) VALUES
	(1, 'اليمن', 'SA', 'منتج يمني', NULL, NULL, NULL, '2025-04-09 23:14:15', '2025-04-09 23:14:15'),
	(2, 'مصر', 'EG', 'منتج مصري', NULL, NULL, NULL, '2025-04-09 23:14:15', '2025-04-09 23:14:15'),
	(3, 'الصين', 'CN', 'منتج صيني', NULL, NULL, NULL, '2025-04-09 23:14:15', '2025-04-09 23:14:15');

-- Dumping structure for table madarsys.migrations
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.migrations: ~103 rows (approximately)
DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_01_10_030319_create_roles_table', 1),
	(5, '2025_01_10_030923_create_role_user_table', 1),
	(6, '2025_01_18_162854_create_companies_table', 1),
	(7, '2025_01_18_170241_create_branches_table', 1),
	(8, '2025_01_18_170755_create_units_table', 1),
	(9, '2025_01_18_170756_create_categories_table', 1),
	(10, '2025_01_18_170757_create_warehouses_table', 1),
	(11, '2025_01_20_144748_create_role_company_table', 1),
	(12, '2025_01_20_144918_create_role_branch_table', 1),
	(13, '2025_01_20_144953_create_role_warehouse_table', 1),
	(14, '2025_01_22_152022_add_status_to_users_table', 1),
	(15, '2025_01_23_041349_add_is_admin_to_roles_table', 1),
	(16, '2025_01_29_150644_add_status_to_roles_table', 1),
	(17, '2025_01_31_105155_create_modules_table', 1),
	(18, '2025_01_31_105733_create_module_actions_table', 1),
	(19, '2025_01_31_141502_create_permissions_table', 1),
	(20, '2025_01_31_141612_create_role_permissions_table', 1),
	(21, '2025_02_03_095439_create_warehouse_reports_table', 1),
	(22, '2025_02_03_115225_create_zones_table', 1),
	(23, '2025_02_03_115226_create_warehouse_storage_areas_table', 1),
	(24, '2025_02_04_090642_create_warehouse_locations_table', 1),
	(25, '2025_02_05_130258_create_partner_types_table', 1),
	(26, '2025_02_05_201246_create_partners_table', 1),
	(27, '2025_02_05_201258_create_products_table', 2),
	(28, '2025_02_07_142552_create_departments_table', 2),
	(29, '2025_02_07_201428_create_transaction_types_table', 2),
	(30, '2025_02_07_203040_create_inventory_transactions_and_items_tables', 2),
	(31, '2025_02_08_144800_add_branch_id_to_multiple_tables', 2),
	(34, '2025_02_09_164015_create_inventory_requests_tables', 3),
	(35, '2025_02_09_170838_add_inventory_request_id_to_inventory_transactions', 3),
	(36, '2025_02_13_154411_create_settings_table', 4),
	(37, '2025_02_13_213235_add_status_to_warehouses_table', 4),
	(38, '2025_02_15_211002_create_warehouse_categories_table', 4),
	(39, '2025_02_16_175750_update_users_status_default_value', 4),
	(42, '2025_02_09_145841_add_effect_to_transaction_types', 5),
	(43, '2025_02_09_152641_add_effect_to_inventory_transactions', 5),
	(44, '2025_02_18_211537_add_warehouse_fields_to_inventory_tables', 6),
	(45, '2025_02_19_192611_add_inventory_movement_count_to_transaction_types_table', 7),
	(46, '2025_02_20_171204_create_inventory_table', 8),
	(47, '2025_02_21_073214_add_created_user_and_updated_user_to_tables', 9),
	(62, '2025_02_22_180722_alter_transaction_date_type_in_inventory_transactions_table', 10),
	(63, '2025_02_24_115438_add_capacity_and_current_occupancy_to_zones_table', 11),
	(64, '2025_02_25_070337_add_branch_id_to_warehouse_tables', 11),
	(65, '2025_02_27_162118_add_status_to_inventory_transactions', 11),
	(66, '2025_02_27_171131_add_converted_price_to_inventory_transaction_items_table', 11),
	(68, '2025_02_28_203802_create_posts_table', 11),
	(69, '2025_03_01_181622_add_more_fields_to_products_table', 11),
	(70, '2025_03_02_085958_add_brand_to_products_table', 11),
	(71, '2025_03_02_193002_create_inventory_products_table', 11),
	(72, '2025_03_04_065151_create_telescope_entries_table', 11),
	(75, '2025_03_05_214236_create_notifications_table', 12),
	(76, '2025_03_06_190429_add_rack_code_to_warehouse_locations_table', 13),
	(77, '2025_03_03_193751_create_payment_types_table', 14),
	(86, '2025_03_09_213909_add_inventory_transaction_id_and_quantity_to_inventory_products', 15),
	(87, '2025_03_14_084442_add_batch_and_dates_to_inventory_products_table', 16),
	(88, '2025_03_18_005900_add_distribution_type_to_inventory_products_table', 17),
	(89, '2025_03_03_193822_create_invoices_table', 18),
	(90, '2025_03_03_193853_create_invoice_items_table', 18),
	(91, '2025_03_18_185611_create_inventory_update_errors_table', 19),
	(92, '2025_03_17_183939_create_currencies_table', 20),
	(93, '2025_03_17_191143_add_columns_to_invoices_table', 20),
	(94, '2025_03_17_211515_add_unit_id_to_invoice_items_table', 20),
	(103, '2025_03_22_150846_create_inventory_update_errors_table', 23),
	(104, '2025_03_22_150444_add_dates_to_inventory_transaction_items_table', 24),
	(106, '2025_03_22_180710_add_dates_to_inventory_transaction_items_table', 25),
	(107, '2025_03_23_024356_add_batch_code_to_inventory_transaction_items', 25),
	(110, '2025_03_25_081058_add_fields_to_inventory_products_table', 26),
	(111, '2025_03_28_101507_create_inventory_audits_tables', 27),
	(112, '2025_03_31_101157_add_status_to_inventory_transaction_items_table', 27),
	(113, '2025_04_01_202652_add_batch_number_to_inventory_transaction_items_table', 28),
	(114, '2025_03_21_180932_add_inventory_transaction_id_to_invoices_table', 29),
	(115, '2025_04_02_084839_add_reference_item_id_to_inventory_transaction_items_table', 29),
	(119, '2025_04_04_232742_create_inventory_movement_subtypes_table', 31),
	(120, '2025_04_05_152951_add_sub_type_id_to_inventory_transactions_table', 0),
	(121, '2025_03_08_120013_create_orders_table', 32),
	(122, '2025_03_09_132901_create_return_orders_table', 33),
	(123, '2025_03_10_105505_create_return_order_items_table', 33),
	(124, '2025_03_10_201730_create_return_suppliers_table', 33),
	(125, '2025_03_11_102247_create_return_suppliers_orders_table', 33),
	(126, '2025_03_11_102911_create_return_suppliers_order_items_table', 33),
	(127, '2025_03_16_120216_create_order_details_table', 33),
	(128, '2025_03_20_012119_create_shipments_table', 33),
	(130, '2025_04_09_155816_add_details_to_products_table', 34),
	(131, '2025_04_09_162501_add_fields_to_brands_and_manufacturing_countries', 35),
	(132, '2025_04_14_050957_update_invoices_add_dates_and_default_payment_type', 36),
	(133, '2025_04_14_051904_update_users_and_role_user_constraints', 37),
	(134, '2025_04_14_094632_add_permission_types_to_role_permissions_table', 38),
	(135, '2025_04_11_232235_add_purchase_order_fields_to_orders_table', 39),
	(136, '2025_04_11_233808_add_partner_and_purchase_order_fields_to_orders', 39),
	(137, '2025_04_11_233953_add_order_id_to_invoices_table', 39),
	(138, '2025_04_12_123456_add_invoice_from_orders_menu_actions', 39),
	(139, '2025_04_12_create_purchase_orders_table', 40),
	(140, '2025_04_12_create_sales_orders_table', 40),
	(141, '2025_04_13_add_order_relations_to_invoices_table', 40),
	(142, '2025_04_14_184303_create_role_companies_table', 40),
	(143, '2025_04_18_073439_add_unit_id_and_warehouse_id_to_orders_table', 41),
	(144, '2025_04_20_013201_drop_inventory_request_foreign_from_inventory_transactions', 42),
	(145, '2025_04_23_221734_add_item_source_id_to_inventory_products_table', 43),
	(146, '2025_04_25_090115_add_is_printed_to_inventory_products_table', 44),
	(147, '2025_05_04_131729_add_status_and_return_number_to_return_suppliers_orders_table', 45);

-- Dumping structure for table madarsys.modules
DROP TABLE IF EXISTS `modules`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='يخزن معلومات عن الوحدات البرمجية في النظام';

-- Dumping data for table madarsys.modules: ~12 rows (approximately)
DELETE FROM `modules`;
INSERT INTO `modules` (`id`, `name`, `key`, `scope_level`, `created_at`, `created_user`, `updated_at`, `updated_user`, `branch_id`) VALUES
	(1, 'إدارة المستخدمين', 'مستخدمين', 'company', '2025-02-01 00:26:04', NULL, '2025-02-01 00:26:04', NULL, 2),
	(2, 'إدارة الشركات و المستودعات', 'شركاتومستودعات', 'branch', '2025-02-01 00:26:04', NULL, '2025-02-01 00:26:04', NULL, 2),
	(3, 'إدارة المخزون', 'مخزون', 'warehouse', '2025-02-01 00:26:04', NULL, '2025-02-01 00:26:04', NULL, 2),
	(4, 'إدارة الشحنات', 'شحنات', 'branch', '2025-02-01 00:26:04', NULL, '2025-02-01 00:26:04', NULL, 2),
	(5, 'إدارة الفواتير', 'فواتير', 'company', '2025-02-01 00:26:04', NULL, '2025-02-01 00:26:04', NULL, 2),
	(6, 'إدارة الشركاء', 'شركاء', 'company', '2025-02-01 00:26:04', NULL, '2025-02-01 00:26:04', NULL, 2),
	(7, 'إدارة العوائد (المرتجعات)', 'عوائد(مرتجعات)', 'company', '2025-02-01 00:26:04', NULL, '2025-02-01 00:26:04', NULL, 2),
	(8, 'إدارة الطلبات', 'طلبات', 'company', '2025-02-01 00:26:04', NULL, '2025-02-01 00:26:04', NULL, 2),
	(9, 'إدارة المنتجات', 'إدارة المنتجات', 'warehouse', '2025-02-01 00:26:04', NULL, '2025-02-01 00:26:04', NULL, 2),
	(10, 'إنشاء التقارير', 'إنشاءتقارير', 'company', '2025-02-01 00:26:04', NULL, '2025-02-01 00:26:04', NULL, 2),
	(11, 'لوحة تحكم الجرد', 'الجرد', 'branch', '2025-02-01 00:26:04', NULL, '2025-02-01 00:26:04', NULL, 2),
	(12, 'إدارة الأوامر المخزنية', 'أوامر المخزنية', 'branch', '2025-02-01 00:26:04', NULL, '2025-02-01 00:26:04', NULL, 2);

-- Dumping structure for table madarsys.module_actions
DROP TABLE IF EXISTS `module_actions`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='يخزن الإجراءات المتاحة للوحدات البرمجية مثل إضافة أو تعديل أو حذف';

-- Dumping data for table madarsys.module_actions: ~52 rows (approximately)
DELETE FROM `module_actions`;
INSERT INTO `module_actions` (`id`, `module_id`, `name`, `action_key`, `route`, `icon`, `created_at`, `created_user`, `updated_at`, `updated_user`, `branch_id`) VALUES
	(42, 1, 'إدارة بيانات المستخدمين', 'users.index', '/users', 'fa fa-user-edit text-red-500', '2025-02-01 00:30:35', NULL, '2025-02-01 00:30:35', NULL, 2),
	(43, 1, 'إدارة أدوار المستخدمين', 'roles', '/roles', 'fa fa-user-edit text-red-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(44, 1, 'منح الأذونات والصلاحيات', 'permissions', '/role-permissions', 'fa fa-key text-orange-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(45, 1, 'اضافة صلاحيات', 'activity', '/role-permissions/create', 'fa fa-chart-pie text-green-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(46, 2, 'إضافة مستودع جديد', 'create', '/warehouses/create', 'fa fa-plus text-green-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(47, 2, 'قائمة المستودعات', 'warehouses', '/warehouses', 'fa fa-list text-blue-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(48, 2, 'إضافة فرع جديد', 'branches', '/branches/create', 'fa fa-plus text-green-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(49, 2, 'قائمة الفروع', 'branches', '/branches', 'fa fa-list text-blue-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(50, 12, 'إدارة أوامر الشراء المخزنية', 'purchase-orders', '/purchase-orders', 'fa-solid fa-arrow-up-short-wide', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(51, 5, 'انشاء فاتورة شراء من الطلب', 'invoices.create-from-order\n', '/invoices/purchase-orders', 'fa fa-check-circle text-teal-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(52, 2, 'تقارير المستودع', 'reports', '/warehouse-reports', 'fa fa-file-alt text-gray-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(53, 2, 'قائمة الشركات', 'settings', '/companies', 'fa fa-list text-blue-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(54, 3, 'قائمة الحركات المخزنية', 'add', '/inventory/transactions', 'fa fa-edit text-blue-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(55, 3, 'إضافة حركة مخزنية', 'update-quantities', '/inventory/transactions/create', 'fa fa-plus text-green-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(56, 3, 'إدارة المنتجات في المستودعات', 'distribute', '/inventory-products', 'fa fa-arrows-alt text-purple-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(57, 3, 'مراقبة حالة المخزون', 'inventory-review', '/inventory-review', 'fa fa-eye text-teal-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(58, 4, 'استلام الشحنات', 'receive', '/shipments/receive', 'fa fa-inbox text-green-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(59, 4, 'إرسال الشحنات', 'send', '/shipments/send', 'fa fa-paper-plane text-blue-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(60, 4, 'متابعة حالة الشحنات', 'track', '/shipments/track', 'fa fa-map-marker-alt text-red-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(61, 5, 'إنشاء فواتير المبيعات', 'sales', '/invoices/sale', 'fa fa-receipt text-yellow-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(62, 5, 'إنشاء فواتير المشتريات', 'purchases', '/invoices/purchases', 'fa fa-shopping-cart text-green-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(63, 12, 'إدارة أوامر الصرف المخزني', 'purchase-orders', '/sales-orders', 'fa-solid fa-arrow-up-wide-short\r\n', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(64, 6, 'ادارة الشركاء', 'partners\r\n', '/partners', 'fa fa-plus-square text-blue-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(65, 6, 'إضافة شريك جديد', 'track', '/partners/create', 'fa fa-plus text-green-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(66, 1, 'اعدادت عامة', 'validate', '/settings', 'fa fa-check-circle text-red-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(67, 7, 'معالجة المرتجعات', 'process', '/returns/process', 'fa fa-cogs text-yellow-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(68, 7, 'إرسال المرتجعات للموردين', 'supplier', '/returns/supplier', 'fa fa-truck-loading text-purple-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(69, 8, 'إضافة طلب جديد', 'add', '/orders/create', 'fa-solid fa-calculator', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(70, 8, 'قائمة الطلبات', 'order', '/orders', 'fa fa-clipboard-check text-red-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(71, 8, 'تقارير', 'details', '/suppliers/details', 'fa fa-info-circle text-blue-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(72, 9, 'ترميز المنتجات', 'add', '/products', 'fas fa-barcode text-purple-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(73, 9, 'فئات المنتجات', 'add', '/categories', 'fas fa-folder text-blue-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(74, 10, 'تقرير المنتجات المقاربة لإنتهاء الصلاحية', 'inventory', '/reports/expired-products', 'fa fa-clipboard-list text-green-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(75, 10, 'تقرير طلب المنتجات ', 'inventory', '/reports/search-products', 'fa fa-shipping-fast text-blue-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(76, 10, 'تقرير موردي المنتجات', 'inventory', '/reports/search-partners', 'fa fa-file-alt text-orange-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(77, 11, 'سجل عمليات الجرد', 'add', '/inventory/audit', 'fa fa-address-card text-blue-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(78, 11, 'إضافة جرد مخزني جديد', 'add', '/inventory/audit/create', 'fa fa-plus text-green-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(79, 11, 'تقرير جرد مخزني', 'track', '/inventory/audit/report', 'fa fa-boxes text-yellow-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(80, 12, 'التحقق من الطلبات الموكدة', 'representatives.add', '/orders/check-confirmed', 'fa fa-user-plus text-green-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(81, 12, 'أوامر الصرف المعتمدة', 'add', '/invoices/sales-orders', 'fa fa-tasks text-blue-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(82, 12, 'الطلبات المعلقة', 'orders.pending-approval\n', '/orders/pending-approval\n', 'fa fa-comments text-purple-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(83, 1, 'إدارة بيانات المستخدمين', 'users.index', '/users', 'fa fa-user-edit text-red-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(84, 2, 'إضافة شركة جديدة', 'companies.create', '/companies/create', 'fa fa-plus text-green-500', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(85, 2, 'إعدادات النظام', 'settings', '/settings', 'fa-solid fa-gears', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(86, 10, 'تقرير المنتجات المنتهية  الصلاحية ', 'inventory', '/reports/get-expired-products', 'fa-solid fa-paste text-red-500', NULL, NULL, NULL, NULL, NULL),
	(87, 10, 'تقرير الحركات المخزنية', 'inventoty', '/reports/inventory-transactions', 'fa-solid fa-diagram-project', NULL, NULL, NULL, NULL, NULL),
	(88, 10, 'تقرير المنتجات', 'prodacte', '/reports/product-stock', 'fa-solid fa-bars-progress', '2025-03-27 18:24:39', NULL, '2025-03-27 18:24:42', NULL, NULL),
	(89, 9, 'الوحدات', 'add', '/units', 'fa-solid fa-ruler-horizontal', '2025-02-01 00:30:35', NULL, '2025-02-02 00:08:19', NULL, 2),
	(90, 10, 'تقرير الشركاء', 'partners', '/reports/partners', 'fa fa-tasks text-blue-500', '2025-04-12 01:33:08', NULL, '2025-04-12 01:33:25', NULL, 2),
	(91, 9, 'العلامات التجارية', 'add', '/brands', 'fa fa-check-circle text-red-500', '2025-04-14 04:43:27', NULL, '2025-04-14 04:43:28', NULL, 2),
	(92, 9, 'بلد الصنع', 'add', '/manufacturing_countries', 'fa-solid fa-globe', NULL, NULL, NULL, NULL, 2),
	(94, 5, 'انشاء فاتورة بيع من الطلب', 'invoice.sale', '/invoices/sales-orders', 'fa fa-info-circle text-blue-500', NULL, NULL, NULL, NULL, NULL);

-- Dumping structure for table madarsys.notifications
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='يخزن الإشعارات المرسلة للمستخدمين';

-- Dumping data for table madarsys.notifications: ~0 rows (approximately)
DELETE FROM `notifications`;

-- Dumping structure for table madarsys.orders
DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرف الطلب',
  `warehouse_id` bigint unsigned DEFAULT NULL,
  `type` enum('buy','sell') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'نوع الطلب (شراء/بيع)',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرف الفرع المرتبط بالطلب',
  `status` enum('pending','confirmed','completed','canceled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending' COMMENT 'حالة الطلب',
  `payment_type_id` bigint unsigned DEFAULT NULL COMMENT 'معرف نوع الدفع المرتبط بالطلب',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ الطلب',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي قام بتحديث الطلب',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت إنشاء الطلب',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت آخر تحديث للطلب',
  `partner_id` bigint unsigned DEFAULT NULL,
  `purchase_order_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_printed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `orders_branch_id_foreign` (`branch_id`),
  KEY `orders_payment_type_id_foreign` (`payment_type_id`),
  KEY `orders_created_user_foreign` (`created_user`),
  KEY `orders_updated_user_foreign` (`updated_user`),
  KEY `orders_partner_id_foreign` (`partner_id`),
  KEY `orders_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `orders_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_created_user_foreign` FOREIGN KEY (`created_user`) REFERENCES `users` (`id`),
  CONSTRAINT `orders_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_payment_type_id_foreign` FOREIGN KEY (`payment_type_id`) REFERENCES `payment_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_updated_user_foreign` FOREIGN KEY (`updated_user`) REFERENCES `users` (`id`),
  CONSTRAINT `orders_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.orders: ~18 rows (approximately)
DELETE FROM `orders`;
INSERT INTO `orders` (`id`, `warehouse_id`, `type`, `branch_id`, `status`, `payment_type_id`, `created_user`, `updated_user`, `created_at`, `updated_at`, `partner_id`, `purchase_order_number`, `is_printed`) VALUES
	(13, 1, 'buy', 2, 'confirmed', 2, 5, 5, '2025-04-23 14:37:58', '2025-05-04 08:40:41', 11, 'PO-20250504-0031', 1),
	(14, 1, 'buy', 2, 'confirmed', 2, 5, 5, '2025-04-23 15:01:31', '2025-04-23 15:05:28', 11, 'PO-20250424', 0),
	(15, 1, 'sell', 2, 'confirmed', 2, 5, 5, '2025-04-23 18:24:30', '2025-05-04 08:38:24', 11, 'SO-20250423-0001', 1),
	(18, 1, 'buy', 2, 'confirmed', 2, 5, 5, '2025-04-25 10:06:10', '2025-04-27 05:56:51', 1, 'PO-20250427-0021', 0),
	(19, 1, 'sell', 2, 'confirmed', 2, 5, 5, '2025-04-25 10:15:34', '2025-04-25 10:20:48', 21, 'SO-20250425-0009', 0),
	(20, 1, 'buy', 2, 'confirmed', 1, 5, 5, '2025-04-26 03:46:30', '2025-04-27 06:46:11', 7, 'PO-20250427-0022', 0),
	(21, 1, 'buy', 2, 'confirmed', 1, 5, 5, '2025-04-26 03:46:30', '2025-04-26 03:48:17', 7, 'PO-20250426-0017', 0),
	(22, 1, 'sell', 2, 'confirmed', 1, 5, 5, '2025-04-26 04:17:05', '2025-04-26 04:17:37', 12, 'SO-20250426-0010', 0),
	(23, 1, 'buy', 2, 'confirmed', 1, 5, 5, '2025-04-27 05:47:01', '2025-05-04 08:34:54', 1, 'PO-20250504-0030', 0),
	(24, 1, 'buy', 2, 'confirmed', 1, 5, 5, '2025-04-27 05:47:56', '2025-04-27 05:50:37', 14, 'PO-20250427-0019', 0),
	(25, 1, 'buy', 2, 'confirmed', 1, 5, 5, '2025-04-27 05:53:10', '2025-04-28 09:12:08', 1, 'PO-20250428-0025', 0),
	(26, 2, 'buy', 2, 'confirmed', 2, 5, 5, '2025-04-29 01:07:51', '2025-04-29 01:08:21', 11, 'PO-20250429-0026', 0),
	(27, 2, 'buy', 2, 'confirmed', 2, 5, 5, '2025-04-29 18:16:33', '2025-05-04 08:42:43', 11, 'PO-20250504-0032', 1),
	(28, 2, 'sell', 2, 'confirmed', 2, 5, 5, '2025-04-29 23:27:05', '2025-04-29 23:27:35', 11, 'SO-20250430-0011', 0),
	(29, 2, 'buy', 2, 'confirmed', 2, 5, 5, '2025-04-30 00:23:24', '2025-04-30 00:23:41', 7, 'PO-20250430-0027', 0),
	(30, 2, 'sell', 2, 'confirmed', 1, 5, 5, '2025-04-30 01:27:41', '2025-04-30 01:27:56', 20, 'SO-20250430-0012', 0),
	(31, 1, 'sell', 2, 'confirmed', 2, 5, 5, '2025-05-01 15:35:37', '2025-05-01 15:36:14', 13, 'SO-20250501-0013', 0),
	(32, 1, 'sell', 2, 'confirmed', 1, 5, 5, '2025-05-01 16:12:00', '2025-05-04 08:08:50', 12, 'SO-20250504-0017', 0);

-- Dumping structure for table madarsys.order_details
DROP TABLE IF EXISTS `order_details`;
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرف تفاصيل الطلب',
  `order_id` bigint unsigned NOT NULL COMMENT 'معرف الطلب المرتبط بتفاصيل الطلب',
  `product_id` bigint unsigned NOT NULL COMMENT 'معرف المنتج المرتبط بتفاصيل الطلب',
  `quantity` int NOT NULL COMMENT 'الكمية المطلوبة من المنتج',
  `price` decimal(10,2) NOT NULL COMMENT 'سعر الوحدة للمنتج',
  `total_price` decimal(10,2) NOT NULL COMMENT 'السعر الإجمالي لتفاصيل الطلب',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت إنشاء تفاصيل الطلب',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت آخر تحديث لتفاصيل الطلب',
  `unit_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_details_order_id_foreign` (`order_id`),
  KEY `order_details_product_id_foreign` (`product_id`),
  CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.order_details: ~24 rows (approximately)
DELETE FROM `order_details`;
INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`, `total_price`, `created_at`, `updated_at`, `unit_id`) VALUES
	(17, 13, 52, 3, 3.00, 9.00, '2025-04-23 14:37:58', '2025-04-23 14:37:58', 2),
	(18, 14, 86, 9, 9.00, 81.00, '2025-04-23 15:01:31', '2025-04-23 15:01:31', 2),
	(19, 15, 52, 2, 23.00, 46.00, '2025-04-23 18:24:30', '2025-04-23 18:24:30', 2),
	(22, 18, 85, 2, 10000.00, 20000.00, '2025-04-25 10:06:10', '2025-04-25 10:06:10', 2),
	(23, 18, 65, 2, 9000.00, 18000.00, '2025-04-25 10:06:10', '2025-04-25 10:06:10', 2),
	(24, 19, 85, 10, 5000.00, 50000.00, '2025-04-25 10:15:34', '2025-04-25 10:15:34', 1),
	(25, 19, 65, 10, 10000.00, 100000.00, '2025-04-25 10:15:34', '2025-04-25 10:15:34', 1),
	(26, 20, 94, 2, 5000.00, 10000.00, '2025-04-26 03:46:30', '2025-04-26 03:46:30', 1),
	(27, 20, 92, 3, 6000.00, 18000.00, '2025-04-26 03:46:30', '2025-04-26 03:46:30', 1),
	(28, 21, 94, 2, 5000.00, 10000.00, '2025-04-26 03:46:30', '2025-04-26 03:46:30', 1),
	(29, 21, 92, 3, 6000.00, 18000.00, '2025-04-26 03:46:30', '2025-04-26 03:46:30', 1),
	(30, 22, 92, 1, 10000.00, 10000.00, '2025-04-26 04:17:05', '2025-04-26 04:17:05', 1),
	(31, 22, 94, 2, 4000.00, 8000.00, '2025-04-26 04:17:05', '2025-04-26 04:17:05', 1),
	(32, 23, 76, 2, 2000.00, 4000.00, '2025-04-27 05:47:01', '2025-04-27 05:47:01', 1),
	(33, 24, 53, 10, 10000.00, 100000.00, '2025-04-27 05:47:56', '2025-04-27 05:47:56', 5),
	(34, 25, 56, 3, 100.00, 300.00, '2025-04-27 05:53:10', '2025-04-27 05:53:10', 1),
	(35, 26, 80, 10, 23.00, 230.00, '2025-04-29 01:07:51', '2025-04-29 01:07:51', 1),
	(36, 27, 80, 10, 324.00, 3240.00, '2025-04-29 18:16:33', '2025-04-29 18:16:33', 1),
	(37, 28, 80, 10, 90.00, 900.00, '2025-04-29 23:27:05', '2025-04-29 23:27:05', 1),
	(38, 29, 52, 10, 1000.00, 10000.00, '2025-04-30 00:23:24', '2025-04-30 00:23:24', 1),
	(39, 29, 54, 15, 1200.00, 18000.00, '2025-04-30 00:23:24', '2025-04-30 00:23:24', 1),
	(40, 30, 52, 5, 2000.00, 10000.00, '2025-04-30 01:27:41', '2025-04-30 01:27:41', 1),
	(41, 31, 56, 23, 342.00, 7866.00, '2025-05-01 15:35:37', '2025-05-01 15:35:37', 1),
	(42, 32, 58, 5, 345.00, 1725.00, '2025-05-01 16:12:00', '2025-05-01 16:12:00', 1);

-- Dumping structure for table madarsys.partners
DROP TABLE IF EXISTS `partners`;
CREATE TABLE IF NOT EXISTS `partners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرف الشريك',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم الشريك',
  `type` bigint unsigned NOT NULL COMMENT 'نوع الشريك',
  `contact_person` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'اسم الشخص المسؤول للتواصل',
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رقم الهاتف للشريك',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'البريد الإلكتروني للشريك',
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'عنوان الشريك',
  `tax_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رقم الضريبة للشريك',
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.partners: ~31 rows (approximately)
DELETE FROM `partners`;
INSERT INTO `partners` (`id`, `name`, `type`, `contact_person`, `phone`, `email`, `address`, `tax_number`, `is_active`, `created_at`, `created_user`, `updated_at`, `updated_user`, `branch_id`) VALUES
	(1, 'مورد1', 1, 'مورد مسؤول 1', '0123456785682', 'supplier1@example.com', 'عنوان المورد 1', 'SUP9460736', 1, NULL, NULL, '2025-02-22 05:37:20', 5, 2),
	(2, 'مورد 2', 1, 'مورد مسؤول 2', '0123456785873', 'supplier2@example.com', 'عنوان المورد 2', 'SUP4331955', 1, NULL, NULL, NULL, NULL, 2),
	(3, 'مورد 3', 1, 'مورد مسؤول 3', '0123456788058', 'supplier3@example.com', 'عنوان المورد 3', 'SUP3653168', 1, NULL, NULL, NULL, NULL, 2),
	(4, 'مورد 4', 1, 'مورد مسؤول 4', '0123456782659', 'supplier4@example.com', 'عنوان المورد 4', 'SUP9771086', 1, NULL, NULL, NULL, NULL, 2),
	(5, 'مورد 5', 1, 'مورد مسؤول 5', '0123456781148', 'supplier5@example.com', 'عنوان المورد 5', 'SUP7832133', 1, NULL, NULL, NULL, NULL, 2),
	(6, 'مورد 6', 1, 'مورد مسؤول 6', '0123456782116', 'supplier6@example.com', 'عنوان المورد 6', 'SUP1907263', 1, NULL, NULL, NULL, NULL, 2),
	(7, 'مورد 7', 1, 'مورد مسؤول 7', '0123456781003', 'supplier7@example.com', 'عنوان المورد 7', 'SUP2391667', 0, NULL, NULL, '2025-02-26 01:47:30', 5, 2),
	(8, 'مورد 8', 1, 'مورد مسؤول 8', '0123456783645', 'supplier8@example.com', 'عنوان المورد 8', 'SUP3975827', 1, NULL, NULL, NULL, NULL, 2),
	(9, 'مورد 9', 1, 'مورد مسؤول 9', '0123456788377', 'supplier9@example.com', 'عنوان المورد 9', 'SUP6006676', 1, NULL, NULL, NULL, NULL, 2),
	(10, 'مورد 10', 1, 'مورد مسؤول 10', '0123456781754', 'supplier10@example.com', 'عنوان المورد 10', 'SUP2924475', 1, NULL, NULL, NULL, NULL, 2),
	(11, 'عميل 1', 2, 'عميل مسؤول 1', '0112345678318', 'customer1@example.com', 'عنوان العميل 1', 'CUS3149433', 1, NULL, NULL, NULL, NULL, 2),
	(12, 'عميل 2', 2, 'عميل مسؤول 2', '0112345676399', 'customer2@example.com', 'عنوان العميل 2', 'CUS2997984', 1, NULL, NULL, NULL, NULL, 2),
	(13, 'عميل 3', 2, 'عميل مسؤول 3', '0112345676402', 'customer3@example.com', 'عنوان العميل 3', 'CUS8864877', 1, NULL, NULL, NULL, NULL, 2),
	(14, 'عميل 4', 2, 'عميل مسؤول 4', '0112345675978', 'customer4@example.com', 'عنوان العميل 4', 'CUS3330799', 1, NULL, NULL, NULL, NULL, 2),
	(15, 'عميل 5', 2, 'عميل مسؤول 5', '0112345674874', 'customer5@example.com', 'عنوان العميل 5', 'CUS8499011', 1, NULL, NULL, NULL, NULL, 2),
	(16, 'عميل 6', 2, 'عميل مسؤول 6', '0112345678589', 'customer6@example.com', 'عنوان العميل 6', 'CUS6593481', 1, NULL, NULL, NULL, NULL, 2),
	(17, 'عميل 7', 2, 'عميل مسؤول 7', '0112345678992', 'customer7@example.com', 'عنوان العميل 7', 'CUS7421754', 1, NULL, NULL, NULL, NULL, 2),
	(18, 'عميل 8', 2, 'عميل مسؤول 8', '0112345677505', 'customer8@example.com', 'عنوان العميل 8', 'CUS1103420', 1, NULL, NULL, NULL, NULL, 2),
	(19, 'عميل 9', 2, 'عميل مسؤول 9', '0112345674138', 'customer9@example.com', 'عنوان العميل 9', 'CUS3096538', 1, NULL, NULL, NULL, NULL, 2),
	(20, 'عميل 10', 2, 'عميل مسؤول 10', '0112345678712', 'customer10@example.com', 'عنوان العميل 10', 'CUS5449842', 1, NULL, NULL, NULL, NULL, 2),
	(21, 'مندوب 1', 3, 'مندوب مسؤول 1', '0109876544568', 'representative1@example.com', 'عنوان المندوب 1', 'REP1114416', 1, NULL, NULL, NULL, NULL, 2),
	(22, 'مندوب 2', 3, 'مندوب مسؤول 2', '0109876548774', 'representative2@example.com', 'عنوان المندوب 2', 'REP5451567', 1, NULL, NULL, NULL, NULL, 2),
	(23, 'مندوب 3', 3, 'مندوب مسؤول 3', '0109876548410', 'representative3@example.com', 'عنوان المندوب 3', 'REP9896402', 1, NULL, NULL, NULL, NULL, 2),
	(24, 'مندوب 4', 3, 'مندوب مسؤول 4', '0109876543280', 'representative4@example.com', 'عنوان المندوب 4', 'REP1399592', 1, NULL, NULL, NULL, NULL, 2),
	(25, 'مندوب 5', 3, 'مندوب مسؤول 5', '0109876542259', 'representative5@example.com', 'عنوان المندوب 5', 'REP4265734', 1, NULL, NULL, NULL, NULL, 2),
	(26, 'مندوب 6', 3, 'مندوب مسؤول 6', '0109876544517', 'representative6@example.com', 'عنوان المندوب 6', 'REP4893681', 1, NULL, NULL, NULL, NULL, 2),
	(27, 'مندوب 7', 3, 'مندوب مسؤول 7', '0109876544347', 'representative7@example.com', 'عنوان المندوب 7', 'REP2637681', 1, NULL, NULL, NULL, NULL, 2),
	(28, 'مندوب 8', 3, 'مندوب مسؤول 8', '0109876549589', 'representative8@example.com', 'عنوان المندوب 8', 'REP5578223', 1, NULL, NULL, NULL, NULL, 2),
	(29, 'مندوب 9', 3, 'مندوب مسؤول 9', '0109876549424', 'representative9@example.com', 'عنوان المندوب 9', 'REP4228791', 1, NULL, NULL, NULL, NULL, 2),
	(30, 'مندوب 10', 3, 'مندوب مسؤول 10', '0109876542337', 'representative10@example.com', 'عنوان المندوب 10', 'REP2418717', 1, NULL, NULL, NULL, NULL, 2),
	(36, 'ادارة داخلية', 9, 'مدير ادارة', '01897657865', 'dond2@example.com', NULL, NULL, 1, '2025-04-01 06:39:43', 5, '2025-04-01 06:39:48', 5, 2);

-- Dumping structure for table madarsys.partner_types
DROP TABLE IF EXISTS `partner_types`;
CREATE TABLE IF NOT EXISTS `partner_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرف نوع الشريك',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم نوع الشريك',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت آخر تحديث للسجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي قام بتحديث السجل',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرف الفرع المرتبط بنوع الشريك',
  PRIMARY KEY (`id`),
  UNIQUE KEY `partner_types_name_unique` (`name`),
  KEY `partner_types_branch_id_foreign` (`branch_id`),
  CONSTRAINT `partner_types_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.partner_types: ~9 rows (approximately)
DELETE FROM `partner_types`;
INSERT INTO `partner_types` (`id`, `name`, `created_at`, `created_user`, `updated_at`, `updated_user`, `branch_id`) VALUES
	(1, 'مورد', '2025-02-18 11:50:16', NULL, '2025-02-18 11:50:16', NULL, 2),
	(2, 'عميل', '2025-02-18 11:50:16', NULL, '2025-02-18 11:50:16', NULL, 2),
	(3, 'مندوب', '2025-02-18 11:50:16', NULL, '2025-02-18 11:50:16', NULL, 2),
	(4, 'جهة متبرعة', '2025-02-18 11:50:16', NULL, '2025-02-18 11:50:16', NULL, 2),
	(5, 'شريك لوجستي', '2025-02-18 11:50:16', NULL, '2025-02-18 11:50:16', NULL, 2),
	(6, 'ورشة تدوير', '2025-02-18 11:50:16', NULL, '2025-02-18 11:50:16', NULL, 2),
	(7, 'جهة حكومية', '2025-02-18 11:50:16', NULL, '2025-02-18 11:50:16', NULL, 2),
	(8, 'مؤسسة غير ربحية', '2025-02-18 11:50:16', NULL, '2025-02-18 11:50:16', NULL, 2),
	(9, 'الادارة الداخلية', '2025-04-01 02:15:56', NULL, '2025-04-01 02:15:59', NULL, 2);

-- Dumping structure for table madarsys.password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'البريد الإلكتروني للمستخدم الذي طلب إعادة تعيين كلمة المرور',
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رمز التحقق لإعادة تعيين كلمة المرور',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت إنشاء الرمز',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ الرمز',
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.password_reset_tokens: ~0 rows (approximately)
DELETE FROM `password_reset_tokens`;

-- Dumping structure for table madarsys.payment_types
DROP TABLE IF EXISTS `payment_types`;
CREATE TABLE IF NOT EXISTS `payment_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم نوع الدفع',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'رقم المستخدم الذي قام بإضافة هذا السجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'رقم المستخدم الذي قام بآخر تحديث لهذا السجل',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_types_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.payment_types: ~2 rows (approximately)
DELETE FROM `payment_types`;
INSERT INTO `payment_types` (`id`, `name`, `created_user`, `updated_user`, `created_at`, `updated_at`) VALUES
	(1, 'نقدي', 1, 1, '2025-03-09 01:59:06', '2025-03-09 01:59:06'),
	(2, 'اجل', 1, 1, '2025-03-09 01:59:06', '2025-03-09 01:59:06');

-- Dumping structure for table madarsys.permissions
DROP TABLE IF EXISTS `permissions`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.permissions: ~50 rows (approximately)
DELETE FROM `permissions`;
INSERT INTO `permissions` (`id`, `name`, `module_id`, `module_action_id`, `permission_key`, `scope_level`, `created_at`, `created_user`, `updated_at`, `updated_user`, `branch_id`) VALUES
	(31, 'إدارة بيانات المستخدمين', 1, 42, 'مستخدمين.users.index', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(32, 'إدارة أدوار المستخدمين', 1, 43, 'مستخدمين.roles', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(33, 'منح الأذونات والصلاحيات', 1, 44, 'مستخدمين.permissions', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(34, 'مراقبة نشاط المستخدمين', 1, 45, 'مستخدمين.activity', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(35, 'إضافة مستودع جديد', 2, 46, 'شركاتومستودعات.create', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(36, 'قائمة المستودعات', 2, 47, 'شركاتومستودعات.warehouses', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(37, 'إدارة مواقع المستودع', 2, 48, 'شركاتومستودعات.locations', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(38, 'إدارة المناطق التخزينية', 2, 49, 'شركاتومستودعات.storage-areas', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(39, 'إدارة الأمان والتصاريح', 2, 50, 'شركاتومستودعات.security', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(40, 'التدقيق والمراجعة', 2, 51, 'شركاتومستودعات.audit', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(41, 'تقارير المستودع', 2, 52, 'شركاتومستودعات.reports', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(42, 'قائمة الشركات', 2, 53, 'شركاتومستودعات.settings', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(43, 'إضافة شركة جديدة', 2, 84, 'شركاتومستودعات.companies.create', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(44, 'اضافة منتج جديد', 3, 54, 'مخزون.add', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(45, 'حركات مخزنية', 3, 55, 'مخزون.update-quantities', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(46, 'توزيع المخزون', 3, 56, 'مخزون.distribute', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(47, 'مراقبة حالة المخزون', 3, 57, 'مخزون.monitor', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(48, 'فئات المنتجات', 3, 66, 'مخزون.validate', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(49, 'استلام الشحنات', 4, 58, 'شحنات.receive', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(50, 'إدارة أوامر الشراء المخزني', 4, 59, 'شحنات.send', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(51, 'انشاء فاتورة من الطلب', 4, 60, 'انشاء فاتورة من الطلب..invoices.create-from-order\n', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(52, 'إنشاء فواتير المبيعات', 5, 61, 'فواتير.sales', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(53, 'إنشاء فواتير المشتريات', 5, 62, 'فواتير.purchases', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(54, 'إدارة الأوامر الصرف المخزني', 8, 63, 'parchasr-orders', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(55, 'ادارة الشركاء', 6, 64, 'شركاء.create', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(56, 'إضافة شريك جديد', 6, 65, 'شركاء.track', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(57, 'معالجة المرتجعات', 7, 67, 'عوائد(مرتجعات).process', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(58, 'إرسال المرتجعات للموردين', 7, 68, 'عوائد(مرتجعات).supplier', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(59, 'إضافة وتحديث بيانات الموردين', 8, 69, 'موردين.add', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(60, 'متابعة حالة التعامل مع الموردين', 8, 70, 'موردين.track', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(61, 'إدارة تفاصيل الموردين', 8, 71, 'موردين.details', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(62, 'تتبع الكميات في المستودعات والمحلات', 9, 72, 'تتبعكميات.warehouses', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(63, 'تحديث الكميات بناءً على الشحنات والمبيعات', 9, 73, 'تتبعكميات.update', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(64, 'تقرير المنتجات المقاربة لإنتهاء الصلاحية', 10, 74, 'إنشاءتقاريرالمقاربهعلىالانتهاء.inventory', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(65, 'تقرير المنتجات التي وصلت لحد إعادة الطلب', 10, 75, 'إنشاءتقاريرحدالطلب.inventory', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(66, 'تقرير موردي المنتجات', 10, 76, 'إنشاءتقاريرمورديالمنتحات.inventory', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(67, 'إضافة وتحديث بيانات العملاء والمحلات', 11, 77, 'عملاءومحلات.customers-stores.add', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(68, 'إدارة الطلبات من العملاء', 11, 78, 'عملاءومحلات.customers-stores.orders', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(69, 'إدارة المخزون في المحلات', 11, 79, 'عملاءومحلات.customers-stores.inventory', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(70, 'التحقق من الطلبات الموكدة', 12, 80, 'التحقق من الطلبات .orders.check-confirmed', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(71, 'أوامر الصرف المعتمدة', 12, 81, '.invoicessales-ordersاوامرالصرف المعتمدة', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(72, 'الطلبات المعلقة', 12, 82, 'الطلبات المعلقة.orders.pending-approval\n', 'company', '2025-02-18 12:14:29', NULL, '2025-02-18 12:14:29', NULL, NULL),
	(73, 'تقرير المنتجات المنتهية  الصلاحية ', 10, 86, 'انشاءتقاريرالمنتهيات.inventory', 'company', NULL, NULL, NULL, NULL, NULL),
	(74, 'تقرير الحركات المخزنية', 10, 87, 'تقارير.invetory', 'company', '2025-03-27 18:16:27', NULL, NULL, NULL, NULL),
	(75, 'تقرير المنتجات التي وصلت لأعلى حد الطلب', 10, 88, ' تقارير.inventory', 'company', '2025-03-27 18:25:49', NULL, NULL, NULL, NULL),
	(77, 'الوحدات', 9, 89, '', 'company', NULL, NULL, NULL, NULL, NULL),
	(78, 'تقرير الشركاء', 10, 90, 'شركاء.partener', 'company', '2025-04-12 01:36:13', NULL, '2025-04-12 01:36:14', NULL, NULL),
	(80, 'العلامات التجارية', 9, 91, 'منتجات.brand.add', 'company', '2025-04-14 04:55:05', NULL, '2025-04-14 04:55:07', NULL, NULL),
	(81, 'بلدالصنع', 9, 92, 'بلدالصنع.manufacturing_countries.add', 'company', '2025-04-14 05:02:54', NULL, '2025-04-14 05:02:55', NULL, NULL),
	(82, 'انشاء فاتورة بيع من الطلب', 5, 94, 'انشاء فاتورة بيع من الطلب.invoice.seles-orders', 'company', '2025-04-19 00:49:40', NULL, '2025-04-19 00:49:44', NULL, NULL);

-- Dumping structure for table madarsys.posts
DROP TABLE IF EXISTS `posts`;
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

-- Dumping data for table madarsys.posts: ~0 rows (approximately)
DELETE FROM `posts`;

-- Dumping structure for table madarsys.products
DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للمنتج',
  `brand_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف البراند',
  `manufacturing_country_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف بلد الصنع',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم المنتج',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رابط صورة المنتج',
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'وصف تفصيلي للمنتج',
  `ingredients` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'المكونات',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'ملاحظات إضافية',
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.products: ~54 rows (approximately)
DELETE FROM `products`;
INSERT INTO `products` (`id`, `brand_id`, `manufacturing_country_id`, `name`, `image`, `description`, `ingredients`, `notes`, `category_id`, `supplier_id`, `barcode`, `sku`, `purchase_price`, `selling_price`, `stock_quantity`, `min_stock_level`, `max_stock_level`, `unit_id`, `is_active`, `created_at`, `created_user`, `updated_at`, `updated_user`, `branch_id`, `tax`, `discount`, `supplier_contact`, `purchase_date`, `manufacturing_date`, `expiration_date`, `last_updated`) VALUES
	(52, 2, 3, 'بنطال جينز', 'products/pants.jpg', 'Eos modi nesciunt qui.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 2, 24, '9780404752347', 'SKU-30508', 54.88, 24.39, 18, 4, 123, 1, 1, '2025-02-18 21:36:24', NULL, '2025-04-26 04:44:11', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(53, 2, 3, 'تمر مجفف', 'products/UFIIhYw4qMguFJVLMS3elNnOtiwB1EpUVRULAhP2.jpg', 'Ipsam enim culpa earum aut voluptatum qui molestiae.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, NULL, '9784036129119', 'SKU-50707', 74.63, 27.74, 2, 6, 159, 4, 1, '2025-02-18 21:36:24', NULL, '2025-04-21 19:21:57', 5, 2, NULL, NULL, '999', '2025-04-01', NULL, NULL, NULL),
	(54, 2, 3, 'تيشيرت قطني', 'products/t-shirt.jpeg', 'Odit voluptatum sed provident et sed eius.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 2, 21, '9780487833193', 'SKU-76494', 24.40, 80.56, 3, 9, 191, 1, 1, '2025-02-18 21:36:24', NULL, '2025-04-21 19:20:25', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(55, 2, 3, 'حقيبة يد جلدية', 'products/handbag.jpg', 'Enim unde deleniti ex illo quia quia.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 2, 12, '9786852262421', 'SKU-11472', 87.82, 14.58, 75, 2, 135, 1, 1, '2025-02-18 21:36:24', NULL, '2025-04-23 12:40:46', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(56, 2, 3, 'عصير طبيعي', 'products/juice.jpg', 'Est qui non repellendus est voluptatem et.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, 6, '9788826393056', 'SKU-60138', 11.70, 135.11, 19, 10, 181, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(57, 2, 3, 'معطف شتوي', 'products/image3.jpg', 'Rerum qui dicta consequatur eius nihil sint et.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 2, 7, '9791537937457', 'SKU-45675', 83.58, 139.84, 45, 7, 170, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(58, 2, 3, 'شوكولاتة فاخرة', 'products/image2.jpg', 'Beatae maiores doloremque earum provident.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, 13, '9788254873922', 'SKU-17684', 42.41, 65.47, 25, 5, 165, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(59, 2, 3, 'ماوس لاسلكي', 'products/image2.jpg', 'Autem deserunt deleniti tenetur eos reiciendis id voluptatem sapiente.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 1, 21, '9783987410239', 'SKU-25093', 41.45, 25.71, 13, 8, 134, 1, 1, '2025-02-18 21:36:24', NULL, '2025-04-21 20:04:53', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(60, 2, 3, 'مكبر صوت', 'products/image3.jpg', 'Aut dolore et placeat vel quibusdam voluptas.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 5, 12, '9793017350770', 'SKU-09280', 23.65, 103.08, 94, 6, 168, 1, 1, '2025-02-18 21:36:24', NULL, '2025-04-28 08:20:48', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(61, 2, 3, 'عصير طبيعي', 'products/juice.jpg', 'Quibusdam officia neque beatae est corrupti est minima aut.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, 17, '9783790573701', 'SKU-49799', 10.20, 103.44, 52, 9, 155, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(62, 2, 3, 'ماوس لاسلكي', 'products/image12.jpg', 'Eaque inventore modi enim totam ad.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 1, 2, '9797145603456', 'SKU-26366', 39.52, 118.30, 43, 1, 140, 1, 1, '2025-02-18 21:36:24', NULL, '2025-05-04 08:19:47', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(63, 2, 3, 'تيشيرت قطني', 'products/t-shirt.jpeg', 'In et suscipit tempore.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 2, 28, '9792090132785', 'SKU-85967', 44.25, 108.13, 57, 7, 108, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(64, 2, 3, 'معطف شتوي', 'products/image1.jpg', 'Quia dignissimos aut delectus harum quae qui nulla.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 2, 11, '9792000518463', 'SKU-66259', 27.06, 46.06, 80, 10, 189, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(65, 2, 3, 'زيت زيتون نقي', 'products/olive.jpeg', 'Et iure vitae perferendis doloremque tenetur vitae.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, 29, '9798439496921', 'SKU-84873', 85.09, 137.51, 62, 3, 197, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(66, 2, 3, 'عصير طبيعي', 'products/juice.jpg', 'Quaerat aspernatur accusamus dolores similique explicabo accusamus.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, 19, '9799129533018', 'SKU-72990', 48.43, 63.62, 63, 4, 179, 1, 1, '2025-02-18 21:36:24', NULL, '2025-05-04 12:05:29', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(67, 2, 3, 'هاتف ذكي', 'products/image15.jpg', 'Nemo reprehenderit illum quia et repellat.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 1, 2, '9792582107222', 'SKU-62678', 22.61, 97.20, 70, 8, 102, 1, 1, '2025-02-18 21:36:24', NULL, '2025-04-21 20:02:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(68, 2, 3, 'تمر مجفف', 'products/Date.jpg', 'Ut voluptatem eum inventore id unde.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, 9, '9784187634517', 'SKU-05166', 64.32, 87.55, 55, 3, 138, 1, 1, '2025-02-18 21:36:24', NULL, '2025-04-28 05:37:18', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(69, 2, 3, 'مكبر صوت', 'products/image3.jpg', 'Quia aut in molestiae consequatur neque laudantium assumenda.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 5, 9, '9785660836848', 'SKU-40581', 36.38, 118.91, 25, 6, 133, 1, 1, '2025-02-18 21:36:24', NULL, '2025-04-26 04:47:14', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(70, 2, 3, 'مكبر صوت', 'products/image4.jpg', 'Ut autem aut fugiat pariatur magni dolor officia.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 5, 5, '9796261153364', 'SKU-87384', 85.77, 39.76, 75, 7, 157, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(71, 2, 3, 'معطف شتوي', 'products/image11.jpg', 'Cupiditate deleniti rem molestias ut est.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 2, 23, '9782379214578', 'SKU-91971', 76.32, 88.89, 31, 2, 188, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(72, 2, 3, 'كاميرا رقمية', 'products/image11.jpg', 'Quas blanditiis est aperiam delectus et eum modi.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 1, 4, '9787422403589', 'SKU-57884', 44.91, 86.80, 57, 1, 147, 1, 1, '2025-02-18 21:36:24', NULL, '2025-04-26 04:45:14', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(73, 2, 3, 'قهوة عربية', 'products/image11.jpg', 'Voluptatem voluptates laborum tempore magni sed odit.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, 30, '9781784940676', 'SKU-02905', 39.80, 127.95, 75, 1, 110, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(74, 2, 3, 'جهاز لوحي', 'products/computer.jpg', 'Vel tempore ut quas velit totam et dolorum modi.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 1, 9, '9788117170304', 'SKU-92957', 63.48, 51.59, 49, 5, 142, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(75, 2, 3, 'حاسوب محمول', 'products/OsEoTm1Ejc2h7VvBiuT5kAJ5io6PkAMlu5oU4mid.jpg', 'Ut modi sunt ad expedita ipsam nobis et enim.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 1, NULL, '9780136033127', 'SKU-22063', 37.71, 125.16, 19, 10, 181, 1, 1, '2025-02-18 21:36:24', NULL, '2025-04-10 15:19:10', 5, 2, NULL, NULL, '45', '2025-04-03', NULL, NULL, NULL),
	(76, 2, 3, 'تمر مجفف', 'products/Date.jpg', 'Enim omnis ex aperiam culpa ipsa nobis voluptas blanditiis.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, 8, '9780444394279', 'SKU-18723', 39.72, 148.05, 39, 3, 198, 1, 1, '2025-02-18 21:36:24', NULL, '2025-04-28 04:28:56', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(77, 2, 3, 'حاسوب محمول', 'products/computer1.jpg', 'Qui aut nobis atque omnis earum ut consequatur.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 1, 15, '9788214095500', 'SKU-80769', 50.31, 124.52, 15, 2, 120, 1, 1, '2025-02-18 21:36:24', NULL, '2025-05-04 08:19:47', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(78, 2, 3, 'معطف شتوي', 'products/image7.jpg', 'Consectetur distinctio mollitia est sunt voluptatibus sed.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 2, 29, '9799314274207', 'SKU-65072', 17.84, 94.91, 73, 10, 118, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(79, 2, 3, 'عصير طبيعي', 'products/juice.jpg', 'Aliquid dolores enim minima voluptatem at.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, 25, '9799785156873', 'SKU-45241', 34.16, 129.67, 14, 7, 135, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(80, 2, 3, 'حقيبة يد جلدية', 'products/handbag.jpg', 'Et hic quia non repudiandae.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 2, 13, '9790483111096', 'SKU-98789', 13.69, 53.27, 55, 7, 149, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(81, 2, 3, 'تمر مجفف', 'products/Date.jpg', 'Corporis omnis id nisi omnis.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, 28, '9796229278696', 'SKU-79378', 72.96, 139.29, 44, 4, 165, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(82, 2, 3, 'عصير طبيعي', 'products/juice.jpg', 'Rerum molestiae ut et inventore.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, 14, '9796030283131', 'SKU-69177', 90.31, 140.28, 56, 4, 104, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(83, 2, 3, 'ساعة ذكية', 'products/image4.jpg', 'Reiciendis aut architecto nobis et impedit et aliquid consequatur.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 1, 16, '9790191775740', 'SKU-43499', 17.60, 114.15, 81, 8, 192, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(84, 2, 3, 'مكبر صوت', 'products/image10.jpg', 'Ut quo possimus totam ab et hic.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 5, 1, '9792110385849', 'SKU-78864', 29.93, 149.93, 15, 8, 186, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(85, 2, 3, 'قهوة عربية', 'products/image8.jpg', 'Harum fugiat dolor molestiae laborum voluptatem ad quis.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, 6, '9789472709857', 'SKU-34228', 11.86, 35.70, 70, 7, 176, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(86, 2, 3, 'تمر مجفف', 'products/Date.jpg', 'Sint iusto expedita sunt aut unde sint et eveniet.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, 9, '9783371028101', 'SKU-14586', 15.65, 61.76, 19, 4, 180, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(87, 2, 3, 'جهاز لوحي', 'products/computer.jpg', 'Sed voluptates ipsa vitae nobis rerum et aperiam.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 1, 29, '9783474652166', 'SKU-89374', 40.28, 30.38, 52, 6, 198, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(88, 2, 3, 'زيت زيتون نقي', 'products/olive.jpeg', 'Nemo sequi voluptas suscipit similique qui et esse enim.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, 23, '9783145295975', 'SKU-07836', 37.65, 100.19, 49, 7, 189, 1, 1, '2025-02-18 21:36:24', NULL, '2025-05-04 10:27:09', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(89, 2, 3, 'زيت زيتون نقي', 'products/olive.jpeg', 'Velit quidem eligendi nihil aliquam ut culpa necessitatibus sed.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, 22, '9786678688726', 'SKU-96001', 67.69, 94.61, 36, 10, 112, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(90, 2, 3, 'ساعة ذكية', 'products/image1.jpg', 'Dolore cupiditate harum alias enim autem et.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 1, 17, '9780799377750', 'SKU-77330', 39.62, 83.14, 95, 3, 100, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(91, 2, 3, 'زيت زيتون نقي', 'products/olive.jpeg', 'Sunt maxime animi quis.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, 19, '9783950402636', 'SKU-64762', 32.78, 25.13, 42, 6, 136, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(92, 2, 3, 'ساعة ذكية', 'products/image8.jpg', 'Ipsum iusto qui ut qui maxime.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 1, 30, '9799869469721', 'SKU-02925', 29.12, 27.56, 19, 5, 119, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(93, 2, 3, 'معطف شتوي', 'products/image5.jpg', 'Magni ratione est hic et eligendi non non necessitatibus.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 2, 14, '9786750295446', 'SKU-06252', 36.54, 147.51, 28, 2, 134, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(94, 2, 3, 'ماوس لاسلكي', 'products/image9.jpg', 'Voluptas deleniti aliquid et sit odit exercitationem esse.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 1, 1, '9799811517388', 'SKU-65317', 58.20, 31.98, 22, 1, 187, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(95, 2, 3, 'زيت زيتون نقي', 'products/olive.jpeg', 'Rerum et inventore inventore et.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 2, 2, '9792006773965', 'SKU-21410', 40.28, 73.87, 76, 4, 135, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(96, 2, 3, 'شوكولاتة فاخرة', 'products/image2.jpg', 'Sit repudiandae minima ea et consequatur.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, 30, '9783101248052', 'SKU-73068', 44.96, 120.40, 20, 5, 168, 1, 1, '2025-02-18 21:36:24', NULL, '2025-04-23 13:07:58', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(97, 2, 3, 'بنطال جينز', 'products/pants.jpg', 'Esse porro blanditiis deserunt totam qui illum.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 2, 3, '9783185334184', 'SKU-79960', 81.77, 112.02, 50, 4, 118, 1, 1, '2025-02-18 21:36:24', NULL, '2025-04-28 05:10:08', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-09'),
	(98, 2, 3, 'عصير طبيعي', 'products/juice.jpg', 'Quos qui vel culpa.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, 6, '9786366932148', 'SKU-18104', 14.00, 47.10, 99, 4, 146, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(99, 2, 3, 'ساعة ذكية', 'products/image15.jpg', 'Aperiam omnis vel fugiat porro.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 1, 21, '9791457219893', 'SKU-34141', 49.81, 22.01, 92, 3, 162, 1, 1, '2025-02-18 21:36:24', NULL, '2025-02-18 21:36:24', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(100, 2, 3, 'مكبر صوت', 'products/image11.jpg', 'Quod beatae delectus dolorem at repudiandae quia.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 5, 6, '9784108100565', 'SKU-77998', 69.38, 41.29, 100, 5, 173, 1, 1, '2025-02-18 21:36:24', NULL, '2025-04-26 04:43:58', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(101, 2, 3, 'تمر مجفف', 'products/Date.jpg', 'Fugit ipsam nobis eius.', 'مكونات افتراضية: مكون 1، مكون 2، مكون 3', 'تم التحديث تلقائيًا', 4, 19, '9791789838557', 'SKU-64946', 61.36, 37.35, 16, 9, 117, 1, 1, '2025-02-18 21:36:24', NULL, '2025-04-28 04:28:56', NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
	(108, NULL, NULL, 'كاميرا درون', 'products/NaKbJDYoB8tJYXSeDIv3zWgoeaWDe0nYqgWdiqpO.jpg', NULL, NULL, NULL, 5, 4, '87998777', 'IND-NO--000102', 100.00, 500.00, 89, 6, 50, 1, 1, '2025-04-10 12:17:09', 5, '2025-04-10 14:54:07', 5, 2, 9.00, 3.00, '334', '2025-04-08', NULL, NULL, NULL),
	(111, 2, 1, 'ساعه', 'products/qqlFhsBpcje95ZOPsWzltOrlw3ucdgeqAknCFskn.jpg', NULL, NULL, NULL, 1, 3, '11111119999999', 'ELE-{"I-000109', 40.00, 70.00, 6, 5, 100, 1, 1, '2025-04-30 06:07:07', 5, '2025-04-30 06:07:07', 5, 2, NULL, NULL, NULL, '2025-04-30', NULL, NULL, NULL),
	(112, 2, 1, 'aaa', 'products/Z9lOXq3CjZF5Tv6fEFnMx3ZURQ7pMsXGXSiy1cPx.jpg', NULL, NULL, NULL, 1, 3, '33333333333333', 'ELE-BR--000112', 1111.00, 111.00, 3, 3, 8, 3, 1, '2025-05-05 16:13:45', 5, '2025-05-05 16:13:45', 5, 2, NULL, NULL, NULL, '2025-05-05', NULL, NULL, NULL),
	(113, 3, 1, 'jj', NULL, NULL, NULL, NULL, 1, 2, '888888888', 'ELEC-BR-C-000113', 4.00, 5.00, 4, 3, 5, 5, 1, '2025-05-05 16:19:12', 5, '2025-05-05 16:19:12', 5, 2, NULL, NULL, NULL, '2025-05-05', NULL, NULL, NULL);

-- Dumping structure for table madarsys.purchase_orders
DROP TABLE IF EXISTS `purchase_orders`;
CREATE TABLE IF NOT EXISTS `purchase_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `partner_id` bigint unsigned NOT NULL,
  `status` enum('pending','approved','completed','canceled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `issue_date` date NOT NULL,
  `expected_delivery_date` date DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_printed` tinyint(1) NOT NULL DEFAULT '0',
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_orders_order_number_unique` (`order_number`),
  KEY `purchase_orders_order_id_foreign` (`order_id`),
  KEY `purchase_orders_partner_id_foreign` (`partner_id`),
  KEY `purchase_orders_created_user_foreign` (`created_user`),
  KEY `purchase_orders_updated_user_foreign` (`updated_user`),
  CONSTRAINT `purchase_orders_created_user_foreign` FOREIGN KEY (`created_user`) REFERENCES `users` (`id`),
  CONSTRAINT `purchase_orders_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_orders_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_orders_updated_user_foreign` FOREIGN KEY (`updated_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.purchase_orders: ~19 rows (approximately)
DELETE FROM `purchase_orders`;
INSERT INTO `purchase_orders` (`id`, `order_number`, `order_id`, `partner_id`, `status`, `issue_date`, `expected_delivery_date`, `notes`, `is_printed`, `created_user`, `updated_user`, `created_at`, `updated_at`) VALUES
	(12, 'PO-20250423-0001', 13, 11, 'completed', '2025-04-23', '2025-04-30', NULL, 0, 5, 5, '2025-04-23 18:18:59', '2025-04-23 18:19:41'),
	(15, 'PO-20250425-0013', 18, 1, 'completed', '2025-04-25', '2025-05-02', NULL, 0, 5, 5, '2025-04-25 10:08:07', '2025-04-25 10:09:09'),
	(16, 'PO-20250426-0016', 13, 11, 'pending', '2025-04-26', '2025-05-03', NULL, 0, 5, NULL, '2025-04-26 03:27:14', '2025-04-26 03:27:14'),
	(17, 'PO-20250426-0017', 21, 7, 'completed', '2025-04-26', '2025-05-03', NULL, 1, 5, 5, '2025-04-26 03:48:17', '2025-04-26 04:38:53'),
	(18, 'PO-20250427-0018', 13, 11, 'approved', '2025-04-27', '2025-05-04', NULL, 0, 5, 5, '2025-04-27 05:48:35', '2025-04-27 05:48:39'),
	(19, 'PO-20250427-0019', 24, 14, 'approved', '2025-04-27', '2025-05-04', NULL, 0, 5, 5, '2025-04-27 05:50:37', '2025-04-27 05:50:39'),
	(20, 'PO-20250427-0020', 25, 1, 'approved', '2025-04-27', '2025-05-04', NULL, 0, 5, 5, '2025-04-27 05:53:26', '2025-04-27 05:53:30'),
	(21, 'PO-20250427-0021', 18, 1, 'approved', '2025-04-27', '2025-05-04', NULL, 1, 5, 5, '2025-04-27 05:56:51', '2025-04-27 06:02:09'),
	(22, 'PO-20250427-0022', 20, 7, 'pending', '2025-04-27', '2025-05-04', NULL, 1, 5, NULL, '2025-04-27 06:46:11', '2025-04-27 06:46:16'),
	(23, 'PO-20250427-0023', 13, 11, 'approved', '2025-04-27', '2025-05-04', NULL, 1, 5, 5, '2025-04-27 07:17:12', '2025-04-27 07:18:14'),
	(24, 'PO-20250427-0024', 13, 11, 'approved', '2025-04-27', '2025-05-04', NULL, 0, 5, 5, '2025-04-27 07:21:54', '2025-04-27 07:22:26'),
	(25, 'PO-20250428-0025', 25, 1, 'approved', '2025-04-28', '2025-05-05', NULL, 0, 5, 5, '2025-04-28 09:12:08', '2025-04-28 09:12:11'),
	(26, 'PO-20250429-0026', 26, 11, 'completed', '2025-04-29', '2025-05-06', NULL, 1, 5, 5, '2025-04-29 01:08:21', '2025-04-30 00:15:40'),
	(27, 'PO-20250430-0027', 29, 7, 'completed', '2025-04-30', '2025-05-07', NULL, 0, 5, 5, '2025-04-30 00:23:41', '2025-04-30 00:23:59'),
	(28, 'PO-20250430-0028', 13, 11, 'approved', '2025-04-30', '2025-05-07', NULL, 1, 5, 5, '2025-04-30 05:48:37', '2025-04-30 06:28:10'),
	(29, 'PO-20250504-0029', 13, 11, 'pending', '2025-05-04', '2025-05-11', NULL, 0, 5, NULL, '2025-05-04 08:34:01', '2025-05-04 08:34:01'),
	(30, 'PO-20250504-0030', 23, 1, 'pending', '2025-05-04', '2025-05-11', NULL, 1, 5, NULL, '2025-05-04 08:34:54', '2025-05-04 08:36:01'),
	(31, 'PO-20250504-0031', 13, 11, 'pending', '2025-05-04', '2025-05-11', NULL, 1, 5, NULL, '2025-05-04 08:40:41', '2025-05-04 08:40:42'),
	(32, 'PO-20250504-0032', 27, 11, 'pending', '2025-05-04', '2025-05-11', NULL, 1, 5, NULL, '2025-05-04 08:42:30', '2025-05-04 08:45:43');

-- Dumping structure for table madarsys.request_types
DROP TABLE IF EXISTS `request_types`;
CREATE TABLE IF NOT EXISTS `request_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم نوع الطلب',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'وصف لنوع الطلب',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `request_types_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.request_types: ~6 rows (approximately)
DELETE FROM `request_types`;
INSERT INTO `request_types` (`id`, `name`, `description`, `created_at`, `created_user`, `updated_at`, `updated_user`) VALUES
	(1, 'طلب توريد', 'طلب توريد من الموردين', NULL, NULL, NULL, NULL),
	(2, 'طلب صرف', 'طلب صرف داخلي للأقسام', NULL, NULL, NULL, NULL),
	(3, 'طلب ارجاع', 'طلب إرجاع المنتجات', NULL, NULL, NULL, NULL),
	(4, 'طلب اصلاح', 'طلب إصلاح المنتجات', NULL, NULL, NULL, NULL),
	(5, 'طلب اعارة', 'طلب إعارة المنتجات', NULL, NULL, NULL, NULL),
	(6, 'طلب تحويل', 'طلب تحويل المخزون بين المستودعات', NULL, NULL, NULL, NULL);

-- Dumping structure for table madarsys.return_orders
DROP TABLE IF EXISTS `return_orders`;
CREATE TABLE IF NOT EXISTS `return_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد لسجل عملية الإرجاع',
  `return_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رقم عملية الإرجاع',
  `customer_id` bigint unsigned NOT NULL COMMENT 'معرّف العميل الذي قام بعملية الإرجاع',
  `return_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'سبب الإرجاع من قِبل العميل',
  `return_date` date NOT NULL COMMENT 'تاريخ تنفيذ عملية الإرجاع',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  `status` enum('معلق','مكتمل','ملغي') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `return_orders_return_number_unique` (`return_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.return_orders: ~4 rows (approximately)
DELETE FROM `return_orders`;
INSERT INTO `return_orders` (`id`, `return_number`, `customer_id`, `return_reason`, `return_date`, `created_at`, `updated_at`, `status`) VALUES
	(9, 'RET-1745829438', 20, 'تالف', '2025-04-28', '2025-04-28 05:37:18', '2025-05-04 08:23:14', 'ملغي'),
	(10, 'RET-1746357587', 14, 'غير مناسب', '2025-05-04', '2025-05-04 08:19:47', '2025-05-04 08:21:56', 'مكتمل'),
	(11, 'RET-1746357626', 15, 'منتهي', '2025-05-04', '2025-05-04 08:20:26', '2025-05-04 08:20:26', 'معلق'),
	(13, 'RET-1746371129', 19, 'تالف', '2025-05-04', '2025-05-04 12:05:29', '2025-05-04 12:05:29', 'معلق');

-- Dumping structure for table madarsys.return_order_items
DROP TABLE IF EXISTS `return_order_items`;
CREATE TABLE IF NOT EXISTS `return_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد لكل عنصر إرجاع',
  `return_order_id` bigint unsigned NOT NULL COMMENT 'معرّف الإرجاع المرتبط بهذه العنصر',
  `product_id` bigint unsigned NOT NULL COMMENT 'معرّف المنتج الذي تم إرجاعه',
  `quantity` int NOT NULL COMMENT 'الكمية التي تم إرجاعها من المنتج',
  `status` enum('قبول الإرجاع','إرجاع للمخزون','إرسال للصيانة','تصنيف كمنتج تالف','منتهي الصلاحية') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'تصنيف كمنتج تالف' COMMENT 'الحالة التي تم اتخاذها بعد الإرجاع',
  `Is_Send` int NOT NULL DEFAULT '0' COMMENT 'هل تم إرسال المنتج لاتخاذ الإجراء المناسب (1 نعم / 0 لا)',
  `return_number` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `return_order_items_return_order_id_foreign` (`return_order_id`),
  KEY `return_order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `return_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `return_order_items_return_order_id_foreign` FOREIGN KEY (`return_order_id`) REFERENCES `return_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.return_order_items: ~5 rows (approximately)
DELETE FROM `return_order_items`;
INSERT INTO `return_order_items` (`id`, `return_order_id`, `product_id`, `quantity`, `status`, `Is_Send`, `return_number`) VALUES
	(9, 9, 68, 1, 'تصنيف كمنتج تالف', 0, 0),
	(10, 10, 77, 1, 'تصنيف كمنتج تالف', 0, 0),
	(11, 10, 62, 1, 'تصنيف كمنتج تالف', 0, 0),
	(12, 11, 66, 1, 'تصنيف كمنتج تالف', 0, 0),
	(14, 13, 66, 1, 'تصنيف كمنتج تالف', 0, 0);

-- Dumping structure for table madarsys.return_suppliers
DROP TABLE IF EXISTS `return_suppliers`;
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

-- Dumping data for table madarsys.return_suppliers: ~0 rows (approximately)
DELETE FROM `return_suppliers`;

-- Dumping structure for table madarsys.return_suppliers_orders
DROP TABLE IF EXISTS `return_suppliers_orders`;
CREATE TABLE IF NOT EXISTS `return_suppliers_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `status` enum('قيد المراجعة','قيد التوصيل','تم الاستلام') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'قيد المراجعة',
  `return_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_id` bigint unsigned NOT NULL,
  `return_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `return_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `return_suppliers_orders_supplier_id_foreign` (`supplier_id`),
  CONSTRAINT `return_suppliers_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.return_suppliers_orders: ~3 rows (approximately)
DELETE FROM `return_suppliers_orders`;
INSERT INTO `return_suppliers_orders` (`id`, `status`, `return_number`, `supplier_id`, `return_reason`, `return_date`, `created_at`, `updated_at`) VALUES
	(5, 'قيد المراجعة', NULL, 1, 'منتهي', '2025-04-23', '2025-04-23 13:07:58', '2025-04-23 13:07:58'),
	(6, 'قيد المراجعة', NULL, 5, 'تالف', '2025-04-28', '2025-04-28 04:28:56', '2025-04-28 04:28:56'),
	(7, 'قيد المراجعة', 'SRET-1746365229', 3, 'تالف', '2025-05-04', '2025-05-04 10:27:09', '2025-05-04 10:27:09');

-- Dumping structure for table madarsys.return_suppliers_order_items
DROP TABLE IF EXISTS `return_suppliers_order_items`;
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

-- Dumping data for table madarsys.return_suppliers_order_items: ~4 rows (approximately)
DELETE FROM `return_suppliers_order_items`;
INSERT INTO `return_suppliers_order_items` (`id`, `return_supplier_order_id`, `product_id`, `quantity`, `status`, `created_at`, `updated_at`) VALUES
	(5, 5, 96, 1, 'قيد المراجعة', '2025-04-23 13:07:58', '2025-04-23 13:07:58'),
	(6, 6, 76, 1, 'قيد المراجعة', '2025-04-28 04:28:56', '2025-04-28 04:28:56'),
	(7, 6, 101, 1, 'قيد المراجعة', '2025-04-28 04:28:56', '2025-04-28 04:28:56'),
	(8, 7, 88, 1, 'قيد المراجعة', '2025-05-04 10:27:09', '2025-05-04 10:27:09');

-- Dumping structure for table madarsys.roles
DROP TABLE IF EXISTS `roles`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.roles: ~6 rows (approximately)
DELETE FROM `roles`;
INSERT INTO `roles` (`id`, `name`, `status`, `description`, `created_at`, `created_user`, `updated_at`, `updated_user`, `is_admin`) VALUES
	(1, 'مدير النظام', 1, NULL, '2025-02-18 01:19:41', NULL, '2025-02-18 01:19:41', NULL, 0),
	(2, 'مدير شركة', 1, NULL, '2025-02-18 01:19:41', NULL, '2025-02-18 01:19:41', NULL, 0),
	(3, 'مشرف فرع', 1, NULL, '2025-02-18 01:19:41', NULL, '2025-02-18 01:19:41', NULL, 0),
	(4, 'مسؤول مستودع', 1, NULL, '2025-02-18 01:19:41', NULL, '2025-02-18 01:19:41', NULL, 0),
	(5, 'موظف شحن', 1, NULL, '2025-02-18 01:19:41', NULL, '2025-02-18 01:19:41', NULL, 0),
	(9, 'مراقب مخزون', 1, NULL, '2025-03-10 01:04:56', NULL, '2025-03-10 01:07:11', NULL, 0);

-- Dumping structure for table madarsys.role_branch
DROP TABLE IF EXISTS `role_branch`;
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

-- Dumping data for table madarsys.role_branch: ~5 rows (approximately)
DELETE FROM `role_branch`;
INSERT INTO `role_branch` (`role_id`, `branch_id`, `company_id`, `created_at`, `created_user`, `updated_at`, `updated_user`) VALUES
	(1, 2, 1, NULL, NULL, NULL, NULL),
	(2, 2, 1, NULL, NULL, NULL, NULL),
	(9, 2, 1, '2025-04-15 01:44:40', NULL, '2025-04-15 01:44:40', NULL),
	(5, 2, 1, '2025-04-15 04:50:47', NULL, '2025-04-15 04:50:47', NULL),
	(4, 2, 1, '2025-04-15 05:06:03', NULL, '2025-04-15 05:06:03', NULL);

-- Dumping structure for table madarsys.role_companies
DROP TABLE IF EXISTS `role_companies`;
CREATE TABLE IF NOT EXISTS `role_companies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.role_companies: ~0 rows (approximately)
DELETE FROM `role_companies`;

-- Dumping structure for table madarsys.role_company
DROP TABLE IF EXISTS `role_company`;
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

-- Dumping data for table madarsys.role_company: ~5 rows (approximately)
DELETE FROM `role_company`;
INSERT INTO `role_company` (`role_id`, `company_id`, `created_at`, `created_user`, `updated_at`, `updated_user`) VALUES
	(1, 1, NULL, NULL, NULL, NULL),
	(2, 1, NULL, NULL, NULL, NULL),
	(9, 1, '2025-04-15 01:44:40', NULL, '2025-04-15 01:44:40', NULL),
	(5, 1, '2025-04-15 04:50:47', NULL, '2025-04-15 04:50:47', NULL),
	(4, 1, '2025-04-15 05:06:03', NULL, '2025-04-15 05:06:03', NULL);

-- Dumping structure for table madarsys.role_permissions
DROP TABLE IF EXISTS `role_permissions`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.role_permissions: ~153 rows (approximately)
DELETE FROM `role_permissions`;
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`, `can_view`, `can_create`, `can_update`, `can_delete`, `status`, `status_updated_at`, `created_at`, `created_user`, `updated_at`, `updated_user`) VALUES
	(1350, 5, 49, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:01:50', NULL, '2025-04-15 05:01:50', NULL),
	(1351, 5, 50, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:01:50', NULL, '2025-04-15 05:01:50', NULL),
	(1352, 5, 51, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:01:50', NULL, '2025-04-15 05:01:50', NULL),
	(1353, 5, 62, 1, 0, 0, 0, 1, NULL, '2025-04-15 05:01:50', NULL, '2025-04-15 05:01:50', NULL),
	(1354, 5, 63, 1, 0, 0, 0, 1, NULL, '2025-04-15 05:01:50', NULL, '2025-04-15 05:01:50', NULL),
	(1466, 4, 35, 1, 0, 1, 0, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1467, 4, 36, 1, 0, 1, 0, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1468, 4, 44, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1469, 4, 45, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1470, 4, 46, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1471, 4, 47, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1472, 4, 49, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1473, 4, 50, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1474, 4, 51, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1475, 4, 52, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1476, 4, 53, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1477, 4, 57, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1478, 4, 58, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1479, 4, 59, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1480, 4, 60, 1, 1, 0, 1, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1481, 4, 62, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1482, 4, 63, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1483, 4, 64, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1484, 4, 65, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1485, 4, 66, 1, 1, 1, 1, 1, NULL, '2025-04-15 05:09:52', NULL, '2025-04-15 05:09:52', NULL),
	(1486, 9, 36, 1, 0, 0, 0, 1, NULL, '2025-04-16 07:29:00', NULL, '2025-04-16 07:29:00', NULL),
	(1487, 9, 38, 1, 0, 0, 0, 1, NULL, '2025-04-16 07:29:00', NULL, '2025-04-16 07:29:00', NULL),
	(1488, 9, 44, 1, 1, 1, 0, 1, NULL, '2025-04-16 07:29:00', NULL, '2025-04-16 07:29:00', NULL),
	(1489, 9, 45, 1, 1, 1, 1, 1, NULL, '2025-04-16 07:29:00', NULL, '2025-04-16 07:29:00', NULL),
	(1490, 9, 46, 1, 1, 1, 1, 1, NULL, '2025-04-16 07:29:00', NULL, '2025-04-16 07:29:00', NULL),
	(1491, 9, 59, 1, 1, 1, 1, 1, NULL, '2025-04-16 07:29:00', NULL, '2025-04-16 07:29:00', NULL),
	(1492, 9, 60, 1, 1, 1, 0, 1, NULL, '2025-04-16 07:29:00', NULL, '2025-04-16 07:29:00', NULL),
	(1493, 9, 62, 1, 1, 1, 0, 1, NULL, '2025-04-16 07:29:00', NULL, '2025-04-16 07:29:00', NULL),
	(1494, 9, 63, 1, 0, 0, 0, 1, NULL, '2025-04-16 07:29:00', NULL, '2025-04-16 07:29:00', NULL),
	(1495, 9, 64, 1, 1, 0, 0, 1, NULL, '2025-04-16 07:29:00', NULL, '2025-04-16 07:29:00', NULL),
	(1496, 9, 65, 1, 1, 0, 0, 1, NULL, '2025-04-16 07:29:00', NULL, '2025-04-16 07:29:00', NULL),
	(1497, 9, 66, 1, 1, 0, 0, 1, NULL, '2025-04-16 07:29:00', NULL, '2025-04-16 07:29:00', NULL),
	(1498, 9, 67, 1, 1, 1, 1, 1, NULL, '2025-04-16 07:29:00', NULL, '2025-04-16 07:29:00', NULL),
	(1499, 9, 68, 1, 1, 1, 1, 1, NULL, '2025-04-16 07:29:00', NULL, '2025-04-16 07:29:00', NULL),
	(1500, 9, 69, 1, 1, 1, 1, 1, NULL, '2025-04-16 07:29:00', NULL, '2025-04-16 07:29:00', NULL),
	(1501, 9, 75, 1, 1, 1, 1, 1, NULL, '2025-04-16 07:29:00', NULL, '2025-04-16 07:29:00', NULL),
	(1502, 9, 80, 1, 0, 0, 0, 1, NULL, '2025-04-16 07:29:00', NULL, '2025-04-16 07:29:00', NULL),
	(1503, 9, 81, 1, 0, 0, 0, 1, NULL, '2025-04-16 07:29:00', NULL, '2025-04-16 07:29:00', NULL),
	(2191, 1, 31, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2192, 1, 32, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2193, 1, 33, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2194, 1, 34, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2195, 1, 35, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2196, 1, 36, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2197, 1, 37, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2198, 1, 38, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2199, 1, 39, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2200, 1, 40, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2201, 1, 42, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2202, 1, 43, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2203, 1, 44, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2204, 1, 45, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2205, 1, 46, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2206, 1, 47, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2207, 1, 48, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2208, 1, 49, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2209, 1, 50, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2210, 1, 51, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2211, 1, 52, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2212, 1, 53, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2213, 1, 54, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2214, 1, 55, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2215, 1, 56, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2216, 1, 57, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2217, 1, 58, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2218, 1, 59, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2219, 1, 60, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2220, 1, 62, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2221, 1, 63, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2222, 1, 64, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2223, 1, 65, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2224, 1, 66, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2225, 1, 67, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2226, 1, 68, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2227, 1, 70, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2228, 1, 71, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2229, 1, 72, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2230, 1, 73, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2231, 1, 74, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2232, 1, 75, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2233, 1, 77, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2234, 1, 78, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2235, 1, 80, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2236, 1, 81, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2237, 1, 82, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:05:16', NULL, '2025-04-29 16:05:16', NULL),
	(2238, 2, 35, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2239, 2, 36, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2240, 2, 37, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2241, 2, 38, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2242, 2, 39, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2243, 2, 40, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2244, 2, 42, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2245, 2, 43, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2246, 2, 44, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2247, 2, 45, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2248, 2, 46, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2249, 2, 47, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2250, 2, 48, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2251, 2, 49, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2252, 2, 50, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2253, 2, 51, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2254, 2, 52, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2255, 2, 53, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2256, 2, 54, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2257, 2, 55, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2258, 2, 56, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2259, 2, 57, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2260, 2, 58, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2261, 2, 59, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2262, 2, 60, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2263, 2, 61, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2264, 2, 62, 1, 1, 1, 0, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2265, 2, 63, 1, 1, 1, 0, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2266, 2, 64, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2267, 2, 65, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2268, 2, 66, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2269, 2, 67, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2270, 2, 68, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2271, 2, 73, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2272, 2, 74, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2273, 2, 75, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2274, 2, 77, 1, 0, 0, 0, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2275, 2, 78, 1, 1, 0, 0, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2276, 2, 80, 1, 1, 0, 0, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2277, 2, 81, 1, 1, 0, 0, 1, NULL, '2025-04-29 16:06:14', NULL, '2025-04-29 16:06:14', NULL),
	(2278, 3, 36, 1, 0, 1, 0, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2279, 3, 37, 1, 0, 1, 0, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2280, 3, 38, 1, 1, 1, 0, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2281, 3, 42, 1, 0, 0, 0, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2282, 3, 44, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2283, 3, 45, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2284, 3, 46, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2285, 3, 47, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2286, 3, 48, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2287, 3, 49, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2288, 3, 50, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2289, 3, 51, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2290, 3, 52, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2291, 3, 53, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2292, 3, 57, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2293, 3, 58, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2294, 3, 59, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2295, 3, 60, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2296, 3, 62, 1, 0, 1, 0, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2297, 3, 63, 1, 0, 1, 0, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2298, 3, 64, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2299, 3, 65, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL),
	(2300, 3, 66, 1, 1, 1, 1, 1, NULL, '2025-04-29 16:06:37', NULL, '2025-04-29 16:06:37', NULL);

-- Dumping structure for table madarsys.role_user
DROP TABLE IF EXISTS `role_user`;
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

-- Dumping data for table madarsys.role_user: ~7 rows (approximately)
DELETE FROM `role_user`;
INSERT INTO `role_user` (`user_id`, `role_id`, `created_at`, `created_user`, `updated_at`, `updated_user`) VALUES
	(5, 1, NULL, NULL, NULL, NULL),
	(6, 2, NULL, NULL, NULL, NULL),
	(7, 3, NULL, NULL, NULL, NULL),
	(12, 9, '2025-04-14 12:58:18', NULL, '2025-04-14 12:58:18', NULL),
	(14, 5, '2025-04-15 04:58:26', NULL, '2025-04-15 04:58:26', NULL),
	(15, 4, '2025-04-15 05:05:48', NULL, '2025-04-15 05:05:48', NULL),
	(16, 3, '2025-04-18 23:28:37', NULL, '2025-04-18 23:28:37', NULL);

-- Dumping structure for table madarsys.role_warehouse
DROP TABLE IF EXISTS `role_warehouse`;
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

-- Dumping data for table madarsys.role_warehouse: ~7 rows (approximately)
DELETE FROM `role_warehouse`;
INSERT INTO `role_warehouse` (`role_id`, `warehouse_id`, `branch_id`, `created_at`, `created_user`, `updated_at`, `updated_user`) VALUES
	(2, 1, 2, '2025-02-21 11:18:03', NULL, '2025-02-21 11:18:03', NULL),
	(2, 3, 2, '2025-02-21 11:18:18', NULL, '2025-02-21 11:18:18', NULL),
	(1, 1, 2, NULL, NULL, NULL, NULL),
	(9, 1, 2, '2025-04-15 01:44:40', 5, '2025-04-15 01:44:40', 5),
	(5, 1, 2, '2025-04-15 04:50:47', 5, '2025-04-15 04:50:47', 5),
	(4, 1, 2, '2025-04-15 05:06:03', 5, '2025-04-15 05:06:03', 5),
	(1, 2, 2, '2025-04-29 00:54:35', 5, '2025-04-29 00:54:35', 5);

-- Dumping structure for table madarsys.sales_orders
DROP TABLE IF EXISTS `sales_orders`;
CREATE TABLE IF NOT EXISTS `sales_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `partner_id` bigint unsigned NOT NULL,
  `status` enum('pending','approved','completed','canceled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `issue_date` date NOT NULL,
  `expected_delivery_date` date DEFAULT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_printed` tinyint(1) NOT NULL DEFAULT '0',
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_orders_order_number_unique` (`order_number`),
  KEY `sales_orders_order_id_foreign` (`order_id`),
  KEY `sales_orders_partner_id_foreign` (`partner_id`),
  KEY `sales_orders_created_user_foreign` (`created_user`),
  KEY `sales_orders_updated_user_foreign` (`updated_user`),
  CONSTRAINT `sales_orders_created_user_foreign` FOREIGN KEY (`created_user`) REFERENCES `users` (`id`),
  CONSTRAINT `sales_orders_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sales_orders_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sales_orders_updated_user_foreign` FOREIGN KEY (`updated_user`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.sales_orders: ~12 rows (approximately)
DELETE FROM `sales_orders`;
INSERT INTO `sales_orders` (`id`, `order_number`, `order_id`, `partner_id`, `status`, `issue_date`, `expected_delivery_date`, `notes`, `is_printed`, `created_user`, `updated_user`, `created_at`, `updated_at`) VALUES
	(6, 'SO-20250423-0001', 15, 11, 'completed', '2025-04-23', '2025-04-30', NULL, 1, 5, 5, '2025-04-23 18:24:55', '2025-04-23 18:25:37'),
	(7, 'SO-20250425-0007', 19, 21, 'completed', '2025-04-25', '2025-05-02', NULL, 0, 5, 5, '2025-04-25 10:15:52', '2025-04-25 10:16:11'),
	(8, 'SO-20250425-0008', 19, 21, 'completed', '2025-04-25', '2025-05-02', NULL, 0, 5, 5, '2025-04-25 10:16:54', '2025-04-25 10:17:09'),
	(9, 'SO-20250425-0009', 19, 21, 'completed', '2025-04-25', '2025-05-02', NULL, 0, 5, 5, '2025-04-25 10:20:48', '2025-04-25 10:21:11'),
	(10, 'SO-20250426-0010', 22, 12, 'completed', '2025-04-26', '2025-05-03', NULL, 0, 5, 5, '2025-04-26 04:17:37', '2025-04-26 04:18:19'),
	(11, 'SO-20250430-0011', 28, 11, 'completed', '2025-04-30', '2025-05-07', NULL, 1, 5, 5, '2025-04-29 23:27:35', '2025-04-29 23:29:15'),
	(12, 'SO-20250430-0012', 30, 20, 'completed', '2025-04-30', '2025-05-07', NULL, 1, 5, 5, '2025-04-30 01:27:56', '2025-04-30 06:28:24'),
	(13, 'SO-20250501-0013', 31, 13, 'completed', '2025-05-01', '2025-05-08', NULL, 1, 5, 5, '2025-05-01 15:36:14', '2025-05-01 15:36:45'),
	(14, 'SO-20250501-0014', 32, 12, 'completed', '2025-05-01', '2025-05-08', NULL, 0, 5, 5, '2025-05-01 16:12:20', '2025-05-01 16:12:39'),
	(15, 'SO-20250504-0015', 32, 12, 'approved', '2025-05-04', '2025-05-11', NULL, 0, 5, 5, '2025-05-04 08:05:37', '2025-05-04 08:05:44'),
	(16, 'SO-20250504-0016', 32, 12, 'pending', '2025-05-04', '2025-05-11', NULL, 0, 5, NULL, '2025-05-04 08:05:53', '2025-05-04 08:05:53'),
	(17, 'SO-20250504-0017', 32, 12, 'approved', '2025-05-04', '2025-05-11', NULL, 1, 5, 5, '2025-05-04 08:08:50', '2025-05-04 08:37:33');

-- Dumping structure for table madarsys.sessions
DROP TABLE IF EXISTS `sessions`;
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

-- Dumping data for table madarsys.sessions: ~6 rows (approximately)
DELETE FROM `sessions`;
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('0MgtuhWrKtIuJ3Az1C2O2qhvF9hGZal1joWfdGpX', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTjJGMjROc1hJRm9IMlduYVhMWmhPYUd2YTBmbFV6ZHZQM1ZjS1g5cyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3JldHVybnMvc3VwcGxpZXIiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1747716656),
	('9ia4QM8EYrzQgZpUUdCIcV35jVLE42Z4sDFbF4lt', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRHFydlBkQjc3aU5OdWZSYmJ1YWE2WG9KTG9xaU9jbmlEN0xJbXc1TCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZXR1cm5zL3N1cHBsaWVyIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTtzOjIxOiJzZWxlY3RlZF93YXJlaG91c2VfaWQiO2k6MTt9', 1747686695),
	('c2iyXPAwtHC1JVsUk6MIy37IlUr92Bz3kcbohc8B', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZlh0MlVDT1hMMjg0Ulo0T2FTRzA4czdaOFpsb1MycXk0d3NKc2hyOCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZXR1cm5zL3N1cHBsaWVyIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTtzOjIxOiJzZWxlY3RlZF93YXJlaG91c2VfaWQiO2k6MTt9', 1747750201),
	('CaGZSPMXMXMx5O6aFDlJ1i9SQhUzyf6oSjO2rNDp', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoiMUg3SDdlenoyVGZSajI2dDRMQlFMSFZqbE8xMnk1MGc1ZUU0cDZiNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9pbnZlbnRvcnkvdHJhbnNhY3Rpb25zL2NyZWF0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjE6e2k6MDtzOjg6InByb2R1Y3RzIjt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O3M6MjE6InNlbGVjdGVkX3dhcmVob3VzZV9pZCI7aToxO3M6ODoicHJvZHVjdHMiO047fQ==', 1747849002),
	('ipcl1JzvnECRFG6jGaXkRGZ4kCEkG5whk9A7YNbK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36 Edg/136.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTlF5dUdFN09hMnA5NFJkT0l0cGRKejNteWc1SnNsZEdXNUNsNU5HUSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3JldHVybnMvc3VwcGxpZXIiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1747774069),
	('mFfFsS715eSDkHWlLy5hZnI9siGTj3JkwrvhKvuS', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiOEpDV2M4VFV5dlk0WHFMS1hJYWMyY1lJM0tjV0lLTVg1TWoyMkZXSSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZXR1cm5zL3N1cHBsaWVyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NTtzOjIxOiJzZWxlY3RlZF93YXJlaG91c2VfaWQiO2k6MTt9', 1747755899);

-- Dumping structure for table madarsys.settings
DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للإعداد',
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'المفتاح الفريد للإعداد',
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'قيمة الإعداد المرتبطة بالمفتاح',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف الفرع المرتبط بهذا الإعداد',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي قام بإنشاء السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل للسجل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي قام بتعديل السجل',
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`),
  KEY `settings_branch_id_foreign` (`branch_id`),
  CONSTRAINT `settings_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.settings: ~20 rows (approximately)
DELETE FROM `settings`;
INSERT INTO `settings` (`id`, `key`, `value`, `branch_id`, `created_at`, `created_user`, `updated_at`, `updated_user`) VALUES
	(1, 'inventory_transaction_min_date', NULL, NULL, NULL, NULL, '2025-04-26 17:37:44', NULL),
	(2, 'system_start_date', '2025-01-01', 2, NULL, NULL, NULL, NULL),
	(3, 'fiscal_year_start_date', '2025-01-01', NULL, NULL, NULL, NULL, NULL),
	(4, 'fiscal_year_end_date', '2024-12-31', NULL, NULL, NULL, NULL, NULL),
	(5, 'daily_transaction_limit', '100', NULL, NULL, NULL, NULL, NULL),
	(6, 'minimum_items_per_transaction', '1', NULL, NULL, NULL, NULL, NULL),
	(7, 'max_quantity_per_product', '1000', NULL, NULL, NULL, NULL, NULL),
	(8, 'currency', 'RY', NULL, NULL, NULL, NULL, NULL),
	(9, 'tax_rate', '14', NULL, NULL, NULL, NULL, NULL),
	(10, 'is_test_mode', 'true', NULL, NULL, NULL, NULL, NULL),
	(11, 'max_file_size', '10', NULL, NULL, NULL, NULL, NULL),
	(12, 'last_settings_update_date', '2025-02-18 04:19:32', NULL, NULL, NULL, NULL, NULL),
	(13, 'alert_settings', '{"low_stock_alert":true,"email_notifications":true}', NULL, NULL, NULL, NULL, NULL),
	(14, 'discount_enabled', 'true', NULL, NULL, NULL, NULL, NULL),
	(15, 'return_period_days', '30', NULL, NULL, NULL, NULL, NULL),
	(16, 'logging_enabled', 'true', NULL, NULL, NULL, NULL, NULL),
	(17, 'auto_close_accounts', 'true', NULL, NULL, NULL, NULL, NULL),
	(18, 'password_protection_enabled', 'true', NULL, NULL, NULL, NULL, NULL),
	(19, 'expiration_threshold_days', '35', NULL, '2025-03-23 04:10:03', NULL, NULL, NULL),
	(20, 'accept_products_expiry', '0', NULL, NULL, NULL, '2025-04-27 02:31:49', NULL);

-- Dumping structure for table madarsys.shipments
DROP TABLE IF EXISTS `shipments`;
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

-- Dumping data for table madarsys.shipments: ~7 rows (approximately)
DELETE FROM `shipments`;
INSERT INTO `shipments` (`id`, `shipment_number`, `shipment_date`, `quantity`, `status`, `product_id`, `created_at`, `updated_at`) VALUES
	(7, '1', '2025-04-23', 2, 'delivered', 52, '2025-04-23 12:37:19', '2025-04-28 06:17:39'),
	(8, '2', '2025-04-23', 1, 'pending', 55, '2025-04-23 12:40:04', '2025-04-28 06:17:47'),
	(9, '3', '2025-04-23', 1, 'pending', 52, '2025-04-23 12:45:43', '2025-04-28 06:17:55'),
	(10, '4', '2025-04-26', 2, 'shipped', 100, '2025-04-26 04:43:47', '2025-04-28 06:18:03'),
	(11, '5', '2025-04-28', 3, 'delivered', 60, '2025-04-28 06:18:44', '2025-04-28 08:20:48'),
	(12, '6', '2025-04-28', 5, 'pending', 58, '2025-04-28 08:23:38', '2025-04-28 08:23:38'),
	(13, '7', '2025-04-28', 2, 'shipped', 61, '2025-04-28 08:24:02', '2025-04-28 08:24:02');

-- Dumping structure for table madarsys.transaction_types
DROP TABLE IF EXISTS `transaction_types`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.transaction_types: ~14 rows (approximately)
DELETE FROM `transaction_types`;
INSERT INTO `transaction_types` (`id`, `name`, `description`, `effect`, `branch_id`, `created_at`, `created_user`, `updated_at`, `updated_user`, `inventory_movement_count`) VALUES
	(1, 'شراء', 'عملية شراء المنتجات من الموردين وإضافتها إلى المخزون.', 1, 2, NULL, NULL, NULL, NULL, 1),
	(2, 'إرجاع من عميل', 'إرجاع المنتجات من العملاء إلى المخزون بعد البيع.', 1, 2, NULL, NULL, NULL, NULL, 1),
	(3, 'إنتاج داخلي', 'إضافة المنتجات المصنَّعة داخليًا إلى المخزون.', 1, 2, NULL, NULL, NULL, NULL, 1),
	(4, 'صرف', 'صرف المنتجات للأقسام أو الجهات الداخلية داخل الشركة.', -1, 2, NULL, NULL, NULL, NULL, 1),
	(5, 'تحويل مخزني', 'نقل المنتجات بين المستودعات أو الأرفف المختلفة.', 0, 2, NULL, NULL, NULL, NULL, 2),
	(6, 'إرجاع لمورد', 'إرجاع المنتجات إلى المورد بسبب التلف أو الخطأ في الطلبية.', 1, 2, NULL, NULL, NULL, NULL, 1),
	(7, 'بيع', 'بيع المنتجات للعملاء وإخراجها من المخزون.', -1, 2, NULL, NULL, NULL, NULL, 1),
	(8, 'جرد مخزني', 'عملية تدقيق لمقارنة الكمية الفعلية مع الكمية المسجلة في النظام.', 0, 2, NULL, NULL, NULL, NULL, 1),
	(9, 'تلف', 'تسجيل المنتجات التالفة وإخراجها من المخزون.', -1, 2, NULL, NULL, NULL, NULL, 1),
	(10, 'سرقة', 'تسجيل نقص المخزون الناتج عن فقدان غير مبرر أو سرقة.', -1, 2, NULL, NULL, NULL, NULL, 1),
	(11, 'تعديل يدوي', 'تصحيح المخزون يدويًا بسبب أخطاء تسجيل سابقة.', 0, 2, NULL, NULL, NULL, NULL, 1),
	(12, 'حجز المخزون', 'تحديد كمية من المخزون لطلبية قبل تنفيذ عملية البيع.', -1, 2, NULL, NULL, NULL, NULL, 1),
	(13, 'انتهاء الصلاحية', 'إخراج المنتجات من المخزون بسبب انتهاء فترة صلاحيتها.', -1, 2, NULL, NULL, NULL, NULL, 1),
	(14, 'استرجاع من الإنتاج', 'إرجاع المواد الخام غير المستخدمة إلى المخزون بعد التصنيع.', 1, 2, NULL, NULL, NULL, NULL, 1);

-- Dumping structure for table madarsys.units
DROP TABLE IF EXISTS `units`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.units: ~6 rows (approximately)
DELETE FROM `units`;
INSERT INTO `units` (`id`, `name`, `parent_unit_id`, `conversion_factor`, `branch_id`, `created_at`, `created_user`, `updated_at`, `updated_user`) VALUES
	(1, 'حبة', NULL, NULL, 2, '2025-02-18 21:32:56', NULL, '2025-02-18 21:32:56', NULL),
	(2, 'كرتون', 1, 10.0000, 2, '2025-02-18 21:32:56', NULL, '2025-02-18 21:32:56', NULL),
	(3, 'صندوق', 2, 5.0000, 2, '2025-02-18 21:32:56', NULL, '2025-02-18 21:32:56', NULL),
	(4, 'كيلوغرام', NULL, NULL, 2, '2025-02-18 21:32:56', NULL, '2025-02-18 21:32:56', NULL),
	(5, 'جرام', 4, 1000.0000, 2, '2025-02-18 21:32:56', NULL, '2025-02-18 21:32:56', NULL),
	(11, 'بوكس', 3, 10.0000, NULL, NULL, NULL, NULL, NULL);

-- Dumping structure for table madarsys.users
DROP TABLE IF EXISTS `users`;
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

-- Dumping structure for table madarsys.warehouses
DROP TABLE IF EXISTS `warehouses`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.warehouses: ~15 rows (approximately)
DELETE FROM `warehouses`;
INSERT INTO `warehouses` (`id`, `name`, `code`, `address`, `contact_info`, `branch_id`, `supervisor_id`, `latitude`, `longitude`, `area`, `capacity`, `is_smart`, `has_security_system`, `has_cctv`, `is_integrated_with_wms`, `last_maintenance`, `has_automated_systems`, `temperature`, `humidity`, `is_active`, `created_at`, `created_user`, `updated_at`, `updated_user`) VALUES
	(1, 'مستودع 1 في الفرع 1', 'WH11', 'عنوان المستودع 1', 'معلومات الاتصال للمستودع 1', 2, NULL, 31.0000000, 32.0000000, 1000.5, 5000, 1, 1, 1, 0, '2025-02-18 01:32:16', 1, 22.5, 60, 1, '2025-02-18 01:32:16', NULL, '2025-02-18 01:32:16', NULL),
	(2, 'مستودع 2 في الفرع 1', 'WH12', 'عنوان المستودع 2', 'معلومات الاتصال للمستودع 2', 2, NULL, 31.0000000, 33.0000000, 1000.5, 5000, 1, 1, 1, 0, '2025-02-18 01:32:16', 1, 22.5, 60, 1, '2025-02-18 01:32:16', NULL, '2025-02-18 01:32:16', NULL),
	(3, 'مستودع 3 في الفرع 1', 'WH13', 'عنوان المستودع 3', 'معلومات الاتصال للمستودع 3', 3, NULL, 31.0000000, 34.0000000, 1000.5, 5000, 1, 1, 1, 0, '2025-02-18 01:32:16', 1, 22.5, 60, 1, '2025-02-18 01:32:16', NULL, '2025-02-18 01:32:16', NULL),
	(4, 'مستودع 1 في الفرع 2', 'WH21', 'عنوان المستودع 1', 'معلومات الاتصال للمستودع 1', 3, NULL, 32.0000000, 32.0000000, 1000.5, 5000, 1, 1, 1, 0, '2025-02-18 01:32:16', 1, 22.5, 60, 1, '2025-02-18 01:32:16', NULL, '2025-02-18 01:32:16', NULL),
	(5, 'مستودع 2 في الفرع 2', 'WH22', 'عنوان المستودع 2', 'معلومات الاتصال للمستودع 2', 3, NULL, 32.0000000, 33.0000000, 1000.5, 5000, 1, 1, 1, 0, '2025-02-18 01:32:16', 1, 22.5, 60, 1, '2025-02-18 01:32:16', NULL, '2025-02-18 01:32:16', NULL),
	(6, 'مستودع 3 في الفرع 2', 'WH23', 'عنوان المستودع 3', 'معلومات الاتصال للمستودع 3', 2, NULL, 32.0000000, 34.0000000, 1000.5, 5000, 1, 1, 1, 0, '2025-02-18 01:32:16', 1, 22.5, 60, 1, '2025-02-18 01:32:16', NULL, '2025-02-18 01:32:16', NULL),
	(7, 'مستودع 1 في الفرع 3', 'WH31', 'عنوان المستودع 1', 'معلومات الاتصال للمستودع 1', 4, NULL, 33.0000000, 32.0000000, 1000.5, 5000, 1, 1, 1, 0, '2025-02-18 01:32:16', 1, 22.5, 60, 1, '2025-02-18 01:32:16', NULL, '2025-02-18 01:32:16', NULL),
	(8, 'مستودع 2 في الفرع 3', 'WH32', 'عنوان المستودع 2', 'معلومات الاتصال للمستودع 2', 4, NULL, 33.0000000, 33.0000000, 1000.5, 5000, 1, 1, 1, 0, '2025-02-18 01:32:16', 1, 22.5, 60, 1, '2025-02-18 01:32:16', NULL, '2025-02-18 01:32:16', NULL),
	(9, 'مستودع 3 في الفرع 3', 'WH33', 'عنوان المستودع 3', 'معلومات الاتصال للمستودع 3', 4, NULL, 33.0000000, 34.0000000, 1000.5, 5000, 1, 1, 1, 0, '2025-02-18 01:32:16', 1, 22.5, 60, 1, '2025-02-18 01:32:16', NULL, '2025-02-18 01:32:16', NULL),
	(10, 'مستودع 1 في الفرع 4', 'WH41', 'عنوان المستودع 1', 'معلومات الاتصال للمستودع 1', 5, NULL, 34.0000000, 32.0000000, 1000.5, 5000, 1, 1, 1, 0, '2025-02-18 01:32:16', 1, 22.5, 60, 1, '2025-02-18 01:32:16', NULL, '2025-02-18 01:32:16', NULL),
	(11, 'مستودع 2 في الفرع 4', 'WH42', 'عنوان المستودع 2', 'معلومات الاتصال للمستودع 2', 5, NULL, 34.0000000, 33.0000000, 1000.5, 5000, 1, 1, 1, 0, '2025-02-18 01:32:16', 1, 22.5, 60, 1, '2025-02-18 01:32:16', NULL, '2025-02-18 01:32:16', NULL),
	(12, 'مستودع 3 في الفرع 4', 'WH43', 'عنوان المستودع 3', 'معلومات الاتصال للمستودع 3', 5, NULL, 34.0000000, 34.0000000, 1000.5, 5000, 1, 1, 1, 0, '2025-02-18 01:32:16', 1, 22.5, 60, 1, '2025-02-18 01:32:16', NULL, '2025-02-18 01:32:16', NULL),
	(13, 'مستودع 1 في الفرع 5', 'WH51', 'عنوان المستودع 1', 'معلومات الاتصال للمستودع 1', 6, NULL, 35.0000000, 32.0000000, 1000.5, 5000, 1, 1, 1, 0, '2025-02-18 01:32:16', 1, 22.5, 60, 1, '2025-02-18 01:32:16', NULL, '2025-02-18 01:32:16', NULL),
	(14, 'مستودع 2 في الفرع 5', 'WH52', 'عنوان المستودع 2', 'معلومات الاتصال للمستودع 2', 6, NULL, 35.0000000, 33.0000000, 1000.5, 5000, 1, 1, 1, 0, '2025-02-18 01:32:16', 1, 22.5, 60, 1, '2025-02-18 01:32:16', NULL, '2025-02-18 01:32:16', NULL),
	(15, 'مستودع 3 في الفرع 5', 'WH53', 'عنوان المستودع 3', 'معلومات الاتصال للمستودع 3', 6, NULL, 35.0000000, 34.0000000, 1000.5, 5000, 1, 1, 1, 0, '2025-02-18 01:32:16', 1, 22.5, 60, 1, '2025-02-18 01:32:16', NULL, '2025-02-18 01:32:16', NULL);

-- Dumping structure for table madarsys.warehouse_category_warehouse
DROP TABLE IF EXISTS `warehouse_category_warehouse`;
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

-- Dumping data for table madarsys.warehouse_category_warehouse: ~0 rows (approximately)
DELETE FROM `warehouse_category_warehouse`;

-- Dumping structure for table madarsys.warehouse_locations
DROP TABLE IF EXISTS `warehouse_locations`;
CREATE TABLE IF NOT EXISTS `warehouse_locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد لموقع التخزين',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'المعرّف الفرعي المرتبط بالموقع',
  `warehouse_id` bigint unsigned NOT NULL COMMENT 'المستودع الذي يحتوي هذا الموقع',
  `storage_area_id` bigint unsigned NOT NULL COMMENT 'منطقة التخزين ضمن المستودع',
  `aisle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'الممر داخل منطقة التخزين',
  `rack` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رمز الرف',
  `shelf` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رمز الرفوف',
  `position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'الموقع الدقيق أو الخانة داخل الرف',
  `barcode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'الباركود الفريد لموقع التخزين',
  `is_occupied` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'هل الموقع مشغول أم لا (1 = نعم، 0 = لا)',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'ملاحظات إضافية حول الموقع',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ الإنشاء',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم المنشئ',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ التحديث الأخير',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرّف المستخدم الذي قام بالتعديل',
  `rack_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رمز مخصص للرف الكامل لتسهيل التتبع',
  PRIMARY KEY (`id`),
  UNIQUE KEY `warehouse_locations_barcode_unique` (`barcode`),
  UNIQUE KEY `warehouse_locations_rack_code_unique` (`rack_code`),
  KEY `warehouse_locations_warehouse_id_foreign` (`warehouse_id`),
  KEY `warehouse_locations_storage_area_id_foreign` (`storage_area_id`),
  KEY `warehouse_locations_branch_id_foreign` (`branch_id`),
  CONSTRAINT `warehouse_locations_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `warehouse_locations_storage_area_id_foreign` FOREIGN KEY (`storage_area_id`) REFERENCES `warehouse_storage_areas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `warehouse_locations_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.warehouse_locations: ~5 rows (approximately)
DELETE FROM `warehouse_locations`;
INSERT INTO `warehouse_locations` (`id`, `branch_id`, `warehouse_id`, `storage_area_id`, `aisle`, `rack`, `shelf`, `position`, `barcode`, `is_occupied`, `notes`, `created_at`, `created_user`, `updated_at`, `updated_user`, `rack_code`) VALUES
	(5, 2, 1, 1, 'A1', '1', '1', '1', '99700897', 0, NULL, '2025-03-07 03:09:25', 5, '2025-03-07 03:09:25', 5, 'RACK-67C9F2E5A80F0'),
	(6, 2, 1, 4, '1', '1', '1', '1', '1111111', 0, NULL, '2025-03-07 03:26:48', 5, '2025-03-07 03:26:48', 5, 'WH1-004-0001'),
	(7, 2, 1, 1, 'A1', 'R1', '1', '1', '6666', 0, NULL, '2025-03-07 03:36:31', 5, '2025-03-07 03:36:31', 5, 'WH1-001-0081'),
	(8, 2, 1, 1, '2', '2', '2', '2', '99900897', 0, NULL, '2025-03-07 03:47:14', 5, '2025-03-07 03:47:14', 5, 'WH1-001-0082'),
	(9, NULL, 1, 1, '2', '1', '2', '3', '123333', 0, NULL, '2025-03-07 03:54:35', 5, '2025-03-07 03:54:35', 5, 'WH1-001-0083');

-- Dumping structure for table madarsys.warehouse_reports
DROP TABLE IF EXISTS `warehouse_reports`;
CREATE TABLE IF NOT EXISTS `warehouse_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد لتقرير المستودع',
  `warehouse_id` bigint unsigned NOT NULL COMMENT 'معرّف المستودع المرتبط بالتقرير',
  `report_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'نوع التقرير (مثل: حركة، جرد، ...)',
  `report_data` json NOT NULL COMMENT 'بيانات التقرير بصيغة JSON',
  `report_date` datetime NOT NULL COMMENT 'تاريخ إصدار التقرير',
  `generated_by` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم أو معرّف من أنشأ التقرير',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء السجل',
  `created_user` bigint unsigned DEFAULT NULL COMMENT 'المستخدم الذي أنشأ السجل',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تعديل',
  `updated_user` bigint unsigned DEFAULT NULL COMMENT 'المستخدم الذي قام بآخر تعديل',
  PRIMARY KEY (`id`),
  KEY `warehouse_reports_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `warehouse_reports_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.warehouse_reports: ~1 rows (approximately)
DELETE FROM `warehouse_reports`;
INSERT INTO `warehouse_reports` (`id`, `warehouse_id`, `report_type`, `report_data`, `report_date`, `generated_by`, `created_at`, `created_user`, `updated_at`, `updated_user`) VALUES
	(17, 1, 'details', '{"features": {"has_cctv": 1, "is_smart": 1, "has_security_system": 1, "has_automated_systems": 1, "is_integrated_with_wms": 0}, "warehouse_info": {"code": "WH11", "name": "مستودع 1 في الفرع 1", "areas": [{"shelves": [{"rack": "1", "aisle": "A1", "shelf": "1"}, {"rack": "R1", "aisle": "A1", "shelf": "1"}, {"rack": "2", "aisle": "2", "shelf": "2"}, {"rack": "1", "aisle": "2", "shelf": "2"}], "area_name": "منطقة التخزين الرئيسية"}, {"shelves": [{"rack": "1", "aisle": "1", "shelf": "1"}], "area_name": "منطقة التخزين الثانوية"}], "branch": "فرع 1", "address": "عنوان المستودع 1", "capacity": 5000, "humidity": 60, "supervisor": "غير محدد", "temperature": 22.5, "shelves_count": null}}', '2025-03-22 11:07:18', 'Admin User', '2025-03-22 18:07:18', 5, '2025-03-22 18:07:18', 5);

-- Dumping structure for table madarsys.warehouse_storage_areas
DROP TABLE IF EXISTS `warehouse_storage_areas`;
CREATE TABLE IF NOT EXISTS `warehouse_storage_areas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد لمنطقة التخزين',
  `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف الفرع المرتبطة به منطقة التخزين',
  `warehouse_id` bigint unsigned NOT NULL COMMENT 'معرّف المستودع المرتبط بمنطقة التخزين',
  `area_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم منطقة التخزين',
  `area_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'نوع منطقة التخزين (مثل: مبردة، جافة، إلخ)',
  `capacity` int NOT NULL COMMENT 'الطاقة الاستيعابية القصوى لمنطقة التخزين',
  `current_occupancy` int NOT NULL DEFAULT '0' COMMENT 'عدد الوحدات المخزنة حاليًا في المنطقة',
  `zone_id` bigint unsigned DEFAULT NULL COMMENT 'معرّف المنطقة الجغرافية داخل المستودع إن وُجد',
  `storage_conditions` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'شروط التخزين الخاصة (درجة حرارة، رطوبة، إلخ)',
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.warehouse_storage_areas: ~4 rows (approximately)
DELETE FROM `warehouse_storage_areas`;
INSERT INTO `warehouse_storage_areas` (`id`, `branch_id`, `warehouse_id`, `area_name`, `area_type`, `capacity`, `current_occupancy`, `zone_id`, `storage_conditions`, `created_at`, `created_user`, `updated_at`, `updated_user`) VALUES
	(1, NULL, 1, 'منطقة التخزين الرئيسية', 'رئيسية', 1000, 500, 3, 'منظقة جافة', '2025-02-25 22:25:03', 5, '2025-02-26 02:46:59', 5),
	(2, NULL, 2, 'منطقة التخزين العلوية', 'علوية', 2000, 1200, 5, 'ظروف التخزين الحارة', '2025-02-25 22:27:33', 5, '2025-02-25 22:27:33', 5),
	(3, NULL, 2, 'منطقة التخزين السفلية', 'سفلية', 1500, 700, 5, 'ظروف التخزين الرطبة', '2025-02-25 22:28:30', 5, '2025-02-25 22:28:30', 5),
	(4, NULL, 1, 'منطقة التخزين الثانوية', 'ثانوية', 500, 300, 4, 'ظروف التخزين المبردة', '2025-02-26 02:28:10', 5, '2025-02-26 04:35:36', 5);

-- Dumping structure for table madarsys.zones
DROP TABLE IF EXISTS `zones`;
CREATE TABLE IF NOT EXISTS `zones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table madarsys.zones: ~8 rows (approximately)
DELETE FROM `zones`;
INSERT INTO `zones` (`id`, `name`, `code`, `description`, `created_at`, `created_user`, `updated_at`, `updated_user`, `warehouse_id`, `branch_id`, `capacity`, `current_occupancy`) VALUES
	(3, 'منطقة المواد الخام', 'RAW-', 'تخزين المواد الأولية قبل الإنتاج', '2025-02-24 18:06:49', 5, '2025-02-24 18:06:49', 5, 2, NULL, 0, 0),
	(4, 'المناطق الباردة', 'COLD-', 'تخزين في درجات حرارة منخفضة مثل المجمدات', '2025-02-24 18:13:39', 5, '2025-02-26 02:25:41', 5, 1, NULL, 0, 0),
	(5, 'المناطق الجافة', 'DRY-', 'تخزين المواد التي لا تحتاج إلى تحكم في الرطوبة أو الحرارة', '2025-02-24 18:15:03', 5, '2025-02-24 18:15:03', 5, 1, NULL, 0, 0),
	(6, 'المناطق المعزولة', 'QUARANTINE-', 'تخزين المنتجات المشتبه بها أو التي تحتاج إلى فحص قبل الاستخدام أو الشحن', '2025-02-25 21:59:16', 5, '2025-02-25 21:59:16', 5, 1, 2, 0, 0),
	(7, 'منطقة المنتجات الجاهزة', 'FG-', 'تخزين المنتجات النهائية الجاهزة للشحن أو التوزيع', '2025-02-25 22:05:53', 5, '2025-02-25 22:05:53', 5, 2, 2, 0, 0),
	(8, 'منطقة المواد القابلة للتلف', 'PERISHABLE-', 'تخزين المواد التي تحتاج إلى تحكم في درجات الحرارة والرطوبة، مثل المواد الغذائية والأدوية', '2025-02-25 22:06:30', 5, '2025-02-25 22:06:30', 5, 2, 2, 0, 0),
	(9, 'منطقة المواد الخطرة', 'HAZARD-', 'تخزين المواد الكيميائية أو السامة وفقًا لمعايير السلامة', '2025-02-25 22:06:40', 5, '2025-02-25 22:06:40', 5, 2, 2, 0, 0),
	(10, 'منطقة المواد القابلة لإعادة التدوير', 'RECYCLABLE-', 'تخزين المواد التي يمكن إعادة استخدامها أو إعادة تدويرها', '2025-02-25 22:15:27', 5, '2025-02-25 22:15:27', 5, 2, 2, 0, 0);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

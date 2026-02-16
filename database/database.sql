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

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

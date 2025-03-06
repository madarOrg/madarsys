-- MySQL dump 10.13  Distrib 8.0.39, for Win64 (x86_64)
--
-- Host: localhost    Database: madarsys
-- ------------------------------------------------------
-- Server version	8.0.39

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `branches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_info` text COLLATE utf8mb4_unicode_ci,
  `company_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branches_id_company_id_unique` (`id`,`company_id`),
  KEY `branches_company_id_foreign` (`company_id`),
  CONSTRAINT `branches_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branches`
--

LOCK TABLES `branches` WRITE;
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;
INSERT INTO `branches` VALUES (1,'فرع 1','عنوان الفرع 1','معلومات الاتصال للفرع 1',1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL),(2,'فرع 2','عنوان الفرع 2','معلومات الاتصال للفرع 2',1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL),(3,'فرع 3','عنوان الفرع 3','معلومات الاتصال للفرع 3',1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL),(4,'فرع 4','عنوان الفرع 4','معلومات الاتصال للفرع 4',1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL),(5,'فرع 5','عنوان الفرع 5','معلومات الاتصال للفرع 5',1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL);
/*!40000 ALTER TABLE `branches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('navbar_links_9b5f9b6848d72fee6cb64682eb73055a','O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:12:{i:0;a:4:{s:4:\"text\";s:31:\"إدارة المستخدمين\";s:3:\"key\";s:16:\"مستخدمين\";s:4:\"href\";s:1:\"#\";s:8:\"children\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:4:{i:0;a:3:{s:4:\"text\";s:44:\"إدارة بيانات المستخدمين\";s:4:\"href\";s:6:\"/users\";s:4:\"icon\";s:28:\"fa fa-user-edit text-red-500\";}i:1;a:3:{s:4:\"text\";s:42:\"إدارة أدوار المستخدمين\";s:4:\"href\";s:6:\"/roles\";s:4:\"icon\";s:28:\"fa fa-user-edit text-red-500\";}i:2;a:3:{s:4:\"text\";s:44:\"منح الأذونات والصلاحيات\";s:4:\"href\";s:23:\"/role-permissions/index\";s:4:\"icon\";s:25:\"fa fa-key text-orange-500\";}i:3;a:3:{s:4:\"text\";s:42:\"مراقبة نشاط المستخدمين\";s:4:\"href\";s:18:\"/users-rolesi/ndex\";s:4:\"icon\";s:30:\"fa fa-chart-pie text-green-500\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}i:1;a:4:{s:4:\"text\";s:49:\"إدارة الشركات و المستودعات\";s:3:\"key\";s:28:\"شركاتومستودعات\";s:4:\"href\";s:1:\"#\";s:8:\"children\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:9:{i:0;a:3:{s:4:\"text\";s:32:\"إضافة مستودع جديد\";s:4:\"href\";s:18:\"/warehouses/create\";s:4:\"icon\";s:25:\"fa fa-plus text-green-500\";}i:1;a:3:{s:4:\"text\";s:31:\"قائمة المستودعات\";s:4:\"href\";s:11:\"/warehouses\";s:4:\"icon\";s:24:\"fa fa-list text-blue-500\";}i:2;a:3:{s:4:\"text\";s:38:\"إدارة مواقع المستودع\";s:4:\"href\";s:11:\"/warehouses\";s:4:\"icon\";s:29:\"fa fa-map-marker text-red-500\";}i:3;a:3:{s:4:\"text\";s:44:\"إدارة المناطق التخزينية\";s:4:\"href\";s:11:\"/warehouses\";s:4:\"icon\";s:17:\"fa-solid fa-store\";}i:4;a:3:{s:4:\"text\";s:42:\"إدارة الأمان والتصاريح\";s:4:\"href\";s:20:\"/warehouses/security\";s:4:\"icon\";s:32:\"fa fa-shield-alt text-orange-500\";}i:5;a:3:{s:4:\"text\";s:33:\"التدقيق والمراجعة\";s:4:\"href\";s:17:\"/warehouses/audit\";s:4:\"icon\";s:32:\"fa fa-check-circle text-teal-500\";}i:6;a:3:{s:4:\"text\";s:29:\"تقارير المستودع\";s:4:\"href\";s:18:\"/warehouse-reports\";s:4:\"icon\";s:28:\"fa fa-file-alt text-gray-500\";}i:7;a:3:{s:4:\"text\";s:25:\"قائمة الشركات\";s:4:\"href\";s:10:\"/companies\";s:4:\"icon\";s:24:\"fa fa-list text-blue-500\";}i:8;a:3:{s:4:\"text\";s:30:\"إضافة شركة جديدة\";s:4:\"href\";s:17:\"/companies/create\";s:4:\"icon\";s:25:\"fa fa-plus text-green-500\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}i:2;a:4:{s:4:\"text\";s:25:\"إدارة المخزون\";s:3:\"key\";s:10:\"مخزون\";s:4:\"href\";s:1:\"#\";s:8:\"children\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:5:{i:0;a:3:{s:4:\"text\";s:28:\"اضافة منتج جديد\";s:4:\"href\";s:9:\"/products\";s:4:\"icon\";s:25:\"fa fa-plus text-green-500\";}i:1;a:3:{s:4:\"text\";s:23:\"حركات مخزنية\";s:4:\"href\";s:30:\"/inventory/transactions/create\";s:4:\"icon\";s:24:\"fa fa-edit text-blue-500\";}i:2;a:3:{s:4:\"text\";s:25:\"توزيع المخزون\";s:4:\"href\";s:21:\"/inventory/distribute\";s:4:\"icon\";s:32:\"fa fa-arrows-alt text-purple-500\";}i:3;a:3:{s:4:\"text\";s:36:\"مراقبة حالة المخزون\";s:4:\"href\";s:18:\"/inventory/monitor\";s:4:\"icon\";s:23:\"fa fa-eye text-teal-500\";}i:4;a:3:{s:4:\"text\";s:25:\"فئات المنتجات\";s:4:\"href\";s:11:\"/categories\";s:4:\"icon\";s:31:\"fa fa-check-circle text-red-500\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}i:3;a:4:{s:4:\"text\";s:25:\"إدارة الشحنات\";s:3:\"key\";s:10:\"شحنات\";s:4:\"href\";s:1:\"#\";s:8:\"children\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:3:{i:0;a:3:{s:4:\"text\";s:27:\"استلام الشحنات\";s:4:\"href\";s:18:\"/shipments/receive\";s:4:\"icon\";s:26:\"fa fa-inbox text-green-500\";}i:1;a:3:{s:4:\"text\";s:25:\"إرسال الشحنات\";s:4:\"href\";s:15:\"/shipments/send\";s:4:\"icon\";s:31:\"fa fa-paper-plane text-blue-500\";}i:2;a:3:{s:4:\"text\";s:36:\"متابعة حالة الشحنات\";s:4:\"href\";s:16:\"/shipments/track\";s:4:\"icon\";s:33:\"fa fa-map-marker-alt text-red-500\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}i:4;a:4:{s:4:\"text\";s:27:\"إدارة الفواتير\";s:3:\"key\";s:12:\"فواتير\";s:4:\"href\";s:1:\"#\";s:8:\"children\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:3:{i:0;a:3:{s:4:\"text\";s:40:\"إنشاء فواتير المبيعات\";s:4:\"href\";s:15:\"/invoices/sales\";s:4:\"icon\";s:29:\"fa fa-receipt text-yellow-500\";}i:1;a:3:{s:4:\"text\";s:42:\"إنشاء فواتير المشتريات\";s:4:\"href\";s:19:\"/invoices/purchases\";s:4:\"icon\";s:34:\"fa fa-shopping-cart text-green-500\";}i:2;a:3:{s:4:\"text\";s:38:\"تتبع تفاصيل الفواتير\";s:4:\"href\";s:15:\"/invoices/track\";s:4:\"icon\";s:26:\"fa fa-search text-teal-500\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}i:5;a:4:{s:4:\"text\";s:25:\"إدارة الشركاء\";s:3:\"key\";s:10:\"شركاء\";s:4:\"href\";s:1:\"#\";s:8:\"children\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:2:{i:0;a:3:{s:4:\"text\";s:25:\"ادارة الشركاء\";s:4:\"href\";s:9:\"/partners\";s:4:\"icon\";s:32:\"fa fa-plus-square text-green-500\";}i:1;a:3:{s:4:\"text\";s:16:\"المنتجات\";s:4:\"href\";s:9:\"/products\";s:4:\"icon\";s:25:\"fa fa-tasks text-blue-500\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}i:6;a:4:{s:4:\"text\";s:46:\"إدارة العوائد (المرتجعات)\";s:3:\"key\";s:26:\"عوائد(مرتجعات)\";s:4:\"href\";s:1:\"#\";s:8:\"children\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:2:{i:0;a:3:{s:4:\"text\";s:31:\"معالجة المرتجعات\";s:4:\"href\";s:16:\"/returns/process\";s:4:\"icon\";s:26:\"fa fa-cogs text-yellow-500\";}i:1;a:3:{s:4:\"text\";s:46:\"إرسال المرتجعات للموردين\";s:4:\"href\";s:17:\"/returns/supplier\";s:4:\"icon\";s:35:\"fa fa-truck-loading text-purple-500\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}i:7;a:4:{s:4:\"text\";s:27:\"إدارة الموردين\";s:3:\"key\";s:12:\"موردين\";s:4:\"href\";s:1:\"#\";s:8:\"children\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:3:{i:0;a:3:{s:4:\"text\";s:53:\"إضافة وتحديث بيانات الموردين\";s:4:\"href\";s:14:\"/suppliers/add\";s:4:\"icon\";s:30:\"fa fa-user-plus text-green-500\";}i:1;a:3:{s:4:\"text\";s:58:\"متابعة حالة التعامل مع الموردين\";s:4:\"href\";s:16:\"/suppliers/track\";s:4:\"icon\";s:35:\"fa fa-clipboard-check text-teal-500\";}i:2;a:3:{s:4:\"text\";s:40:\"إدارة تفاصيل الموردين\";s:4:\"href\";s:18:\"/suppliers/details\";s:4:\"icon\";s:31:\"fa fa-info-circle text-blue-500\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}i:8;a:4:{s:4:\"text\";s:23:\"تتبع الكميات\";s:3:\"key\";s:18:\"تتبعكميات\";s:4:\"href\";s:1:\"#\";s:8:\"children\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:2:{i:0;a:3:{s:4:\"text\";s:66:\"تتبع الكميات في المستودعات والمحلات\";s:4:\"href\";s:20:\"/tracking/warehouses\";s:4:\"icon\";s:31:\"fa fa-warehouse text-purple-500\";}i:1;a:3:{s:4:\"text\";s:77:\"تحديث الكميات بناءً على الشحنات والمبيعات\";s:4:\"href\";s:16:\"/tracking/update\";s:4:\"icon\";s:24:\"fa fa-sync text-blue-500\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}i:9;a:4:{s:4:\"text\";s:27:\"إنشاء التقارير\";s:3:\"key\";s:22:\"إنشاءتقارير\";s:4:\"href\";s:1:\"#\";s:8:\"children\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:3:{i:0;a:3:{s:4:\"text\";s:27:\"تقارير المخزون\";s:4:\"href\";s:18:\"/reports/inventory\";s:4:\"icon\";s:35:\"fa fa-clipboard-list text-green-500\";}i:1;a:3:{s:4:\"text\";s:27:\"تقارير الشحنات\";s:4:\"href\";s:18:\"/reports/shipments\";s:4:\"icon\";s:33:\"fa fa-shipping-fast text-teal-500\";}i:2;a:3:{s:4:\"text\";s:29:\"تقارير الفواتير\";s:4:\"href\";s:17:\"/reports/invoices\";s:4:\"icon\";s:30:\"fa fa-file-alt text-orange-500\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}i:10;a:4:{s:4:\"text\";s:42:\"إدارة العملاء والمحلات\";s:3:\"key\";s:22:\"عملاءومحلات\";s:4:\"href\";s:1:\"#\";s:8:\"children\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:3:{i:0;a:3:{s:4:\"text\";s:68:\"إضافة وتحديث بيانات العملاء والمحلات\";s:4:\"href\";s:21:\"/customers-stores/add\";s:4:\"icon\";s:32:\"fa fa-address-card text-blue-500\";}i:1;a:3:{s:4:\"text\";s:45:\"إدارة الطلبات من العملاء\";s:4:\"href\";s:24:\"/customers-stores/orders\";s:4:\"icon\";s:34:\"fa fa-shopping-cart text-green-500\";}i:2;a:3:{s:4:\"text\";s:45:\"إدارة المخزون في المحلات\";s:4:\"href\";s:27:\"/customers-stores/inventory\";s:4:\"icon\";s:27:\"fa fa-boxes text-yellow-500\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}i:11;a:4:{s:4:\"text\";s:40:\"إدارة مندوبين المحلات\";s:3:\"key\";s:24:\"مندوبينمحلات\";s:4:\"href\";s:1:\"#\";s:8:\"children\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:3:{i:0;a:3:{s:4:\"text\";s:55:\"إضافة وتحديث بيانات المندوبين\";s:4:\"href\";s:20:\"/representatives/add\";s:4:\"icon\";s:30:\"fa fa-user-plus text-green-500\";}i:1;a:3:{s:4:\"text\";s:21:\"تتبع المهام\";s:4:\"href\";s:22:\"/representatives/tasks\";s:4:\"icon\";s:25:\"fa fa-tasks text-blue-500\";}i:2;a:3:{s:4:\"text\";s:70:\"إدارة التواصل بين المحلات والمستودعات\";s:4:\"href\";s:30:\"/representatives/communication\";s:4:\"icon\";s:30:\"fa fa-comments text-purple-500\";}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}',1740913371),('user_permissions_1','a:42:{i:0;s:28:\"مستخدمين.users.index\";i:1;s:22:\"مستخدمين.roles\";i:2;s:28:\"مستخدمين.permissions\";i:3;s:25:\"مستخدمين.activity\";i:4;s:35:\"شركاتومستودعات.create\";i:5;s:39:\"شركاتومستودعات.warehouses\";i:6;s:38:\"شركاتومستودعات.locations\";i:7;s:42:\"شركاتومستودعات.storage-areas\";i:8;s:37:\"شركاتومستودعات.security\";i:9;s:34:\"شركاتومستودعات.audit\";i:10;s:36:\"شركاتومستودعات.reports\";i:11;s:37:\"شركاتومستودعات.settings\";i:12;s:45:\"شركاتومستودعات.companies.create\";i:13;s:14:\"مخزون.add\";i:14;s:28:\"مخزون.update-quantities\";i:15;s:21:\"مخزون.distribute\";i:16;s:18:\"مخزون.monitor\";i:17;s:19:\"مخزون.validate\";i:18;s:18:\"شحنات.receive\";i:19;s:15:\"شحنات.send\";i:20;s:16:\"شحنات.track\";i:21;s:18:\"فواتير.sales\";i:22;s:22:\"فواتير.purchases\";i:23;s:18:\"فواتير.track\";i:24;s:17:\"شركاء.create\";i:25;s:16:\"شركاء.track\";i:26;s:34:\"عوائد(مرتجعات).process\";i:27;s:35:\"عوائد(مرتجعات).supplier\";i:28;s:16:\"موردين.add\";i:29;s:18:\"موردين.track\";i:30;s:20:\"موردين.details\";i:31;s:29:\"تتبعكميات.warehouses\";i:32;s:25:\"تتبعكميات.update\";i:33;s:32:\"إنشاءتقارير.inventory\";i:34;s:32:\"إنشاءتقارير.shipments\";i:35;s:31:\"إنشاءتقارير.invoices\";i:36;s:43:\"عملاءومحلات.customers-stores.add\";i:37;s:46:\"عملاءومحلات.customers-stores.orders\";i:38;s:49:\"عملاءومحلات.customers-stores.inventory\";i:39;s:44:\"مندوبينمحلات.representatives.add\";i:40;s:46:\"مندوبينمحلات.representatives.tasks\";i:41;s:54:\"مندوبينمحلات.representatives.communication\";}',1740913371);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_name_unique` (`name`),
  UNIQUE KEY `categories_code_unique` (`code`),
  KEY `categories_branch_id_foreign` (`branch_id`),
  CONSTRAINT `categories_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'إلكترونيات','ELEC','الأجهزة الإلكترونية مثل الهواتف الذكية والحواسيب.','2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(2,'ملابس','CLOTH','ملابس رجالية ونسائية وأطفال.','2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(3,'أثاث','FURN','الأثاث المنزلي والمكتبي.','2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(4,'مواد غذائية','FOOD','المواد الغذائية والمنتجات الاستهلاكية.','2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(5,'معدات صناعية','INDUST','المعدات الثقيلة والمستلزمات الصناعية.','2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `companies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `additional_info` text COLLATE utf8mb4_unicode_ci,
  `settings` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `companies_phone_number_unique` (`phone_number`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (1,'شركة المدارات','path_to_logo','123456997890','company@example.com','عنوان الشركة','معلومات إضافية','\"{\\\"key\\\":\\\"value\\\"}\"','2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL);
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `departments_branch_id_foreign` (`branch_id`),
  CONSTRAINT `departments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departments`
--

LOCK TABLES `departments` WRITE;
/*!40000 ALTER TABLE `departments` DISABLE KEYS */;
/*!40000 ALTER TABLE `departments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_value` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_warehouse_id_foreign` (`warehouse_id`),
  KEY `inventory_product_id_foreign` (`product_id`),
  CONSTRAINT `inventory_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory`
--

LOCK TABLES `inventory` WRITE;
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_request_details`
--

DROP TABLE IF EXISTS `inventory_request_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_request_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `inventory_request_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL COMMENT 'الكمية المطلوبة',
  `unit_price` decimal(10,2) DEFAULT NULL COMMENT 'سعر الوحدة للمنتج',
  `total` decimal(15,2) DEFAULT NULL COMMENT 'إجمالي السعر',
  `warehouse_location_id` bigint unsigned DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_request_details`
--

LOCK TABLES `inventory_request_details` WRITE;
/*!40000 ALTER TABLE `inventory_request_details` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_request_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_requests`
--

DROP TABLE IF EXISTS `inventory_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_type_id` bigint unsigned NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'تاريخ الطلب',
  `partner_id` bigint unsigned DEFAULT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `warehouse_id` bigint unsigned NOT NULL,
  `status` enum('قيد الانتظار','موافق عليه','مرفوض','مكتمل') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'حالة الطلب: 1 قيد الانتظار، 2 موافق عليه، 3 مرفوض، 4 مكتمل',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'ملاحظات إضافية',
  `branch_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_requests`
--

LOCK TABLES `inventory_requests` WRITE;
/*!40000 ALTER TABLE `inventory_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_transaction_items`
--

DROP TABLE IF EXISTS `inventory_transaction_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_transaction_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `inventory_transaction_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `unit_id` bigint unsigned DEFAULT NULL,
  `unit_product_id` bigint unsigned DEFAULT NULL,
  `converted_quantity` decimal(10,4) DEFAULT NULL,
  `unit_prices` decimal(15,2) DEFAULT NULL,
  `total` decimal(20,6) DEFAULT NULL,
  `warehouse_location_id` bigint unsigned DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `target_warehouse_id` bigint unsigned DEFAULT NULL,
  `converted_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `inventory_transaction_items_inventory_transaction_id_foreign` (`inventory_transaction_id`),
  KEY `inventory_transaction_items_product_id_foreign` (`product_id`),
  KEY `inventory_transaction_items_unit_id_foreign` (`unit_id`),
  KEY `inventory_transaction_items_unit_product_id_foreign` (`unit_product_id`),
  KEY `inventory_transaction_items_warehouse_location_id_foreign` (`warehouse_location_id`),
  KEY `inventory_transaction_items_branch_id_foreign` (`branch_id`),
  KEY `inventory_transaction_items_target_warehouse_id_foreign` (`target_warehouse_id`),
  CONSTRAINT `inventory_transaction_items_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transaction_items_inventory_transaction_id_foreign` FOREIGN KEY (`inventory_transaction_id`) REFERENCES `inventory_transactions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_transaction_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_transaction_items_target_warehouse_id_foreign` FOREIGN KEY (`target_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transaction_items_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transaction_items_unit_product_id_foreign` FOREIGN KEY (`unit_product_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transaction_items_warehouse_location_id_foreign` FOREIGN KEY (`warehouse_location_id`) REFERENCES `warehouse_locations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_transaction_items`
--

LOCK TABLES `inventory_transaction_items` WRITE;
/*!40000 ALTER TABLE `inventory_transaction_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_transaction_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventory_transactions`
--

DROP TABLE IF EXISTS `inventory_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `inventory_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `transaction_type_id` bigint unsigned NOT NULL,
  `effect` tinyint DEFAULT NULL COMMENT 'تحديد تأثير العملية على المخزون: + زيادة، - نقصان، 0 محايدة',
  `transaction_date` timestamp NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `partner_id` bigint unsigned DEFAULT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `warehouse_id` bigint unsigned NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `branch_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `inventory_request_id` bigint unsigned DEFAULT NULL,
  `secondary_warehouse_id` bigint unsigned DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `inventory_transactions_transaction_type_id_foreign` (`transaction_type_id`),
  KEY `inventory_transactions_partner_id_foreign` (`partner_id`),
  KEY `inventory_transactions_department_id_foreign` (`department_id`),
  KEY `inventory_transactions_warehouse_id_foreign` (`warehouse_id`),
  KEY `inventory_transactions_branch_id_foreign` (`branch_id`),
  KEY `inventory_transactions_inventory_request_id_foreign` (`inventory_request_id`),
  KEY `inventory_transactions_secondary_warehouse_id_foreign` (`secondary_warehouse_id`),
  CONSTRAINT `inventory_transactions_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transactions_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transactions_inventory_request_id_foreign` FOREIGN KEY (`inventory_request_id`) REFERENCES `inventory_requests` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transactions_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transactions_secondary_warehouse_id_foreign` FOREIGN KEY (`secondary_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE SET NULL,
  CONSTRAINT `inventory_transactions_transaction_type_id_foreign` FOREIGN KEY (`transaction_type_id`) REFERENCES `transaction_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_transactions_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventory_transactions`
--

LOCK TABLES `inventory_transactions` WRITE;
/*!40000 ALTER TABLE `inventory_transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `inventory_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_01_10_030319_create_roles_table',1),(5,'2025_01_10_030923_create_role_user_table',1),(6,'2025_01_18_162854_create_companies_table',1),(7,'2025_01_18_170241_create_branches_table',1),(8,'2025_01_18_170755_create_units_table',1),(9,'2025_01_18_170756_create_categories_table',1),(10,'2025_01_18_170757_create_warehouses_table',1),(11,'2025_01_20_144748_create_role_company_table',1),(12,'2025_01_20_144918_create_role_branch_table',1),(13,'2025_01_20_144953_create_role_warehouse_table',1),(14,'2025_01_22_152022_add_status_to_users_table',1),(15,'2025_01_23_041349_add_is_admin_to_roles_table',1),(16,'2025_01_29_150644_add_status_to_roles_table',1),(17,'2025_01_31_105155_create_modules_table',1),(18,'2025_01_31_105733_create_module_actions_table',1),(19,'2025_01_31_141502_create_permissions_table',1),(20,'2025_01_31_141612_create_role_permissions_table',1),(21,'2025_02_03_095439_create_warehouse_reports_table',1),(22,'2025_02_03_115225_create_zones_table',1),(23,'2025_02_03_115226_create_warehouse_storage_areas_table',1),(24,'2025_02_04_090642_create_warehouse_locations_table',1),(25,'2025_02_05_130258_create_partner_types_table',1),(26,'2025_02_05_201246_create_partners_table',1),(27,'2025_02_05_201258_create_products_table',1),(28,'2025_02_07_142552_create_departments_table',1),(29,'2025_02_07_201428_create_transaction_types_table',1),(30,'2025_02_07_203040_create_inventory_transactions_and_items_tables',1),(31,'2025_02_08_144800_add_branch_id_to_multiple_tables',1),(32,'2025_02_09_145841_add_effect_to_transaction_types',1),(33,'2025_02_09_152641_add_effect_to_inventory_transactions',1),(34,'2025_02_09_164015_create_inventory_requests_tables',1),(35,'2025_02_09_170838_add_inventory_request_id_to_inventory_transactions',1),(36,'2025_02_13_154411_create_settings_table',1),(37,'2025_02_13_213235_add_status_to_warehouses_table',1),(38,'2025_02_15_211002_create_warehouse_categories_table',1),(39,'2025_02_16_175750_update_users_status_default_value',1),(40,'2025_02_18_211537_add_warehouse_fields_to_inventory_tables',1),(41,'2025_02_19_192611_add_inventory_movement_count_to_transaction_types_table',1),(42,'2025_02_20_171204_create_inventory_table',1),(43,'2025_02_21_073214_add_created_user_and_updated_user_to_tables',1),(44,'2025_02_22_180722_alter_transaction_date_type_in_inventory_transactions_table',1),(45,'2025_02_25_070337_add_branch_id_to_warehouse_tables',1),(46,'2025_02_27_162118_add_status_to_inventory_transactions',1),(47,'2025_02_27_171131_add_converted_price_to_inventory_transaction_items_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `module_actions`
--

DROP TABLE IF EXISTS `module_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `module_actions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `module_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `module_actions_module_id_foreign` (`module_id`),
  KEY `module_actions_branch_id_foreign` (`branch_id`),
  CONSTRAINT `module_actions_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `module_actions_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `module_actions`
--

LOCK TABLES `module_actions` WRITE;
/*!40000 ALTER TABLE `module_actions` DISABLE KEYS */;
INSERT INTO `module_actions` VALUES (42,1,'إدارة بيانات المستخدمين','users.index','/users','fa fa-user-edit text-red-500','2025-01-31 13:30:35',NULL,'2025-01-31 13:30:35',NULL,2),(43,1,'إدارة أدوار المستخدمين','roles','/roles','fa fa-user-edit text-red-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(44,1,'منح الأذونات والصلاحيات','permissions','/role-permissions/index','fa fa-key text-orange-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(45,1,'مراقبة نشاط المستخدمين','activity','/users-rolesi/ndex','fa fa-chart-pie text-green-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(46,2,'إضافة مستودع جديد','create','/warehouses/create','fa fa-plus text-green-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(47,2,'قائمة المستودعات','warehouses','/warehouses','fa fa-list text-blue-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(48,2,'إدارة مواقع المستودع','locations','/warehouses','fa fa-map-marker text-red-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(49,2,'إدارة المناطق التخزينية','storage-areas','/warehouses','fa-solid fa-store','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(50,2,'إدارة الأمان والتصاريح','security','/warehouses/security','fa fa-shield-alt text-orange-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(51,2,'التدقيق والمراجعة','audit','/warehouses/audit','fa fa-check-circle text-teal-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(52,2,'تقارير المستودع','reports','/warehouse-reports','fa fa-file-alt text-gray-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(53,2,'قائمة الشركات','settings','/companies','fa fa-list text-blue-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(54,3,'اضافة منتج جديد','add','/products','fa fa-plus text-green-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(55,3,'حركات مخزنية','update-quantities','/inventory/transactions/create','fa fa-edit text-blue-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(56,3,'توزيع المخزون','distribute','/inventory/distribute','fa fa-arrows-alt text-purple-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(57,3,'مراقبة حالة المخزون','monitor','/inventory/monitor','fa fa-eye text-teal-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(58,4,'استلام الشحنات','receive','/shipments/receive','fa fa-inbox text-green-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(59,4,'إرسال الشحنات','send','/shipments/send','fa fa-paper-plane text-blue-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(60,4,'متابعة حالة الشحنات','track','/shipments/track','fa fa-map-marker-alt text-red-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(61,5,'إنشاء فواتير المبيعات','sales','/invoices/sales','fa fa-receipt text-yellow-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(62,5,'إنشاء فواتير المشتريات','purchases','/invoices/purchases','fa fa-shopping-cart text-green-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(63,5,'تتبع تفاصيل الفواتير','track','/invoices/track','fa fa-search text-teal-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(64,6,'ادارة الشركاء','create','/partners','fa fa-plus-square text-green-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(65,6,'المنتجات','track','/products','fa fa-tasks text-blue-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(66,3,'فئات المنتجات','validate','/categories','fa fa-check-circle text-red-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(67,7,'معالجة المرتجعات','process','/returns/process','fa fa-cogs text-yellow-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(68,7,'إرسال المرتجعات للموردين','supplier','/returns/supplier','fa fa-truck-loading text-purple-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(69,8,'إضافة وتحديث بيانات الموردين','add','/suppliers/add','fa fa-user-plus text-green-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(70,8,'متابعة حالة التعامل مع الموردين','track','/suppliers/track','fa fa-clipboard-check text-teal-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(71,8,'إدارة تفاصيل الموردين','details','/suppliers/details','fa fa-info-circle text-blue-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(72,9,'تتبع الكميات في المستودعات والمحلات','warehouses','/tracking/warehouses','fa fa-warehouse text-purple-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(73,9,'تحديث الكميات بناءً على الشحنات والمبيعات','update','/tracking/update','fa fa-sync text-blue-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(74,10,'تقارير المخزون','inventory','/reports/inventory','fa fa-clipboard-list text-green-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(75,10,'تقارير الشحنات','shipments','/reports/shipments','fa fa-shipping-fast text-teal-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(76,10,'تقارير الفواتير','invoices','/reports/invoices','fa fa-file-alt text-orange-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(77,11,'إضافة وتحديث بيانات العملاء والمحلات','customers-stores.add','/customers-stores/add','fa fa-address-card text-blue-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(78,11,'إدارة الطلبات من العملاء','customers-stores.orders','/customers-stores/orders','fa fa-shopping-cart text-green-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(79,11,'إدارة المخزون في المحلات','customers-stores.inventory','/customers-stores/inventory','fa fa-boxes text-yellow-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(80,12,'إضافة وتحديث بيانات المندوبين','representatives.add','/representatives/add','fa fa-user-plus text-green-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(81,12,'تتبع المهام','representatives.tasks','/representatives/tasks','fa fa-tasks text-blue-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(82,12,'إدارة التواصل بين المحلات والمستودعات','representatives.communication','/representatives/communication','fa fa-comments text-purple-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(83,1,'إدارة بيانات المستخدمين','users.index','/users','fa fa-user-edit text-red-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(84,2,'إضافة شركة جديدة','companies.create','/companies/create','fa fa-plus text-green-500','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2),(85,2,'إعدادات النظام','settings','/settings','fa-solid fa-gears','2025-01-31 13:30:35',NULL,'2025-02-01 13:08:19',NULL,2);
/*!40000 ALTER TABLE `module_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `modules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope_level` enum('company','branch','warehouse') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `modules_key_unique` (`key`),
  KEY `modules_branch_id_foreign` (`branch_id`),
  CONSTRAINT `modules_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES (1,'إدارة المستخدمين','مستخدمين','company','2025-01-31 13:26:04',NULL,'2025-01-31 13:26:04',NULL,2),(2,'إدارة الشركات و المستودعات','شركاتومستودعات','branch','2025-01-31 13:26:04',NULL,'2025-01-31 13:26:04',NULL,2),(3,'إدارة المخزون','مخزون','warehouse','2025-01-31 13:26:04',NULL,'2025-01-31 13:26:04',NULL,2),(4,'إدارة الشحنات','شحنات','branch','2025-01-31 13:26:04',NULL,'2025-01-31 13:26:04',NULL,2),(5,'إدارة الفواتير','فواتير','company','2025-01-31 13:26:04',NULL,'2025-01-31 13:26:04',NULL,2),(6,'إدارة الشركاء','شركاء','company','2025-01-31 13:26:04',NULL,'2025-01-31 13:26:04',NULL,2),(7,'إدارة العوائد (المرتجعات)','عوائد(مرتجعات)','company','2025-01-31 13:26:04',NULL,'2025-01-31 13:26:04',NULL,2),(8,'إدارة الموردين','موردين','company','2025-01-31 13:26:04',NULL,'2025-01-31 13:26:04',NULL,2),(9,'تتبع الكميات','تتبعكميات','warehouse','2025-01-31 13:26:04',NULL,'2025-01-31 13:26:04',NULL,2),(10,'إنشاء التقارير','إنشاءتقارير','company','2025-01-31 13:26:04',NULL,'2025-01-31 13:26:04',NULL,2),(11,'إدارة العملاء والمحلات','عملاءومحلات','branch','2025-01-31 13:26:04',NULL,'2025-01-31 13:26:04',NULL,2),(12,'إدارة مندوبين المحلات','مندوبينمحلات','branch','2025-01-31 13:26:04',NULL,'2025-01-31 13:26:04',NULL,2);
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partner_types`
--

DROP TABLE IF EXISTS `partner_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partner_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `partner_types_name_unique` (`name`),
  KEY `partner_types_branch_id_foreign` (`branch_id`),
  CONSTRAINT `partner_types_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partner_types`
--

LOCK TABLES `partner_types` WRITE;
/*!40000 ALTER TABLE `partner_types` DISABLE KEYS */;
INSERT INTO `partner_types` VALUES (1,'مورد','2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(2,'عميل','2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(3,'مندوب','2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(4,'جهة متبرعة','2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(5,'شريك لوجستي','2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(6,'ورشة تدوير','2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(7,'جهة حكومية','2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(8,'مؤسسة غير ربحية','2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2);
/*!40000 ALTER TABLE `partner_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` bigint unsigned NOT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `tax_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `partners_email_unique` (`email`),
  UNIQUE KEY `partners_tax_number_unique` (`tax_number`),
  KEY `partners_type_foreign` (`type`),
  KEY `partners_branch_id_foreign` (`branch_id`),
  CONSTRAINT `partners_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `partners_type_foreign` FOREIGN KEY (`type`) REFERENCES `partner_types` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partners`
--

LOCK TABLES `partners` WRITE;
/*!40000 ALTER TABLE `partners` DISABLE KEYS */;
/*!40000 ALTER TABLE `partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module_id` bigint unsigned NOT NULL,
  `module_action_id` bigint unsigned NOT NULL,
  `permission_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scope_level` enum('company','branch','warehouse') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'company',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_unique` (`name`),
  UNIQUE KEY `permissions_permission_key_unique` (`permission_key`),
  KEY `permissions_module_id_foreign` (`module_id`),
  KEY `permissions_module_action_id_foreign` (`module_action_id`),
  KEY `permissions_branch_id_foreign` (`branch_id`),
  CONSTRAINT `permissions_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `permissions_module_action_id_foreign` FOREIGN KEY (`module_action_id`) REFERENCES `module_actions` (`id`),
  CONSTRAINT `permissions_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'إدارة بيانات المستخدمين',1,42,'مستخدمين.users.index','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(2,'إدارة أدوار المستخدمين',1,43,'مستخدمين.roles','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(3,'منح الأذونات والصلاحيات',1,44,'مستخدمين.permissions','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(4,'مراقبة نشاط المستخدمين',1,45,'مستخدمين.activity','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(5,'إضافة مستودع جديد',2,46,'شركاتومستودعات.create','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(6,'قائمة المستودعات',2,47,'شركاتومستودعات.warehouses','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(7,'إدارة مواقع المستودع',2,48,'شركاتومستودعات.locations','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(8,'إدارة المناطق التخزينية',2,49,'شركاتومستودعات.storage-areas','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(9,'إدارة الأمان والتصاريح',2,50,'شركاتومستودعات.security','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(10,'التدقيق والمراجعة',2,51,'شركاتومستودعات.audit','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(11,'تقارير المستودع',2,52,'شركاتومستودعات.reports','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(12,'قائمة الشركات',2,53,'شركاتومستودعات.settings','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(13,'إضافة شركة جديدة',2,84,'شركاتومستودعات.companies.create','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(14,'اضافة منتج جديد',3,54,'مخزون.add','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(15,'حركات مخزنية',3,55,'مخزون.update-quantities','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(16,'توزيع المخزون',3,56,'مخزون.distribute','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(17,'مراقبة حالة المخزون',3,57,'مخزون.monitor','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(18,'فئات المنتجات',3,66,'مخزون.validate','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(19,'استلام الشحنات',4,58,'شحنات.receive','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(20,'إرسال الشحنات',4,59,'شحنات.send','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(21,'متابعة حالة الشحنات',4,60,'شحنات.track','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(22,'إنشاء فواتير المبيعات',5,61,'فواتير.sales','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(23,'إنشاء فواتير المشتريات',5,62,'فواتير.purchases','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(24,'تتبع تفاصيل الفواتير',5,63,'فواتير.track','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(25,'ادارة الشركاء',6,64,'شركاء.create','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(26,'المنتجات',6,65,'شركاء.track','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(27,'معالجة المرتجعات',7,67,'عوائد(مرتجعات).process','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(28,'إرسال المرتجعات للموردين',7,68,'عوائد(مرتجعات).supplier','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(29,'إضافة وتحديث بيانات الموردين',8,69,'موردين.add','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(30,'متابعة حالة التعامل مع الموردين',8,70,'موردين.track','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(31,'إدارة تفاصيل الموردين',8,71,'موردين.details','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(32,'تتبع الكميات في المستودعات والمحلات',9,72,'تتبعكميات.warehouses','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(33,'تحديث الكميات بناءً على الشحنات والمبيعات',9,73,'تتبعكميات.update','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(34,'تقارير المخزون',10,74,'إنشاءتقارير.inventory','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(35,'تقارير الشحنات',10,75,'إنشاءتقارير.shipments','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(36,'تقارير الفواتير',10,76,'إنشاءتقارير.invoices','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(37,'إضافة وتحديث بيانات العملاء والمحلات',11,77,'عملاءومحلات.customers-stores.add','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(38,'إدارة الطلبات من العملاء',11,78,'عملاءومحلات.customers-stores.orders','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(39,'إدارة المخزون في المحلات',11,79,'عملاءومحلات.customers-stores.inventory','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(40,'إضافة وتحديث بيانات المندوبين',12,80,'مندوبينمحلات.representatives.add','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(41,'تتبع المهام',12,81,'مندوبينمحلات.representatives.tasks','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL),(42,'إدارة التواصل بين المحلات والمستودعات',12,82,'مندوبينمحلات.representatives.communication','company','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,NULL);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `category_id` bigint unsigned NOT NULL,
  `supplier_id` bigint unsigned DEFAULT NULL,
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `selling_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock_quantity` int unsigned NOT NULL DEFAULT '0',
  `min_stock_level` int unsigned NOT NULL DEFAULT '1',
  `max_stock_level` int unsigned DEFAULT NULL,
  `unit_id` bigint unsigned NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  UNIQUE KEY `products_barcode_unique` (`barcode`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_supplier_id_foreign` (`supplier_id`),
  KEY `products_unit_id_foreign` (`unit_id`),
  KEY `products_branch_id_foreign` (`branch_id`),
  CONSTRAINT `products_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `partners` (`id`) ON DELETE SET NULL,
  CONSTRAINT `products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'قهوة عربية','products/image1.jpg','Ut et voluptas molestiae voluptate.',5,NULL,'9799114912347','SKU-76845',68.93,92.03,32,6,110,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(2,'لوحة مفاتيح ميكانيكية','products/image7.jpg','Voluptatem recusandae repudiandae minima nesciunt reprehenderit sit vel.',5,NULL,'9794519534637','SKU-32648',99.63,131.70,21,9,162,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(3,'قهوة عربية','products/image6.jpg','Nisi sunt maiores placeat nostrum cumque.',5,NULL,'9780659978707','SKU-84327',49.20,57.74,56,4,123,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(4,'حاسوب محمول','products/image2.jpg','Voluptate ut dolore officiis aliquid id eum.',5,NULL,'9794193239187','SKU-55177',24.00,39.88,49,3,196,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(5,'تمر مجفف','products/image11.jpg','Animi sunt maiores eum pariatur nam.',3,NULL,'9782846515399','SKU-97495',77.93,27.85,14,4,119,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(6,'تيشيرت قطني','products/image14.jpg','Delectus enim voluptatem minima expedita deserunt.',5,NULL,'9791722642371','SKU-74428',33.69,21.57,25,1,159,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(7,'ماوس لاسلكي','products/image15.jpg','Aut neque unde fuga saepe quaerat laudantium sint.',3,NULL,'9791372903419','SKU-43385',34.98,73.12,89,7,187,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(8,'تمر مجفف','products/image2.jpg','Enim consequatur corrupti rem sint non ducimus earum.',2,NULL,'9793842846509','SKU-04482',54.61,34.55,39,8,186,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(9,'حاسوب محمول','products/image10.jpg','Mollitia sequi id recusandae quo vel quae.',2,NULL,'9790365041312','SKU-99917',52.51,144.83,99,10,123,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(10,'شاشة LED','products/image15.jpg','Consectetur consequatur ut fugiat distinctio qui tempore voluptate.',5,NULL,'9785369222737','SKU-98039',93.74,79.06,25,8,104,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(11,'زيت زيتون نقي','products/image10.jpg','Omnis ipsa voluptatum non voluptatem optio quis.',1,NULL,'9784197990955','SKU-24633',22.53,149.20,76,1,168,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(12,'حاسوب محمول','products/image6.jpg','Est exercitationem aut debitis ut placeat beatae inventore.',3,NULL,'9798991618588','SKU-93012',27.05,10.90,35,1,178,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(13,'حذاء رياضي','products/image12.jpg','In voluptas ea sequi repellendus et.',1,NULL,'9780320292927','SKU-34057',18.02,122.82,21,4,173,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(14,'سماعات لاسلكية','products/image13.jpg','Odit quo quae dolorem et.',4,NULL,'9798767284757','SKU-68288',58.79,113.02,86,1,151,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(15,'تمر مجفف','products/image7.jpg','Magnam ea in corporis similique consequatur et.',2,NULL,'9786804111890','SKU-16231',21.07,143.05,28,3,176,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(16,'تيشيرت قطني','products/image9.jpg','Ut unde dolores quam sunt ex.',5,NULL,'9788898353453','SKU-77062',20.34,136.50,39,4,142,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(17,'حقيبة يد جلدية','products/image7.jpg','Molestiae quo accusantium voluptas aliquam consequatur quod illo.',3,NULL,'9796831019717','SKU-61466',82.67,49.30,11,5,176,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(18,'هاتف ذكي','products/image13.jpg','Perferendis culpa rerum suscipit est non quidem inventore.',1,NULL,'9790981178812','SKU-44321',84.90,130.82,89,7,194,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(19,'حقيبة يد جلدية','products/image4.jpg','Ipsum ut temporibus consequatur aut eum eius.',5,NULL,'9796498240127','SKU-71725',20.27,77.09,77,9,151,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(20,'قهوة عربية','products/image3.jpg','Consectetur hic facere molestiae voluptatibus explicabo.',3,NULL,'9792228270877','SKU-81898',97.64,67.79,28,8,107,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(21,'حذاء رياضي','products/image5.jpg','Tempore eaque perferendis ipsum delectus minus ipsa.',3,NULL,'9799699702739','SKU-61755',34.24,53.88,17,9,107,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(22,'هاتف ذكي','products/image12.jpg','Enim explicabo quod dolorem saepe ad.',5,NULL,'9785862156171','SKU-21406',84.11,87.01,88,3,192,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(23,'بنطال جينز','products/image3.jpg','Sed non sed quam deserunt temporibus mollitia quisquam eos.',1,NULL,'9782086405412','SKU-82366',25.94,51.06,73,5,183,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(24,'حاسوب محمول','products/image5.jpg','Ut voluptatem nostrum in dolore ut.',5,NULL,'9788883559631','SKU-72185',36.43,116.56,32,10,188,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(25,'تيشيرت قطني','products/image14.jpg','Nisi est iure hic est ut quia.',1,NULL,'9799316381972','SKU-59165',44.33,68.31,84,9,136,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(26,'تيشيرت قطني','products/image15.jpg','Accusamus quis assumenda qui ea quia.',5,NULL,'9794339276229','SKU-61328',57.77,138.68,83,4,120,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(27,'لوحة مفاتيح ميكانيكية','products/image8.jpg','Ut sunt sequi quas quod non recusandae inventore.',3,NULL,'9791797677919','SKU-82328',69.69,42.12,65,7,114,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(28,'تيشيرت قطني','products/image14.jpg','Voluptas dicta veniam fugit quisquam aliquam natus et.',5,NULL,'9795131467778','SKU-42694',72.44,61.70,74,4,138,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(29,'ساعة ذكية','products/image3.jpg','Ut dolorem dolores ut mollitia.',4,NULL,'9784237870896','SKU-29086',42.36,148.72,92,7,101,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(30,'شوكولاتة فاخرة','products/image14.jpg','Est minus sed aut rem sunt laborum sint enim.',3,NULL,'9792004153592','SKU-81405',90.07,81.02,91,10,126,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(31,'حقيبة يد جلدية','products/image9.jpg','Eius vel qui voluptatibus consequuntur mollitia maxime neque.',3,NULL,'9791316519638','SKU-87135',66.01,20.93,66,3,200,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(32,'بنطال جينز','products/image9.jpg','Libero et dolores quo occaecati corporis.',1,NULL,'9788153951363','SKU-84313',13.42,75.06,46,7,116,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(33,'تيشيرت قطني','products/image9.jpg','Optio qui laboriosam quae excepturi ipsam.',3,NULL,'9794378971727','SKU-16144',95.53,19.98,61,4,178,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(34,'ماوس لاسلكي','products/image12.jpg','Quaerat totam libero expedita qui temporibus blanditiis iusto.',5,NULL,'9783015009299','SKU-34954',77.48,44.16,35,1,165,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(35,'لوحة مفاتيح ميكانيكية','products/image11.jpg','Voluptate aut adipisci autem.',1,NULL,'9793518836476','SKU-93085',34.61,38.68,57,5,200,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(36,'شاشة LED','products/image1.jpg','Esse eius omnis corrupti pariatur enim voluptas et.',5,NULL,'9786292736315','SKU-50114',17.61,105.29,42,4,115,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(37,'سماعات لاسلكية','products/image1.jpg','Repellendus sunt voluptatem cum harum.',1,NULL,'9786830249130','SKU-57683',99.93,117.92,49,9,106,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(38,'تمر مجفف','products/image1.jpg','Ut consequatur placeat voluptas error.',5,NULL,'9781258194314','SKU-83626',79.43,139.15,25,8,118,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(39,'جهاز لوحي','products/image10.jpg','Ipsum voluptatem quas dolor sed perspiciatis quisquam dolor autem.',4,NULL,'9782210759480','SKU-75706',31.40,111.06,79,5,105,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(40,'بنطال جينز','products/image3.jpg','Commodi doloribus enim occaecati minima voluptas quod eum et.',5,NULL,'9797460141367','SKU-59876',85.06,34.95,49,8,131,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(41,'معطف شتوي','products/image15.jpg','Error id ut ut.',1,NULL,'9790425898672','SKU-08285',85.16,40.34,23,9,121,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(42,'هاتف ذكي','products/image8.jpg','Laboriosam assumenda velit qui aliquid non iusto.',1,NULL,'9790556227198','SKU-74346',62.33,62.52,30,9,191,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(43,'جهاز لوحي','products/image12.jpg','Natus asperiores repellat molestiae voluptatem nesciunt vero ut veniam.',2,NULL,'9784480050694','SKU-16984',78.86,137.99,63,1,182,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(44,'تيشيرت قطني','products/image14.jpg','Explicabo consequatur placeat minima non voluptas.',2,NULL,'9781019530771','SKU-86909',9.22,50.53,53,9,157,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(45,'هاتف ذكي','products/image8.jpg','Unde magni exercitationem fugit quis minus qui.',5,NULL,'9791145477529','SKU-94406',58.78,103.94,56,7,149,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(46,'شاشة LED','products/image12.jpg','A eum nemo velit maxime.',5,NULL,'9799183566878','SKU-88631',66.30,104.98,31,10,119,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(47,'هاتف ذكي','products/image15.jpg','Itaque commodi et in porro suscipit architecto autem.',1,NULL,'9791751628520','SKU-24781',41.75,91.31,50,5,127,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(48,'تمر مجفف','products/image4.jpg','Non commodi culpa praesentium rerum odio nihil libero.',3,NULL,'9780313013546','SKU-62651',61.25,72.52,73,4,102,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(49,'تيشيرت قطني','products/image4.jpg','Dolore dolore et accusamus sed.',3,NULL,'9788182760844','SKU-60012',36.83,96.43,73,7,174,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2),(50,'بنطال جينز','products/image15.jpg','Corporis qui magnam quam odio.',1,NULL,'9799817041566','SKU-95622',63.26,48.16,90,8,187,1,1,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL,2);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `request_types`
--

DROP TABLE IF EXISTS `request_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `request_types` (
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
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `request_types`
--

LOCK TABLES `request_types` WRITE;
/*!40000 ALTER TABLE `request_types` DISABLE KEYS */;
INSERT INTO `request_types` VALUES (1,'طلب توريد','طلب توريد من الموردين',NULL,NULL,NULL,NULL),(2,'طلب صرف','طلب صرف داخلي للأقسام',NULL,NULL,NULL,NULL),(3,'طلب ارجاع','طلب إرجاع المنتجات',NULL,NULL,NULL,NULL),(4,'طلب اصلاح','طلب إصلاح المنتجات',NULL,NULL,NULL,NULL),(5,'طلب اعارة','طلب إعارة المنتجات',NULL,NULL,NULL,NULL),(6,'طلب تحويل','طلب تحويل المخزون بين المستودعات',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `request_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_branch`
--

DROP TABLE IF EXISTS `role_branch`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_branch` (
  `role_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `company_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  KEY `role_branch_role_id_foreign` (`role_id`),
  KEY `role_branch_company_id_foreign` (`company_id`),
  KEY `role_branch_branch_id_company_id_foreign` (`branch_id`,`company_id`),
  CONSTRAINT `role_branch_branch_id_company_id_foreign` FOREIGN KEY (`branch_id`, `company_id`) REFERENCES `branches` (`id`, `company_id`) ON DELETE CASCADE,
  CONSTRAINT `role_branch_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_branch_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_branch_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_branch`
--

LOCK TABLES `role_branch` WRITE;
/*!40000 ALTER TABLE `role_branch` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_branch` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_company`
--

DROP TABLE IF EXISTS `role_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_company` (
  `role_id` bigint unsigned NOT NULL,
  `company_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  KEY `role_company_role_id_foreign` (`role_id`),
  KEY `role_company_company_id_foreign` (`company_id`),
  CONSTRAINT `role_company_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_company_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_company`
--

LOCK TABLES `role_company` WRITE;
/*!40000 ALTER TABLE `role_company` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL,
  `permission_id` bigint unsigned NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `status_updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_permissions_role_id_foreign` (`role_id`),
  KEY `role_permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=253 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES (1,1,1,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(2,1,2,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(3,1,3,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(4,1,4,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(5,1,5,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(6,1,6,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(7,1,7,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(8,1,8,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(9,1,9,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(10,1,10,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(11,1,11,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(12,1,12,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(13,1,13,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(14,1,14,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(15,1,15,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(16,1,16,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(17,1,17,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(18,1,18,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(19,1,19,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(20,1,20,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(21,1,21,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(22,1,22,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(23,1,23,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(24,1,24,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(25,1,25,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(26,1,26,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(27,1,27,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(28,1,28,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(29,1,29,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(30,1,30,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(31,1,31,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(32,1,32,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(33,1,33,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(34,1,34,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(35,1,35,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(36,1,36,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(37,1,37,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(38,1,38,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(39,1,39,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(40,1,40,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(41,1,41,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(42,1,42,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(43,2,1,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(44,2,2,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(45,2,3,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(46,2,4,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(47,2,5,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(48,2,6,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(49,2,7,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(50,2,8,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(51,2,9,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(52,2,10,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(53,2,11,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(54,2,12,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(55,2,13,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(56,2,14,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(57,2,15,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(58,2,16,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(59,2,17,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(60,2,18,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(61,2,19,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(62,2,20,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(63,2,21,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(64,2,22,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(65,2,23,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(66,2,24,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(67,2,25,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(68,2,26,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(69,2,27,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(70,2,28,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(71,2,29,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(72,2,30,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(73,2,31,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(74,2,32,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(75,2,33,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(76,2,34,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(77,2,35,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(78,2,36,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(79,2,37,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(80,2,38,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(81,2,39,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(82,2,40,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(83,2,41,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(84,2,42,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(85,3,1,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(86,3,2,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(87,3,3,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(88,3,4,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(89,3,5,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(90,3,6,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(91,3,7,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(92,3,8,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(93,3,9,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(94,3,10,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(95,3,11,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(96,3,12,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(97,3,13,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(98,3,14,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(99,3,15,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(100,3,16,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(101,3,17,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(102,3,18,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(103,3,19,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(104,3,20,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(105,3,21,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(106,3,22,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(107,3,23,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(108,3,24,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(109,3,25,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(110,3,26,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(111,3,27,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(112,3,28,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(113,3,29,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(114,3,30,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(115,3,31,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(116,3,32,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(117,3,33,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(118,3,34,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(119,3,35,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(120,3,36,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(121,3,37,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(122,3,38,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(123,3,39,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(124,3,40,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(125,3,41,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(126,3,42,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(127,4,1,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(128,4,2,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(129,4,3,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(130,4,4,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(131,4,5,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(132,4,6,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(133,4,7,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(134,4,8,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(135,4,9,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(136,4,10,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(137,4,11,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(138,4,12,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(139,4,13,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(140,4,14,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(141,4,15,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(142,4,16,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(143,4,17,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(144,4,18,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(145,4,19,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(146,4,20,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(147,4,21,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(148,4,22,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(149,4,23,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(150,4,24,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(151,4,25,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(152,4,26,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(153,4,27,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(154,4,28,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(155,4,29,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(156,4,30,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(157,4,31,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(158,4,32,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(159,4,33,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(160,4,34,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(161,4,35,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(162,4,36,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(163,4,37,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(164,4,38,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(165,4,39,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(166,4,40,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(167,4,41,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(168,4,42,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(169,5,1,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(170,5,2,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(171,5,3,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(172,5,4,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(173,5,5,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(174,5,6,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(175,5,7,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(176,5,8,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(177,5,9,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(178,5,10,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(179,5,11,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(180,5,12,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(181,5,13,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(182,5,14,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(183,5,15,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(184,5,16,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(185,5,17,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(186,5,18,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(187,5,19,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(188,5,20,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(189,5,21,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(190,5,22,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(191,5,23,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(192,5,24,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(193,5,25,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(194,5,26,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(195,5,27,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(196,5,28,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(197,5,29,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(198,5,30,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(199,5,31,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(200,5,32,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(201,5,33,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(202,5,34,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(203,5,35,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(204,5,36,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(205,5,37,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(206,5,38,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(207,5,39,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(208,5,40,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(209,5,41,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(210,5,42,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(211,6,1,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(212,6,2,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(213,6,3,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(214,6,4,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(215,6,5,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(216,6,6,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(217,6,7,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(218,6,8,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(219,6,9,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(220,6,10,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(221,6,11,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(222,6,12,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(223,6,13,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(224,6,14,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(225,6,15,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(226,6,16,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(227,6,17,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(228,6,18,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(229,6,19,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(230,6,20,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(231,6,21,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(232,6,22,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(233,6,23,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(234,6,24,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(235,6,25,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(236,6,26,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(237,6,27,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(238,6,28,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(239,6,29,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(240,6,30,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(241,6,31,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(242,6,32,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(243,6,33,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(244,6,34,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(245,6,35,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(246,6,36,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(247,6,37,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(248,6,38,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(249,6,39,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(250,6,40,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(251,6,41,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL),(252,6,42,1,'2025-03-01 08:01:12','2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL);
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_user`
--

DROP TABLE IF EXISTS `role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_user` (
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_user_role_id_foreign` (`role_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_user`
--

LOCK TABLES `role_user` WRITE;
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;
INSERT INTO `role_user` VALUES (1,1,NULL,NULL,NULL,NULL),(2,2,NULL,NULL,NULL,NULL),(3,3,NULL,NULL,NULL,NULL),(4,4,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_warehouse`
--

DROP TABLE IF EXISTS `role_warehouse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_warehouse` (
  `role_id` bigint unsigned NOT NULL,
  `warehouse_id` bigint unsigned NOT NULL,
  `branch_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  KEY `role_warehouse_role_id_foreign` (`role_id`),
  KEY `role_warehouse_warehouse_id_foreign` (`warehouse_id`),
  KEY `role_warehouse_branch_id_foreign` (`branch_id`),
  CONSTRAINT `role_warehouse_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_warehouse_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_warehouse_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_warehouse`
--

LOCK TABLES `role_warehouse` WRITE;
/*!40000 ALTER TABLE `role_warehouse` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_warehouse` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'مدير النظام',1,NULL,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL,0),(2,'مدير شركة',1,NULL,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL,0),(3,'مشرف فرع',1,NULL,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL,0),(4,'مسؤول مستودع',1,NULL,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL,0),(5,'موظف شحن',1,NULL,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL,0),(6,'مراقب مخزون',1,NULL,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL,0);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('2rHyBKRm4j436tAHOi7k44hgKDzCaRPCrCgRN1Vh',1,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiV2hZN2xiMmZaV3dITzIyQ2VuSk05WUl6MnlpMlNXYUp0eHZLcjIyZiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1740826972);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`),
  KEY `settings_branch_id_foreign` (`branch_id`),
  CONSTRAINT `settings_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'inventory_transaction_min_date','30',NULL,NULL,NULL,NULL,NULL),(2,'system_start_date','2025-01-01',2,NULL,NULL,NULL,NULL),(3,'fiscal_year_start_date','2025-01-01',NULL,NULL,NULL,NULL,NULL),(4,'fiscal_year_end_date','2024-12-31',NULL,NULL,NULL,NULL,NULL),(5,'daily_transaction_limit','100',NULL,NULL,NULL,NULL,NULL),(6,'minimum_items_per_transaction','1',NULL,NULL,NULL,NULL,NULL),(7,'max_quantity_per_product','1000',NULL,NULL,NULL,NULL,NULL),(8,'currency','RY',NULL,NULL,NULL,NULL,NULL),(9,'tax_rate','14',NULL,NULL,NULL,NULL,NULL),(10,'is_test_mode','true',NULL,NULL,NULL,NULL,NULL),(11,'max_file_size','10',NULL,NULL,NULL,NULL,NULL),(12,'last_settings_update_date','2025-03-01 11:01:12',NULL,NULL,NULL,NULL,NULL),(13,'alert_settings','{\"low_stock_alert\":true,\"email_notifications\":true}',NULL,NULL,NULL,NULL,NULL),(14,'discount_enabled','true',NULL,NULL,NULL,NULL,NULL),(15,'return_period_days','30',NULL,NULL,NULL,NULL,NULL),(16,'logging_enabled','true',NULL,NULL,NULL,NULL,NULL),(17,'auto_close_accounts','true',NULL,NULL,NULL,NULL,NULL),(18,'password_protection_enabled','true',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_types`
--

DROP TABLE IF EXISTS `transaction_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaction_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم نوع العملية المخزنية',
  `description` text COLLATE utf8mb4_unicode_ci COMMENT 'وصف لنوع العملية',
  `effect` tinyint NOT NULL DEFAULT '1' COMMENT 'تحديد ما إذا كانت العملية المخزنية إضافة (1) أو خصم (-1) أو محايدة (0)',
  `branch_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `inventory_movement_count` int NOT NULL DEFAULT '1' COMMENT 'عدد الحركات المخزنية لهذا النوع: 1 للإدخال أو الإخراج، 2 للتحويل المخزني',
  PRIMARY KEY (`id`),
  UNIQUE KEY `transaction_types_name_unique` (`name`),
  KEY `transaction_types_branch_id_foreign` (`branch_id`),
  CONSTRAINT `transaction_types_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_types`
--

LOCK TABLES `transaction_types` WRITE;
/*!40000 ALTER TABLE `transaction_types` DISABLE KEYS */;
INSERT INTO `transaction_types` VALUES (1,'شراء','عملية شراء المنتجات من الموردين وإضافتها إلى المخزون.',1,NULL,NULL,NULL,NULL,NULL,1),(2,'إرجاع من عميل','إرجاع المنتجات من العملاء إلى المخزون بعد البيع.',1,NULL,NULL,NULL,NULL,NULL,1),(3,'إنتاج داخلي','إضافة المنتجات المصنَّعة داخليًا إلى المخزون.',1,NULL,NULL,NULL,NULL,NULL,1),(4,'صرف','صرف المنتجات للأقسام أو الجهات الداخلية داخل الشركة.',-1,NULL,NULL,NULL,NULL,NULL,1),(5,'تحويل مخزني','نقل المنتجات بين المستودعات أو الأرفف المختلفة.',0,NULL,NULL,NULL,NULL,NULL,2),(6,'إرجاع لمورد','إرجاع المنتجات إلى المورد بسبب التلف أو الخطأ في الطلبية.',-1,NULL,NULL,NULL,NULL,NULL,1),(7,'بيع','بيع المنتجات للعملاء وإخراجها من المخزون.',-1,NULL,NULL,NULL,NULL,NULL,1),(8,'جرد مخزني','عملية تدقيق لمقارنة الكمية الفعلية مع الكمية المسجلة في النظام.',0,NULL,NULL,NULL,NULL,NULL,0),(9,'تلف','تسجيل المنتجات التالفة وإخراجها من المخزون.',-1,NULL,NULL,NULL,NULL,NULL,1),(10,'سرقة','تسجيل نقص المخزون الناتج عن فقدان غير مبرر أو سرقة.',-1,NULL,NULL,NULL,NULL,NULL,1),(11,'تعديل يدوي','تصحيح المخزون يدويًا بسبب أخطاء تسجيل سابقة.',0,NULL,NULL,NULL,NULL,NULL,0),(12,'حجز المخزون','تحديد كمية من المخزون لطلبية قبل تنفيذ عملية البيع.',1,NULL,NULL,NULL,NULL,NULL,1),(13,'انتهاء الصلاحية','إخراج المنتجات من المخزون بسبب انتهاء فترة صلاحيتها.',-1,NULL,NULL,NULL,NULL,NULL,1),(14,'استرجاع من الإنتاج','إرجاع المواد الخام غير المستخدمة إلى المخزون بعد التصنيع.',1,NULL,NULL,NULL,NULL,NULL,1);
/*!40000 ALTER TABLE `transaction_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `units` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_unit_id` bigint unsigned DEFAULT NULL,
  `conversion_factor` decimal(10,4) DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `units_name_unique` (`name`),
  KEY `units_parent_unit_id_foreign` (`parent_unit_id`),
  KEY `units_branch_id_foreign` (`branch_id`),
  CONSTRAINT `units_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `units_parent_unit_id_foreign` FOREIGN KEY (`parent_unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
INSERT INTO `units` VALUES (1,'حبة',NULL,NULL,2,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL),(2,'كرتون',1,10.0000,2,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL),(3,'صندوق',2,5.0000,2,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL),(4,'كيلوغرام',NULL,NULL,2,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL),(5,'جرام',4,1000.0000,2,'2025-03-01 08:01:13',NULL,'2025-03-01 08:01:13',NULL);
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `branch_id` bigint unsigned DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_branch_id_foreign` (`branch_id`),
  CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin User','admin@example.com',NULL,'$2y$12$KG4.PreqQVEVbcDPkC/gv.MsytEPP2RSenwzq2oM2sIAgBid8jABO',NULL,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL,2,1),(2,'Representative User','representative@example.com',NULL,'$2y$12$tQ8eJgtLBTqHM77o5cEoSueoTdnYdwh67x/WS96XFoe4BDXSyQYR6',NULL,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL,2,1),(3,'Storekeeper User','storekeeper@example.com',NULL,'$2y$12$mLOxZgmJmKkqiKX9AKJ5JeOw/cSsFylyeE4OUkz3FcQdo6wZ/IcJW',NULL,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL,2,1),(4,'Accountant User','accountant@example.com',NULL,'$2y$12$nENQZs6j5b1tZlH/TL5TaeVrnBXUeTX4JSJg.KDxP7a5Inzlx3Y8.',NULL,'2025-03-01 08:01:12',NULL,'2025-03-01 08:01:12',NULL,2,1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warehouse_category_warehouse`
--

DROP TABLE IF EXISTS `warehouse_category_warehouse`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warehouse_category_warehouse` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint unsigned NOT NULL,
  `warehouse_category_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warehouse_category_warehouse_warehouse_id_foreign` (`warehouse_id`),
  KEY `warehouse_category_warehouse_warehouse_category_id_foreign` (`warehouse_category_id`),
  CONSTRAINT `warehouse_category_warehouse_warehouse_category_id_foreign` FOREIGN KEY (`warehouse_category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `warehouse_category_warehouse_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouse_category_warehouse`
--

LOCK TABLES `warehouse_category_warehouse` WRITE;
/*!40000 ALTER TABLE `warehouse_category_warehouse` DISABLE KEYS */;
/*!40000 ALTER TABLE `warehouse_category_warehouse` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warehouse_locations`
--

DROP TABLE IF EXISTS `warehouse_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warehouse_locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned DEFAULT NULL,
  `warehouse_id` bigint unsigned NOT NULL,
  `storage_area_id` bigint unsigned NOT NULL,
  `aisle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rack` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shelf` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_occupied` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `warehouse_locations_barcode_unique` (`barcode`),
  KEY `warehouse_locations_warehouse_id_foreign` (`warehouse_id`),
  KEY `warehouse_locations_storage_area_id_foreign` (`storage_area_id`),
  KEY `warehouse_locations_branch_id_foreign` (`branch_id`),
  CONSTRAINT `warehouse_locations_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `warehouse_locations_storage_area_id_foreign` FOREIGN KEY (`storage_area_id`) REFERENCES `warehouse_storage_areas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `warehouse_locations_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouse_locations`
--

LOCK TABLES `warehouse_locations` WRITE;
/*!40000 ALTER TABLE `warehouse_locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `warehouse_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warehouse_reports`
--

DROP TABLE IF EXISTS `warehouse_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warehouse_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_id` bigint unsigned NOT NULL,
  `report_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_data` json NOT NULL,
  `report_date` datetime NOT NULL,
  `generated_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warehouse_reports_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `warehouse_reports_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouse_reports`
--

LOCK TABLES `warehouse_reports` WRITE;
/*!40000 ALTER TABLE `warehouse_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `warehouse_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warehouse_storage_areas`
--

DROP TABLE IF EXISTS `warehouse_storage_areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warehouse_storage_areas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint unsigned DEFAULT NULL,
  `warehouse_id` bigint unsigned NOT NULL,
  `area_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NOT NULL,
  `current_occupancy` int NOT NULL DEFAULT '0',
  `zone_id` bigint unsigned DEFAULT NULL,
  `storage_conditions` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `warehouse_storage_areas_warehouse_id_foreign` (`warehouse_id`),
  KEY `warehouse_storage_areas_zone_id_foreign` (`zone_id`),
  KEY `warehouse_storage_areas_branch_id_foreign` (`branch_id`),
  CONSTRAINT `warehouse_storage_areas_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `warehouse_storage_areas_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `warehouse_storage_areas_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `zones` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouse_storage_areas`
--

LOCK TABLES `warehouse_storage_areas` WRITE;
/*!40000 ALTER TABLE `warehouse_storage_areas` DISABLE KEYS */;
/*!40000 ALTER TABLE `warehouse_storage_areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `warehouses`
--

DROP TABLE IF EXISTS `warehouses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `warehouses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_info` text COLLATE utf8mb4_unicode_ci,
  `branch_id` bigint unsigned NOT NULL,
  `supervisor_id` bigint unsigned DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `area` float DEFAULT NULL,
  `capacity` double DEFAULT NULL,
  `is_smart` tinyint(1) NOT NULL DEFAULT '0',
  `has_security_system` tinyint(1) NOT NULL DEFAULT '0',
  `has_cctv` tinyint(1) NOT NULL DEFAULT '0',
  `is_integrated_with_wms` tinyint(1) NOT NULL DEFAULT '0',
  `last_maintenance` timestamp NULL DEFAULT NULL,
  `has_automated_systems` tinyint(1) NOT NULL DEFAULT '0',
  `temperature` double DEFAULT NULL,
  `humidity` double DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'تحديد ما إذا كان المستودع متاحًا أم مغلقًا',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `warehouses_code_unique` (`code`),
  KEY `warehouses_branch_id_foreign` (`branch_id`),
  KEY `warehouses_supervisor_id_foreign` (`supervisor_id`),
  CONSTRAINT `warehouses_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `warehouses_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `warehouses`
--

LOCK TABLES `warehouses` WRITE;
/*!40000 ALTER TABLE `warehouses` DISABLE KEYS */;
INSERT INTO `warehouses` VALUES (1,'مستودع 1 في الفرع 1','WH11','عنوان المستودع 1','معلومات الاتصال للمستودع 1',1,NULL,31.0000000,32.0000000,1000.5,5000,1,1,1,0,'2025-03-01 08:01:11',1,22.5,60,1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL),(2,'مستودع 2 في الفرع 1','WH12','عنوان المستودع 2','معلومات الاتصال للمستودع 2',1,NULL,31.0000000,33.0000000,1000.5,5000,1,1,1,0,'2025-03-01 08:01:11',1,22.5,60,1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL),(3,'مستودع 3 في الفرع 1','WH13','عنوان المستودع 3','معلومات الاتصال للمستودع 3',1,NULL,31.0000000,34.0000000,1000.5,5000,1,1,1,0,'2025-03-01 08:01:11',1,22.5,60,1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL),(4,'مستودع 1 في الفرع 2','WH21','عنوان المستودع 1','معلومات الاتصال للمستودع 1',2,NULL,32.0000000,32.0000000,1000.5,5000,1,1,1,0,'2025-03-01 08:01:11',1,22.5,60,1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL),(5,'مستودع 2 في الفرع 2','WH22','عنوان المستودع 2','معلومات الاتصال للمستودع 2',2,NULL,32.0000000,33.0000000,1000.5,5000,1,1,1,0,'2025-03-01 08:01:11',1,22.5,60,1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL),(6,'مستودع 3 في الفرع 2','WH23','عنوان المستودع 3','معلومات الاتصال للمستودع 3',2,NULL,32.0000000,34.0000000,1000.5,5000,1,1,1,0,'2025-03-01 08:01:11',1,22.5,60,1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL),(7,'مستودع 1 في الفرع 3','WH31','عنوان المستودع 1','معلومات الاتصال للمستودع 1',3,NULL,33.0000000,32.0000000,1000.5,5000,1,1,1,0,'2025-03-01 08:01:11',1,22.5,60,1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL),(8,'مستودع 2 في الفرع 3','WH32','عنوان المستودع 2','معلومات الاتصال للمستودع 2',3,NULL,33.0000000,33.0000000,1000.5,5000,1,1,1,0,'2025-03-01 08:01:11',1,22.5,60,1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL),(9,'مستودع 3 في الفرع 3','WH33','عنوان المستودع 3','معلومات الاتصال للمستودع 3',3,NULL,33.0000000,34.0000000,1000.5,5000,1,1,1,0,'2025-03-01 08:01:11',1,22.5,60,1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL),(10,'مستودع 1 في الفرع 4','WH41','عنوان المستودع 1','معلومات الاتصال للمستودع 1',4,NULL,34.0000000,32.0000000,1000.5,5000,1,1,1,0,'2025-03-01 08:01:11',1,22.5,60,1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL),(11,'مستودع 2 في الفرع 4','WH42','عنوان المستودع 2','معلومات الاتصال للمستودع 2',4,NULL,34.0000000,33.0000000,1000.5,5000,1,1,1,0,'2025-03-01 08:01:11',1,22.5,60,1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL),(12,'مستودع 3 في الفرع 4','WH43','عنوان المستودع 3','معلومات الاتصال للمستودع 3',4,NULL,34.0000000,34.0000000,1000.5,5000,1,1,1,0,'2025-03-01 08:01:11',1,22.5,60,1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL),(13,'مستودع 1 في الفرع 5','WH51','عنوان المستودع 1','معلومات الاتصال للمستودع 1',5,NULL,35.0000000,32.0000000,1000.5,5000,1,1,1,0,'2025-03-01 08:01:11',1,22.5,60,1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL),(14,'مستودع 2 في الفرع 5','WH52','عنوان المستودع 2','معلومات الاتصال للمستودع 2',5,NULL,35.0000000,33.0000000,1000.5,5000,1,1,1,0,'2025-03-01 08:01:11',1,22.5,60,1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL),(15,'مستودع 3 في الفرع 5','WH53','عنوان المستودع 3','معلومات الاتصال للمستودع 3',5,NULL,35.0000000,34.0000000,1000.5,5000,1,1,1,0,'2025-03-01 08:01:11',1,22.5,60,1,'2025-03-01 08:01:11',NULL,'2025-03-01 08:01:11',NULL);
/*!40000 ALTER TABLE `warehouses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `zones`
--

DROP TABLE IF EXISTS `zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `zones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_user` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_user` bigint unsigned DEFAULT NULL,
  `warehouse_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `zones_name_unique` (`name`),
  UNIQUE KEY `zones_code_unique` (`code`),
  KEY `zones_warehouse_id_foreign` (`warehouse_id`),
  CONSTRAINT `zones_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `zones`
--

LOCK TABLES `zones` WRITE;
/*!40000 ALTER TABLE `zones` DISABLE KEYS */;
/*!40000 ALTER TABLE `zones` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-03-01 19:06:19

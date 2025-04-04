-- إضافة التعليقات إلى جدول branches
ALTER TABLE `branches`
  MODIFY `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرف الفرع (رقم فريد)',
  MODIFY `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم الفرع',
  MODIFY `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'عنوان الفرع',
  MODIFY `contact_info` text COLLATE utf8mb4_unicode_ci COMMENT 'معلومات الاتصال',
  MODIFY `company_id` bigint unsigned NOT NULL COMMENT 'معرف الشركة المرتبطة بالفرع',
  MODIFY `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت الإنشاء',
  MODIFY `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت التحديث';

-- إضافة التعليقات إلى جدول cache_locks
ALTER TABLE `cache_locks`
  MODIFY `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'المفتاح الخاص بالقفل',
  MODIFY `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'مالك القفل',
  MODIFY `expiration` int NOT NULL COMMENT 'مدة انتهاء القفل (بالثواني)';

-- إضافة التعليقات إلى جدول categories
ALTER TABLE `categories`
  MODIFY `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرف الفئة (رقم فريد)',
  MODIFY `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم الفئة',
  MODIFY `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رمز الفئة',
  MODIFY `description` text COLLATE utf8mb4_unicode_ci COMMENT 'وصف الفئة',
  MODIFY `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت الإنشاء',
  MODIFY `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ الفئة',
  MODIFY `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت التحديث',
  MODIFY `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي قام بالتحديث',
  MODIFY `branch_id` bigint unsigned DEFAULT NULL COMMENT 'معرف الفرع المرتبط بالفئة';

-- إضافة التعليقات إلى جدول companies
ALTER TABLE `companies`
  MODIFY `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرف الشركة (رقم فريد)',
  MODIFY `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم الشركة',
  MODIFY `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'شعار الشركة',
  MODIFY `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رقم الهاتف',
  MODIFY `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'البريد الإلكتروني',
  MODIFY `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'عنوان الشركة',
  MODIFY `additional_info` text COLLATE utf8mb4_unicode_ci COMMENT 'معلومات إضافية',
  MODIFY `settings` json DEFAULT NULL COMMENT 'الإعدادات الخاصة بالشركة',
  MODIFY `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت الإنشاء',
  MODIFY `created_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ الشركة',
  MODIFY `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت التحديث',
  MODIFY `updated_user` bigint unsigned DEFAULT NULL COMMENT 'معرف المستخدم الذي قام بالتحديث';

-- إضافة التعليقات إلى جدول currencies
ALTER TABLE `currencies`
  MODIFY `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'معرف العملة (رقم فريد)',
  MODIFY `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم العملة',
  MODIFY `code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رمز العملة',
  MODIFY `symbol` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رمز العملة (مثال: $)',
  MODIFY `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت الإنشاء',
  MODIFY `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ ووقت التحديث';
-- تعديل هيكل جدول departments
ALTER TABLE `departments`
  MODIFY `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للقسم',
  MODIFY `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم القسم',
  MODIFY `description` text COLLATE utf8mb4_unicode_ci COMMENT 'وصف القسم',
  MODIFY `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'حالة تفعيل القسم';

-- تعديل هيكل جدول inventory
ALTER TABLE `inventory`
  MODIFY `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للمخزون',
  MODIFY `warehouse_id` bigint unsigned NOT NULL COMMENT 'المعرّف الفريد للمستودع',
  MODIFY `product_id` bigint unsigned NOT NULL COMMENT 'المعرّف الفريد للمنتج',
  MODIFY `quantity` int NOT NULL COMMENT 'كمية المنتج في المخزون',
  MODIFY `unit_price` decimal(10,2) NOT NULL COMMENT 'سعر الوحدة',
  MODIFY `total_value` decimal(15,2) NOT NULL COMMENT 'إجمالي قيمة المخزون';

-- تعديل هيكل جدول inventory_audits
ALTER TABLE `inventory_audits`
  MODIFY `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد لعملية الجرد',
  MODIFY `inventory_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'كود الجرد الفريد',
  MODIFY `inventory_type` tinyint unsigned NOT NULL COMMENT 'نوع الجرد',
  MODIFY `start_date` datetime DEFAULT NULL COMMENT 'تاريخ بدء الجرد',
  MODIFY `end_date` datetime DEFAULT NULL COMMENT 'تاريخ انتهاء الجرد',
  MODIFY `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT 'حالة الجرد',
  MODIFY `expected_products_count` int DEFAULT NULL COMMENT 'عدد المنتجات المتوقع وجودها',
  MODIFY `counted_products_count` int DEFAULT NULL COMMENT 'عدد المنتجات التي تم عدّها',
  MODIFY `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'ملاحظات حول الجرد';

-- تعديل هيكل جدول inventory_audit_users
ALTER TABLE `inventory_audit_users`
  MODIFY `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد لسجل المستخدم في الجرد',
  MODIFY `inventory_audit_id` bigint unsigned NOT NULL COMMENT 'المعرّف الفريد للجرد المرتبط',
  MODIFY `user_id` bigint unsigned NOT NULL COMMENT 'المعرّف الفريد للمستخدم',
  MODIFY `branch_id` bigint unsigned NOT NULL COMMENT 'المعرّف الفريد للفرع',
  MODIFY `operation_type` tinyint unsigned NOT NULL DEFAULT '1' COMMENT 'نوع العملية التي قام بها المستخدم';

-- تعديل هيكل جدول inventory_audit_warehouses
ALTER TABLE `inventory_audit_warehouses`
  MODIFY `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد لسجل الجرد في المستودع',
  MODIFY `inventory_audit_id` bigint unsigned NOT NULL COMMENT 'المعرّف الفريد للجرد المرتبط',
  MODIFY `warehouse_id` bigint unsigned NOT NULL COMMENT 'المعرّف الفريد للمستودع',
  MODIFY `branch_id` bigint unsigned NOT NULL COMMENT 'المعرّف الفريد للفرع';

-- تعديل هيكل جدول inventory_products
ALTER TABLE `inventory_products`
  MODIFY `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرّف الفريد للمنتج داخل المستودع',
  MODIFY `batch_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رقم الدفعة للمنتج',
  MODIFY `production_date` date DEFAULT NULL COMMENT 'تاريخ الإنتاج',
  MODIFY `expiration_date` date DEFAULT NULL COMMENT 'تاريخ انتهاء الصلاحية',
  MODIFY `product_id` bigint unsigned NOT NULL COMMENT 'المعرّف الفريد للمنتج',
  MODIFY `branch_id` bigint unsigned DEFAULT NULL COMMENT 'المعرّف الفريد للفرع',
  MODIFY `warehouse_id` bigint unsigned NOT NULL COMMENT 'المعرّف الفريد للمستودع',
  MODIFY `storage_area_id` bigint unsigned NOT NULL COMMENT 'المعرّف الفريد لمنطقة التخزين',
  MODIFY `location_id` bigint unsigned DEFAULT NULL COMMENT 'المعرّف الفريد لموقع التخزين',
  MODIFY `created_user` bigint unsigned DEFAULT NULL COMMENT 'رقم المستخدم الذي قام بإضافة هذا السجل',
  MODIFY `updated_user` bigint unsigned DEFAULT NULL COMMENT 'رقم المستخدم الذي قام بآخر تحديث لهذا السجل',
  MODIFY `quantity` int NOT NULL DEFAULT '0' COMMENT 'كمية المنتج الحالية في المستودع',
  MODIFY `temporary_transfer_expiry_date` timestamp NULL DEFAULT NULL COMMENT 'تاريخ انتهاء النقل المؤقت',
  MODIFY `distribution_type` tinyint NOT NULL DEFAULT (1) COMMENT 'نوع التوزيع',
  MODIFY `unit_id` bigint unsigned DEFAULT NULL COMMENT 'المعرّف الفريد للوحدة',
  MODIFY `unit_product_id` bigint unsigned DEFAULT NULL COMMENT 'المعرّف الفريد للوحدة المرتبطة بالمنتج',
  MODIFY `converted_quantity` decimal(10,4) DEFAULT NULL COMMENT 'الكمية المحولة حسب الوحدة',
  MODIFY `price` decimal(20,6) DEFAULT NULL COMMENT 'سعر المنتج في المستودع';
  
  ALTER TABLE `inventory_requests` 
MODIFY COLUMN `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرف الفريد لطلب المخزون',
MODIFY COLUMN `transaction_type_id` bigint unsigned NOT NULL COMMENT 'نوع العملية المرتبطة بالطلب',
MODIFY COLUMN `partner_id` bigint unsigned DEFAULT NULL COMMENT 'المورد أو العميل المرتبط بالطلب',
MODIFY COLUMN `department_id` bigint unsigned DEFAULT NULL COMMENT 'القسم الذي قدم الطلب',
MODIFY COLUMN `warehouse_id` bigint unsigned NOT NULL COMMENT 'المستودع الذي سيتم تنفيذ الطلب منه',
MODIFY COLUMN `branch_id` bigint unsigned DEFAULT NULL COMMENT 'الفرع المرتبط بالطلب',
MODIFY COLUMN `created_user` bigint unsigned DEFAULT NULL COMMENT 'المستخدم الذي أنشأ الطلب',
MODIFY COLUMN `updated_user` bigint unsigned DEFAULT NULL COMMENT 'آخر مستخدم قام بالتعديل';

ALTER TABLE `inventory_request_details` 
MODIFY COLUMN `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرف الفريد لتفاصيل الطلب',
MODIFY COLUMN `inventory_request_id` bigint unsigned NOT NULL COMMENT 'الطلب الرئيسي المرتبط بهذه التفاصيل',
MODIFY COLUMN `product_id` bigint unsigned NOT NULL COMMENT 'المنتج المطلوب',
MODIFY COLUMN `quantity` int NOT NULL COMMENT 'عدد الوحدات المطلوبة',
MODIFY COLUMN `unit_price` decimal(10,2) DEFAULT NULL COMMENT 'السعر لكل وحدة',
MODIFY COLUMN `total` decimal(15,2) DEFAULT NULL COMMENT 'المجموع الكلي للتكلفة',
MODIFY COLUMN `warehouse_location_id` bigint unsigned DEFAULT NULL COMMENT 'موقع التخزين داخل المستودع',
MODIFY COLUMN `branch_id` bigint unsigned DEFAULT NULL COMMENT 'الفرع المرتبط بالطلب';

ALTER TABLE `inventory_transactions` 
MODIFY COLUMN `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرف الفريد للعملية المخزنية',
MODIFY COLUMN `transaction_type_id` bigint unsigned NOT NULL COMMENT 'نوع العملية (إضافة، إخراج، تعديل)',
MODIFY COLUMN `effect` tinyint DEFAULT NULL COMMENT 'تأثير العملية على المخزون (+ زيادة، - نقصان، 0 محايدة)',
MODIFY COLUMN `transaction_date` timestamp NOT NULL COMMENT 'تاريخ تنفيذ العملية',
MODIFY COLUMN `reference` varchar(255) DEFAULT NULL COMMENT 'مرجع العملية إن وجد',
MODIFY COLUMN `partner_id` bigint unsigned DEFAULT NULL COMMENT 'المورد أو العميل المرتبط بالعملية',
MODIFY COLUMN `department_id` bigint unsigned DEFAULT NULL COMMENT 'القسم الذي قام بالعملية',
MODIFY COLUMN `warehouse_id` bigint unsigned NOT NULL COMMENT 'المستودع الذي حدثت فيه العملية',
MODIFY COLUMN `secondary_warehouse_id` bigint unsigned DEFAULT NULL COMMENT 'المستودع الثانوي المستخدم إن وجد',
MODIFY COLUMN `status` int NOT NULL DEFAULT '0' COMMENT 'حالة العملية المخزنية';

ALTER TABLE `inventory_transaction_items` 
MODIFY COLUMN `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرف الفريد لتفاصيل العنصر في العملية المخزنية',
MODIFY COLUMN `inventory_transaction_id` bigint unsigned NOT NULL COMMENT 'المعرف المرتبط بالعملية المخزنية',
MODIFY COLUMN `product_id` bigint unsigned NOT NULL COMMENT 'المنتج المتأثر بالعملية',
MODIFY COLUMN `batch_code` varchar(255) DEFAULT NULL COMMENT 'كود الدفعة الخاصة بالمنتج',
MODIFY COLUMN `quantity` int NOT NULL COMMENT 'عدد الوحدات المتأثرة',
MODIFY COLUMN `unit_id` bigint unsigned DEFAULT NULL COMMENT 'الوحدة المستخدمة',
MODIFY COLUMN `converted_quantity` decimal(10,4) DEFAULT NULL COMMENT 'الكمية المحولة حسب الوحدة',
MODIFY COLUMN `unit_prices` decimal(15,2) DEFAULT NULL COMMENT 'سعر الوحدة',
MODIFY COLUMN `total` decimal(20,6) DEFAULT NULL COMMENT 'الإجمالي بعد الحساب',
MODIFY COLUMN `warehouse_location_id` bigint unsigned DEFAULT NULL COMMENT 'الموقع داخل المستودع',
MODIFY COLUMN `branch_id` bigint unsigned DEFAULT NULL COMMENT 'الفرع المرتبط بالعملية',
MODIFY COLUMN `target_warehouse_id` bigint unsigned DEFAULT NULL COMMENT 'المستودع المستهدف في عمليات التحويل',
MODIFY COLUMN `source_warehouse_id` bigint unsigned DEFAULT NULL COMMENT 'المستودع المصدر في عمليات التحويل',
MODIFY COLUMN `status` tinyint unsigned NOT NULL DEFAULT '1' COMMENT 'حالة العنصر في العملية: 1 = قيد التنفيذ، 2 = مكتملة، 3 = مُلغاة',
MODIFY COLUMN `result` tinyint unsigned NOT NULL DEFAULT '1' COMMENT 'نتيجة الجرد: 1 = مطابقة، 2 = تلف، 3 = فقدان، 4 = نقل',
MODIFY COLUMN `expected_audit_quantity` int DEFAULT NULL COMMENT 'الكمية المتوقعة من الجرد',
MODIFY COLUMN `batch_number` varchar(255) DEFAULT NULL COMMENT 'رقم الدفعة من جدول inventory_products';

ALTER TABLE `inventory_update_errors` 
MODIFY COLUMN `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'المعرف الفريد لخطأ التحديث',
MODIFY COLUMN `inventory_transaction_item_id` bigint unsigned NOT NULL COMMENT 'العنصر المرتبط بالخطأ',
MODIFY COLUMN `product_id` bigint unsigned NOT NULL COMMENT 'المنتج الذي حدث فيه الخطأ',
MODIFY COLUMN `warehouse_id` bigint unsigned NOT NULL COMMENT 'المستودع المرتبط بالخطأ',
MODIFY COLUMN `quantity` int NOT NULL COMMENT 'الكمية التي تسببت في الخطأ',
MODIFY COLUMN `error_message` varchar(255) NOT NULL COMMENT 'وصف الخطأ';

-- إضافة تعليقات إلى جدول modules
ALTER TABLE `modules` 
  COMMENT = 'يخزن معلومات عن الوحدات البرمجية في النظام';

ALTER TABLE `modules` MODIFY COLUMN `id` BIGINT UNSIGNED AUTO_INCREMENT COMMENT 'معرف الوحدة البرمجية',
MODIFY COLUMN `name` VARCHAR(255) COLLATE utf8mb4_unicode_ci COMMENT 'اسم الوحدة البرمجية',
MODIFY COLUMN `key` VARCHAR(255) COLLATE utf8mb4_unicode_ci COMMENT 'مفتاح الوحدة البرمجية',
MODIFY COLUMN `scope_level` ENUM('company','branch','warehouse') COLLATE utf8mb4_unicode_ci COMMENT 'مستوى النطاق: الشركة، الفرع، المخزن',
MODIFY COLUMN `created_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'تاريخ ووقت الإنشاء',
MODIFY COLUMN `created_user` BIGINT UNSIGNED DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ الوحدة',
MODIFY COLUMN `updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'تاريخ ووقت آخر تحديث',
MODIFY COLUMN `updated_user` BIGINT UNSIGNED DEFAULT NULL COMMENT 'معرف المستخدم الذي قام بآخر تحديث',
MODIFY COLUMN `branch_id` BIGINT UNSIGNED DEFAULT NULL COMMENT 'معرف الفرع';

-- إضافة تعليقات إلى جدول module_actions
ALTER TABLE `module_actions` 
  COMMENT = 'يخزن الإجراءات المتاحة للوحدات البرمجية مثل إضافة أو تعديل أو حذف';

ALTER TABLE `module_actions` MODIFY COLUMN `id` BIGINT UNSIGNED AUTO_INCREMENT COMMENT 'معرف الإجراء',
MODIFY COLUMN `module_id` BIGINT UNSIGNED NOT NULL COMMENT 'معرف الوحدة البرمجية',
MODIFY COLUMN `name` VARCHAR(255) COLLATE utf8mb4_unicode_ci COMMENT 'اسم الإجراء',
MODIFY COLUMN `action_key` VARCHAR(255) COLLATE utf8mb4_unicode_ci COMMENT 'مفتاح الإجراء',
MODIFY COLUMN `route` VARCHAR(255) COLLATE utf8mb4_unicode_ci COMMENT 'المسار المرتبط بالإجراء',
MODIFY COLUMN `icon` VARCHAR(255) COLLATE utf8mb4_unicode_ci COMMENT 'أيقونة الإجراء',
MODIFY COLUMN `created_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'تاريخ ووقت الإنشاء',
MODIFY COLUMN `created_user` BIGINT UNSIGNED DEFAULT NULL COMMENT 'معرف المستخدم الذي أنشأ الإجراء',
MODIFY COLUMN `updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'تاريخ ووقت آخر تحديث',
MODIFY COLUMN `updated_user` BIGINT UNSIGNED DEFAULT NULL COMMENT 'معرف المستخدم الذي قام بآخر تحديث',
MODIFY COLUMN `branch_id` BIGINT UNSIGNED DEFAULT NULL COMMENT 'معرف الفرع';

-- إضافة تعليقات إلى جدول notifications
ALTER TABLE `notifications` 
  COMMENT = 'يخزن الإشعارات المرسلة للمستخدمين';

ALTER TABLE `notifications` MODIFY COLUMN `id` INT AUTO_INCREMENT COMMENT 'معرف الإشعار',
MODIFY COLUMN `user_id` INT COMMENT 'معرف المستخدم الذي تم إرسال الإشعار إليه',
MODIFY COLUMN `message` TEXT COMMENT 'محتوى الإشعار',
MODIFY COLUMN `status` ENUM('read', 'unread') COMMENT 'حالة الإشعار';

-- إضافة تعليقات إلى جدول orders
ALTER TABLE `orders` 
  COMMENT = 'يخزن بيانات الطلبات مثل الطلبات التجارية';

ALTER TABLE `orders` MODIFY COLUMN `id` INT AUTO_INCREMENT COMMENT 'معرف الطلب',
MODIFY COLUMN `partner_id` INT COMMENT 'معرف الشريك المرتبط بالطلب',
MODIFY COLUMN `order_date` DATETIME COMMENT 'تاريخ الطلب',
MODIFY COLUMN `total_amount` DECIMAL(10,2) COMMENT 'إجمالي قيمة الطلب',
MODIFY COLUMN `status` ENUM('pending', 'completed', 'cancelled') COMMENT 'حالة الطلب';

-- إضافة تعليقات إلى جدول order_details
ALTER TABLE `order_details` 
  COMMENT = 'يخزن تفاصيل المنتجات المرتبطة بكل طلب';

ALTER TABLE `order_details` MODIFY COLUMN `id` INT AUTO_INCREMENT COMMENT 'معرف تفاصيل الطلب',
MODIFY COLUMN `order_id` INT COMMENT 'معرف الطلب',
MODIFY COLUMN `product_id` INT COMMENT 'معرف المنتج',
MODIFY COLUMN `quantity` INT COMMENT 'الكمية المطلوبة',
MODIFY COLUMN `price` DECIMAL(10,2) COMMENT 'سعر المنتج';

-- إضافة تعليقات إلى جدول partners
ALTER TABLE `partners` 
  COMMENT = 'يخزن بيانات الشركاء مثل العملاء والموردين';

ALTER TABLE `partners` MODIFY COLUMN `id` INT AUTO_INCREMENT COMMENT 'معرف الشريك',
MODIFY COLUMN `name` VARCHAR(255) COMMENT 'اسم الشريك',
MODIFY COLUMN `partner_type_id` INT COMMENT 'نوع الشريك',
MODIFY COLUMN `contact_info` TEXT COMMENT 'معلومات الاتصال بالشريك',
MODIFY COLUMN `tax_id` VARCHAR(50) COMMENT 'رقم الضريبة للشريك';

-- إضافة تعليقات إلى جدول partner_types
ALTER TABLE `partner_types` 
  COMMENT = 'يخزن أنواع الشركاء مثل العملاء، الموردين، وغيرهم';

ALTER TABLE `partner_types` MODIFY COLUMN `id` INT AUTO_INCREMENT COMMENT 'معرف نوع الشريك',
MODIFY COLUMN `type_name` VARCHAR(255) COMMENT 'اسم نوع الشريك';

-- إضافة تعليقات إلى جدول password_reset_tokens
ALTER TABLE `password_reset_tokens` 
  COMMENT = 'يخزن رموز إعادة تعيين كلمات المرور للمستخدمين';

ALTER TABLE `password_reset_tokens` MODIFY COLUMN `id` INT AUTO_INCREMENT COMMENT 'معرف رمز إعادة التعيين',
MODIFY COLUMN `user_id` INT COMMENT 'معرف المستخدم',
MODIFY COLUMN `token` VARCHAR(255) COMMENT 'رمز إعادة تعيين كلمة المرور',
MODIFY COLUMN `created_at` DATETIME COMMENT 'تاريخ ووقت إنشاء رمز إعادة التعيين';

ALTER TABLE `payment_types`
    MODIFY `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم نوع الدفع',
    MODIFY `created_user` bigint unsigned DEFAULT NULL COMMENT 'رقم المستخدم الذي قام بإضافة هذا السجل',
    MODIFY `updated_user` bigint unsigned DEFAULT NULL COMMENT 'رقم المستخدم الذي قام بآخر تحديث لهذا السجل',
    MODIFY `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إضافة السجل',
    MODIFY `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تحديث للسجل';
ALTER TABLE `permissions`
    MODIFY `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم الصلاحية',
    MODIFY `module_id` bigint unsigned NOT NULL COMMENT 'رقم الموديول',
    MODIFY `module_action_id` bigint unsigned NOT NULL COMMENT 'رقم الإجراء للموديول',
    MODIFY `permission_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'مفتاح الصلاحية',
    MODIFY `scope_level` enum('company','branch','warehouse') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'company' COMMENT 'مستوى نطاق الصلاحية',
    MODIFY `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إنشاء الصلاحية',
    MODIFY `created_user` bigint unsigned DEFAULT NULL COMMENT 'رقم المستخدم الذي أنشأ الصلاحية',
    MODIFY `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تحديث للصلاحية',
    MODIFY `updated_user` bigint unsigned DEFAULT NULL COMMENT 'رقم المستخدم الذي قام بتحديث الصلاحية',
    MODIFY `branch_id` bigint unsigned DEFAULT NULL COMMENT 'رقم الفرع المرتبط بالصلاحية';

ALTER TABLE `products`
    MODIFY `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم المنتج',
    MODIFY `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'صورة المنتج',
    MODIFY `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'وصف المنتج',
    MODIFY `category_id` bigint unsigned NOT NULL COMMENT 'رقم الفئة التي ينتمي إليها المنتج',
    MODIFY `supplier_id` bigint unsigned DEFAULT NULL COMMENT 'رقم المورد الذي يقدم هذا المنتج',
    MODIFY `barcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'رمز المنتج الشريطي',
    MODIFY `sku` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رمز المنتج في المخزون',
    MODIFY `purchase_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'سعر شراء المنتج',
    MODIFY `selling_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'سعر بيع المنتج',
    MODIFY `stock_quantity` int unsigned NOT NULL DEFAULT '0' COMMENT 'كمية المنتج في المخزون',
    MODIFY `min_stock_level` int unsigned NOT NULL DEFAULT '1' COMMENT 'أدنى مستوى مخزون للمنتج',
    MODIFY `max_stock_level` int unsigned DEFAULT NULL COMMENT 'أقصى مستوى مخزون للمنتج',
    MODIFY `unit_id` bigint unsigned NOT NULL COMMENT 'رقم وحدة قياس المنتج',
    MODIFY `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'هل المنتج نشط',
    MODIFY `created_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ إضافة المنتج',
    MODIFY `created_user` bigint unsigned DEFAULT NULL COMMENT 'رقم المستخدم الذي أضاف المنتج',
    MODIFY `updated_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ آخر تحديث للمنتج',
    MODIFY `updated_user` bigint unsigned DEFAULT NULL COMMENT 'رقم المستخدم الذي قام بتحديث المنتج',
    MODIFY `branch_id` bigint unsigned DEFAULT NULL COMMENT 'رقم الفرع الذي يوجد فيه المنتج',
    MODIFY `tax` decimal(5,2) DEFAULT NULL COMMENT 'نسبة الضريبة على المنتج',
    MODIFY `discount` decimal(5,2) DEFAULT NULL COMMENT 'نسبة الخصم على المنتج',
    MODIFY `supplier_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'معلومات الاتصال بالمورد',
    MODIFY `purchase_date` date DEFAULT NULL COMMENT 'تاريخ شراء المنتج',
    MODIFY `manufacturing_date` date DEFAULT NULL COMMENT 'تاريخ تصنيع المنتج',
    MODIFY `expiration_date` date DEFAULT NULL COMMENT 'تاريخ انتهاء صلاحية المنتج',
    MODIFY `last_updated` date DEFAULT NULL COMMENT 'تاريخ آخر تحديث للمنتج',
    MODIFY `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'علامة تجارية للمنتج';


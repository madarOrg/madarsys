## مدار - MadarSys

### 1. إعداد بيئة العمل
لضمان إعداد بيئة العمل بشكل صحيح، يجب أن يكون لدى كل عضو في الفريق الأدوات التالية:

- **PHP** إصدار 8.3.15
- **Composer** لتثبيت حزم PHP
- **Node.js** و **npm** أو **Yarn** (إذا كان هناك واجهة أمامية تعتمد على JavaScript)
- **MySQL** كقاعدة بيانات
- **Git** لإدارة الأكواد

---

### 2. تنزيل المستودع (Repository)
#### **كيفية تحميل المستودع:**
1. استنساخ المستودع إلى جهازك:
   ```bash
   HTTPS:
   git clone https://github.com/madarOrg/madarsys.git
   ```
   SSH keys:
   ادخل المفتاح 
   ```bash
   git clone git@github.com:madarOrg/madarsys.git
   Enter passphrase for key 'C:\Users\ASUS/.ssh/id_rsa':
   ```
2. انتقل إلى مجلد المشروع:
   ```bash
   cd madarsys
   ```

---

### 3. إعداد Laravel
#### **تنصيب وتكوين Laravel:**
1. تثبيت الحزم الخاصة بـ Laravel عبر Composer:
   ```bash
   composer install
   ```
2. إنشاء ملف البيئة `.env`:
   ```bash
   cp .env.example .env
   ```
3. تحديث إعدادات قاعدة البيانات في ملف `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=madarsys
   DB_USERNAME=root
   DB_PASSWORD=
   ```
4. توليد مفتاح التطبيق:
   ```bash
   php artisan key:generate
   ```
5. تشغيل الرابط الرمزي لمجلد التخزين:
   ```bash
   php artisan storage:link
   ```
   مسار تخزين الصور \madarsys\public\storage
   \madarsys\public\storage\logos: للشعارات
    \madarsys\public\storage\products: للمنتجات

6. تشغيل الهجرة (Migrations) لإنشاء الجداول:
   ```bash
   php artisan migrate
   ```
7. تشغيل ملء البيانات الأولية (إذا كان مطلوبًا):
   ```bash
   php artisan db:seed
   ```

---

### 4. إعداد Node.js
إذا كان المشروع يحتوي على واجهة أمامية تعتمد على JavaScript، قم بتثبيت الحزم اللازمة:

1. باستخدام npm:
   ```bash
   npm install
   ```
2. أو باستخدام Yarn:
   ```bash
   yarn install
   ```

---

### 5. تشغيل الخادم المحلي
#### **تشغيل خادم Laravel:**
```bash
php artisan serve
# أو
php -S 127.0.0.1:8000 -t public
```

#### **تشغيل الواجهة الأمامية (إذا كانت موجودة):**
1. باستخدام npm:
   ```bash
   npm run dev
   ```
2. أو باستخدام Yarn:
   ```bash
   yarn dev
   ```

---
#### **تنفيذ إشعارات وتنبيهات لحظية باستخدام WebSockets**
1. شغل redis :
   ```bash
   cd "C:\Program Files\Redis"

   redis-server.exe redis.windows.conf
   ```
2. ثم شغل laravel echo:
   ```bash
   cd c:/madarsys
   laravel-echo-server start
   ```


### 6. التعامل مع Git
#### **إدارة الفروع في Git:**
1. إنشاء فرع جديد:
   ```bash
   git checkout -b feature-branch deply
   ```
2. إضافة التعديلات إلى Git:
   ```bash
   git add .
   ```
3. حفظ التعديلات مع تعليق:
   ```bash
   git commit -m "وصف التغيير"
   ```
4. رفع التعديلات إلى المستودع:
   ```bash
   git push origin feature-name
   ```
5. تقديم **Pull Request** لدمج التعديلات بعد المراجعة.

6. لمعرفة البرانش الحالي واخر التحديثات:
   ```bash
git status

---

### 7. ملاحظات إضافية
- **التوثيق والاختبارات:** يجب على جميع الأعضاء الالتزام بمعايير التوثيق وكتابة اختبارات للوحدات البرمجية لتجنب الأخطاء المستقبلية.
- **تغييرات قاعدة البيانات:** عند إجراء أي تغييرات على هيكل قاعدة البيانات، يجب تحديث ملفات **المهاجرات (Migrations)** ومزامنتها مع البيئة الإنتاجية.
- **التنظيم والتنسيق:** الالتزام بمعايير موحدة للتطوير يضمن سير العمل بكفاءة وسلاسة.

---
### الهدف
- **تسهيل إعداد بيئة العمل والتطوير للفريق.**
- **تقديم خطوات واضحة لضمان تنفيذ جميع الأعضاء للمراحل بشكل صحيح ومنظم.**

errors:
C:\madarsys>npm run dev

> dev
> vite

X [ERROR] Cannot start service: Host version "0.25.2" does not match binary version "0.24.2"

1 error
failed to load config from C:\madarsys\vite.config.js
error when starting dev server:
Error: The service was stopped
    at C:\madarsys\node_modules\esbuild\lib\main.js:969:34
    at responseCallbacks.<computed> (C:\madarsys\node_modules\esbuild\lib\main.js:623:9)
    at Socket.afterClose (C:\madarsys\node_modules\esbuild\lib\main.js:614:28)
    at Socket.emit (node:events:525:35)
    at endReadableNT (node:internal/streams/readable:1696:12)
    at process.processTicksAndRejections (node:internal/process/task_queues:90:21)

C:\madarsys>npm update esbuild --save-dev
nmp install
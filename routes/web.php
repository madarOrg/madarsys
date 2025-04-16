<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\WarehouseReports;
use App\Models\Product;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\InvoiceCreationController;

use App\Http\Controllers\{
    AuthController,
    SignupController,
    PasswordController,
    CompanyController,
    BranchesController,
    WarehousesController,
    UserController,
    RoleUserController,
    RoleController,
    RolePermissionController,
    NavbarController,
    WarehouseStorageAreaController,
    WarehouseLocationController,
    ZonesController,
    CategoryController,
    PartnerController,
    ProductController,
    InventoryTransactionController,
    SettingController,
    RoleWarehouseController,
    InventoryProductController,
    // BroadcastController,
    InvoiceController,
    InventoryReviewController,
    ReportController,
    OrderController,
    ReturnOrderSupplierController,
    ReturnOrderController,
    ShipmentController,
    InventoryReportController,
    InventoryAuditController,
    InventoryTransactionItemsController,
    DashboardController,


    UnitController,
    BrandController,
    ManufacturingCountryController,
    PartnerReportController,
    PurchaseOrderController,
    SalesOrderController,
    InvoiceFromOrdersController

};
use App\Services\UnitService;

Route::get('/test', function (UnitService $unitService) {
    $units = $unitService->updateUnits(52); // اختبر مع معرف منتج صالح
    dd($units);
});

// use App\Events\NotificationCreated;
// use App\Models\Notification;

// Route::get('/test-broadcast', function () {
//     $notification = Notification::create([
//         'user_id' => 5, // تأكد أن المستخدم موجود
//         'title'   => 'اختبار البث',
//     ]);

//     broadcast(new NotificationCreated($notification));
//     return 'تم إرسال الإشعار!';
// });

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/signup', [SignupController::class, 'create'])->name('signup');
Route::post('/signup', [SignupController::class, 'store'])->name('signup.submit');

Route::get('/reset-password', [PasswordController::class, 'create'])->name('password.change');
Route::post('/reset-password', [PasswordController::class, 'store'])->name('password.update');
// routes/web.php
Route::get('/', function () {
    return view('home');
})->name('home');


// عرض الصفحة الرئيسية
Route::get('/team', function () {
    return view('team');
});

//
// web.php
Route::get('/welcome', function () {
    return view('splash');
});


// استخدام middleware للتأكد من أن المستخدم مسجل الدخول
// Route::middleware('auth')->group(function () {
    Route::middleware(['web', 'auth',\App\Http\Middleware\CheckPermission::class])->group(function () { 

        // مسارات مباشرة لصفحات تقارير المرتجعات
        Route::get('/returns-management/reports', [App\Http\Controllers\ReturnOrderController::class, 'reports'])->name('returns-management.reports');
        Route::get('/returns-management/reports/customer', [App\Http\Controllers\ReturnOrderController::class, 'customerReports'])->name('returns-management.customer-reports'); 



    // عرض لوحة التحكم (dashboard)

    // Route::get('/dashboard', [NavbarController::class, 'showNavbar'])->name('dashboard');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/warehouse-reports', WarehouseReports::class)->name('warehouse.reports');
    // مجموعة مسارات لإدارة الأدوار
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index'); // عرض جميع الأدوار
        Route::get('/create', [RoleController::class, 'create'])->name('create'); // نموذج إضافة دور جديد
        Route::post('/', [RoleController::class, 'store'])->name('store'); // تخزين الدور الجديد
        Route::get('/{role}', [RoleController::class, 'show'])->name('show'); // عرض تفاصيل الدور
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit'); // نموذج تعديل الدور
        Route::put('/{role}', [RoleController::class, 'update'])->name('update'); // تحديث بيانات الدور
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy'); // حذف الدور
    });

    //roleWarehouse

    Route::prefix('role-warehouse')->name('role-warehouse.')->group(function () {
        Route::get('/', [RoleWarehouseController::class, 'index'])->name('index');
        Route::post('/store', [RoleWarehouseController::class, 'store'])->name('store');
        Route::put('/update/{id}', [RoleWarehouseController::class, 'update'])->name('update');
        // Route::delete('/delete/{id}', [RoleWarehouseController::class, 'destroy'])->name('destroy');

    });
    Route::delete('role-warehouse/{id}', [RoleWarehouseController::class, 'destroy'])->name('role-warehouse.destroy');



    //role primissions 
    Route::prefix('role-permissions')->name('role-permissions.')->group(function () {
        // عرض قائمة الصلاحيات المرتبطة بالأدوار
        Route::get('/', [RolePermissionController::class, 'index'])->name('index');

        // عرض نموذج إضافة صلاحيات لدور معين
        Route::get('/create', [RolePermissionController::class, 'create'])->name('create');

        // حفظ الصلاحيات المحددة لدور معين
        Route::post('/', [RolePermissionController::class, 'store'])->name('store');

        // عرض تفاصيل صلاحيات دور معين
        Route::get('/{role}', [RolePermissionController::class, 'show'])->name('show');

        // عرض نموذج تعديل الصلاحيات الخاصة بدور معين
        Route::get('/{role}/edit', [RolePermissionController::class, 'edit'])->name('edit');

        // تحديث الصلاحيات لدور معين
        Route::put('/{role}', [RolePermissionController::class, 'update'])->name('update');

        // حذف صلاحيات دور معين
        Route::delete('/{id}', [RolePermissionController::class, 'destroy'])->name('destroy');
    });

    // users
    Route::resource('users', UserController::class);

    // Route::prefix('users')->name('users.')->group(function () {
    //     // مسار عرض جميع المستخدمين (GET)
    //     Route::get('/', [UserController::class, 'index'])->name('index');

    //     // مسار عرض نموذج إضافة مستخدم جديد (GET)
    //     // Route::get('/create', [UserController::class, 'create'])->name('create');

    //     // مسار تخزين بيانات المستخدم الجديد (POST)
    //     Route::post('/', [UserController::class, 'store'])->name('store');

    //     // مسار عرض تفاصيل المستخدم (GET)
    //     Route::get('/{user}', [UserController::class, 'show'])->name('show');

    //     // مسار عرض نموذج تعديل المستخدم (GET)
    //     Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');

    //     // مسار تحديث بيانات المستخدم (PUT/PATCH)
    //     Route::put('/{user}', [UserController::class, 'update'])->name('update');
    //     Route::patch('/{user}', [UserController::class, 'update'])->name('update');

    //     // مسار حذف المستخدم (DELETE)
    //     Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
    // });

    // user roles
    Route::resource('users-roles', RoleUserController::class);

    Route::get('/users-roles', [RoleUserController::class, 'index'])->name('users-roles.index'); // عرض القائمة
    Route::post('/users-roles', [RoleUserController::class, 'store'])->name('users-roles.store'); // إضافة دور

    //create user in steps
    // Route::get('/user/create', UserSteps::class)->name('user.create');
    Route::get('/users/create', function () {
        return view('users.create');
    })->name('users.create');


    // عرض معلومات
    Route::get('/info', function () {
        return view('info');
    });


    // Routes for Companies
    Route::get('companies', [CompanyController::class, 'index'])->name('companies.index');          // عرض جميع الشركات
    Route::get('companies/create', [CompanyController::class, 'create'])->name('companies.create'); // عرض صفحة إضافة شركة جديدة
    Route::post('companies', [CompanyController::class, 'store'])->name('companies.store');         // حفظ شركة جديدة
    Route::get('companies/{company}', [CompanyController::class, 'show'])->name('companies.show');  // عرض شركة واحدة
    Route::get('companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit'); // عرض صفحة تعديل الشركة
    Route::put('companies/{company}', [CompanyController::class, 'update'])->name('companies.update'); // تحديث بيانات الشركة
    Route::delete('companies/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy'); // حذف شركة
    Route::get('/companies/search', [CompanyController::class, 'search'])->name('companies.search');

    // Routes for Branches
    Route::get('branches', [BranchesController::class, 'index'])->name('branches.index');           // عرض جميع الفروع
    Route::get('branches/create', [BranchesController::class, 'create'])->name('branches.create');   // عرض صفحة إضافة فرع جديد
    Route::post('branches', [BranchesController::class, 'store'])->name('branches.store');           // حفظ فرع جديد
    Route::get('branches/{branch}', [BranchesController::class, 'show'])->name('branches.show');     // عرض فرع واحد
    Route::get('branches/{branch}/edit', [BranchesController::class, 'edit'])->name('branches.edit'); // عرض صفحة تعديل الفرع
    Route::put('branches/{branch}', [BranchesController::class, 'update'])->name('branches.update'); // تحديث بيانات الفرع
    Route::delete('branches/{branch}', [BranchesController::class, 'destroy'])->name('branches.destroy'); // حذف فرع

    // Routes for Warehouses
    Route::get('warehouses', [WarehousesController::class, 'index'])->name('warehouses.index');            // عرض جميع المستودعات
    Route::get('warehouses/create', [WarehousesController::class, 'create'])->name('warehouses.create');    // عرض صفحة إضافة مستودع جديد
    Route::post('warehouses', [WarehousesController::class, 'store'])->name('warehouses.store');            // حفظ مستودع جديد
    Route::get('warehouses/{warehouse}', [WarehousesController::class, 'show'])->name('warehouses.show');   // عرض مستودع واحد
    Route::get('warehouses/{warehouse}/edit', [WarehousesController::class, 'edit'])->name('warehouses.edit'); // عرض صفحة تعديل المستودع
    Route::put('warehouses/{warehouse}', [WarehousesController::class, 'update'])->name('warehouses.update'); // تحديث بيانات المستودع
    Route::delete('warehouses/{warehouse}', [WarehousesController::class, 'destroy'])->name('warehouses.destroy'); // حذف مستودع

    // Routes for Warehouse Storage Areas
    Route::prefix('warehouses/{warehouse}/storage-areas')->name('warehouse.storage-areas.')->group(function () {
        Route::get('/', [WarehouseStorageAreaController::class, 'index'])->name('index');           // عرض مناطق التخزين
        Route::get('/create', [WarehouseStorageAreaController::class, 'create'])->name('create');    // صفحة إضافة منطقة تخزين
        Route::post('/', [WarehouseStorageAreaController::class, 'store'])->name('store');          // حفظ منطقة تخزين جديدة
        Route::get('/{storage_area}', [WarehouseStorageAreaController::class, 'show'])->name('show'); // عرض منطقة تخزين محددة
        Route::get('/{storage_area}/edit', [WarehouseStorageAreaController::class, 'edit'])->name('edit'); // تعديل منطقة تخزين
        Route::put('/{storage_area}', [WarehouseStorageAreaController::class, 'update'])->name('update'); // تحديث منطقة تخزين
        Route::delete('/{storage_area}', [WarehouseStorageAreaController::class, 'destroy'])->name('destroy'); // حذف منطقة تخزين
    });



    Route::prefix('warehouses/{warehouse}/locations')->group(function () {
        Route::get('/', [WarehouseLocationController::class, 'index'])->name('warehouses.locations.index');
        Route::get('/create', [WarehouseLocationController::class, 'create'])->name('warehouses.locations.create');
        Route::post('/', [WarehouseLocationController::class, 'store'])->name('warehouses.locations.store');
        Route::get('/{warehouse_location}/edit', [WarehouseLocationController::class, 'edit'])->name('warehouses.locations.edit');
        Route::put('/{warehouse_location}', [WarehouseLocationController::class, 'update'])->name('warehouses.locations.update');
        Route::delete('/{warehouse_location}', [WarehouseLocationController::class, 'destroy'])->name('warehouses.locations.destroy');
    });


    // Route::prefix('warehouses')->name('warehouses.')->group(function () {
    //     Route::resource('zones', ZonesController::class);
    // });

    Route::prefix('warehouses/{warehouse}/zones')->name('warehouses.zones.')->group(function () {
        // عرض جميع المناطق
        Route::get('/', [ZonesController::class, 'index'])->name('index');

        // عرض نموذج إضافة منطقة جديدة
        Route::get('/create', [ZonesController::class, 'create'])->name('create');

        // حفظ منطقة جديدة
        Route::post('/', [ZonesController::class, 'store'])->name('store');

        // عرض تفاصيل منطقة معينة
        Route::get('/{zone}', [ZonesController::class, 'show'])->name('show');

        // عرض نموذج تعديل منطقة معينة
        Route::get('/{zone}/edit', [ZonesController::class, 'edit'])->name('edit');

        // تحديث بيانات منطقة معينة
        Route::put('/{zone}', [ZonesController::class, 'update'])->name('update');

        // حذف منطقة معينة
        Route::delete('/{zone}', [ZonesController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('warehouses/zones')->name('warehouses.zones.')->group(function () {
        // عرض جميع المناطق بغض النظر عن المستودع
        Route::get('/all', [ZonesController::class, 'index'])->name('all.index');
    });

    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
    Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
    Route::put('/brands/{brand}', [BrandController::class, 'store'])->name('brands.update'); // إعادة استخدام نفس دالة store
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brands.destroy');
    Route::get('brands/{brand}/edit', [BrandController::class, 'edit'])->name('brands.edit');

    // عرض جميع الشركات المصنعة
    Route::get('/manufacturing_countries', [ManufacturingCountryController::class, 'index'])->name('manufacturing_countries.index');

    // عرض نموذج إضافة شركة جديدة
    Route::get('/manufacturing_countries/create', [ManufacturingCountryController::class, 'create'])->name('manufacturing_countries.create');

    // حفظ شركة جديدة أو تحديث شركة موجودة
    Route::post('/manufacturing_countries', [ManufacturingCountryController::class, 'store'])->name('manufacturing_countries.store');

    // تحديث شركة موجودة
    Route::put('/manufacturing_countries/{id}', [ManufacturingCountryController::class, 'update'])->name('manufacturing_countries.update');

    // حذف شركة
    Route::delete('/manufacturing_countries/{id}', [ManufacturingCountryController::class, 'destroy'])->name('manufacturing_countries.destroy');


    //Categroy routers
    Route::prefix('categories')->name('categories.')->group(function () {
        // عرض قائمة الفئات
        Route::get('/', [CategoryController::class, 'index'])->name('index');

        // عرض نموذج إضافة فئة جديدة
        Route::get('/create', [CategoryController::class, 'create'])->name('create');

        // تخزين فئة جديدة
        Route::post('/', [CategoryController::class, 'store'])->name('store');

        // عرض نموذج تعديل فئة
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');

        // تحديث بيانات الفئة
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');

        // حذف الفئة
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    Route::get('/units', [UnitController::class, 'index'])->name('units.index');
    Route::post('/units', [UnitController::class, 'store'])->name('units.store');
    Route::post('/units/{unit}', [UnitController::class, 'update'])->name('units.update');

    // مجموعة مسارات إدارة الشركاء
    Route::prefix('partners')->name('partners.')->group(function () {
        Route::get('/', [PartnerController::class, 'index'])->name('index'); // عرض قائمة الشركاء
        Route::get('/create', [PartnerController::class, 'create'])->name('create'); // نموذج إنشاء شريك جديد
        Route::post('/', [PartnerController::class, 'store'])->name('store'); // تخزين الشريك الجديد
        Route::get('/{partner}/edit', [PartnerController::class, 'edit'])->name('edit'); // تعديل الشريك
        Route::put('/{partner}', [PartnerController::class, 'update'])->name('update'); // تحديث بيانات الشريك
        Route::delete('/{partner}', [PartnerController::class, 'destroy'])->name('destroy'); // حذف الشريك
    });
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index'); // قائمة المنتجات
        Route::get('/create', [ProductController::class, 'create'])->name('create'); // إنشاء منتج جديد
        Route::post('/', [ProductController::class, 'store'])->name('store'); // تخزين المنتج الجديد
        Route::get('/{product}', [ProductController::class, 'show'])->name('show'); // عرض المنتج
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit'); // تعديل المنتج
        Route::put('/{product}', [ProductController::class, 'update'])->name('update'); // تحديث المنتج
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy'); // حذف المنتج
    });
    //settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    
    //inventory/transactions
    // في ملف routes/web.php
    Route::get('transaction-effect/{transactionType}', [InventoryTransactionController::class, 'getEffectByTransactionType']);

    Route::get('/get-units/{productId}', [ProductController::class, 'getUnits']);
    Route::prefix('inventory/transactions')->name('inventory.transactions.')->group(function () {
        // عرض صفحة إنشاء عملية مخزنية جديدة
        Route::get('/create', [InventoryTransactionController::class, 'create'])
            ->name('create');

        Route::get('/', [InventoryTransactionController::class, 'index'])
            ->name('index');
        // تخزين العملية المخزنية الجديدة
        Route::post('/', [InventoryTransactionController::class, 'store'])
            ->name('store');

        // عرض تفاصيل عملية مخزنية معينة
        Route::get('/{id}', [InventoryTransactionController::class, 'show'])
            ->name('show');

        // عرض صفحة تعديل عملية مخزنية
        Route::get('/{id}/edit', [InventoryTransactionController::class, 'edit'])
            ->name('edit');

        // تحديث بيانات العملية المخزنية
        Route::put('/{id}', [InventoryTransactionController::class, 'update'])
            ->name('update');

        // حذف العملية المخزنية
        Route::delete('/{id}', [InventoryTransactionController::class, 'destroy'])
            ->name('destroy');
    });

    Route::prefix('inventory/transactions/items')->group(function () {
        Route::get('/{item}/edit', [InventoryTransactionItemsController::class, 'edit'])->name('inventory.transactions.editItem');
        Route::put('/{item}', [InventoryTransactionItemsController::class, 'update'])->name('inventory.transactions.updateItem');
    });


    // مجموعة Routes الخاصة بحركة المخزون
    Route::prefix('inventory-products')->name('inventory-products.')->group(function () {
        Route::get('/', [InventoryProductController::class, 'index'])->name('index');

        // عرض صفحة إضافة حركة مخزنية
        Route::get('create', [InventoryProductController::class, 'create'])->name('create');
        Route::get('new', [InventoryProductController::class, 'new'])->name('new');
        Route::get('createOut', [InventoryProductController::class, 'createOut'])->name('createOut');


        // تخزين حركة مخزنية جديدة
        Route::post('/', [InventoryProductController::class, 'store'])->name('store');

        // عرض صفحة تعديل حركة مخزنية
        Route::get('{id}/edit', [InventoryProductController::class, 'edit'])->name('edit');

        // تحديث حركة مخزنية
        Route::put('{id}', [InventoryProductController::class, 'update'])->name('update');

        // حذف حركة مخزنية
        Route::delete('{id}', [InventoryProductController::class, 'destroy'])->name('destroy');
        // بحث
        Route::get('search', [InventoryProductController::class, 'search'])->name('search');
    });

    Route::get('/get-warehouses/{branch_id}', [InventoryProductController::class, 'getWarehouses']);
    Route::get('/get-storage-areas/{warehouse_id}', [InventoryProductController::class, 'getStorageAreas']);
    Route::get('/get-locations/{storage_area_id}', [InventoryProductController::class, 'getLocations']);

    Route::get('/get-inventory-transactions/{warehouse_id}', [InventoryProductController::class, 'getInventoryTransactions']);
    Route::get('/get-inventory-transactions-out/{warehouse_id}', [InventoryProductController::class, 'getInventoryTransactionsOut']);

    Route::get('/get-product/{transaction_id}', [InventoryProductController::class, 'getProduct']);
    Route::get('/get-product-inventory/{transaction_id}', [InventoryProductController::class, 'getProductInventory']);

    Route::get('/get-products/{transaction_id}', [InventoryProductController::class, 'getProducts']);
    // التقارير

    Route::middleware([\App\Http\Middleware\GlobalVariablesMiddleware::class])->group(function () {
        Route::get('/inventory-transactions/{warehouse_id}', [InventoryProductController::class, 'getInventoryTransactions']);
        Route::get('/products/{transaction_id}', [InventoryProductController::class, 'getProducts']);
        Route::prefix('reports')->name('reports.')->group(function () {
            // تقرير المنتجات التي وصلت لحد إعادة الطلب
            Route::get('/purchase', [InventoryReportController::class, 'reorderReport'])->name('reorder');
            Route::get('/search-products', [InventoryReportController::class, 'searchProducts'])->name('search-products');

            //المنتج و الموردين
            Route::get('/reorder', [InventoryReportController::class, 'purchaseReport'])->name('purchaseReport');
            Route::get('/search-partners', [InventoryReportController::class, 'searchPartners'])->name('search-partners');


            // تقرير المنتجات المقاربه المنتهية صلاحيتها
            Route::get('/expired-products', [InventoryReportController::class, 'expirationReport'])->name('expired-products');

            // تقرير المنتحات المنتهيه
            Route::get('/get-expired-products', [InventoryReportController::class, 'getExpiredProducts'])->name('get-expired-products');
            // تقرير الحركات المخزنية
            Route::get('/inventory-transactions', [InventoryReportController::class, 'inventoryTransactions'])
                ->name('inventory-transactions');
            
            Route::get('/product-stock', [InventoryReportController::class, 'productStockReport'])->name('product-stock');

            Route::get('/partners', [PartnerReportController::class, 'index'])->name('partner');
            Route::get('/products-by-warehouse', [PartnerReportController::class, 'getProductsByWarehouse']);

        });
        // Route::prefix('reports')->name('reports.')
        // ->middleware([\App\Http\Middleware\GlobalVariablesMiddleware::class])
        // ->group(function () {

        // تقرير المنتجات التي وصلت لحد إعادة الطلب
        Route::get('/purchase', [InventoryReportController::class, 'reorderReport'])->name('reorder');
        Route::get('/search-products', [InventoryReportController::class, 'searchProducts'])->name('search-products');

        // المنتج و الموردين
        Route::get('/reorder', [InventoryReportController::class, 'purchaseReport'])->name('purchaseReport');
        Route::get('/search-partners', [InventoryReportController::class, 'searchPartners'])->name('search-partners');

        // تقرير المنتجات المقاربه المنتهية صلاحيتها
        Route::get('/expired-products', [InventoryReportController::class, 'expirationReport'])->name('expired-products');

        // تقرير المنتحات المنتهيه
        Route::get('/get-expired-products', [InventoryReportController::class, 'getExpiredProducts'])->name('get-expired-products');

        // تقرير الحركات المخزنية
        Route::get('/inventory-transactions', [InventoryReportController::class, 'inventoryTransactions'])
            ->name('inventory-transactions');

            // Route::get('/products', [InventoryReportController::class, 'productStockReport'])->name('products');

    });

    // Auditing
    Route::prefix('/inventory/audit')->name('inventory.audit.')->group(function () {
        Route::get('/', [InventoryAuditController::class, 'index'])->name('index');
        Route::get('/create', [InventoryAuditController::class, 'create'])->name('create');
        Route::post('/store', [InventoryAuditController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [InventoryAuditController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [InventoryAuditController::class, 'update'])->name('update');
        
        Route::get('/editTrans/{id}', [InventoryAuditController::class, 'editTrans'])->name('editTrans');
        Route::get('/audit-transaction/{auditId}/{warehouseId}', [InventoryAuditController::class, 'createInventoryAuditTransaction']);


        Route::post('/updateTrans/{id}', [InventoryAuditController::class, 'updateTrans'])->name('updateTrans');
        Route::delete('/destroy/{id}', [InventoryAuditController::class, 'destroy'])->name('destroy');
        Route::middleware([\App\Http\Middleware\GlobalVariablesMiddleware::class])->group(function () {
            Route::get('/warehouse-report', [InventoryAuditController::class, 'warehouseReport'])->name('warehouseReport');
            Route::get('/report', [InventoryAuditController::class, 'report'])->name('report');
        });
    });



    // عرض الحركات المراجعة
    Route::get('/inventory-review', [InventoryReviewController::class, 'index'])->name('inventory-review.index');

    // تحديث حالة المراجعة
    Route::post('/inventory-review/{id}/update-status', [InventoryReviewController::class, 'updateStatus'])->name('inventory-review.updateStatus');

    //notifications routes
    Route::get('/notifications', [NotificationController::class, 'getUnreadNotifications']);
    Route::post('/mark-notification-as-read/{id}', [NotificationController::class, 'markAsRead']);
    // Route::post('/broadcasting/auth', [BroadcastController::class, 'authenticate']);

    // مسارات الفواتير
    // Route::prefix('invoices')->name('invoices.')->group(function () {
    //     Route::get('/', [InvoiceController::class, 'index'])->name('index'); // List invoices
    //     Route::get('/{type}/create', [InvoiceController::class, 'create'])->name('create'); // Create invoice form
    //     Route::post('/{type}', [InvoiceController::class, 'store'])->name('store'); // Store new invoice
    //     Route::get('/{type}/{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit'); // Edit invoice form
    //     Route::put('/{type}/{invoice}', [InvoiceController::class, 'update'])->name('update'); // Update invoice
    //     Route::delete('/{type}/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy'); // Delete invoice
          //invoices routes
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('{type}', [InvoiceController::class, 'index'])->name('index'); // List invoices by type
        Route::get('{type}/create', [InvoiceController::class, 'create'])->name('create'); // Create form for invoice type
        Route::post('{type}', [InvoiceController::class, 'store'])->name('store'); // Store new invoice
        Route::get('{type}/{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit'); // Edit invoice
        Route::put('{type}/{invoice}', [InvoiceController::class, 'update'])->name('update'); // Update invoice
        Route::delete('{type}/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy'); // Delete invoice
    
        // مسارات إنشاء فاتورة من أمر شراء وأمر صرف
        Route::get('purchase-orders', [InvoiceFromOrdersController::class, 'purchaseOrders'])->name('purchase-orders');
        Route::get('sales-orders', [InvoiceFromOrdersController::class, 'salesOrders'])->name('sales-orders');
        Route::get('create-from-purchase-order/{id}', [InvoiceFromOrdersController::class, 'createFromPurchaseOrder'])->name('create-from-purchase-order');
        Route::post('store-from-purchase-order/{id}', [InvoiceFromOrdersController::class, 'storeFromPurchaseOrder'])->name('store-from-purchase-order');
        
        // مسارات إنشاء فاتورة من أمر صرف باستخدام المتحكم الجديد
        Route::get('create-from-sales-order/{id}', [InvoiceCreationController::class, 'createFromSalesOrder'])->name('create-from-sales-order');
        Route::post('store-from-sales-order/{id}', [InvoiceCreationController::class, 'storeFromSalesOrder'])->name('store-from-sales-order');
        
        // مسارات إنشاء فاتورة من طلب
        Route::get('confirmed-orders', [InvoiceController::class, 'confirmedOrders'])->name('confirmed-orders');
        Route::get('create-from-order/{id}', [InvoiceCreationController::class, 'createFromOrder'])->name('create-from-order');
        Route::post('store-from-order/{id}', [InvoiceCreationController::class, 'storeFromOrder'])->name('store-from-order');
        
        // إبقاء المسارات القديمة للتوافق مع الروابط القديمة
        Route::get('new-create-from-sales-order/{id}', [InvoiceCreationController::class, 'createFromSalesOrder'])->name('new-create-from-sales-order');
        Route::post('new-store-from-sales-order/{id}', [InvoiceCreationController::class, 'storeFromSalesOrder'])->name('new-store-from-sales-order');
    });
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');
    Route::post('/reports/get-fields', [ReportController::class, 'getFields'])->name('reports.get-fields');

    Route::prefix('returns/process')->name('returns_process.')->group(function () {

        Route::get('/', [ReturnOrderController::class, 'index'])->name('index'); // قائمة المرتجعات
        // عرض تفاصيل المرتجع
        Route::get('{id}', [ReturnOrderController::class, 'show'])->name('show');
        // إضافة مرتجع جديد
        Route::get('create', [ReturnOrderController::class, 'create'])->name('create');

        // تعديل مرتجع
        Route::get('{id}/edit', [ReturnOrderController::class, 'edit'])->name('edit');

// ...
    Route::prefix('returns-management')->name('returns-management.')->group(function () {
        // الصفحة الرئيسية للمرتجعات
        Route::get('/', [ReturnOrderController::class, 'index'])->name('index');
        Route::get('/create', [ReturnOrderController::class, 'create'])->name('create');
        Route::post('/', [ReturnOrderController::class, 'store'])->name('store');
        Route::get('/{id}', [ReturnOrderController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ReturnOrderController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ReturnOrderController::class, 'update'])->name('update');
        Route::delete('/{id}', [ReturnOrderController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/print', [ReturnOrderController::class, 'print'])->name('print');
        
        // تقارير المرتجعات
        Route::get('/reports', [ReturnOrderController::class, 'reports'])->name('reports'); 
        Route::get('/reports/customer', [ReturnOrderController::class, 'customerReports'])->name('reports.customer');
    });
    
    // مسارات مرتجعات الموردين
    Route::prefix('returns-suppliers')->name('returns-suppliers.')->group(function () {
        Route::get('/', [ReturnOrderSupplierController::class, 'index'])->name('index');
        Route::get('/create', [ReturnOrderSupplierController::class, 'create'])->name('create');
        Route::post('/', [ReturnOrderSupplierController::class, 'store'])->name('store');
        Route::get('/{id}', [ReturnOrderSupplierController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ReturnOrderSupplierController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ReturnOrderSupplierController::class, 'update'])->name('update');
        Route::delete('/{id}', [ReturnOrderSupplierController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/print', [ReturnOrderSupplierController::class, 'print'])->name('print');
        Route::put('/{id}/send', [ReturnOrderSupplierController::class, 'send'])->name('send');
        
        // تقارير مرتجعات الموردين
        Route::get('/reports', [ReturnOrderSupplierController::class, 'reports'])->name('reports');
    });

    // طرق الشحنات
    Route::prefix('shipments')->group(function () {
        // صفحات خاصة
        Route::get('/receive', [ShipmentController::class, 'receiveIndex'])->name('shipments.receive.index');
        Route::get('/send', [ShipmentController::class, 'sendIndex'])->name('shipments.send.index');
        Route::get('/track', [ShipmentController::class, 'trackIndex'])->name('shipments.track.index');
        
        // طرق استلام الشحنات
        Route::get('/{id}/receive', [ShipmentController::class, 'showReceiveForm'])->name('shipments.receive.form');
        Route::post('/{id}/receive', [ShipmentController::class, 'receive'])->name('shipments.receive');
        
        // طرق CRUD الأساسية
        Route::get('/', [ShipmentController::class, 'index'])->name('shipments.index');
        Route::get('/create', [ShipmentController::class, 'create'])->name('shipments.create');
        Route::post('/', [ShipmentController::class, 'store'])->name('shipments.store');
        Route::get('/{id}', [ShipmentController::class, 'show'])->name('shipments.show');
        Route::get('/{id}/edit', [ShipmentController::class, 'edit'])->name('shipments.edit');
        Route::put('/{id}', [ShipmentController::class, 'update'])->name('shipments.update');
        Route::delete('/{id}', [ShipmentController::class, 'destroy'])->name('shipments.destroy');
    });

    // طرق مباشرة لصفحات الشحنات
    Route::get('/shipments-receive', [ShipmentController::class, 'receiveIndex'])->name('shipments.receive.direct');
    Route::get('/shipments-send', [ShipmentController::class, 'sendIndex'])->name('shipments.send.direct');
    Route::get('/shipments-track', [ShipmentController::class, 'trackIndex'])->name('shipments.track.direct');

    // إزالة الطرق المتعارضة
    // Route::resource('shipments', ShipmentController::class);
    // Route::get('shipments/{shipment}/receive', [ShipmentController::class, 'showReceiveForm'])->name('shipments.receive.form');
    // Route::post('shipments/{shipment}/receive', [ShipmentController::class, 'receive'])->name('shipments.receive');

    // طرق إدارة المرتجعات
    Route::prefix('returns')->group(function () {
        // مرتجعات الموردين
        Route::get('/supplier', [ReturnOrderSupplierController::class, 'index'])->name('returns.supplier.index');
        Route::get('/supplier/create', [ReturnOrderSupplierController::class, 'create'])->name('returns.supplier.create');
        Route::post('/supplier', [ReturnOrderSupplierController::class, 'store'])->name('returns.supplier.store');
        Route::get('/supplier/{id}', [ReturnOrderSupplierController::class, 'show'])->name('returns.supplier.show');
        Route::get('/supplier/{id}/edit', [ReturnOrderSupplierController::class, 'edit'])->name('returns.supplier.edit');
        Route::put('/supplier/{id}', [ReturnOrderSupplierController::class, 'update'])->name('returns.supplier.update');
        Route::delete('/supplier/{id}', [ReturnOrderSupplierController::class, 'destroy'])->name('returns.supplier.destroy');
        Route::get('/supplier/{id}/pdf', [ReturnOrderSupplierController::class, 'generatePdf'])->name('returns.supplier.pdf');
        Route::post('/supplier/{id}/send', [ReturnOrderSupplierController::class, 'sendToSupplier'])->name('returns.supplier.send');
        
        // معالجة المرتجعات
        Route::get('/process', [ReturnOrderController::class, 'index'])->name('returns.process.index');
        Route::get('/process/create', [ReturnOrderController::class, 'create'])->name('returns.process.create');
        Route::post('/process', [ReturnOrderController::class, 'store'])->name('returns.process.store');
        Route::get('/process/{id}', [ReturnOrderController::class, 'show'])->name('returns.process.show');
        Route::get('/process/{id}/edit', [ReturnOrderController::class, 'edit'])->name('returns.process.edit');
        Route::put('/process/{id}', [ReturnOrderController::class, 'update'])->name('returns.process.update');
        Route::delete('/process/{id}', [ReturnOrderController::class, 'destroy'])->name('returns.process.destroy');
        
        // تقارير المرتجعات
        Route::get('/reports', [ReturnOrderController::class, 'reports'])->name('returns.reports.index');
        Route::get('/reports/supplier', [ReturnOrderSupplierController::class, 'reports'])->name('returns.reports.supplier');
        Route::get('/reports/customer', [ReturnOrderController::class, 'customerReports'])->name('returns.reports.customer');
    });

    // عرض جميع الطلبات
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    // عرض صفحة إضافة طلب جديد
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');

    // حفظ طلب جديد
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // عرض صفحة تعديل حالة الطلب
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');

    // تحديث حالة الطلب
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');

    // عرض طلبات الشراء التي تحتاج إلى موافقة
    Route::get('/orders/pending-approval', [OrderController::class, 'pendingApproval'])->name('orders.pending-approval');
    
    // الموافقة على طلب الشراء
    Route::post('/orders/{id}/approve', [OrderController::class, 'approve'])->name('orders.approve');
    
    // طباعة أمر الشراء
    Route::get('/orders/{id}/print-purchase-order', [OrderController::class, 'printPurchaseOrder'])->name('orders.print-purchase-order');
    Route::get('/orders/{id}/print-sales-order', [OrderController::class, 'printSalesOrder'])->name('orders.print-sales-order');

    // فواتير الشراء المرتبطة بطلبات الشراء
    Route::get('/invoices/confirmed-orders', [InvoiceController::class, 'confirmedOrders'])->name('invoices.confirmed-orders');
    Route::get('/invoices/create-from-order/{orderId}', [InvoiceController::class, 'createFromOrder'])->name('invoices.create-from-order');
    Route::post('/invoices/store-from-order/{orderId}', [InvoiceController::class, 'storeFromOrder'])->name('invoices.store-from-order');
    Route::get('/orders/check-confirmed', [OrderController::class, 'checkConfirmedOrders'])->name('orders.check-confirmed');

    // مسارات أوامر الشراء
    Route::prefix('purchase-orders')->name('purchase-orders.')->group(function () {
        Route::get('/', [PurchaseOrderController::class, 'index'])->name('index');
        Route::get('/create/{orderId}', [PurchaseOrderController::class, 'create'])->name('create');
        Route::post('/', [PurchaseOrderController::class, 'store'])->name('store');
        Route::get('/{id}', [PurchaseOrderController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [PurchaseOrderController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PurchaseOrderController::class, 'update'])->name('update');
        Route::delete('/{id}', [PurchaseOrderController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/print', [PurchaseOrderController::class, 'print'])->name('print');
        Route::post('/{id}/approve', [PurchaseOrderController::class, 'approve'])->name('approve');
    });

    // مسارات أوامر الصرف (البيع)
    Route::prefix('sales-orders')->name('sales-orders.')->group(function () {
        Route::get('/', [SalesOrderController::class, 'index'])->name('index');
        Route::get('/create/{orderId}', [SalesOrderController::class, 'create'])->name('create');
        Route::post('/', [SalesOrderController::class, 'store'])->name('store');
        Route::get('/{id}', [SalesOrderController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SalesOrderController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SalesOrderController::class, 'update'])->name('update');
        Route::delete('/{id}', [SalesOrderController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/print', [SalesOrderController::class, 'print'])->name('print');
        Route::post('/{id}/approve', [SalesOrderController::class, 'approve'])->name('approve');
    });
    
    // مسارات إنشاء فواتير من أوامر الشراء والصرف
    Route::prefix('invoices')->name('invoices.')->group(function () {
        // عرض أوامر الشراء التي يمكن إنشاء فواتير منها
        Route::get('/purchase-orders', [InvoiceFromOrdersController::class, 'purchaseOrders'])->name('purchase-orders');
        
        // عرض أوامر الصرف التي يمكن إنشاء فواتير منها
        Route::get('/sales-orders', [InvoiceFromOrdersController::class, 'salesOrders'])->name('sales-orders');
        
        // إنشاء فاتورة من أمر شراء
        Route::get('/create-from-purchase-order/{id}', [InvoiceFromOrdersController::class, 'createFromPurchaseOrder'])->name('create-from-purchase-order');
        Route::post('/store-from-purchase-order/{id}', [InvoiceFromOrdersController::class, 'storeFromPurchaseOrder'])->name('store-from-purchase-order');
        
        // إنشاء فاتورة من أمر صرف
        Route::get('/create-from-sales-order/{id}', [InvoiceFromOrdersController::class, 'createFromSalesOrder'])->name('create-from-sales-order');
        Route::post('/store-from-sales-order/{id}', [InvoiceFromOrdersController::class, 'storeFromSalesOrder'])->name('store-from-sales-order');
    });

});
});
Route::get('/orders/check-confirmed', [OrderController::class, 'checkConfirmedOrders'])->name('orders.check-confirmed');

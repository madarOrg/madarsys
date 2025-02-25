<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\WarehouseReports;
use App\Models\Product;

use App\Http\Controllers\
{AuthController,
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

};

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/signup', [SignupController::class, 'create'])->name('signup');
Route::post('/signup', [SignupController::class, 'store'])->name('signup.submit');

Route::get('/reset-password', [PasswordController::class, 'create'])->name('password.change');
Route::post('/reset-password', [PasswordController::class, 'store'])->name('password.update');

 // عرض الصفحة الرئيسية
 Route::get('/', function () {
    return view('home');
});
// استخدام middleware للتأكد من أن المستخدم مسجل الدخول
Route::middleware('auth')->group(function () {

    // عرض لوحة التحكم (dashboard)

    Route::get('/dashboard', [NavbarController::class, 'showNavbar'])->name('dashboard');
    
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
    Route::delete('/delete/{id}', [RoleWarehouseController::class, 'destroy'])->name('destroy');
});

   
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
Route::get('branches/{branch}/edit', [BranchesController::class, 'edit'])->name('branches.edit');// عرض صفحة تعديل الفرع
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
    Route::get('/', [WarehouseLocationController::class, 'index'])->name('warehouse.locations.index');
    Route::get('/create', [WarehouseLocationController::class, 'create'])->name('warehouse.locations.create');
    Route::post('/', [WarehouseLocationController::class, 'store'])->name('warehouse.locations.store');
    Route::get('/{warehouse_location}/edit', [WarehouseLocationController::class, 'edit'])->name('warehouse.locations.edit');
    Route::put('/{warehouse_location}', [WarehouseLocationController::class, 'update'])->name('warehouse.locations.update');
    Route::delete('/{warehouse_location}', [WarehouseLocationController::class, 'destroy'])->name('warehouse.locations.destroy');
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






});

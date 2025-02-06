<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\WarehouseReports;

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
ZoneController,
CategoryController};

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
  
// user roles
Route::resource('users-roles', RoleUserController::class);

Route::get('/users-roles', [RoleUserController::class, 'index'])->name('users-roles.index'); // عرض القائمة
Route::post('/users-roles', [RoleUserController::class, 'store'])->name('users-roles.store'); // إضافة دور

//create user in steps
// Route::get('/user/create', UserSteps::class)->name('user.create');
Route::get('/user/create', function () {
    return view('users.create');
})->name('users.index');


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


Route::resource('zones', ZoneController::class);

//Categroy routers
Route::prefix('inventory')->name('inventory.')->group(function () {
    // عرض قائمة الفئات
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');

    // عرض نموذج إضافة فئة جديدة
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');

    // تخزين فئة جديدة
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');

    // عرض نموذج تعديل فئة
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');

    // تحديث بيانات الفئة
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');

    // حذف الفئة
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});
 
});

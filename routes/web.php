<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\UserSteps;

use App\Http\Controllers\
{AuthController,
    SignupController,
PasswordController,
CompanyController,
BranchesController,
WarehousesController,
UserController,
RoleUserController};

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
    Route::get('/dashboard', function () {
        return view('dashboard');
    });

  
// users
Route::resource('users', UserController::class);
  
// user roles
Route::get('/users-roles', [RoleUserController::class, 'index'])->name('users-roles.index'); // عرض القائمة
Route::post('/users-roles', [RoleUserController::class, 'store'])->name('users-roles.store'); // إضافة دور

//create user in steps
// Route::get('/user/create', UserSteps::class)->name('user.create');
Route::get('/user/create', function () {
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

});

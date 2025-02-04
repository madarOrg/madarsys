<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; 
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RolePermissionControllerCopy extends Controller
{
    public function create()
    {
        // جلب جميع الأدوار والصلاحيات
        $roles = Role::all();
        $permissions = Permission::all();

        // استخراج آخر صلاحية فعّالة للدور عبر جدول role_permissions
        $lastActivePermission = $roles->first()->permissions()
                                      ->wherePivot('status', 1) // صلاحية فعالة
                                      ->latest('pivot_updated_at') // ترتيب الصلاحيات حسب تاريخ التحديث في جدول pivot
                                      ->first();

        return view('role-permissions.create', compact('roles', 'permissions', 'lastActivePermission'));
    }

    // تخزين صلاحيات الدور
    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
            'status' => 'required|boolean',
        ]);

        $role = Role::findOrFail($request->role_id);
        
        // إضافة التاريخ مباشرة هنا
        $role->permissions()->attach($request->permission_id, [
            'status' => $request->status,
            'status_updated_at' => now(), // إضافة التاريخ هنا
        ]);
    
        return redirect()->route('role-permissions.create')->with('success', 'تم تعيين الصلاحيات بنجاح.');
    }
    
    public function index(Request $request)
{
    $query = Role::with(['permissions' => function ($query) use ($request) {
        // تضمين بيانات الـ pivot
        $query->withPivot('status', 'status_updated_at', 'id'); 

        // إضافة الفلاتر بناءً على المدخلات في الاستعلام
        if ($request->has('search') && $request->input('search') !== '') {
            $query->where('permissions.name', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->has('status') && $request->input('status') !== '') {
            $query->wherePivot('status', $request->input('status'));
        }

        if ($request->has('role') && $request->input('role') !== '') {
            $query->whereHas('role', function ($query) use ($request) {
                $query->where('roles.id', $request->input('role'));
            });
        }

        if ($request->has('permission') && $request->input('permission') !== '') {
            $query->where('permissions.id', $request->input('permission'));
        }
    }]);

    // جلب الأدوار مع صلاحياتها بناءً على الفلاتر
    $rolePermissions = $query->get();

    // جلب كل الأدوار والصلاحيات لتعبئة الـ dropdown في واجهة المستخدم
    $roles = Role::all();
    $permissions = Permission::all();

    // تمرير البيانات إلى الـ view
    return view('role-permissions.index', compact('rolePermissions', 'roles', 'permissions'));
}

    
    // عرض صفحة تعديل صلاحيات الدور
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('role-permissions.edit', compact('role', 'permissions'));
    }

    // تحديث صلاحيات الدور
    public function update(Request $request, $id)
    {
        $request->validate([
            'permission_id' => 'required|exists:permissions,id',
            'status' => 'required|boolean',
        ]);

        $role = Role::findOrFail($id);

        // تحديث البيانات في جدول pivot
        $role->permissions()->updateExistingPivot($request->permission_id, [
            'status' => $request->status,
            'status_updated_at' => now(), // تحديث التاريخ هنا
        ]);

        return redirect()->route('role-permissions.index')->with('success', 'تم تحديث الصلاحيات بنجاح');
    }

    // حذف صلاحية معينة من الدور
    public function detachPermission($roleId, $permissionId)
    {
        $role = Role::findOrFail($roleId);
        $role->permissions()->detach($permissionId);

        return redirect()->route('role-permissions.index')->with('success', 'تم إزالة الصلاحية من الدور بنجاح.');
    }
// إضافة دالة destroy لحذف الصلاحية من الدور

    public function destroy($id)
    {
        // تحقق من وجود السجل في جدول role_permissions باستخدام الـ id
        $rolePermission = DB::table('role_permissions')->where('id', $id)->first();

        if ($rolePermission) {
            // إزالة السجل من جدول role_permissions
            DB::table('role_permissions')->where('id', $id)->delete();

            return redirect()->route('role-permissions.index')->with('success', 'تم إزالة السجل بنجاح.');
        }

        // إذا لم يتم العثور على السجل
        return redirect()->route('role-permissions.index')->with('error', 'لم يتم العثور على السجل.');
    }
}

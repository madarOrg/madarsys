<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
class RolePermissionController extends Controller
{
    public function index()
{
    $roles = Role::with('permissions')->get();
    $permissions = Permission::all();

    $rolePermissions = $roles; // تعريف المتغير ليتطابق مع الاسم في Blade

    return view('role-permissions.index', compact('roles', 'permissions', 'rolePermissions'));
}
public function create()
{
    $roles = Role::all();
    $permissions = Permission::all();
// dd($permissions);
    return view('role-permissions.create', compact('roles', 'permissions'));
}

    public function update(Request $request)
    {
        $role = Role::findOrFail($request->role_id);
        $role->permissions()->sync($request->permissions); // تحديث الصلاحيات

        return response()->json(['message' => 'تم تحديث الصلاحيات بنجاح']);
    }
    public function destroy($id)
{
    $role = Role::findOrFail($id);
    $role->delete();

    return redirect()->route('role-permissions.index')->with('success', 'تم حذف الدور بنجاح.');
}
 /**
     * تخزين سجل جديد لصلاحية الدور.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات القادمة من الطلب
        $validated = $request->validate([
            'role_id'       => 'required|exists:roles,id',       // تأكد من وجود الدور في جدول roles
            'permission_id' => 'required|exists:permissions,id', // تأكد من وجود الصلاحية في جدول permissions
            'status'        => 'sometimes|boolean',                // يمكن إرسالها أو لا، وإذا لم تُرسل ستستخدم القيمة الافتراضية
        ]);

        // إنشاء سجل جديد باستخدام البيانات المُحقق منها
        $rolePermission = new RolePermission();
        $rolePermission->role_id = $validated['role_id'];
        $rolePermission->permission_id = $validated['permission_id'];
        $rolePermission->status = $validated['status'] ?? 1; // إذا لم يُرسل قيمة يتم تعيين 1 كافتراضية
        $rolePermission->status_updated_at = now(); // يتم تعيين تاريخ ووقت التحديث الحالي

        // حفظ السجل في قاعدة البيانات
        $rolePermission->save();

        // إعادة التوجيه مع رسالة نجاح (يمكن تعديلها حسب الحاجة)
        return redirect()->back()->with('success', 'تم حفظ صلاحية الدور بنجاح!');
    }
}


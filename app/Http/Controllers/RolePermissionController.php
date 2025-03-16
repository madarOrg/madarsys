<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;

class RolePermissionController extends Controller
{
    public function index(Request $request)
    {
        try {
            // جلب الأدوار مع الصلاحيات المرتبطة
            $query = Role::with('permissions');
    
            // تطبيق فلتر البحث عن الدور إذا تم إدخاله
            if ($request->filled('role')) {
                $query->where('id', $request->input('role'));
            }
    
            // تطبيق فلتر البحث عن الصلاحية إذا تم إدخالها
            if ($request->filled('permission')) {
                $query->whereHas('permissions', function ($q) use ($request) {
                    $q->where('id', $request->input('permission'));
                });
            }
    
            // تطبيق فلتر البحث حسب الحالة (فعال/غير فعال)
            if ($request->filled('status')) {
                $query->whereHas('permissions', function ($q) use ($request) {
                    $q->where('pivot.status', $request->input('status'));
                });
            }
    
            // تنفيذ الاستعلام بناءً على الفلاتر المحددة
            $roles = Role::all(); // جلب كل الأدوار من أجل القوائم المنسدلة
            $permissions = Permission::all(); // جلب جميع الصلاحيات من أجل القوائم المنسدلة
            $rolePermissions = $query->get(); 
    
            return view('role-permissions.index', compact('roles', 'permissions', 'rolePermissions'));
        } catch (\Exception $e) {
            return response()->view('errors.500', ['error' => 'حدث خطأ أثناء جلب الأدوار والصلاحيات: ' . $e->getMessage()], 500);

        }
    }
    

    public function create()
    {
        try {
            $roles = Role::all();
            $permissions = Permission::all();
            return view('role-permissions.create', compact('roles', 'permissions'));
        } catch (\Exception $e) {
            return response()->view('errors.500', ['error' => 'حدث خطأ أثناء عرض نموذج إضافة صلاحية الدور: ' . $e->getMessage()], 500);
        }
    }
    public function edit($id)
    {
        try {
            $role = Role::with('permissions')->findOrFail($id);
            $permissions = Permission::all();
            return view('role-permissions.edit', compact('role', 'permissions'));
        } catch (\Exception $e) {
            return response()->view('errors.500', ['error' => 'حدث خطأ أثناء جلب بيانات الدور للتعديل: ' . $e->getMessage()], 500);
        }
    }
    

    public function update(Request $request)
    {
        try {
            $role = Role::findOrFail($request->role_id);
            $role->permissions()->sync($request->permissions); // تحديث الصلاحيات

            return response()->json(['message' => 'تم تحديث الصلاحيات بنجاح']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'حدث خطأ أثناء تحديث الصلاحيات: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();

            return redirect()->route('role-permissions.index')->with('success', 'تم حذف الدور بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('role-permissions.index')->withErrors(['error' => 'حدث خطأ أثناء حذف الدور: ' . $e->getMessage()]);
        }
    }

    /**
     * تخزين سجل جديد لصلاحية الدور.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء حفظ صلاحية الدور: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $role = Role::with('permissions')->findOrFail($id);
            return view('role-permissions.show', compact('role'));
        } catch (\Exception $e) {
            return response()->view('errors.500', ['error' => 'حدث خطأ أثناء جلب بيانات الدور: ' . $e->getMessage()], 500);
        }
    }
}

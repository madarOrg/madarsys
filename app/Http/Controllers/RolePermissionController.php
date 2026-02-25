<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


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


    public function create(Request $request)
    {
        $roles = Role::all();
        $roleId = $request->query('role_id');

        $permissions = Permission::with('moduleAction')->get();

        // تحميل الصلاحيات الحالية المرتبطة بالدور إن وجد
        $rolePermissions = [];
        if ($roleId) {
            $rolePermissions = RolePermission::where('role_id', $roleId)
                ->get()
                ->keyBy('permission_id')
                ->map(function ($item) {
                    return [
                        'can_create' => $item->can_create,
                        'can_update' => $item->can_update,
                        'can_delete' => $item->can_delete,
                        'status' => $item->status,
                    ];
                });
        }

        return view('role-permissions.create', compact('roles', 'permissions', 'roleId', 'rolePermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $roleId = $request->input('role_id');
        $permissionsData = $request->input('permissions', []);

        // حذف الصلاحيات القديمة
        RolePermission::where('role_id', $roleId)->delete();

        // إدخال الصلاحيات الجديدة
        foreach ($permissionsData as $permissionId => $data) {
            if (!isset($data['selected'])) continue;

            RolePermission::create([
                'role_id' => $roleId,
                'permission_id' => $permissionId,
                'can_create' => isset($data['can_create']),
                'can_update' => isset($data['can_update']),
                'can_delete' => isset($data['can_delete']),
                'status' => $data['status'] ?? 1,
            ]);
        }

        return redirect()->route('role-permissions.create', ['role_id' => $roleId])
            ->with('success', 'تم تحديث صلاحيات الدور بنجاح');
    }

    public function edit(Request $request)
    {
        $roles = Role::all();
        $selectedRole = null;
        $permissions = [];

        if ($request->has('role_id')) {
            $selectedRole = Role::with(['permissions' => function ($q) {
                $q->withPivot(['can_update', 'can_delete', 'status']);
            }])->findOrFail($request->role_id);

            $permissions = $selectedRole->permissions;
        }

        return view('role-permissions.edit', compact('roles', 'selectedRole', 'permissions'));
    }
    public function update(Request $request, $roleId)
    {
        $role = Role::findOrFail($roleId);

        foreach ($request->permissions as $permissionId => $data) {
            $role->permissions()->updateExistingPivot($permissionId, [
                'can_update' => isset($data['can_update']),
                'can_delete' => isset($data['can_delete']),
                'status' => $data['status'] ?? 0,
                'status_updated_at' => now(),
            ]);
        }

        return redirect()->route('role-permissions.edit', ['role_id' => $roleId])
            ->with('success', 'تم تحديث الصلاحيات بنجاح.');
    }


    // طرد المستخدمين من sessions المرتبطين بالدور المحدث
    private function logoutUsersByRole($roleId)
    {
        $userIds = DB::table('role_user')
            ->where('role_id', $roleId)
            ->pluck('user_id');

        $currentUserId = Auth::id();

        $userIds = $userIds->filter(function ($id) use ($currentUserId) {
            return $id != $currentUserId;
        });

        if ($userIds->isNotEmpty()) {
            DB::table('sessions')->whereIn('user_id', $userIds)->delete();
        }
    }

    public function destroy($pivotId)
    {
        try {
            // استخراج السطر من جدول role_permissions
            $pivotRecord = DB::table('role_permissions')->where('id', $pivotId)->first();

            if (!$pivotRecord) {
                return redirect()->route('role-permissions.index')->withErrors(['error' => 'لم يتم العثور على هذا السطر في جدول role_permissions.']);
            }

            $roleId = $pivotRecord->role_id;

            // حذف السجل
            DB::table('role_permissions')->where('id', $pivotId)->delete();

            // إخراج المستخدمين التابعين لهذا الدور
            $this->logoutUsersByRole($roleId);

            return redirect()->route('role-permissions.index')->with('success', 'تم حذف الصلاحية من الدور بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('role-permissions.index')->withErrors(['error' => 'حدث خطأ أثناء حذف الصلاحية من الدور: ' . $e->getMessage()]);
        }
    }
}

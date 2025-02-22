<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * عرض جميع الأدوار.
     */
    public function index(Request $request)
    {
        try {
            // الحصول على كلمة البحث من الطلب
            $search = $request->input('search');
    
            // جلب الأدوار مع البحث في جميع الحقول
            $roles = Role::when($search, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%")   // البحث في اسم الدور
                      ->orWhere('description', 'LIKE', "%{$search}%");  // البحث في وصف الدور إذا كان موجوداً
            })
            ->paginate(7);  // تحديد عدد الأدوار المعروضة في الصفحة
    
            return view('roles.index', compact('roles'));
        } catch (\Exception $e) {
            // في حالة حدوث خطأ
            return response()->view('errors.500', ['error' => 'حدث خطأ أثناء جلب الأدوار: ' . $e->getMessage()], 500);
        }
    }

    /**
     * إظهار نموذج إضافة دور جديد.
     */
    public function create()
    {
        try {
            return view('roles.create');
        } catch (\Exception $e) {
            // في حالة حدوث خطأ
            return response()->view('errors.500', ['error' => 'حدث خطأ أثناء عرض نموذج إضافة الدور: ' . $e->getMessage()], 500);
        }
    }

    /**
     * تخزين دور جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles',
                'status' => 'required|boolean',
            ]);

            Role::create($request->all());

            return redirect()->route('roles.index')->with('success', 'تمت إضافة الدور بنجاح.');
        } catch (\Exception $e) {
            // في حالة حدوث خطأ
            return back()->withErrors(['error' => 'حدث خطأ أثناء إضافة الدور: ' . $e->getMessage()]);
        }
    }

    /**
     * إظهار تفاصيل دور معين.
     */
    public function show(Role $role)
    {
        try {
            return view('roles.show', compact('role'));
        } catch (\Exception $e) {
            // في حالة حدوث خطأ
            return response()->view('errors.500', ['error' => 'حدث خطأ أثناء عرض تفاصيل الدور: ' . $e->getMessage()], 500);
        }
    }

    /**
     * إظهار نموذج تعديل دور.
     */
    public function edit(Role $role)
    {
        try {
            return view('roles.edit', compact('role'));
        } catch (\Exception $e) {
            // في حالة حدوث خطأ
            return response()->view('errors.500', ['error' => 'حدث خطأ أثناء عرض نموذج تعديل الدور: ' . $e->getMessage()], 500);
        }
    }

    /**
     * تحديث بيانات دور معين.
     */
    public function update(Request $request, Role $role)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
                'status' => 'required|boolean',
            ]);

            $role->update($request->all());

            return redirect()->route('roles.index')->with('success', 'تم تعديل الدور بنجاح.');
        } catch (\Exception $e) {
            // في حالة حدوث خطأ
            return back()->withErrors(['error' => 'حدث خطأ أثناء تعديل الدور: ' . $e->getMessage()]);
        }
    }

    /**
     * حذف دور معين.
     */
    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return redirect()->route('roles.index')->with('success', 'تم حذف الدور بنجاح.');
        } catch (\Exception $e) {
            // في حالة حدوث خطأ
            return back()->withErrors(['error' => 'حدث خطأ أثناء حذف الدور: ' . $e->getMessage()]);
        }
    }
}

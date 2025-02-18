<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * عرض جميع الأدوار.
     */
    public function index()
    {
        try {
            $roles = Role::all();//withCount('users')->get();
            return view('roles.index', compact('roles'));
        } catch (\Exception $e) {
            // Log the error for debugging
            // \Log::error('Error in RoleController@index: ' . $e->getMessage());
            return response()->view('errors.500', [], 500);
        }
    }
    

    /**
     * إظهار نموذج إضافة دور جديد.
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * تخزين دور جديد في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'status' => 'required|boolean',
        ]);

        Role::create($request->all());

        return redirect()->route('roles.index')->with('success', 'تمت إضافة الدور بنجاح.');
    }

    /**
     * إظهار تفاصيل دور معين.
     */
    public function show(Role $role)
    {
        return view('roles.show', compact('role'));
    }

    /**
     * إظهار نموذج تعديل دور.
     */
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    /**
     * تحديث بيانات دور معين.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'status' => 'required|boolean',
        ]);

        $role->update($request->all());

        return redirect()->route('roles.index')->with('success', 'تم تعديل الدور بنجاح.');
    }

    /**
     * حذف دور معين.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'تم حذف الدور بنجاح.');
    }
}

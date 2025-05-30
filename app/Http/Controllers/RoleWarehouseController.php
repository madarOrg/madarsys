<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoleWarehouse;
use App\Models\Role;
use App\Models\Warehouse;
use App\Models\Branch;
use App\Models\RoleBranch;
use App\Models\RoleCompany;

class RoleWarehouseController extends Controller
{
    public function index()
    {
        try {
            $roleWarehouses = RoleWarehouse::with(['role', 'warehouse', 'branch'])->paginate(10); // مثلاً 10 عناصر في كل صفحة
            $roles = Role::all();
            $warehouses = Warehouse::all();
            $branches = Branch::all();

            return view('role-warehouse.index', compact('roleWarehouses', 'roles', 'warehouses', 'branches'));
        } catch (\Exception $e) {
            return response()->view('errors.500', ['error' => 'حدث خطأ أثناء جلب البيانات: ' . $e->getMessage()], 500);
        }
    }

 

public function store(Request $request)
{
    try {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $data = $request->only(['role_id', 'warehouse_id', 'branch_id']);
        RoleWarehouse::create($data);

        // احصل على الـ branch
        $branch = \App\Models\Branch::findOrFail($data['branch_id']);
        $companyId = $branch->company_id;

        // تحقق أو أضف الدور إلى الفرع
        RoleBranch::firstOrCreate([
            'role_id' => $data['role_id'],
            'branch_id' => $data['branch_id'],
            'company_id' => $companyId
        ]);

        // تحقق أو أضف الدور إلى الشركة
        RoleCompany::firstOrCreate([
            'role_id' => $data['role_id'],
            'company_id' => $companyId
        ]);

        return response()->json(['success' => 'تمت الإضافة بنجاح']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'حدث خطأ أثناء إضافة البيانات: ' . $e->getMessage()]);
    }
}

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'role_id' => 'required|exists:roles,id',
                'warehouse_id' => 'required|exists:warehouses,id',
                'branch_id' => 'required|exists:branches,id',
            ]);

            $roleWarehouse = RoleWarehouse::findOrFail($id);
            $roleWarehouse->update($request->all());
            return response()->json(['success' => 'تم التحديث بنجاح']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'حدث خطأ أثناء تحديث البيانات: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $roleWarehouse = RoleWarehouse::findOrFail($id);
            $roleWarehouse->delete();
            return response()->json(['success' => 'تم الحذف بنجاح']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'حدث خطأ أثناء حذف البيانات: ' . $e->getMessage()]);
        }
    }
   

}

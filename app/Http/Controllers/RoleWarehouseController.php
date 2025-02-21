<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoleWarehouse;
use App\Models\Role;
use App\Models\Warehouse;
use App\Models\Branch;

class RoleWarehouseController extends Controller
{
    public function index()
    {
        $roleWarehouses = RoleWarehouse::with(['role', 'warehouse', 'branch'])->get();
        $roles = Role::all();
        $warehouses = Warehouse::all();
        $branches = Branch::all();

        return view('role-warehouse.index', compact('roleWarehouses', 'roles', 'warehouses', 'branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        RoleWarehouse::create($request->all());
        return response()->json(['success' => 'تمت الإضافة بنجاح']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        RoleWarehouse::find($id)->update($request->all());
        return response()->json(['success' => 'تم التحديث بنجاح']);
    }

    public function destroy($id)
    {
        RoleWarehouse::find($id)->delete();
        return response()->json(['success' => 'تم الحذف بنجاح']);
    }
}

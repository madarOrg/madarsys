<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\WarehouseStorageArea;
use Illuminate\Http\Request;

class WarehouseStorageAreaController extends Controller
{
    public function index($warehouseId)
    {
        $warehouse = Warehouse::findOrFail($warehouseId);
        $storageAreas = WarehouseStorageArea::where('warehouse_id', $warehouse->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('warehouses.storage-areas.index', compact('storageAreas', 'warehouse'));
    }

    public function create($warehouseId)
    {
        $warehouse = Warehouse::findOrFail($warehouseId);
        return view('warehouses.storage-areas.create', compact('warehouse'));
    }

    public function store(Request $request, $warehouseId)
    {
        $request->validate([
            'area_name' => 'required|string',
            'area_type' => 'required|string',
            'capacity' => 'required|numeric',
            'current_occupancy' => 'required|numeric',
            'zone_id' => 'nullable|exists:zones,id',
            'storage_conditions' => 'nullable|string',
        ]);

        $warehouse = Warehouse::findOrFail($warehouseId);

        $warehouse->storageAreas()->create($request->all());

        return redirect()->route('warehouse.storage-areas.create', ['warehouse' => $warehouse->id])
                         ->with('success', 'تم إضافة منطقة التخزين بنجاح');
    }

    public function edit($warehouseId, $storageAreaId)
    {
        $warehouse = Warehouse::findOrFail($warehouseId);
        $storageArea = $warehouse->storageAreas()->findOrFail($storageAreaId);

        return view('warehouses.storage-areas.edit', compact('warehouse', 'storageArea'));
    }

    public function update(Request $request, $warehouseId, $storageAreaId)
    {
        $request->validate([
            'area_name' => 'required|string|max:255',
            'area_type' => 'required|string|max:255',
            'capacity' => 'required|numeric|min:0',
            'current_occupancy' => 'nullable|numeric|min:0',
            'storage_conditions' => 'nullable|string|max:255',
        ]);

        $warehouse = Warehouse::findOrFail($warehouseId);
        $storageArea = $warehouse->storageAreas()->findOrFail($storageAreaId);

        $storageArea->update($request->all());

        return redirect()->route('warehouse.storage-areas.index', ['warehouse' => $warehouse->id])
                         ->with('success', 'تم تحديث منطقة التخزين بنجاح');
    }

    public function destroy($warehouseId, $storageAreaId)
    {
        $warehouse = Warehouse::findOrFail($warehouseId);
        $storageArea = $warehouse->storageAreas()->findOrFail($storageAreaId);

        $storageArea->delete();

        return redirect()->route('warehouse.storage-areas.index', ['warehouse' => $warehouse->id])
                         ->with('success', 'تم حذف منطقة التخزين بنجاح');
    }
}

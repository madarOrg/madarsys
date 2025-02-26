<?php

namespace App\Http\Controllers;
use App\Models\Zone;
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
            ->paginate(7);

        return view('warehouses.storage-areas.index', compact('storageAreas', 'warehouse'));
    }

   
    public function create(Warehouse $warehouse)
    {
        // جلب المناطق المرتبطة بالمستودع
        $zones = Zone::where('warehouse_id', $warehouse->id)->pluck('name', 'id');
    
        if ($zones->isEmpty()) {
            // إذا كانت المناطق فارغة، نعرض رسالة تحذير
            session()->flash('warning', 'لا توجد مناطق لهذا المستودع.');
        }
    
        // مرر المتغير zones مع باقي المتغيرات إلى الصفحة
        return view('warehouses.storage-areas.create', compact('zones', 'warehouse'));
    }
    public function store(Request $request, $warehouseId)
    {
        $request->validate([
            'area_name' => 'required|string',
            'area_type' => 'required|string',
            'capacity' => 'required|numeric',// السعة التخزينية 
            'current_occupancy' => 'required|numeric',// الكمية المشغولة حاليًا 
            'zone_id' => 'nullable|exists:zones,id',
            'storage_conditions' => 'nullable|string',
        ]);

        $warehouse = Warehouse::findOrFail($warehouseId);

        $warehouse->storageAreas()->create($request->all());

        return redirect()->route('warehouse.storage-areas.create', ['warehouse' => $warehouse->id])
                         ->with('success', 'تم إضافة منطقة التخزين بنجاح');
    }

    public function edit(Warehouse $warehouse, $storageAreaId)
    {
        $zones = Zone::where('warehouse_id', $warehouse->id)->pluck('name', 'id');  // جلب المناطق المرتبطة بالمستودع

        // $warehouse = Warehouse::findOrFail($warehouseId);
        $storageArea = $warehouse->storageAreas()->findOrFail($storageAreaId);  // جلب منطقة التخزين المحددة

        return view('warehouses.storage-areas.edit', compact('zones', 'warehouse', 'storageArea'));
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
// جلب المناطق المرتبطة بالمستودع
    $zones = Zone::where('warehouse_id', $warehouse->id)->pluck('name', 'id');

    // إذا كانت المناطق فارغة، نعرض رسالة تحذير
    if ($zones->isEmpty()) {
        session()->flash('warning', 'لا توجد مناطق لهذا المستودع.');
    }

    // العودة إلى صفحة التعديل مع تمكين المستخدم من اختيار المناطق المتاحة
    return view('warehouses.storage-areas.edit', compact('warehouse', 'storageArea', 'zones'))
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

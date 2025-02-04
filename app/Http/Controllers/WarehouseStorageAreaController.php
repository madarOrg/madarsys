<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\WarehouseStorageArea;
use Illuminate\Http\Request;

class WarehouseStorageAreaController extends Controller
{
    /**
     * عرض قائمة مناطق التخزين
     */
    public function index($warehouse)
    {
        $storageAreas = WarehouseStorageArea::with('warehouse')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

            return view('warehouses.storage-areas.index', compact('storageAreas', 'warehouse'));
        }

    public function create($warehouseId)
    {
        // العثور على المستودع باستخدام الـ warehouseId
        $warehouse = Warehouse::findOrFail($warehouseId);
    
        // عرض الصفحة التي تحتوي على النموذج
        return view('warehouses.storage-areas.create', compact('warehouse'));
    }
    
    // دالة لتخزين منطقة تخزين جديدة
    public function store(Request $request, $warehouseId)
    {
        // منطق تخزين منطقة التخزين داخل مستودع معين
        $request->validate([
            'area_name' => 'required|string',
            'area_type' => 'required|string',
            'capacity' => 'required|numeric',
            'current_occupancy' => 'required|numeric',
            'zone_id' => 'nullable|exists:zones,id',
            'storage_conditions' => 'nullable|string',
        ]);

        // العثور على المستودع
        $warehouse = Warehouse::findOrFail($warehouseId);

        // إنشاء منطقة التخزين داخل المستودع
        $warehouse->storageAreas()->create([
            'area_name' => $request->area_name,
            'area_type' => $request->area_type,
            'capacity' => $request->capacity,
            'current_occupancy' => $request->current_occupancy,
            'zone_id' => $request->zone_id,
            'storage_conditions' => $request->storage_conditions,
        ]);

        return redirect()->route('warehouses.storage-areas.index', $warehouseId)
                         ->with('success', 'تم إضافة منطقة التخزين بنجاح');
    }

    // دالة لتحرير منطقة التخزين
    public function edit($warehouseId, $storageAreaId)
    {
        // العثور على المستودع ومنطقة التخزين
        $warehouse = Warehouse::findOrFail($warehouseId);
        $storageArea = $warehouse->storageAreas()->findOrFail($storageAreaId);

        return view('storage-areas.edit', compact('warehouse', 'storageArea'));
    }

    // دالة لتحديث منطقة التخزين
    public function update(Request $request, $warehouseId, $storageAreaId)
    {
        // التحقق من البيانات المدخلة
        $request->validate([
            'area_name' => 'required|string|max:255',        // اسم المنطقة مطلوب ويجب أن يكون نصًا
            'area_type' => 'required|string|max:255',         // نوع المنطقة مطلوب ويجب أن يكون نصًا
            'capacity' => 'required|numeric|min:0',           // السعة التخزينية مطلوبة ويجب أن تكون عددًا
            'current_occupancy' => 'nullable|numeric|min:0',  // الإشغال الحالي اختياري ويجب أن يكون عددًا، مع حد أدنى 0
            'storage_conditions' => 'nullable|string|max:255', // شروط التخزين اختياري ويجب أن تكون نصًا
        ]);
        
        // العثور على المستودع ومنطقة التخزين
        $warehouse = Warehouse::findOrFail($warehouseId);
        $storageArea = $warehouse->storageAreas()->findOrFail($storageAreaId);

        // تحديث البيانات
        $storageArea->update($request->all());

        return redirect()->route('warehouses.storage-areas.index', $warehouseId)
                         ->with('success', 'تم تحديث منطقة التخزين بنجاح');
    }

    // دالة لحذف منطقة التخزين
    public function destroy($warehouseId, $storageAreaId)
    {
        // العثور على المستودع ومنطقة التخزين
        $warehouse = Warehouse::findOrFail($warehouseId);
        $storageArea = $warehouse->storageAreas()->findOrFail($storageAreaId);

        // حذف منطقة التخزين
        $storageArea->delete();

        return redirect()->route('warehouses.storage-areas.index', $warehouseId)
                         ->with('success', 'تم حذف منطقة التخزين بنجاح');
    }
}

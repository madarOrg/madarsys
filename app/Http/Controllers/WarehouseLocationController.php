<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\WarehouseLocation;
use Illuminate\Http\Request;
use App\Models\WarehouseStorageArea;

class WarehouseLocationController extends Controller
{
    /**
     * عرض قائمة مواقع المستودعات الخاصة بمستودع معين.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\View\View
     */
    public function index(Warehouse $warehouse)
    {
        // جلب المواقع التي تخص المستودع المحدد
        $warehouseLocations = WarehouseLocation::where('warehouse_id', $warehouse->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('warehouses.locations.index', compact('warehouse', 'warehouseLocations'));
    }

    /**
     * إظهار نموذج إنشاء موقع جديد لمستودع معين.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\View\View
     */
    
    public function create(Warehouse $warehouse)
    {
        // جلب مناطق التخزين من قاعدة البيانات
        $storageAreas = WarehouseStorageArea::all(); 
    
        // تمرير البيانات إلى القالب
        return view('warehouses.locations.create', compact('warehouse', 'storageAreas'));
        $zones = Zone::all(); // استرجاع جميع المناطق
        return view('warehouses.locations.create', compact('zones')); // تمرير المناطق إلى الصفحة
    }
    
    /**
     * حفظ موقع مستودع جديد في قاعدة البيانات.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warehouse     $warehouse
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Warehouse $warehouse)
    {
        $data = $request->validate([
            'storage_area_id' => 'required|exists:warehouse_storage_areas,id',
            'aisle'           => 'required|string|max:255',
            'rack'            => 'required|string|max:255',
            'shelf'           => 'required|string|max:255',
            'position'        => 'required|string|max:255',
            'barcode'         => 'required|string|unique:warehouse_locations,barcode',
            'is_occupied'     => 'sometimes|boolean',
            'notes'           => 'nullable|string',
        ]);

        // ربط الموقع بالمستودع باستخدام معرف المستودع
        $data['warehouse_id'] = $warehouse->id;

        WarehouseLocation::create($data);

        return redirect()->route('warehouses.locations.index', ['warehouse' => $warehouse->id])
                         ->with('success', 'تم إضافة موقع المستودع بنجاح.');
    }

    /**
     * عرض تفاصيل موقع مستودع معين.
     *
     * @param  \App\Models\Warehouse         $warehouse
     * @param  \App\Models\WarehouseLocation $warehouse_location
     * @return \Illuminate\View\View
     */
    public function show(Warehouse $warehouse, WarehouseLocation $warehouse_location)
    {
        return view('warehouses.locations.show', compact('warehouse', 'warehouse_location'));
    }

    /**
     * إظهار نموذج تعديل موقع مستودع معين.
     *
     * @param  \App\Models\Warehouse         $warehouse
     * @param  \App\Models\WarehouseLocation $warehouse_location
     * @return \Illuminate\View\View
     */
    public function edit(Warehouse $warehouse, WarehouseLocation $warehouse_location)
    {
        return view('warehouses.locations.edit', compact('warehouse', 'warehouse_location'));
    }

    /**
     * تحديث بيانات موقع مستودع معين.
     *
     * @param  \Illuminate\Http\Request      $request
     * @param  \App\Models\Warehouse         $warehouse
     * @param  \App\Models\WarehouseLocation $warehouse_location
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Warehouse $warehouse, WarehouseLocation $warehouse_location)
    {
        $data = $request->validate([
            'storage_area_id' => 'required|exists:warehouse_storage_areas,id',
            'aisle'           => 'required|string|max:255',
            'rack'            => 'required|string|max:255',
            'shelf'           => 'required|string|max:255',
            'position'        => 'required|string|max:255',
            'barcode'         => 'required|string|unique:warehouse_locations,barcode,' . $warehouse_location->id,
            'is_occupied'     => 'sometimes|boolean',
            'notes'           => 'nullable|string',
        ]);

        $warehouse_location->update($data);

        return redirect()->route('warehouses.locations.index', ['warehouse' => $warehouse->id])
                         ->with('success', 'تم تحديث موقع المستودع بنجاح.');
    }

    /**
     * حذف موقع مستودع معين.
     *
     * @param  \App\Models\Warehouse         $warehouse
     * @param  \App\Models\WarehouseLocation $warehouse_location
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Warehouse $warehouse, WarehouseLocation $warehouse_location)
    {
        $warehouse_location->delete();

        return redirect()->route('warehouses.locations.index', ['warehouse' => $warehouse->id])
                         ->with('success', 'تم حذف موقع المستودع بنجاح.');
    }
}

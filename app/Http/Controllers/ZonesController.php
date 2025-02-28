<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;
use App\Models\Warehouse;


class ZonesController extends Controller
{
    
    //  عرض جميع المناطق حسب مستودع
    public function index(Request $request, $warehouse)
    {
        // جلب قيمة البحث من الطلب
        $search = $request->input('search');
    
        // استعلام للبحث في البيانات ضمن مستودع معين
        $zones = Zone::where('warehouse_id', $warehouse)
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%')
                             ->orWhere('code', 'like', '%' . $search . '%')
                             ->orWhere('description', 'like', '%' . $search . '%'); 
            })
            ->paginate(7); // تقسيم الصفحات
    
        return view('warehouses.zones.index', compact('zones', 'warehouse'));
    }
    

    public function create(Warehouse $warehouse)
    {
        $warehouses = Warehouse::all();
        return view('warehouses.zones.create', compact('warehouses', 'warehouse'));
    }
    
    // حفظ منطقة جديدة
    public function store(Request $request , Warehouse $warehouse)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:zones,name',
            'code' => 'required|string|max:255|unique:zones,code',
            'description' => 'nullable|string',
            'warehouse_id' => 'required|exists:warehouses,id', // إضافة التحقق من المستودع
            'capacity' => 'required|integer|min:1',
        'current_occupancy' => 'required|integer|min:0|max:' . $request->capacity,
        ]);
        $data['warehouse_id'] = $warehouse->id;
        Zone::create($data);

return redirect()->route('warehouses.zones.index', ['warehouse' => $warehouse->id])
                     ->with('success', 'تمت إضافة المنطقة الفرعية بنجاح.');    }

    // عرض تفاصيل منطقة معينة
    public function show(Zone $zone)
    {
        return view('warehouses.zones.show', compact('zone'));
    }

    // عرض نموذج تعديل المنطقة
    
    public function edit($warehouse, $id)
    {
        $zone = Zone::where('warehouse_id', $warehouse)->findOrFail($id);
        $warehouses = Warehouse::all();
        return view('warehouses.zones.edit', compact('zone', 'warehouses', 'warehouse'));
    }
    

    // تحديث بيانات المنطقة
    public function update(Request $request, $warehouse, $id)
    {
        $zone = Zone::where('warehouse_id', $warehouse)->findOrFail($id);

     $data = $request->validate([
        'name' => 'required|string|max:255|unique:zones,name,' . $zone->id,
        'code' => 'required|string|max:255|unique:zones,code,' . $zone->id,
        'description' => 'nullable|string',
        'capacity' => 'required|integer|min:1',
        'current_occupancy' => 'required|integer|min:0|max:' . $request->capacity,
    ]);

    $zone->update($data);
    return redirect()->route('warehouses.zones.index', ['warehouse' => $warehouse])
                     ->with('success', 'تم تحديث المنطقة الفرعية بنجاح.');
}

    // حذف المنطقة
    public function destroy($warehouse, Zone $zone)
    {
        $zone->delete();
        return redirect()->route('warehouses.zones.index', ['warehouse' => $warehouse])
                         ->with('success', 'تم حذف المنطقة الفرعية بنجاح.');
    }
    
}

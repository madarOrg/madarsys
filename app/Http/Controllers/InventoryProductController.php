<?php

namespace App\Http\Controllers;

use App\Models\InventoryProduct;
use App\Models\Product;
use App\Models\Branch;
use App\Models\Warehouse;
use App\Models\WarehouseStorageArea;
use App\Models\WarehouseLocation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryProductController extends Controller
{
    /**
     * عرض صفحة إضافة حركة مخزنية جديدة.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        // جلب جميع المنتجات
        $products = Product::all();
    
        // جلب جميع الفروع
        $branches = Branch::all();
    
        // جلب المستودعات فقط عند تحديد فرع معين
        $warehouses = Warehouse::when($request->branch_id, function ($query) use ($request) {
            return $query->where('branch_id', $request->branch_id);
        })->get();
    
        // جلب المناطق التخزينية فقط عند اختيار مستودع معين
        $storageAreas = WarehouseStorageArea::when($request->warehouse_id, function ($query) use ($request) {
            return $query->where('warehouse_id', $request->warehouse_id);
        })->get();
    
        // جلب المواقع التخزينية فقط عند اختيار منطقة تخزينية معينة
        $locations = WarehouseLocation::when($request->storage_area_id, function ($query) use ($request) {
            return $query->where('storage_area_id', $request->storage_area_id);
        })->get();
    
        return view('inventory-products.create', compact('products', 'branches', 'warehouses', 'storageAreas', 'locations'));
    }
    

    /**
     * تخزين حركة مخزنية جديدة.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // التحقق من البيانات المدخلة
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'branch_id' => 'nullable|exists:branches,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'storage_area_id' => 'required|exists:warehouse_storage_areas,id',
            'inventory_movement_type' => 'required|in:1,2',
            'shelf_location' => 'nullable|string|max:255',
            'created_user' => 'nullable|exists:users,id',
            'updated_user' => 'nullable|exists:users,id',
        ]);

        // إنشاء حركة مخزنية جديدة
        InventoryProduct::create([
            'product_id' => $request->input('product_id'),
            'branch_id' => $request->input('branch_id'),
            'warehouse_id' => $request->input('warehouse_id'),
            'storage_area_id' => $request->input('storage_area_id'),
            'shelf_location' => $request->input('shelf_location'),
            'inventory_movement_type' => $request->input('inventory_movement_type'),
            'created_user' => Auth::id(), // المستخدم الذي قام بالإضافة
            'updated_user' => Auth::id(), // يمكن تحديثه لاحقًا
        ]);

        // إعادة التوجيه بعد حفظ البيانات
        return redirect()->route('inventory-products.create')->with('success', 'تم إضافة حركة المخزون بنجاح');
    }
}

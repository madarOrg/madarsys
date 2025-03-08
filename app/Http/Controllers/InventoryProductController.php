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

    public function index(Request $request)
    {

        // جلب جميع المستودعات لعرضها في القائمة المنسدلة
        $warehouses = Warehouse::all();
    
        if ($request->has('warehouse_id')) {
            $products = InventoryProduct::where('warehouse_id', $request->warehouse_id)
                ->with(['product', 'storageArea', 'storageArea.locations'])
                ->get();
        } else {
            $products = collect([]);
        }
    
        return view('inventory-products.index', compact('warehouses', 'products'));
    }
    
    
    



    /**
     * عرض صفحة إضافة حركة مخزنية جديدة.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $products = Product::all();
        $branches = Branch::all();

        $warehouses = Warehouse::when($request->branch_id, function ($query) use ($request) {
            return $query->where('branch_id', $request->branch_id);
        })->get();

        $storageAreas = WarehouseStorageArea::when($request->warehouse_id, function ($query) use ($request) {
            return $query->where('warehouse_id', $request->warehouse_id);
        })->get();

        $locations = WarehouseLocation::when($request->storage_area_id, function ($query) use ($request) {
            return $query->where('storage_area_id', $request->storage_area_id);
        })->get()->mapWithKeys(function ($location) {
            return [$location->id => $location->full_location];
        });

        return view('inventory-products.create', compact('products', 'branches', 'warehouses', 'storageAreas', 'locations'));
    }

    //  دالة جلب المستودعات بناءً على الفرع المحدد
    public function getWarehouses($branch_id)
    {
        $warehouses = Warehouse::where('branch_id', $branch_id)->pluck('name', 'id');
        return response()->json($warehouses);
    }

    //  دالة جلب المناطق التخزينية بناءً على المستودع المحدد
    public function getStorageAreas($warehouse_id)
    {
        $storageAreas = WarehouseStorageArea::where('warehouse_id', $warehouse_id)->pluck('area_name', 'id');
        return response()->json($storageAreas);
    }

    //  دالة جلب المواقع بناءً على المنطقة التخزينية المحددة
    public function getLocations($storage_area_id)
    {
        $locations = WarehouseLocation::where('storage_area_id', $storage_area_id)->pluck('rack_code', 'id');
        return response()->json($locations);
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
            'location_id' => 'nullable|string|max:255',
            'created_user' => 'nullable|exists:users,id',
            'updated_user' => 'nullable|exists:users,id',
        ]);

        // إنشاء حركة مخزنية جديدة
        InventoryProduct::create([
            'product_id' => $request->input('product_id'),
            'branch_id' => $request->input('branch_id'),
            'warehouse_id' => $request->input('warehouse_id'),
            'storage_area_id' => $request->input('storage_area_id'),
            'location_id' => $request->input('location_id'),
            // 'inventory_movement_type' => $request->input('inventory_movement_type'),
            'created_user' => Auth::id(), // المستخدم الذي قام بالإضافة
            'updated_user' => Auth::id(), // يمكن تحديثه لاحقًا
        ]);

        // إعادة التوجيه بعد حفظ البيانات
        return redirect()->route('inventory-products.create')->with('success', 'تم إضافة موقع المخزون بنجاح');
    }
}

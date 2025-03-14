<?php

namespace App\Http\Controllers;

use App\Models\InventoryProduct;
use App\Models\Product;
use App\Models\Branch;
use App\Models\Warehouse;
use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionItem;
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
        // $transactions = InventoryTransactionItem::with('items.product')->get();
        
        // $products = Product::all();
        // $transaction = InventoryTransaction::with('items.product')->find($transactions->id);
        // $products = $transaction->items->pluck('product');
        // $products = InventoryTransaction::with('items.product')->get();
        $products = InventoryTransaction::with('items.product')
    ->get()
    ->flatMap(function ($transaction) {
        return $transaction->items->map(function ($item) {
            return $item->product;
        });
    })
    ->unique('id'); // تجنب التكرار

        $branches = Branch::all();
        $userBranch = Auth::user()->branch_id; // فرع المستخدم الحالي

        // $warehouses = Warehouse::when($userBranch, function ($query) use ($userBranch) {
        //     return $query->where('branch_id', $userBranch);
        // })->get();
         // استخدام الـ Scope لجلب المستودعات الخاصة بفرع المستخدم الحالي
    $warehouses = Warehouse::forUserBranch()->get();

// dump($warehouses);
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



    /**
     * تخزين حركة مخزنية جديدة.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // dd($request->inventory_transaction_item_id);
        // التحقق من البيانات المدخلة
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'branch_id' => 'nullable|exists:branches,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'storage_area_id' => 'required|exists:warehouse_storage_areas,id',
            'location_id' => 'nullable|string|max:255',
            'created_user' => 'nullable|exists:users,id',
            'updated_user' => 'nullable|exists:users,id',
            'quantity'         => 'required|numeric|min:1', // التأكد من إدخال الكمية
            'inventory_transaction_item_id' => 'nullable|exists:inventory_transaction_items,id', // تحقق من الحقل الجديد


        ]);
        // جلب الكمية الأصلية من جدول inventory_transaction_items
        $transactionItem = InventoryTransactionItem::findOrFail($request->inventory_transaction_item_id);
        $originalQuantity = $transactionItem->quantity;

        // حساب الكمية التي تم توزيعها لهذا المنتج في نفس المستودع ونفس الحركة المخزنية
        $distributedQuantity = InventoryTransactionItem::where('id', $request->inventory_transaction_item_id)
            ->where('product_id', $request->product_id)
            ->where('target_warehouse_id', $request->warehouse_id)
            ->sum('quantity');

        // التحقق من أن الكمية الجديدة لا تتجاوز الكمية الأصلية
        if (($distributedQuantity + $request->quantity) > $originalQuantity) {
            return redirect()->back()->withErrors(['quantity' => 'إجمالي الكميات الموزعة يتجاوز الكمية الأصلية المتاحة في الحركة المخزنية.']);
        }

        // إنشاء حركة مخزنية جديدة
        InventoryProduct::create([
            'product_id' => $request->input('product_id'),
            'branch_id' => $request->input('branch_id'),
            'warehouse_id' => $request->input('warehouse_id'),
            'storage_area_id' => $request->input('storage_area_id'),
            'location_id' => $request->input('location_id'),
            'created_user' => Auth::id(), // المستخدم الذي قام بالإضافة
            'updated_user' => Auth::id(), // يمكن تحديثه لاحقًا
            'quantity'        => $request->input('quantity'), // تحديث الكمية
            'inventory_transaction_item_id' => $request->input('inventory_transaction_item_id'), // إضافة الحقل الجديد


        ]);

        // إعادة التوجيه بعد حفظ البيانات
        return redirect()->route('inventory-products.create')->with('success', 'تم إضافة موقع المخزون بنجاح');
    }
    /**
     * عرض صفحة تعديل المنتج المخزني.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // جلب المنتج المخزني الحالي مع العلاقات المطلوبة
        $inventoryProduct = InventoryProduct::with(['product', 'storageArea', 'storageArea.locations'])->findOrFail($id);

        // جلب قائمة المنتجات
        $products = Product::all();

        // جلب بيانات الفروع والمستودعات والمناطق إذا كانت مطلوبة
        $branches = Branch::all();
        $warehouses = Warehouse::all();
        $storageAreas = WarehouseStorageArea::all();
        // أما المواقع فقد تحتاج إلى تعديلها حسب منطقك
        $locations = WarehouseLocation::all()->mapWithKeys(function ($location) {
            return [$location->id => $location->full_location];
        });

        // تمرير البيانات إلى view صفحة التعديل
        return view('inventory-products.edit', compact('inventoryProduct', 'products', 'branches', 'warehouses', 'storageAreas', 'locations'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id'       => 'required|exists:products,id',
            'branch_id'        => 'nullable|exists:branches,id',
            'warehouse_id'     => 'required|exists:warehouses,id',
            'storage_area_id'  => 'required|exists:warehouse_storage_areas,id',
            'location_id'      => 'nullable|string|max:255',
            'quantity'         => 'required|numeric|min:1', // التأكد من إدخال الكمية
            'inventory_transaction_item_id' => 'nullable|exists:inventory_transaction_items,id', // تحقق من الحقل الجديد

        ]);
        $inventoryProduct = InventoryProduct::findOrFail($id);
        $transactionItem = InventoryTransactionItem::findOrFail($request->inventory_transaction_item_id);
        $originalQuantity = $transactionItem->quantity;
    
        // حساب الكمية الموزعة بدون الكمية الحالية (لأننا نحدث السجل الحالي)
        $distributedQuantity = InventoryProduct::where('inventory_transaction_item_id', $request->inventory_transaction_item_id)
            ->where('product_id', $request->product_id)
            ->where('warehouse_id', $request->warehouse_id)
            ->where('id', '!=', $id) // استثناء السجل الحالي
            ->sum('quantity');
    
        // التحقق من أن الكمية الجديدة لا تتجاوز الكمية الأصلية
        if (($distributedQuantity + $request->quantity) > $originalQuantity) {
            return redirect()->back()->withErrors(['quantity' => 'إجمالي الكميات الموزعة يتجاوز الكمية الأصلية المتاحة في الحركة المخزنية.']);
        }

        $inventoryProduct->update([
            'product_id'      => $request->input('product_id'),
            'branch_id'       => $request->input('branch_id'),
            'warehouse_id'    => $request->input('warehouse_id'),
            'storage_area_id' => $request->input('storage_area_id'),
            'location_id'     => $request->input('location_id'),
            'quantity'        => $request->input('quantity'), // تحديث الكمية
            'inventory_transaction_item_id' => $request->input('inventory_transaction_item_id'), // إضافة الحقل الجديد

        ]);

        return redirect()->route('inventory-products.index')->with('success', 'تم تعديل موقع المنتج بنجاح.');
    }

    //  دالة جلب المستودعات بناءً على الفرع المحدد


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
    public function getWarehouses($branch_id)
    {
        $warehouses = Warehouse::forUserBranch()->get();
// dump($warehouses);
        // $warehouses = Warehouse::where('branch_id', $branch_id)->pluck('name', 'id');
        return response()->json($warehouses);
    }

    public function getInventoryTransactions($warehouse_id)
    {
        $transactions = InventoryTransactionItem::join('inventory_transactions as t', 'inventory_transaction_items.inventory_transaction_id', '=', 't.id')
            ->where('inventory_transaction_items.target_warehouse_id', $warehouse_id)
            ->pluck('t.reference', 'inventory_transaction_items.id');

        return response()->json($transactions);
    }


    public function getProducts($id)
    {
        
        // جلب جميع المنتجات المرتبطة بهذه الحركة المخزنية
        $products = InventoryTransactionItem::where('id', $id)
            ->with('product')
            ->get()
            ->pluck('product');

        if ($products->isEmpty()) {
            return response()->json([]);
        }

        // إرجاع المنتجات بصيغة [id => name]
        return response()->json($products->pluck('name', 'id'));
    }




    public function destroy($id)
    {
        $inventoryProduct = InventoryProduct::findOrFail($id);
        $inventoryProduct->delete();

        return redirect()->route('inventory-products.index')
            ->with('success', 'تم حذف المنتج المخزني بنجاح.');
    }
}

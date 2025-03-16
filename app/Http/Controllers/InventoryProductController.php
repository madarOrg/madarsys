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
use Carbon\Carbon;
use League\CommonMark\Extension\CommonMark\Renderer\Block\ThematicBreakRenderer;

class InventoryProductController extends Controller
{

    public function index(Request $request)
    {

        // جلب جميع المستودعات لعرضها في القائمة المنسدلة
        $warehouses = Warehouse::all();


        if ($request->has('warehouse_id')) {
            $products = InventoryProduct::query()
                ->select([
                    'inventory_products.id',
                    'inventory_products.warehouse_id',
                    'inventory_products.product_id',
                    'inventory_products.storage_area_id',
                    'inventory_products.location_id',
                    'inventory_products.production_date',
                    'inventory_products.expiration_date',
                    'inventory_products.batch_number',
                    'inventory_products.quantity as productQuantity',
                    'products.name as product_name',
                    'warehouse_storage_areas.area_name',
                    'warehouse_locations.rack_code',
                    'inventory_transaction_items.quantity',
                    'inventory_products.inventory_transaction_item_id'
                ])
                ->leftJoin('products', 'inventory_products.product_id', '=', 'products.id')
                ->leftJoin('warehouse_storage_areas', 'inventory_products.storage_area_id', '=', 'warehouse_storage_areas.id')
                ->leftJoin('warehouse_locations', 'inventory_products.location_id', '=', 'warehouse_locations.id')
                ->leftJoin('inventory_transaction_items', 'inventory_products.inventory_transaction_item_id', '=', 'inventory_transaction_items.id')
                ->where('inventory_products.warehouse_id', $request->warehouse_id)
                ->get();
        } else {
            // في حال عدم وجود warehouse_id يتم إرجاع مجموعة فارغة
            $products = collect([]);
        }
        $distributedQuantities = [];
        foreach ($products as $product) {
            $distributedQuantities[$product->id] = $this->getDistributedQuantity($product->inventory_transaction_item_id, $product->warehouse_id);
        }
        // dd($distributedQuantities);
        return view('inventory-products.index', compact('warehouses', 'products', 'distributedQuantities'));
    }


    /**
     * عرض صفحة إضافة حركة مخزنية جديدة.
     *
     * @return \Illuminate\View\View
     */

    public function create(Request $request)
    {

        $transactions = InventoryTransaction::with('items')->get();
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

        // استخدام الـ Scope لجلب المستودعات الخاصة بفرع المستخدم الحالي
        $warehouses = Warehouse::forUserBranch()->get();

        // dump($warehouses);
        $storageAreas = WarehouseStorageArea::when($request->warehouse_id, function ($query) use ($request) {
            return $query->where('warehouse_id', $request->warehouse_id);
        })->get();

        $locations = WarehouseLocation::when($request->storage_area_id, function ($query) use ($request) {
            return $query->where('storage_area_id', $request->storage_area_id);
        })->get()->mapWithKeys(function ($location) {
            return [$location->id => $location->rack_code];
        });

        return view('inventory-products.create', compact('transactions', 'products', 'branches', 'warehouses', 'storageAreas', 'locations'));
    }

    public function new(Request $request)
    {

        // جلب المنتج باستخدام inventory_transaction_item_id
        $transactionItem = InventoryTransactionItem::with([
            'inventoryTransaction',
            'inventoryProducts.warehouse:id,name',
            'inventoryProducts.product:id,name'
        ])->findOrFail($request->inventory_transaction_item_id);

        $product = $transactionItem->inventoryProducts->first();
        $storageAreas = WarehouseStorageArea::when($request->warehouse_id, function ($query) use ($request) {
            return $query->where('warehouse_id', $request->warehouse_id);
        })->get();
        // dump($storageAreas);

        $locations = WarehouseLocation::when($request->storage_area_id, function ($query) use ($request) {
            return $query->where('storage_area_id', $request->storage_area_id);
        })->get()->mapWithKeys(function ($location) {
            return [$location->id => $location->rack_code];
        });
        // dump($locations);

        return view('inventory-products.new', compact('transactionItem', 'product', 'storageAreas', 'locations'));
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
            'branch_id' => 'nullable|exists:branches,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'storage_area_id' => 'required|exists:warehouse_storage_areas,id',
            'location_id' => 'nullable|string|max:255',
            'created_user' => 'nullable|exists:users,id',
            'updated_user' => 'nullable|exists:users,id',
            'quantity' => 'required|numeric|min:1', // التأكد من إدخال الكمية
            'production_date' => 'nullable|date',
            'expiration_date' => 'nullable|date',
            'batch_number' => 'nullable|string',
            'inventory_transaction_item_id' => 'nullable|exists:inventory_transaction_items,id', // تحقق من الحقل الجديد
        ]);

        // جلب الكمية الأصلية من جدول inventory_transaction_items
        $transactionItem = InventoryTransactionItem::findOrFail($request->inventory_transaction_item_id);
        $originalQuantity = $transactionItem->quantity;
        //  dump($originalQuantity );
        // حساب الكمية التي تم توزيعها لهذا المنتج في نفس المستودع ونفس الحركة المخزنية
        $distributedQuantity = InventoryProduct::where('inventory_transaction_item_id', $request->inventory_transaction_item_id)
            // ->where('product_id', $transactionItem->product_id) // استخدم المنتج من الحركة المخزنية
            ->where('warehouse_id', $request->warehouse_id)
            ->sum('quantity');
        //  dump($distributedQuantity);
        // التحقق من أن الكمية الجديدة لا تتجاوز الكمية الأصلية
        if (($distributedQuantity + $request->quantity) > $originalQuantity) {
            return redirect()->back()->withErrors(['quantity' => 'إجمالي الكميات الموزعة يتجاوز الكمية الأصلية المتاحة في الحركة المخزنية.']);
        }

        // إنشاء حركة مخزنية جديدة باستخدام المنتج المستخرج من العملية المخزنية
        InventoryProduct::create([
            'product_id' => $transactionItem->product_id, // استخراج المنتج من الحركة المخزنية
            'branch_id' => $request->input('branch_id'),
            'warehouse_id' => $request->input('warehouse_id'),
            'storage_area_id' => $request->input('storage_area_id'),
            'location_id' => $request->input('location_id'),
            'created_user' => Auth::id(),
            'updated_user' => Auth::id(),
            'quantity' => $request->input('quantity'),
            'production_date' => $request->input('production_date'),
            'expiration_date' => $request->input('expiration_date'),
            'batch_number' => $request->input('batch_number'),
            'inventory_transaction_item_id' => $request->input('inventory_transaction_item_id'),
        ]);

        // إعادة التوجيه بعد حفظ البيانات
        return redirect()->route('inventory-products.create')->with('success', 'تم إضافة موقع المخزون بنجاح');
    }
    public function edit(Request $request,$id)
    {
        $oldProduct = InventoryProduct::with([
            'transactionItem.inventoryTransaction',
            'warehouse:id,name',

        ])->findOrFail($id);

        $storageAreas = WarehouseStorageArea::when($oldProduct->warehouse_id, function ($query) use ($oldProduct) {
            return $query->where('warehouse_id', $oldProduct->warehouse_id);
        })->get();
        // dump($storageAreas);

        $locations = WarehouseLocation::when($request->storage_area_id, function ($query) use ($request) {
            return $query->where('storage_area_id', $request->storage_area_id);
        })->get();
        
        // dd($locations->first()->rack_code);
        
        $product = $oldProduct;
        return view('inventory-products.edit', compact('product', 'storageAreas', 'locations'));
    }



    /**
     * عرض صفحة تعديل المنتج المخزني.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function update(Request $request, $id)
    {
        // dd($id, $request->all());

        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'branch_id' => 'nullable|exists:branches,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'storage_area_id' => 'required|exists:warehouse_storage_areas,id',
            'location_id' => 'nullable|string|max:255',
            'quantity' => 'required|numeric|min:1', // التأكد من إدخال الكمية
            'inventory_transaction_item_id' => 'nullable|exists:inventory_transaction_items,id', // تحقق من الحقل الجديد
            'production_date' => 'nullable|date', // التحقق من صحة تاريخ الإنتاج (إن كان موجودًا)
            'expiration_date' => 'nullable|date|after_or_equal:production_date', // التحقق من صحة تاريخ انتهاء الصلاحية (إن كان موجودًا)
        ]);

        $inventoryProduct = InventoryProduct::findOrFail($id);
        // dd($inventoryProduct)
        // إذا كان هناك قيمة في inventory_transaction_item_id، قم بالتحقق من الكمية
        if ($request->has('inventory_transaction_item_id') && $request->inventory_transaction_item_id) {
            $transactionItem = InventoryTransactionItem::findOrFail($request->inventory_transaction_item_id);
            $originalQuantity = $transactionItem->quantity;

            // حساب الكمية الموزعة بدون الكمية الحالية (لأننا نحدث السجل الحالي)
            $distributedQuantity = InventoryProduct::where('inventory_transaction_item_id', $request->inventory_transaction_item_id)
                // ->where('product_id', $request->product_id)
                ->where('warehouse_id', $request->warehouse_id)
                ->where('id', '!=', $id) // استثناء السجل الحالي
                ->sum('quantity');

            // التحقق من أن الكمية الجديدة لا تتجاوز الكمية الأصلية
            if (($distributedQuantity + $request->quantity) > $originalQuantity) {
                return redirect()->back()->withErrors(['quantity' => 'إجمالي الكميات الموزعة يتجاوز الكمية الأصلية المتاحة في الحركة المخزنية.']);
            }
        }

        // تحديث السجل
        $inventoryProduct->update([
            'product_id' => $request->input('product_id'),
            'branch_id' => $request->input('branch_id'),
            'warehouse_id' => $request->input('warehouse_id'),
            'storage_area_id' => $request->input('storage_area_id'),
            'location_id' => $request->input('location_id'),
            'quantity' => $request->input('quantity'),
            'inventory_transaction_item_id' => $request->input('inventory_transaction_item_id'), // إضافة الحقل الجديد
            'production_date' => $request->input('production_date'), // إضافة تاريخ الإنتاج
            'expiration_date' => $request->input('expiration_date'), // إضافة تاريخ انتهاء الصلاحية
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
            ->join('products as p', 'inventory_transaction_items.product_id', '=', 'p.id') // ربط جدول المنتجات
            ->where('inventory_transaction_items.target_warehouse_id', $warehouse_id)
            ->select('inventory_transaction_items.id', 't.reference', 'p.name as product_name') // اختيار الأعمدة المطلوبة
            ->get();

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

    public function getDistributedQuantity($transactionItemId, $warehouseId)
    {
        return InventoryProduct::where('inventory_transaction_item_id', $transactionItemId)
            ->where('warehouse_id', $warehouseId)
            ->sum('quantity');
    }

    public function destroy($id)
    {
        $inventoryProduct = InventoryProduct::findOrFail($id);
        $inventoryProduct->delete();

        return redirect()->route('inventory-products.index')
            ->with('success', 'تم حذف المنتج المخزني بنجاح.');
    }
}

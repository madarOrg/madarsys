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
use App\Rules\ValidInventoryTransaction;
use App\Services\InventoryTransaction\InventoryService;
use App\Services\InventoryTransaction\InventoryCalculationService;
use Illuminate\Support\Facades\DB;

class InventoryProductController extends Controller
{
    protected $inventoryService;
    protected $inventoryCalculationService;


    // حقن خدمة InventoryService في الـ Controller عبر الـ constructor
    public function __construct(InventoryService $inventoryService, InventoryCalculationService $inventoryCalculationService)
    {
        $this->inventoryService = $inventoryService;
        $this->inventoryCalculationService = $inventoryCalculationService;
    }



    // دالة البحث: مسؤولة عن تنفيذ الاستعلام وجلب البيانات
    public function search(Request $request)
    {
        $warehouses = Warehouse::ForUserWarehouse()->get();

        $query = InventoryProduct::query()
            ->select([
                'inventory_products.id',
                'inventory_products.distribution_type',
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
            ->leftJoin('inventory_transaction_items', 'inventory_products.inventory_transaction_item_id', '=', 'inventory_transaction_items.id');


        // البحث حسب المستودع

        // dd($query);
        // البحث حسب النوع (إدخال أو إخراج)
        if ($request->filled('distribution_type')) {
            $query->where('inventory_products.distribution_type', $request->distribution_type);
        }

        if ($request->filled('warehouse_id')) {
            $query->where('inventory_products.warehouse_id', $request->warehouse_id);
        }

        // البحث حسب تاريخ الإنتاج
        if ($request->filled('production_date_from') || $request->filled('production_date_to')) {
            $from = $request->filled('production_date_from')
                ? \Carbon\Carbon::parse($request->production_date_from)->startOfDay()
                : \Carbon\Carbon::minValue();

            $to = $request->filled('production_date_to')
                ? \Carbon\Carbon::parse($request->production_date_to)->endOfDay()
                : \Carbon\Carbon::maxValue();

            $query->whereBetween('inventory_products.production_date', [$from, $to]);
        }

        // البحث حسب تاريخ الانتهاء
        if ($request->filled('expiration_date_from') || $request->filled('expiration_date_to')) {
            $from = $request->filled('expiration_date_from')
                ? \Carbon\Carbon::parse($request->expiration_date_from)->startOfDay()
                : \Carbon\Carbon::minValue();

            $to = $request->filled('expiration_date_to')
                ? \Carbon\Carbon::parse($request->expiration_date_to)->endOfDay()
                : \Carbon\Carbon::maxValue();

            $query->whereBetween('inventory_products.expiration_date', [$from, $to]);
        }

        // البحث حسب المنطقة
        if ($request->filled('storage_area_id')) {
            $query->where('inventory_products.storage_area_id', $request->storage_area_id);
        }

        // البحث حسب الرف
        if ($request->filled('location_id')) {
            $query->where('inventory_products.location_id', $request->location_id);
        }

        // البحث حسب تاريخ الإدخال
        if ($request->filled('created_at_from') || $request->filled('created_at_to')) {
            $from = $request->filled('created_at_from')
                ? \Carbon\Carbon::parse($request->created_at_from)->startOfDay()
                : \Carbon\Carbon::minValue();

            $to = $request->filled('created_at_to')
                ? \Carbon\Carbon::parse($request->created_at_to)->endOfDay()
                : \Carbon\Carbon::maxValue();

            $query->whereBetween('inventory_products.created_at', [$from, $to]);
        }

        // البحث حسب رقم الحركة
        if ($request->filled('inventory_transaction_item_id')) {
            $query->where('inventory_products.inventory_transaction_item_id', $request->inventory_transaction_item_id);
        }

        // البحث حسب اسم المنتج
        // البحث حسب المنتج (الاسم، الباركود، الكود، المعرف)
        if ($request->filled('product_name')) {
            $query->where(function ($q) use ($request) {
                $searchTerm = '%' . $request->product_name . '%';
                $q->where('products.name', 'like', $searchTerm)
                    ->orWhere('products.barcode', 'like', $searchTerm)
                    ->orWhere('products.sku', 'like', $searchTerm)
                    ->orWhere('products.id', $request->product_name);
            });
        }


        // البحث حسب رقم الدفعة
        if ($request->filled('batch_number')) {
            $query->where('inventory_products.batch_number', 'like', '%' . $request->batch_number . '%');
        }


        // تنفيذ الاستعلام مع الباجيناشن
        $products = $query->paginate(10)->withQueryString();
        $distributedQuantities = [];
        foreach ($products as $product) {
            $distributedQuantities[$product->id] = $this->getDistributedQuantity($product->inventory_transaction_item_id, $product->warehouse_id);
        };
        // جلب المستودعات والمواقع فقط لعرضها في الفلاتر
        $storageAreas = WarehouseStorageArea::when($request->warehouse_id, function ($query) use ($request) {
            return $query->where('warehouse_id', $request->warehouse_id);
        })->get();

        $locations = WarehouseLocation::when($request->storage_area_id, function ($query) use ($request) {
            return $query->where('storage_area_id', $request->storage_area_id);
        })->get()->mapWithKeys(function ($location) {
            return [$location->id => $location->rack_code];
        });


        $transactions = InventoryTransaction::with('items.product')
            ->where('effect', -1)
            ->where('status', '!=', 0)
            ->get();
        // dd($warehouses);
        // $transactions = InventoryTransaction::with(['items.product'])->get();

        // foreach ($transactions as $transaction) {
        //     foreach ($transaction->items as $item) {
        //         $item->available_batches = InventoryProduct::with(['withdrawals' => function ($query) {
        //                 $query->where('distribution_type', -1);
        //             }])
        //             ->where('product_id', $item->product_id)
        //             ->where('warehouse_id', $transaction->warehouse_id)
        //             ->where('distribution_type', 1)
        //             ->get()
        //             ->filter(function ($batch) {
        //                 $withdrawnQty = abs($batch->withdrawals->sum('quantity'));
        //                 return ($batch->quantity - $withdrawnQty) > 0;
        //             });
        //     }
        // }

        // عرض النتائج مع الفلاتر
        return view('inventory-products.index', compact('warehouses', 'products', 'storageAreas', 'locations', 'distributedQuantities', 'transactions'));
    }

    public function index(Request $request)
    {
        // جلب جميع المستودعات
        // $warehouses = Warehouse::toSql();  // للحصول على الاستعلام الفعلي
        // dd($warehouses); 
        $warehouses = Warehouse::ForUserWarehouse()->get();

        // بناء الاستعلام لجلب المنتجات مع العلاقات اللازمة
        $products = InventoryProduct::query()
            ->select([
                'inventory_products.id',
                'inventory_products.distribution_type',
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
                'inventory_products.inventory_transaction_item_id',
                'warehouses.name as warehouse_name'
            ])
            ->leftJoin('products', 'inventory_products.product_id', '=', 'products.id')
            ->leftJoin('warehouse_storage_areas', 'inventory_products.storage_area_id', '=', 'warehouse_storage_areas.id')
            ->leftJoin('warehouse_locations', 'inventory_products.location_id', '=', 'warehouse_locations.id')
            ->leftJoin('inventory_transaction_items', 'inventory_products.inventory_transaction_item_id', '=', 'inventory_transaction_items.id')
            ->leftJoin('warehouses', 'inventory_products.warehouse_id', '=', 'warehouses.id')  // إضافة الانضمام للمستودعات

            ->paginate(10);  // جلب البيانات


        // متغير لتجميع الكميات الموزعة
        $distributedQuantities = collect();

        // جلب مناطق التخزين بناءً على المستودع المحدد (إذا تم تحديده)
        $storageAreas = WarehouseStorageArea::when($request->warehouse_id, function ($query) use ($request) {
            return $query->where('warehouse_id', $request->warehouse_id);
        })->get();

        // جلب المواقع بناءً على منطقة التخزين المحددة (إذا تم تحديدها)
        $locations = WarehouseLocation::when($request->storage_area_id, function ($query) use ($request) {
            return $query->where('storage_area_id', $request->storage_area_id);
        })->get()->mapWithKeys(function ($location) {
            return [$location->id => $location->rack_code];
        });

        // $transactions = InventoryTransaction::with('items')
        // ->where('effect', -1)
        // ->where('status', 1)
        // ->get();
        $transactions = InventoryTransaction::with('items.product')
            ->where('effect', -1)
            ->where('status', '!=', 0)
            ->get();
        // dd($warehouses);


        // إرجاع البيانات إلى العرض
        return view('inventory-products.index', compact('warehouses', 'products', 'storageAreas', 'locations', 'distributedQuantities', 'transactions'));
    }
    /**
     * عرض صفحة إضافة حركة مخزنية جديدة.
     *
     * @return \Illuminate\View\View
     */
    public function getProduct($transactionId)
    {
        $transaction = InventoryTransactionItem::with('product')->find($transactionId);

        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        return response()->json([
            'product_id' => $transaction->product->id,
            'product_name' => $transaction->product->name,
            'production_date' => $transaction->production_date,
            'expiration_date' => $transaction->expiration_date,
            'quantity' => $transaction->quantity,
        ]);
    }

    // public function getProductInventory($transactionId)
    // {
    //     // جلب تفاصيل المنتج مع الكمية من جدول InventoryTransactionItem
    //     $transaction = InventoryTransactionItem::with('product.unit')->find($transactionId);

    //     if (!$transaction) {
    //         return response()->json(['error' => 'Transaction not found'], 404);
    //     }

    //     // البحث عن الكمية المتبقية في المستودع
    //     $remainingQuantity = DB::table('inventory_products')
    //         ->where('product_id', $transaction->product_id)
    //         ->where('warehouse_id', $transaction->warehouse_id)
    //         ->select(DB::raw('SUM(converted_quantity * distribution_type) as total_quantity'))
    //         ->value('total_quantity');

    //     // إذا لم يتم العثور على قيمة، يتم استخدام الكمية الأصلية
    //     $quantity = $remainingQuantity !== null ? $remainingQuantity : $transaction->quantity;

    //         return response()->json([
    //             'product_id' => $transaction->product_id,
    //             'product_name' => $transaction->product->name,
    //             'quantity' => $quantity,
    //             'production_date' => $transaction->production_date,
    //             'expiration_date' => $transaction->expiration_date,
    //             'unit_name' => $transaction->product->unit?->name, // هنا اسم الوحدة
    //         ]);
    //     }
    public function getProductInventory($transactionId)
    {
        // جلب تفاصيل المنتج مع الكمية من جدول InventoryTransactionItem
        $transaction = InventoryTransactionItem::with('product.unit', 'unit')->find($transactionId);

        if (!$transaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        // البحث عن الكمية المتبقية في المستودع
        $remainingQuantity = DB::table('inventory_products')
            ->where('product_id', $transaction->product_id)
            ->where('warehouse_id', $transaction->warehouse_id)
            ->select(DB::raw('SUM(converted_quantity * distribution_type) as total_quantity'))
            ->value('total_quantity');

        // إذا لم يتم العثور على قيمة، يتم استخدام الكمية الأصلية
        $quantity = $remainingQuantity !== null ? $remainingQuantity : $transaction->quantity;

        return response()->json([
            'product_id' => $transaction->product_id,
            'product_name' => $transaction->product->name,
            'quantity' => $quantity,
            'production_date' => $transaction->production_date,
            'expiration_date' => $transaction->expiration_date,
            'unit_name' => $transaction->product->unit?->name, // اسم وحدة المنتج
            'transaction_unit_name' => $transaction->unit ? $transaction->unit->name : '---', // اسم وحدة الحركة
        ]);
    }

    public function create(Request $request)
    {

        $transactions = collect();

        $products = InventoryTransaction::with('items.product.unit')
            ->get()
            ->flatMap(function ($transaction) {
                return $transaction->items->map(function ($item) {
                    return $item->product; // تأكد أنه يعود كنموذج Eloquent مع العلاقات
                });
            })
            ->unique('id')
            ->values(); // إعادة ترتيب الفهارس

        $transactions = InventoryTransactionItem::with('product.unit', 'inventoryTransaction')
            ->whereHas('inventoryTransaction', function ($query) use ($request) {
                if ($request->warehouse_id) {
                    $query->where('warehouse_id', $request->warehouse_id);
                }
            })
            ->get();


        // $products = $transactions->unique('product_id')->values(); // تجنب التكرار واسترجاع القيم


        $distributionType = $request->input('distribution_type', 1); // Default to '1' (توزيع)

        $branches = Branch::all();
        $userBranch = Auth::user()->branch_id; // فرع المستخدم الحالي

        // استخدام الـ Scope لجلب المستودعات الخاصة بفرع المستخدم الحالي
        // $warehouses = Warehouse::all();
        // $warehouses = Warehouse::forUserBranch()->get();
        $warehouses = Warehouse::ForUserWarehouse()->get();

        //  dd($warehouses);
        // $storageAreas = WarehouseStorageArea::when($request->warehouse_id, function ($query) use ($request) {
        //     return $query->where('warehouse_id', $request->warehouse_id);
        // })->get();

        // $locations = WarehouseLocation::when($request->storage_area_id, function ($query) use ($request) {
        //     return $query->where('storage_area_id', $request->storage_area_id);
        // })->get()->mapWithKeys(function ($location) {
        //     return [$location->id => $location->rack_code];
        // });

        $storageAreas = WarehouseStorageArea::when($request->warehouse_id, function ($q) use ($request) {
            $q->where('warehouse_id', $request->warehouse_id);
        })->get();

        // الآن ننشئ مجموعة المواقع بناءً على storage_area_id
        $locations = WarehouseLocation::when($request->storage_area_id, function ($q) use ($request) {
            $q->where('storage_area_id', $request->storage_area_id);
        })->get(['id', 'rack_code']);

        return view('inventory-products.create', compact('transactions', 'products', 'branches', 'warehouses', 'storageAreas', 'locations', 'distributionType'));
    }
    public function createOut(Request $request)
    {
        // $transactions = InventoryTransaction::with('items')->get();
        $transactions = InventoryTransactionItem::join('inventory_transactions as t', 'inventory_transaction_items.inventory_transaction_id', '=', 't.id')
            ->join('products as p', 'inventory_transaction_items.product_id', '=', 'p.id')
            ->where('inventory_transaction_items.target_warehouse_id',  $request->warehouse_id)
            ->where('inventory_transaction_items.quantity', '<', 0)  // فقط الحركات الخارجة
            ->where('t.status', 1)
            ->select('inventory_transaction_items.id', 't.reference', 'p.name as product_name', 'p.id as product_id', 'p.sku')
            ->get();

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

        return view('inventory-products.createOut', compact('transactions', 'transactionItem', 'product', 'storageAreas', 'locations'));
    }

   /**
 * عرض صفحة إضافة منتج للحركة المخزنية المحددة
 */
public function new(Request $request)
{
    // 1) نوع التوزيع (1: دخول، -1: خروج)
    $distributionType = $request->input('distribution_type', 1);

    // 2) جلب العنصر من حركة المخزون مع علاقاته
    $transactionItem = InventoryTransactionItem::with([
        'inventoryTransaction',
        'inventoryProducts.warehouse:id,name',
        'inventoryProducts.product:id,name'
    ])->findOrFail($request->inventory_transaction_item_id);

    // 3) المنتج الأول المرتبط بهذه الحركة (قد يكون لديك أكثر من منتج)
    $product = $transactionItem->inventoryProducts->first();

    // 4) فروع المستخدم (إذا تحتاجها في الواجهة)
    $branches   = Branch::all();

    // 5) المستودعات المتاحة للمستخدم الحالي (عن طريق scope ForUserWarehouse)
    $warehouses = Warehouse::ForUserWarehouse()->get();

    // 6) المناطق التخزينية بناءً على warehouse_id إن وُجد في الطلب
    $storageAreas = WarehouseStorageArea::when($request->warehouse_id, function ($q) use ($request) {
        $q->where('warehouse_id', $request->warehouse_id);
    })->get();

    // 7) المواقع بناءً على storage_area_id إن وُجد في الطلب
    $locations = WarehouseLocation::when($request->storage_area_id, function ($q) use ($request) {
        $q->where('storage_area_id', $request->storage_area_id);
    })->get(['id','rack_code']);

    // 8) عرض الـ view مع كل المتغيرات
    return view('inventory-products.new', compact(
        'transactionItem',
        'product',
        'branches',
        'warehouses',
        'storageAreas',
        'locations',
        'distributionType'
    ));
}


    /**
     * تخزين حركة مخزنية جديدة.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        $itemSourceId = null;
        $distributionType = $request->input('distribution_type'); // Default to '1' (توزيع)
        //  dd($distributionType);
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
            'expiration_date' => 'nullable|date|after_or_equal:production_date', // التحقق من صحة تاريخ انتهاء الصلاحية (إن كان موجودًا)
            'batch_number' => 'nullable|string',
            'inventory_transaction_item_id' => [
                'required',
                'exists:inventory_transaction_items,id',
                new ValidInventoryTransaction($distributionType), // تمرير نوع العملية
            ],
            'distribution_type' => 'required|in:1,-1', // التأكد من القيم المقبولة
        ]);

        // جلب الكمية الأصلية من جدول inventory_transaction_items
        $transactionItem = InventoryTransactionItem::findOrFail($request->inventory_transaction_item_id);

        // // تحقق من تطابق نوع العملية مع التأثير
        // if ((int)$request->distribution_type !== (int)$transactionItem->effect) {
        //     return redirect()->back()->withErrors(['distribution_type' => 'نوع التوزيع لا يتطابق مع تأثير الحركة المخزنية المحددة.']);
        // }

        $originalQuantity = $transactionItem->converted_quantity;

        /////mange distribution batch Quantity


        $batchQuantity = $request->quantity;

        $unitId = $transactionItem->unit_id;

        $baseUnitId = $transactionItem->unit_product_id;

        $batchConvertedQuantity = $this->inventoryCalculationService->calculateConvertedQuantity($batchQuantity, $unitId, $baseUnitId);

        $batchPrice = ($batchQuantity * $request->total) / $originalQuantity;
        // dd($batchConvertedQuantity);
        //   dd($request->inventory_transaction_item_id );
        // حساب الكمية التي تم توزيعها لهذا المنتج في نفس المستودع ونفس الحركة المخزنية
        $distributedQuantity = InventoryProduct::where('inventory_transaction_item_id', $request->inventory_transaction_item_id)
            // ->where('product_id', $transactionItem->product_id) // استخدم المنتج من الحركة المخزنية
            ->where('warehouse_id', $request->warehouse_id)
            ->where('distribution_type', 1)
            ->sum('converted_quantity');

        $distributedOutQuantity = InventoryProduct::where('inventory_transaction_item_id', $request->inventory_transaction_item_id)
            // ->where('product_id', $transactionItem->product_id) // استخدم المنتج من الحركة المخزنية
            ->where('warehouse_id', $request->warehouse_id)
            ->where('distribution_type', -1)
            ->sum('converted_quantity');
        //   dump($distributedQuantity,$distributedOutQuantity);
        // / اجمع كل الكميات المدخلة من نفس المنتج ونفس رقم الدفعة في نفس المستودع
        $totalOutQuantity = InventoryProduct::where('product_id', $request->product_id)
            ->where('batch_number', $request->batch_number)
            ->where('warehouse_id', $request->warehouse_id)
            ->where('distribution_type', -1)
            ->sum('converted_quantity');
        $totalInQuantity = InventoryProduct::where('product_id', $request->product_id)
            ->where('batch_number', $request->batch_number)
            ->where('warehouse_id', $request->warehouse_id)
            ->where('distribution_type', 1)
            ->sum('converted_quantity');



        // 1) اجلب كل معرفات المصادر التي سُحب منها من قبل (distribution_type = -1)
        $productId = $request->product_id;

        $sourceIds = InventoryProduct::query()
            ->where('product_id', $productId)
            ->where('distribution_type', -1)
            ->pluck('item_source_id')
            ->filter()
            ->unique()
            ->toArray();

        // 2) حدد هذه الحركات الأصلية التي لم يكتمل سحبها
        $availableSources = InventoryTransactionItem::query()
            ->where('status', 1)
            ->whereIn('id', $sourceIds)
            ->withSum(['inventoryProducts as withdrawn_sum' => function ($q) {
                $q->where('distribution_type', -1);
            }], 'converted_quantity')
            ->havingRaw('IFNULL(withdrawn_sum,0) < converted_quantity')
            ->get();
        // dd($availableSources);
        // 3) هل الحركة نفسها (المختارة في الطلب) حركة سحب جديدة لم يُسحب منها شيء؟

        // dd($isFreshOwn);
        $withdrawal = InventoryTransactionItem::query()
            ->where('quantity', '<', 0)
            ->where('status', 1)
            ->withSum(['inventoryProducts as withdrawn_sum' => function ($q) {
                $q->where('distribution_type', -1);
            }], 'converted_quantity')
            // هنا نستخدم ABS(converted_quantity) لأن quantity سالبة في جدول المعاملة
            ->havingRaw('IFNULL(withdrawn_sum, 0) < ABS(converted_quantity)')
            ->orderBy('created_at')    // يمكنك تعديل الترتيب حسب أولويّتك
            ->first();

        // 2) إما لا توجد حركة سحب متاحة:
        if ($request->distribution_type == -1 && !$withdrawal) {
            return redirect()->back()->withErrors([
                'withdrawal_check' => 'لا توجد حركة سحب متاحة أو تم سحب كامل كميتها.'
            ]);
        }


        // dd($withdrawal);
        // 3) عندها يمكنك معرفة الكمية المتبقية للسحب منها:
        $convertedQuantity = $withdrawal->converted_quantity ?? 0;
        $withdrawnSum = $withdrawal->withdrawn_sum ?? 0;
        $remaining = abs($convertedQuantity) - $withdrawnSum;
        // dd($remaining);
        // مثال: في حال رغبت بربط السحب القادم بهذا المصدر:

        // dd($itemSourceId);
        // 4) احسب مجموع الكمية المتبقية من المصادر القديمة
        $totalRemaining = $availableSources->sum(function ($src) {
            return $src->converted_quantity - ($src->withdrawn_sum ?? 0);
        });
        // dd($batchConvertedQuantity);

        // 6) إذا طلب السحب أكبر من المتبقي، امنع العملية
        if ($request->distribution_type == -1 && $batchConvertedQuantity > ($totalRemaining + $remaining)) {
            return redirect()->back()->withErrors([
                'quantity' => 'الكمية المطلوبة للسحب تتجاوز الكمية المتاحة من حركات السحب المصرح بها.'
            ]);
        }
        if ($distributionType == -1 && ($withdrawal->withdrawn_sum >= abs($withdrawal->converted_quantity))) {
            return redirect()->back()->withErrors([
                'withdrawal_check' => 'لا يمكن السحب من هذه الحركة لأنها مكتملة بالفعل.'
            ]);
        }

        // إذا اجتاز هذا الفحص، تابع حفظ حركة السحب...

        // التحقق من أن الكمية الجديدة لا تتجاوز الكمية الأصلية في حالة التوزيع
        if ($request->distribution_type == 1) {
            $quantityInvertory = $batchConvertedQuantity;
            $itemSourceId =  null;

            // dd($distributedQuantity , $batchConvertedQuantity , $originalQuantity);
            // في حالة التوزيع (إدخال)
            if (($distributedQuantity + $batchConvertedQuantity) > $originalQuantity) {
                return redirect()->back()->withErrors(['quantity' => 'إجمالي الكميات الموزعة يتجاوز الكمية الأصلية المتاحة في الحركة المخزنية.']);
            }
        } elseif ($request->distribution_type == -1) {
            $quantityInvertory = -$batchConvertedQuantity;
            $itemSourceId = $withdrawal->id ?? null;

            // dd($distributedQuantity ,$request->quantity ,($distributedQuantity + $quantityInvertory), $originalQuantity,$quantityInvertory);
            // في حالة الإخراج (تخفيض الكمية
            if (($distributedQuantity + $quantityInvertory) > $distributedQuantity) {
                // dd(($distributedQuantity + $quantityInvertory), $originalQuantity);

                return redirect()->back()->withErrors(['quantity' => 'إجمالي الكميات المسحوبة أكثر من الكمية المطلوبة للإخراج.']);
            }
        }
        // dd('k',$request->distribution_type); 
        // // إنشاء حركة مخزنية جديدة باستخدام المنتج المستخرج من العملية المخزنية
        // InventoryProduct::create([
        //     'product_id' => $transactionItem->product_id, // استخراج المنتج من الحركة المخزنية
        //     'branch_id' => $request->input('branch_id'),
        //     'item_source_id'       => $distributionType == -1
        //     ? $availableSources
        //     : null,            
        //     'warehouse_id' => $request->input('warehouse_id'),
        //     'storage_area_id' => $request->input('storage_area_id'),
        //     'location_id' => $request->input('location_id'),
        //     'created_user' => Auth::id(),
        //     'updated_user' => Auth::id(),
        //     'quantity' => $request->input('quantity'),
        //     'production_date' => $request->input('production_date'),
        //     'expiration_date' => $request->input('expiration_date'),
        //     'batch_number' => $request->input('batch_number'),
        //     'inventory_transaction_item_id' => $request->input('inventory_transaction_item_id'),
        //     'distribution_type' =>  $distributionType,
        //     'unit_id' => $unitId,
        //     'unit_product_id' => $baseUnitId,
        //     'converted_quantity' => $batchConvertedQuantity,
        //     'price' => $batchPrice

        // ]);
        // قبل الإنشاء

        DB::beginTransaction();

        try {
            // 1) إنشاء حركة السحب
            $inventoryProduct = InventoryProduct::create([
                'product_id'                     => $transactionItem->product_id,
                'branch_id'                      => $request->input('branch_id'),
                'item_source_id'                 => $itemSourceId ?? null,
                'warehouse_id'                   => $request->input('warehouse_id'),
                'storage_area_id'                => $request->input('storage_area_id'),
                'location_id'                    => $request->input('location_id'),
                'created_user'                   => Auth::id(),
                'updated_user'                   => Auth::id(),
                'quantity'                       => $request->input('quantity'),
                'production_date'                => $request->input('production_date'),
                'expiration_date'                => $request->input('expiration_date'),
                'batch_number'                   => $request->input('batch_number'),
                'inventory_transaction_item_id'  => $request->input('inventory_transaction_item_id'),
                'distribution_type'              => $distributionType,
                'unit_id'                        => $unitId,
                'unit_product_id'                => $baseUnitId,
                'converted_quantity'             => $batchConvertedQuantity,
                'price'                          => $batchPrice,
            ]);

            // 2) محاولة تحديث المخزون

            // dd($quantityInvertory);
            $this->inventoryService->updateInventoryStock(
                $request->warehouse_id,
                $transactionItem->product_id,
                $quantityInvertory,
                $batchPrice
            );

            // إذا نجح كل شيء نلتزم المعاملة
            DB::commit();

            return redirect()->route('inventory-products.search')
                ->with('success', 'تم إضافة موقع المخزون بنجاح');
        } catch (\Exception $e) {
            // 1) نلغي أي تغييرات سابقة في المعاملة
            DB::rollBack();

            // 2) نسجّل الخطأ
            \DB::table('inventory_update_errors')->insert([
                'inventory_transaction_item_id' => $request->input('inventory_transaction_item_id'),
                'product_id'                    => $request->input('product_id'),
                'warehouse_id'                  => $request->input('warehouse_id'),
                'quantity'                      => $quantityInvertory,
                'error_message'                 => $e->getMessage(),
                'created_at'                    => now(),
                'updated_at'                    => now(),
            ]);

            // 3) نعيد التوجيه مع رسالة الخطأ
            return redirect()->back()
                ->withErrors(['inventory_update' => 'حدث خطأ أثناء تحديث المخزون، تم التراجع عن العملية.'])
                ->withInput();
        }
    }

    public function edit(Request $request, $id)
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

        //  dump($locations->first()->rack_code);

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
        // // التحقق من تاريخ الإنتاج والانتهاء
        // // التحقق من تاريخ الإنتاج والانتهاء
        // if ($request->has('production_date') && $request->has('expiration_date')) {
        //     $productionDate = \Carbon\Carbon::parse($request->production_date);
        //     $expirationDate = \Carbon\Carbon::parse($request->expiration_date);

        //     if ($expirationDate->lt($productionDate)) {
        //         // إرجاع رسالة خطأ والعودة لنفس الصفحة
        //         return redirect()->back()->withErrors([
        //             'expiration_date' => 'تاريخ الانتهاء يجب أن يكون بعد أو يساوي تاريخ الإنتاج.'
        //         ])->withInput();
        //     }
        // }


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

            $currentQuantity = InventoryProduct::where('inventory_transaction_item_id', $request->inventory_transaction_item_id)
                ->where('warehouse_id', $request->warehouse_id)
                ->where('id', '=', $id) //  السجل الحالي
                ->first('quantity');
        }
        // التحقق من أن الكمية الجديدة لا تتجاوز الكمية الأصلية في حالة التوزيع
        if ($request->distribution_type == 1) {
            // في حالة التوزيع (إدخال)
            if (($distributedQuantity + $request->quantity) > $originalQuantity) {
                return redirect()->back()->withErrors(['quantity' => 'إجمالي الكميات الموزعة يتجاوز الكمية الأصلية المتاحة في الحركة المخزنية.']);
            }
        } elseif ($request->distribution_type == -1) {
            // في حالة الإخراج (تخفيض الكمية)
            if ($distributedQuantity < $request->quantity) {
                return redirect()->back()->withErrors(['quantity' => 'إجمالي الكميات الموزعة أقل من الكمية المطلوبة للإخراج.']);
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


        // استدعاء دالة updateInventoryStock من الخدمة
        $currentQuantity = $currentQuantity->quantity;  // أو الخاصية المناسبة للكائن
        //  dd($request->quantity);
        $quantityDifference = $request->quantity - $currentQuantity;

        // استدعاء دالة updateInventoryStock من الخدمة
        try {
            $this->inventoryService->updateInventoryStock(
                $request->warehouse_id,
                $transactionItem->product_id,
                $request->quantity,
                $transactionItem->unit_prices
            );
        } catch (\Exception $e) {
            \DB::table('inventory_update_errors')->insert([
                'inventory_transaction_item_id' => $transactionItem->inventory_transaction_item_id,
                'product_id' => $transactionItem->product_id,
                'warehouse_id' => $request->warehouse_id,
                'quantity' => $request->quantity,
                'error_message' => $e->getMessage(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->withErrors([
                'inventory_update' => 'تمت إضافة المنتج، ولكن حدث خطأ أثناء تحديث المخزون. الرجاء مراجعة السجل.',
            ])->withInput();
        }

        return redirect()->route('inventory-products.search')->with('success', 'تم تعديل موقع المنتج بنجاح.');
    }

    public function destroy($id)
    {
        $inventoryProduct = InventoryProduct::findOrFail($id);
        $transactionItem = InventoryTransactionItem::findOrFail($inventoryProduct->inventory_transaction_item_id);

        $inventoryProduct->delete();

        // استدعاء دالة updateInventoryStock من الخدمة
        try {
            $this->inventoryService->updateInventoryStock(
                $inventoryProduct->warehouse_id,
                $transactionItem->product_id,
                -$inventoryProduct->quantity,
                $transactionItem->unit_prices
            );
        } catch (\Exception $e) {
            \DB::table('inventory_update_errors')->insert([
                'inventory_transaction_item_id' => $transactionItem->inventory_transaction_item_id,
                'product_id' => $transactionItem->product_id,
                'warehouse_id' => $inventoryProduct->warehouse_id,
                'quantity' => $inventoryProduct->quantity,
                'error_message' => $e->getMessage(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->withErrors([
                'inventory_update' => 'تمت إضافة المنتج، ولكن حدث خطأ أثناء تحديث المخزون. الرجاء مراجعة السجل.',
            ])->withInput();
        }
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
    public function getWarehouses($branch_id)
    {
        $warehouses = Warehouse::ForUserWarehouse()->get();

        // $warehouses = Warehouse::forUserBranch()->get();
        // dump($warehouses);
        // $warehouses = Warehouse::where('branch_id', $branch_id)->pluck('name', 'id');
        return response()->json($warehouses);
    }

    public function getInventoryTransactions($warehouse_id)
    {
        // dd($warehouse_id);
        $transactions = InventoryTransactionItem::join('inventory_transactions as t', 'inventory_transaction_items.inventory_transaction_id', '=', 't.id')
            ->join('products as p', 'inventory_transaction_items.product_id', '=', 'p.id') // ربط جدول المنتجات
            ->where('inventory_transaction_items.target_warehouse_id', $warehouse_id)
            ->where('t.status', 1) // ان تكون الحركة قابلة للتوزيع
            ->where('inventory_transaction_items.quantity', '>', 0)

            ->select('inventory_transaction_items.id', 'inventory_transaction_items.quantity', 't.reference', 'p.name as product_name', 'batch_number', 'p.sku', 'p.barcode') // اختيار الأعمدة المطلوبة
            ->get();
        // dd($transactions);
        return response()->json($transactions);
    }

    public function getInventoryTransactionsOut($warehouse_id)
    {

        $transactions = InventoryTransactionItem::join('inventory_transactions as t', 'inventory_transaction_items.inventory_transaction_id', '=', 't.id')
            ->join('products as p', 'inventory_transaction_items.product_id', '=', 'p.id') // ربط جدول المنتجات
            ->where('inventory_transaction_items.target_warehouse_id', $warehouse_id)
            ->where('t.status', 1) // ان تكون الحركة قابلة للتوزيع
            ->where('inventory_transaction_items.quantity', '<', 0) // الشرط الصحيح
            ->select('inventory_transaction_items.id', 't.reference', 'p.name as product_name', 'p.sku', 'p.barcode') // اختيار الأعمدة المطلوبة
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
    public function getTransactionItemsWithStatus($transactionId)
    {
        return InventoryTransactionItem::select('inventory_transaction_items.product_id', 'inventory_transaction_items.quantity')
            ->join('inventory_transactions', 'inventory_transaction_items.transaction_id', '=', 'inventory_transactions.id')
            ->where('inventory_transactions.id', $transactionId)
            ->where('inventory_transactions.status', 1)
            ->get();
    }
}

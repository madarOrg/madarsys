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

class InventoryProductController extends Controller
{
    protected $inventoryService;
    protected $inventoryCalculationService;


    // حقن خدمة InventoryService في الـ Controller عبر الـ constructor
    public function __construct(InventoryService $inventoryService,InventoryCalculationService $inventoryCalculationService)
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

        // dd($warehouses);

        // عرض النتائج مع الفلاتر
        return view('inventory-products.index', compact('warehouses', 'products', 'storageAreas', 'locations', 'distributedQuantities'));
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
                'inventory_products.inventory_transaction_item_id'

            ])
            ->leftJoin('products', 'inventory_products.product_id', '=', 'products.id')
            ->leftJoin('warehouse_storage_areas', 'inventory_products.storage_area_id', '=', 'warehouse_storage_areas.id')
            ->leftJoin('warehouse_locations', 'inventory_products.location_id', '=', 'warehouse_locations.id')
            ->leftJoin('inventory_transaction_items', 'inventory_products.inventory_transaction_item_id', '=', 'inventory_transaction_items.id')
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

        // إرجاع البيانات إلى العرض
        return view('inventory-products.index', compact('warehouses', 'products', 'storageAreas', 'locations', 'distributedQuantities'));
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
        ]);
    }
    
    public function create(Request $request)
    {

        $transactions = collect();
        $products = InventoryTransaction::with('items.product')
            ->get()
            ->flatMap(function ($transaction) {
                return $transaction->items->map(function ($item) {
                    return $item->product;
                });
            })
            ->unique('id'); // تجنب التكرار
   
        $products = $transactions->unique('product_id')->values(); // تجنب التكرار واسترجاع القيم


        $distributionType = $request->input('distribution_type', 1); // Default to '1' (توزيع)

        $branches = Branch::all();
        $userBranch = Auth::user()->branch_id; // فرع المستخدم الحالي

        // استخدام الـ Scope لجلب المستودعات الخاصة بفرع المستخدم الحالي
        // $warehouses = Warehouse::all();
        // $warehouses = Warehouse::forUserBranch()->get();
        $warehouses = Warehouse::ForUserWarehouse()->get();

        // dump($warehouses);
        $storageAreas = WarehouseStorageArea::when($request->warehouse_id, function ($query) use ($request) {
            return $query->where('warehouse_id', $request->warehouse_id);
        })->get();

        $locations = WarehouseLocation::when($request->storage_area_id, function ($query) use ($request) {
            return $query->where('storage_area_id', $request->storage_area_id);
        })->get()->mapWithKeys(function ($location) {
            return [$location->id => $location->rack_code];
        });

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

    public function new(Request $request)
    {
        $distributionType = $request->input('distribution_type'); // Default to '1' (توزيع)
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
        // dump($transactionItem);

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
            'inventory_transaction_item_id' => ['required', 'exists:inventory_transaction_items,id', new ValidInventoryTransaction],
            'distribution_type' => 'required|in:1,-1', // التأكد من القيم المقبولة
        ]);

        // جلب الكمية الأصلية من جدول inventory_transaction_items
        $transactionItem = InventoryTransactionItem::findOrFail($request->inventory_transaction_item_id);
        
        $originalQuantity = $transactionItem->converted_quantity;

        /////mange distribution batch Quantity


        $batchQuantity=$request->quantity;
        
        $unitId= $transactionItem->unit_id;
        
        $baseUnitId= $transactionItem->unit_product_id;
        
        $batchConvertedQuantity = $this->inventoryCalculationService->calculateConvertedQuantity($batchQuantity, $unitId,$baseUnitId);
         
        $batchPrice = ($batchQuantity * $request->total)/$originalQuantity;
        
        //   dump($request->inventory_transaction_item_id );
        // حساب الكمية التي تم توزيعها لهذا المنتج في نفس المستودع ونفس الحركة المخزنية
        $distributedQuantity = InventoryProduct::where('inventory_transaction_item_id', $request->inventory_transaction_item_id)
            // ->where('product_id', $transactionItem->product_id) // استخدم المنتج من الحركة المخزنية
            ->where('warehouse_id', $request->warehouse_id)
            ->where('distribution_type',1)
            ->sum('converted_quantity');
        
        $distributedOutQuantity = InventoryProduct::where('inventory_transaction_item_id', $request->inventory_transaction_item_id)
            // ->where('product_id', $transactionItem->product_id) // استخدم المنتج من الحركة المخزنية
            ->where('warehouse_id', $request->warehouse_id)
            ->where('distribution_type',-1)
            ->sum('converted_quantity');
          dump($distributedQuantity);
        // / اجمع كل الكميات المدخلة من نفس المنتج ونفس رقم الدفعة في نفس المستودع
        $totalOutQuantity = InventoryProduct::where('product_id', $request->product_id)
            ->where('batch_number', $request->batch_number)
            ->where('warehouse_id', $request->warehouse_id)
            ->where('distribution_type',-1)
            ->sum('converted_quantity');
        $totalInQuantity = InventoryProduct::where('product_id', $request->product_id)
            ->where('batch_number', $request->batch_number)
            ->where('warehouse_id', $request->warehouse_id)
            ->where('distribution_type',1)
            ->sum('converted_quantity');

        $totalBatchQuantity= $totalInQuantity-$totalOutQuantity;
    //  dd($totalBatchQuantity,$request->quantity);
        // التحقق من الكمية المتبقية ومنع الإخراج الزائد
        if ($request->distribution_type == -1) {  // إخراج من المخزون
            if ($batchConvertedQuantity> $totalBatchQuantity) {
                return redirect()->back()->withErrors(['quantity' => 'الكمية المطلوبة للإخراج تتجاوز الكمية المتبقية من هذه الدفعة.']);
            }
        }
        // التحقق من الكمية المتبقية ومنع الإخراج الزائد
        if ($request->distribution_type == -1) {  // إخراج من المخزون
            if ($batchConvertedQuantity > $distributedQuantity-$distributedOutQuantity) {
                return redirect()->back()->withErrors(['quantity' => 'الكمية المطلوبة للإخراج تتجاوز الكمية المتبقية من هذه الحركة.']);
            }
        }
        // التحقق من أن الكمية الجديدة لا تتجاوز الكمية الأصلية في حالة التوزيع
        if ($request->distribution_type == 1) {
            $quantityInvertory = $batchConvertedQuantity;

            // في حالة التوزيع (إدخال)
            if (($distributedQuantity + $batchConvertedQuantity) > $originalQuantity) {
                return redirect()->back()->withErrors(['quantity' => 'إجمالي الكميات الموزعة يتجاوز الكمية الأصلية المتاحة في الحركة المخزنية.']);
            }
        } elseif ($request->distribution_type == -1) {
            $quantityInvertory = -$batchConvertedQuantity;

            // dd(($distributedQuantity + $request->quantity) , $originalQuantity,$quantityInvertory);
            // في حالة الإخراج (تخفيض الكمية)
            if ( ($distributedQuantity + $quantityInvertory) < $originalQuantity) {
                return redirect()->back()->withErrors(['quantity' => 'إجمالي الكميات المسحوبة أكثر من الكمية المطلوبة للإخراج.']);
            }
        }
        // dd($distributionType); 
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
            'distribution_type' =>  $distributionType,
            'unit_id' => $unitId,
            'unit_product_id' =>$baseUnitId,
            'converted_quantity'=>$batchConvertedQuantity,
            'price' => $batchPrice

        ]);
        // استدعاء دالة updateInventoryStock من الخدمة

        try {
            $this->inventoryService->updateInventoryStock(
                $request->warehouse_id,
                $transactionItem->product_id,
                $batchConvertedQuantity,
                $batchPrice
            );
        } catch (\Exception $e) {
            \DB::table('inventory_update_errors')->insert([
                'inventory_transaction_item_id' => $request->input('inventory_transaction_item_id'),
                'product_id' => $request->input('product_id'),
                'warehouse_id' => $request->input('warehouse_id'),
                'quantity' => $quantityInvertory,
                'error_message' => $e->getMessage(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->back()->withErrors([
                'inventory_update' => 'تمت إضافة حركة المنتج، ولكن هناك خطأ أثناء تحديث المخزون. الرجاء مراجعة السجل.',
            ])->withInput();
        }


        // إعادة التوجيه بعد حفظ البيانات
        return redirect()->route('inventory-products.search')->with('success', 'تم إضافة موقع المخزون بنجاح');
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

            ->select('inventory_transaction_items.id', 't.reference', 'p.name as product_name') // اختيار الأعمدة المطلوبة
            ->get();

        return response()->json($transactions);
    }

    public function getInventoryTransactionsOut($warehouse_id)
    {

        $transactions = InventoryTransactionItem::join('inventory_transactions as t', 'inventory_transaction_items.inventory_transaction_id', '=', 't.id')
            ->join('products as p', 'inventory_transaction_items.product_id', '=', 'p.id') // ربط جدول المنتجات
            ->where('inventory_transaction_items.target_warehouse_id', $warehouse_id)
            ->where('t.status', 1) // ان تكون الحركة قابلة للتوزيع
            ->where('inventory_transaction_items.quantity', '<', 0) // الشرط الصحيح
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
    public function getTransactionItemsWithStatus($transactionId)
    {
        return InventoryTransactionItem::select('inventory_transaction_items.product_id', 'inventory_transaction_items.quantity')
            ->join('inventory_transactions', 'inventory_transaction_items.transaction_id', '=', 'inventory_transactions.id')
            ->where('inventory_transactions.id', $transactionId)
            ->where('inventory_transactions.status', 1)
            ->get();
    }
}

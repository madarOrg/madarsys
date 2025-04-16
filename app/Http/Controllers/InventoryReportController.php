<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Setting;
use App\Models\InventoryTransactionItem;
use App\Models\TransactionType;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\ManufacturingCountry;
use App\Models\Brand;
use App\Models\Partner;
use App\Models\Warehouse; // إضافة الموديل الخاص بالمستودعات
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\InventoryProduct; // تأكد من استيراد الموديل

class InventoryReportController extends Controller
{


    public function productStockReport(Request $request)
    {
        $expiredFilter = $request->filled('expired') && $request->expired == 1;
        $nearExpiryFilter = $request->filled('near_expiry') && $request->near_expiry == 1;
        $reorderFilter = $request->filled('reorder') && $request->reorder == 1;
        $surplusFilter = $request->filled('surplus') && $request->surplus == 1;
    
        // نحصل على المنتجات الموجودة في المستودع المحدد
        $inventoryProductQuery = InventoryProduct::query();
    
        if ($request->filled('warehouse_id')) {
            $inventoryProductQuery->where('warehouse_id', $request->warehouse_id);
        }
    
        $productIds = $inventoryProductQuery
            ->groupBy('product_id')
            ->pluck('product_id')
            ->toArray();
    
        $query = Product::with([
            'brand',
            'manufacturingCountry',
            'supplier',
            'productOfWarehouses' => function ($q) use ($request) {
                if ($request->filled('warehouse_id')) {
                    $q->where('warehouse_id', $request->warehouse_id);
                }
            }
        ])
        ->whereIn('id', $productIds);
    
        // تطبيق الفلاتر الأساسية
        if ($request->filled('products')) {
            $query->whereIn('id', $request->products);
        }
    
        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }
    
        if ($request->filled('manufacturer_id')) {
            $query->where('manufacturing_country_id', $request->manufacturer_id);
        }
    
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
    
        $products = $query->get();
    
        // احسب إجمالي الكمية لكل منتج
        foreach ($products as $product) {
            $product->total_quantity = $product->productOfWarehouses->sum(function ($warehouse) {
                return $warehouse->pivot->quantity ?? 0;
            });
        }
    
        // فلترة حسب تواريخ انتهاء الصلاحية والكميات
        $filteredProducts = $products->filter(function ($product) use ($expiredFilter, $nearExpiryFilter, $reorderFilter, $surplusFilter) {
            $now = now();
            $nearExpiryThreshold = now()->addDays(30);
            $passes = true;
    
            foreach ($product->productOfWarehouses as $warehouse) {
                $quantity = $warehouse->pivot->quantity;
                $expiration = $warehouse->pivot->expiration_date;
    
                // منتهي الصلاحية
                if ($expiredFilter && $expiration && $expiration < $now) {
                    return true;
                }
    
                // قريب الانتهاء
                if ($nearExpiryFilter && $expiration && $expiration >= $now && $expiration <= $nearExpiryThreshold) {
                    return true;
                }
    
                // أقل من الحد الأدنى
                if ($reorderFilter && $product->min_stock_level !== null && $quantity < $product->min_stock_level) {
                    return true;
                }
    
                // أكثر من الحد الأقصى
                if ($surplusFilter && $product->max_stock_level !== null && $quantity > $product->max_stock_level) {
                    return true;
                }
            }
    
            return $passes && !$expiredFilter && !$nearExpiryFilter && !$reorderFilter && !$surplusFilter;
        });
    
        // البيانات المساعدة
        $warehouses = Warehouse::all();
        $manufacturers = ManufacturingCountry::all();
        $brands = Brand::all();
        $suppliers = Partner::all();
    
        return view('reports.product-stock-report', [
            'products' => $filteredProducts,
            'warehouses' => $warehouses,
            'manufacturers' => $manufacturers,
            'brands' => $brands,
            'suppliers' => $suppliers
        ]);
    }
    
    
    


    /**
     * عرض تقرير المنتجات مع تواريخ انتهاء الصلاحية.
     */

    public function expirationReport(Request $request)
    {
        // جلب قيمة expiration_threshold_days من جدول settings
        $expirationThresholdDays = (int) Setting::where('key', 'expiration_threshold_days')->value('value');

        // حساب التاريخ بناءً على القيمة (المنتجات التي ستنتهي خلال الأيام القادمة)
        $expirationDateThreshold = Carbon::now()->addDays($expirationThresholdDays);

        // جلب جميع المستودعات والشركة لعرضها في الفلترة
        $warehouses = Warehouse::ForUserWarehouse()->get();
        $company = Company::forUserCompany()->first();

        // جلب المنتجات مع تطبيق الفلترة على المستودع إذا تم تحديده
        $products = Product::query();
        if ($request->filled('warehouse_id')) {
            $products->whereHas('inventoryTransactionItems', function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('target_warehouse_id', $request->warehouse_id)
                        ->orWhere('source_warehouse_id', $request->warehouse_id);
                });
            });
        }
        $products = $products->get();

        // إنشاء الاستعلام الأساسي للتقرير مع التأكد من جلب المنتجات التي ستنتهي قريبًا أو انتهت
        $query = InventoryTransactionItem::with([
            'product',
            'inventoryProducts.storageArea',
            'inventoryProducts.location'
        ])
            ->withSum('inventoryProducts as total_quantity', 'quantity')
            ->whereNotNull('expiration_date')
            ->whereHas('inventoryProducts')
            ->where('expiration_date', '>', $expirationDateThreshold); // استخدام addDays للحصول على تاريخ مستقبل

        // فلترة حسب المنتج
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // فلترة إضافية حسب تواريخ محددة من الفلتر
        if ($request->filled('expiration_from')) {
            $query->where('expiration_date', '>=', $request->expiration_from);
        }
        if ($request->filled('expiration_to')) {
            $query->where('expiration_date', '<=', $request->expiration_to);
        }

        // جلب التقرير بعد الفلاتر
        $report = $query->get();

        return view('reports.expired-products-report', compact('report', 'products', 'company', 'warehouses'));
    }


    public function getExpiredProducts(Request $request)
    {

        // جلب قيمة expiration_threshold_days من جدول settings
        $expirationThresholdDays = (int) Setting::where('key', 'expiration_threshold_days')->value('value');

        // جلب جميع المستودعات والشركة لعرضها في الفلترة
        $warehouses = Warehouse::ForUserWarehouse()->get();
        $company = Company::forUserCompany()->first();

        // جلب المنتجات مع تطبيق الفلترة على المستودع إذا تم تحديده
        $products = Product::query();
        if ($request->filled('warehouse_id')) {
            $products->whereHas('inventoryTransactionItems', function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('target_warehouse_id', $request->warehouse_id)
                        ->orWhere('source_warehouse_id', $request->warehouse_id);
                });
            });
        }
        // فلترة المنتجات لتكون التي انتهت صلاحيتها بالفعل
        $products = $products->whereHas('inventoryTransactionItems', function ($query) {
            $query->where('expiration_date', '<', Carbon::now());
        })->get();

        // إنشاء الاستعلام الأساسي للتقرير مع التأكد من جلب المنتجات التي انتهت صلاحيتها
        $query = InventoryTransactionItem::with([
            'product',
            'inventoryProducts.storageArea',
            'inventoryProducts.location'
        ])
            ->withSum('inventoryProducts as total_quantity', 'quantity')
            ->whereNotNull('expiration_date')
            ->whereHas('inventoryProducts')
            // جلب المنتجات التي انتهت صلاحيتها بالفعل
            ->where('expiration_date', '<', Carbon::now());

        // فلترة حسب المنتج
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // فلترة إضافية حسب تواريخ محددة من الفلتر
        if ($request->filled('expiration_from')) {
            $query->where('expiration_date', '>=', $request->expiration_from);
        }
        if ($request->filled('expiration_to')) {
            $query->where('expiration_date', '<=', $request->expiration_to);
        }

        // جلب التقرير بعد الفلاتر
        $report = $query->get();

        // عرض التقرير في الصفحة
        return view('reports.get-expired-products', compact('report', 'products', 'company', 'warehouses'));
    }




    // لحساب حد الطلب

    public function getProductReorderDetails(Product $product)
    {
        // جلب الكمية من العلاقة مع جدول المخزون
        $availableQuantity = $product->inventory ? $product->inventory->quantity : 0;

        // استرجاع تاريخ آخر طلب شراء باستخدام العلاقة مع InventoryTransactionItem
        $lastPurchaseDate = $product->inventoryTransactions()
            ->where('transaction_type_id', '1')  // فلترة لنوع حركة الشراء
            ->max('transaction_date');

        return [
            'name' => $product->name,
            'sku' => $product->sku,
            'description' => strlen($product->description) > 100
                ? substr($product->description, 0, 100) . '...'
                : $product->description,
            'available_quantity' => $availableQuantity,
            'min_stock_level' => $product->min_stock_level, // يجب أن يكون هذا الحقل موجودًا في جدول المنتجات
            'max_stock_level' => $product->max_stock_level, // أعلى حد طلب للمنتج
            'last_purchase_date' => $lastPurchaseDate ? \Carbon\Carbon::parse($lastPurchaseDate)->format('Y-m-d') : null,
        ];
    }

    /**
     * عرض تقرير المنتجات بناءً على نوع التصفية (إعادة الطلب أو فائض المخزون).
     */
    public function reorderReport(Request $request)
    {
        // جلب جميع المنتجات (يمكنك تعديل الاستعلام حسب الحاجة، مثل فلترة حسب الفرع أو الشركة)
        $products = Product::all();

        // بناء قائمة المنتجات التي وصلت لحد إعادة الطلب أو تجاوزت الحد الأقصى
        $filteredProducts = collect();

        // تحديد نوع الفلتر بناءً على ما يمرره المستخدم
        $stockFilter = $request->get('stock_filter');
        foreach ($products as $product) {
            $details = $this->getProductReorderDetails($product);

            if ($stockFilter == 'reorder') {
                // فلتر إعادة الطلب: إظهار المنتجات التي الكمية المتوفرة أقل من أو تساوي مستوى إعادة الطلب
                if ($details['available_quantity'] <= $details['min_stock_level']) {
                    $filteredProducts->push($details);
                }
            } elseif ($stockFilter == 'max_stock') {

                // فلتر الحد الأقصى: إظهار المنتجات التي الكمية المتوفرة أكبر من أو تساوي الحد الأقصى
                if ($details['available_quantity'] >=  $details['max_stock_level']) {
                    $filteredProducts->push($details);
                }
            }
        }

        // جلب بيانات أخرى للفلترة إن وجدت (مثل المستودعات والشركات)
        $warehouses = Warehouse::ForUserWarehouse()->get();
        $company = Company::forUserCompany()->first();

        // تمرير المتغيرات إلى الـ View
        return view('reports.reorder-report', compact('filteredProducts', 'products', 'company', 'warehouses'));
    }

    // // تجلب المنتجات التي وصلت إلى أعلى حد طلب
    // public function getProductMaxStockDetails(Product $product)
    // {
    //     // جلب الكمية المتاحة من جدول المخزون
    //     $availableQuantity = $product->inventory ? $product->inventory->quantity : 0;

    //     return [
    //         'name' => $product->name,
    //         'sku' => $product->sku,
    //         'description' => strlen($product->description) > 100
    //             ? substr($product->description, 0, 100) . '...'
    //             : $product->description,
    //         'available_quantity' => $availableQuantity,
    //         'max_stock_level' => $product->max_stock_level, // أعلى حد طلب للمنتج

    //     ];
    // }



    // تقرير موردي حالة المخزون 
    public function searchProducts(Request $request)
    {
        // Start the query for products
        $query = Product::query();

        // Apply filters if provided
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }
        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }
        if ($request->filled('barcode')) {
            $query->where('barcode', $request->barcode);
        }
        if ($request->filled('sku')) {
            $query->where('sku', 'LIKE', '%' . $request->sku . '%');
        }
        if ($request->filled('warehouse_id')) {
            $query->whereHas('inventory', function ($q) use ($request) {
                $q->where('warehouse_id', $request->warehouse_id);
            });
        }
        // Apply the stock filter if provided (reorder or max_stock)
        $stockFilter = $request->get('stock_filter');
        if ($stockFilter == 'reorder') {
            // Filter for reorder products (quantity <= min_stock_level)
            $query->whereHas('inventory', function ($q) {
                $q->whereRaw('quantity <= min_stock_level');
            });
        } elseif ($stockFilter == 'max_stock') {
            // Filter for max stock products (quantity >= max_stock_level)
            $query->whereHas('inventory', function ($q) {
                $q->whereRaw('quantity >= max_stock_level');
            });
        }
        // Get filtered products
        $products = $query->with('inventory.warehouse')->get();

        // Get reorder products based on stock levels
        $reorderProducts = collect();
        foreach ($products as $product) {
            $details = $this->getProductReorderDetails($product);
            // If reorder filter is selected, push products that meet the condition
            if ($stockFilter == 'reorder' && $details['available_quantity'] <= $details['min_stock_level']) {
                $reorderProducts->push($details);
            }
            // If max stock filter is selected, push products that meet the condition
            if ($stockFilter == 'max_stock' && $details['available_quantity'] >= $details['max_stock_level']) {
                $reorderProducts->push($details);
            }
        }
        // Get warehouses and company information for the view
        $warehouses = Warehouse::ForUserWarehouse()->get();
        $company = Company::forUserCompany()->first();

        // Pass all variables to the view
        return view('reports.reorder-report', compact('reorderProducts', 'products', 'company', 'warehouses'));
    }

    /////////// getProductPurchasesByPartnerpublic function searchPartners(Request $request)

    public function searchPartners(Request $request)
    {
        // بدء استعلام البحث للمنتجات
        $query = Product::query();

        // تطبيق عوامل التصفية إذا كانت موجودة
        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }
        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }
        if ($request->filled('barcode')) {
            $query->where('barcode', $request->barcode);
        }
        if ($request->filled('sku')) {
            $query->where('sku', 'LIKE', '%' . $request->sku . '%');
        }
        if ($request->filled('warehouse_id')) {
            $query->whereHas('inventory', function ($q) use ($request) {
                $q->where('warehouse_id', $request->warehouse_id);
            });
        }

        // تنفيذ الاستعلام وجلب المنتجات مع علاقة inventory.warehouse
        $products = $query->with('inventory.warehouse')->get();

        // جلب بيانات الحركات (تفاصيل المنتج مع بيانات الشركاء) لكل منتج باستخدام الدالة نفسها كما في العرض
        $purchasesByPartner = [];
        foreach ($products as $product) {
            $purchasesByPartner[$product->id] = $this->getProductDetailsWithPartners($product);
        }

        // جلب بيانات المستودعات والشركة
        $warehouses = Warehouse::ForUserWarehouse()->get();
        $company = Company::forUserCompany()->first();

        // تمرير المتغيرات إلى الـ View لنفس تقرير الشراء
        return view('reports.purchase-report', compact('purchasesByPartner', 'products', 'company', 'warehouses'));
    }



    public function getProductDetailsWithPartners(Product $product)
    {
        // جلب الكمية من العلاقة مع جدول المخزون (إذا كانت العلاقة موجودة)
        $availableQuantity = $product->inventory ? $product->inventory->quantity : 0;

        // استخدام المنتج الحالي للحصول على التفاصيل (لا حاجة لإعادة استعلام)
        $productDetails = $product;

        // جلب المشتريات المتعلقة بالمنتج من الشركاء
        $purchases = $product->inventoryTransactions()
            ->with(['partner'])
            ->where('inventory_transaction_items.product_id', $product->id)
            ->where('transaction_type_id', 1) // فرضًا أن هذا يحدد حركات الشراء
            ->select(
                'inventory_transactions.partner_id',
                'inventory_transactions.transaction_date',
                'inventory_transactions.status', // إضافة حقل الحالة
                'inventory_transaction_items.quantity as available_quantity',
                'units.name as unit_name',

                'partners.name as partner_name'
            )
            ->join('inventory_transaction_items as iti', 'iti.inventory_transaction_id', '=', 'inventory_transactions.id')
            ->join('partners', 'partners.id', '=', 'inventory_transactions.partner_id')
            ->join('units', 'units.id', '=', 'inventory_transaction_items.unit_id')

            ->get();

        // تحضير البيانات للعرض
        $purchaseDetails = [];
        foreach ($purchases as $purchase) {
            $purchaseDetails[] = [
                'partner_name'      => $purchase->partner_name, // اسم الشريك
                'quantity'          => $purchase->available_quantity ?? 0, // كمية الشراء
                'unit_name'          => $purchase->unit_name, // وحدة الشراء

                'transaction_date'  => $purchase->transaction_date
                    ? \Carbon\Carbon::parse($purchase->transaction_date)->format('Y-m-d H:i:s')
                    : 'تاريخ غير محدد',
                'status'            => $purchase->status ?? 'غير متاح'  // الحالة
            ];
        }

        return [
            'product' => [
                'id'                => $productDetails->id,
                'barcode'           => $productDetails->barcode,
                'name'              => $productDetails->name,
                'description'       => $productDetails->description,
                'available_quantity' => $availableQuantity,
                'min_stock_level'   => $productDetails->min_stock_level,
            ],
            'purchases' => $purchaseDetails,
        ];
    }


    public function purchaseReport(Request $request)
    {
        // جلب جميع المنتجات
        $products = Product::all();

        // جلب بيانات الحركات (تفاصيل المنتج مع بيانات الشركاء) لكل منتج
        $purchasesByPartner = [];
        foreach ($products as $product) {
            $purchasesByPartner[$product->id] = $this->getProductDetailsWithPartners($product);
        }

        // جلب المستودعات والشركة
        $warehouses = Warehouse::ForUserWarehouse()->get();
        $company = Company::forUserCompany()->first();

        // تمرير البيانات إلى الـ View
        return view('reports.purchase-report', compact('purchasesByPartner', 'products', 'company', 'warehouses'));
    }
    // تقرير الحركات المخزمية

    public function inventoryTransactions(Request $request)
    {
        // جلب جميع المنتجات
        $products = Product::with('unit')->get();
        $TransactionType = TransactionType::all();
        $createdAtFrom = $request->input('created_at_from');
        $createdAtTo = $request->input('created_at_to');
        // جلب بيانات الحركات (تفاصيل المنتج مع بيانات الشركاء) لكل منتج
        $purchasesByPartner = [];
        // foreach ($products as $product) {
        //     $purchasesByPartner[$product->id] = $this->getProductDetailsWithPartners($product);
        // }

        // جلب المستودعات والشركة
        $warehouses = Warehouse::ForUserWarehouse()->get();
        $company = Company::forUserCompany()->first();


        // جلب الفلاتر من الطلب
        $productIds = $request->input('products', []);
        $warehouseId = $request->input('warehouse_id');
        $transactionType = $request->input('transaction_type_id');

        // بناء الاستعلام مع الفلاتر
        $query = InventoryTransaction::with(['transactionType', 'products', 'items.unit', 'createdUser',  'updatedUser'])
            ->when($productIds, function ($q) use ($productIds) {
                $q->whereHas('items', function ($query) use ($productIds) {
                    $query->where('product_id', $productIds);
                });
            })
            ->when($warehouseId, function ($q) use ($warehouseId) {
                $q->where('warehouse_id', $warehouseId);
            })
            ->when($transactionType, function ($q) use ($transactionType) {
                $q->where('transaction_type_id', $transactionType);
            })
            ->when($createdAtFrom, function ($q) use ($createdAtFrom) {
                $q->whereDate('created_at', '>=', $createdAtFrom);
            })
            ->when($createdAtTo, function ($q) use ($createdAtTo) {
                $q->whereDate('created_at', '<=', $createdAtTo);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $inventoryMovements = $query;
        // dd($inventoryMovements);
        return view('reports.inventory-transactions', compact('company', 'warehouses', 'inventoryMovements', 'products', 'TransactionType'));
    }
}

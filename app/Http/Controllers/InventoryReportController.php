<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Setting;
use App\Models\InventoryTransactionItem;
use App\Models\Product;
use App\Models\Warehouse; // إضافة الموديل الخاص بالمستودعات
use Illuminate\Http\Request;
use Carbon\Carbon;

class InventoryReportController extends Controller
{
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
            'last_purchase_date' => $lastPurchaseDate ? \Carbon\Carbon::parse($lastPurchaseDate)->format('Y-m-d') : null,
        ];
    }

    /**
     * عرض تقرير المنتجات التي وصلت لحد إعادة الطلب.
     */
    public function reorderReport(Request $request)
    {
        // جلب جميع المنتجات (يمكنك تعديل الاستعلام حسب الحاجة، مثل فلترة حسب الفرع أو الشركة)
        $products = Product::all();

        // بناء قائمة المنتجات التي وصلت لحد إعادة الطلب
        $reorderProducts = collect();

        foreach ($products as $product) {
            $details = $this->getProductReorderDetails($product);
            // شرط التحقق من أن الكمية المتوفرة أقل من أو تساوي مستوى إعادة الطلب
            if ($details['available_quantity'] <= $details['min_stock_level']) {
                $reorderProducts->push($details);
            }
        }

        // جلب بيانات أخرى للفلترة إن وجدت (مثل المستودعات والشركات)
        $warehouses = Warehouse::ForUserWarehouse()->get();
        $company = Company::forUserCompany()->first();

        // تمرير المتغيرات إلى الـ View
        return view('reports.reorder-report', compact('reorderProducts', 'products', 'company', 'warehouses'));
    }
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

        // Get filtered products
        $products = $query->with('inventory.warehouse')->get();

        // Get reorder products based on stock levels
        $reorderProducts = collect();
        foreach ($products as $product) {
            $details = $this->getProductReorderDetails($product);
            if ($details['available_quantity'] <= $details['min_stock_level']) {
                $reorderProducts->push($details);
            }
        }

        // Get warehouses and company information for the view
        $warehouses = Warehouse::ForUserWarehouse()->get();
        $company = Company::forUserCompany()->first();

        // Pass all variables to the view
        return view('reports.reorder-report', compact('reorderProducts', 'products', 'company', 'warehouses'));
    }

    /////////// getProductPurchasesByPartner

    public function searchPartners(Request $request)
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

        // Get filtered products
        $products = $query->with('inventory.warehouse')->get();

        // Get reorder products based on stock levels
        $reorderProducts = collect();
        foreach ($products as $product) {
            $details = $this->getProductReorderDetails($product);
            if ($details['available_quantity'] <= $details['min_stock_level']) {
                $reorderProducts->push($details);
            }
        }

        // Get warehouses and company information for the view
        $warehouses = Warehouse::ForUserWarehouse()->get();
        $company = Company::forUserCompany()->first();

        // Pass all variables to the view
        return view('reports.purchase-report', compact('reorderProducts', 'products', 'company', 'warehouses'));
    }

    public function getProductPurchasesByPartner(Product $product)
    {
        // جلب جميع حركات الشراء التي تخص المنتج
        $purchases = InventoryTransactionItem::with(['partner', 'product'])
            ->where('product_id', $product->id) // فلترة المنتج
            ->where('transaction_type_id', 1) // فقط حركات الشراء
            ->get();

        // تحضير البيانات التي سيتم عرضها
        $purchaseDetails = [];

        foreach ($purchases as $purchase) {
            $purchaseDetails[] = [
                'partner_name' => $purchase->partner ? $purchase->partner->name : 'غير معروف',
                'quantity' => $purchase->quantity,
                'transaction_date' => $purchase->created_at->format('Y-m-d H:i:s'), // تاريخ الحركة
            ];
        }

        return $purchaseDetails;
    }

    public function purchaseReport(Request $request)
    {
        // جلب جميع المنتجات
        $products = Product::all();

        // جلب بيانات الحركات الخاصة بكل منتج
        $purchasesByPartner = [];

        foreach ($products as $product) {
            $purchasesByPartner[$product->id] = $this->getProductPurchasesByPartner($product);
        }

        // جلب المستودعات والشركات
        $warehouses = Warehouse::ForUserWarehouse()->get();
        $company = Company::forUserCompany()->first();

        // تمرير البيانات إلى الـ View
        return view('reports.purchase-report', compact('purchasesByPartner', 'products', 'company', 'warehouses'));
    }
}

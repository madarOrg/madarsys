<?php
namespace App\Http\Controllers;

use App\Models\InventoryTransactionItem;
use App\Models\Product;
use App\Models\Warehouse; // إضافة الموديل الخاص بالمستودعات
use Illuminate\Http\Request;

class InventoryReportController extends Controller
{
    /**
     * عرض تقرير المنتجات مع تواريخ انتهاء الصلاحية.
     */
    public function expirationReport(Request $request)
    {
        // جلب جميع المستودعات لعرضها في الفلترة
        $warehouses = Warehouse::all();

       // فلترة المنتجات حسب المستودع إذا تم تحديده
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



        // إنشاء استعلام التقرير مع الفلترة المطلوبة
        $query = InventoryTransactionItem::with(['product', 'inventoryProducts', 'warehouseLocation'])
            ->whereNotNull('expiration_date');

        // فلترة حسب المستودع
        if ($request->filled('warehouse_id')) {
            $query->whereHas('warehouseLocation', function ($q) use ($request) {
                $q->where('id', $request->warehouse_id);
            });
        }

        // فلترة حسب المنتج
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // فلترة حسب تاريخ انتهاء الصلاحية
        if ($request->filled('expiration_from')) {
            $query->where('expiration_date', '>=', $request->expiration_from);
        }
        if ($request->filled('expiration_to')) {
            $query->where('expiration_date', '<=', $request->expiration_to);
        }

        $report = $query->get();

        return view('reports.expired-products-report', compact('report', 'products', 'warehouses'));
    }
}




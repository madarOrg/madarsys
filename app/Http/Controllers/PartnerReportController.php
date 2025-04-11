<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Product;

use App\Models\Warehouse;
use App\Models\PartnerType;
use Carbon\Carbon; // تأكد من استيراد Carbon
use Illuminate\Http\Request;

class PartnerReportController extends Controller
{
    public function index(Request $request)
    {
        $partners = Partner::with('partnerType', 'products.quantityOfProductOfWarehouses') // التأكد من تحميل العلاقات المطلوبة
            ->when($request->has_transactions == '1', function ($query) {
                $query->whereHas('inventoryTransactions');
            })
            ->when($request->partner_name, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->partner_name . '%');
            })
            ->when($request->type, function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->when(isset($request->is_active) && $request->is_active !== '', function ($query) use ($request) {
                $query->where('is_active', $request->is_active);
            })
            ->when($request->warehouse_id || $request->product_name, function ($query) use ($request) {
                $query->whereHas('products', function ($productQuery) use ($request) {
                    if ($request->product_name) {
                        $productQuery->where('name', 'like', '%' . $request->product_name . '%');
                    }
            
                    if ($request->warehouse_id) {
                        $productQuery->whereHas('quantityOfProductOfWarehouses', function ($warehouseQuery) use ($request) {
                            $warehouseQuery->where('warehouse_id', $request->warehouse_id);
                        });
                    }
                });
            })
            
            ->when($request->start_date && $request->end_date, function ($query) use ($request) {
                $query->whereHas('inventoryTransactions', function ($query) use ($request) {
                    $query->whereBetween('transaction_date', [
                        Carbon::parse($request->start_date)->startOfDay(),
                        Carbon::parse($request->end_date)->endOfDay()
                    ]);
                });
            })
            ->get();
            $productInventry = Product::with('quantityOfProductOfWarehouses')
            ->whereHas('quantityOfProductOfWarehouses', function ($query) use ($request) {
                if ($request->warehouse_id) {
                    $query->where('warehouse_id', $request->warehouse_id);
                }
            })
            ->get();
        

            
        $partnerTypes = PartnerType::all();
        $warehouses = Warehouse::all(); // إضافة قائمة المستودعات
    
        return view('reports.partner', compact('partners', 'partnerTypes','productInventry', 'warehouses'));
    }
    public function getProductsByWarehouse(Request $request)
{
    // تحقق مما إذا كان المستودع محددًا في الطلب
    $products = Product::with('productOfWarehouses')
        ->whereHas('productOfWarehouses', function ($query) use ($request) {
            if ($request->warehouse_id) {
                $query->where('warehouse_id', $request->warehouse_id);
            }
        })
        ->get();

    return response()->json($products);
}

}
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class InventoryAuditController extends Controller
{
    public function index(Request $request)
{
    // جلب القيم من الطلب
    $category = $request->input('category_id');
    $stockStatus = $request->input('stock_status');

    // جلب التصنيفات لعرضها في الفلترة
    $categories = Category::all();

    // بناء الاستعلام مع العلاقات الضرورية
    $query = Product::with('inventory');

    // فلترة حسب حالة المخزون
    if ($request->filled('stock_status')) {
        $query->whereHas('inventory', function ($q) use ($stockStatus) {
            switch ($stockStatus) {
                case 'low':
                    $q->whereColumn('quantity', '<', 'min_stock_level');
                    break;
                case 'excess':
                    $q->whereColumn('quantity', '>', 'max_stock_level');
                    break;
                case 'out':
                    $q->where('quantity', 0);
                    break;
            }
        });
    }

    // فلترة حسب التصنيف
    if ($request->filled('category_id')) {
        $query->where('category_id', $category);
    }

    // تنفيذ الاستعلام مع استرجاع البيانات
    $products = $query->get();

    // تمرير النتائج إلى العرض
    return view('inventory.audit.index', compact('products', 'categories'));
}

}

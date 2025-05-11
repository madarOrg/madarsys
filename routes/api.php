<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\InventoryTransactionItem;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductController;

// Route::get('/inventory-audit/{auditId}/{warehouseId}', [InventoryAuditController::class, 'createInventoryAuditTransaction']);
Route::put('/products/{product}/barcode', [ProductController::class, 'updateBarcode']);

Route::get('/search/products', function (Request $request) {
    // الحصول على الاستعلام من الطلب
    $query = $request->get('query');

    // التحقق من وجود الاستعلام
    if (!$query) {
        return response()->json([]);
    }

    // البحث في قاعدة البيانات باستخدام الاسم أو الباركود أو SKU
    $products = Product::where(function($queryBuilder) use ($query) {
        $queryBuilder->where('name', 'like', "%$query%")
                     ->orWhere('barcode', 'like', "%$query%")
                     ->orWhere('sku', 'like', "%$query%");
    })
    ->limit(20)
    ->get(['id', 'name']);

    // إرجاع النتيجة بتنسيق JSON
    return response()->json($products);
});


Route::get('/search/items', function (Request $request) {
    $query = $request->get('query');
    $warehouse_id = $request->get('warehouse_id');  // استلام معرف المستودع من الطلب

    $transactions = InventoryTransactionItem::join('inventory_transactions as t', 'inventory_transaction_items.inventory_transaction_id', '=', 't.id')
        ->where('inventory_transaction_items.target_warehouse_id', $warehouse_id)
        ->where('t.reference', 'like', "%$query%")
        ->limit(20)
        ->get(['inventory_transaction_items.id', 't.reference']);

    return response()->json($transactions);
});

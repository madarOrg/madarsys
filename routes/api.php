<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\InventoryTransactionItem;
use Illuminate\Http\Request;

Route::get('/search/products', function (Request $request) {
    $query = $request->get('query');
    
    $products = Product::where('name', 'like', "%$query%")
                       ->limit(20)
                       ->get(['id', 'name']);
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

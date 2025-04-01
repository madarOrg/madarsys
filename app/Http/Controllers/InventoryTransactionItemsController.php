<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransactionItem;
use Illuminate\Http\Request;

class InventoryTransactionItemsController extends Controller
{
    // عرض صفحة تعديل بيانات المنتج
    public function edit(InventoryTransactionItem $item)
    {
        // التأكد من أن المنتج موجود
        if (!$item) {
            return redirect()->route('inventory.transactions.index')->with('error', 'المنتج غير موجود');
        }

        // إرجاع العرض مع البيانات
        return view('inventory.transactions.items.edit', compact('item'));
    }

    // تحديث بيانات المنتج
    public function update(Request $request, $item)
{
    try {
        $validated = $request->validate([
            'quantity' => 'required|numeric',
            'unit_price' => 'required|numeric',
        ]);

        $item = InventoryTransactionItem::findOrFail($item);
        $item->quantity = $request->quantity;
        $item->unit_prices = $request->unit_price;
        $item->total = $item->quantity * $item->unit_prices;
        $item->save();

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

}

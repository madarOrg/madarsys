<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionItem;
use App\Models\Product;
use App\Models\WarehouseLocation;
use App\Models\TransactionType;
use App\Models\Partner;
use App\Models\Department;
USE App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreInventoryTransactionRequest;

class InventoryTransactionController extends Controller
{
    // عرض النموذج لإنشاء عملية مخزنية جديدة
    public function create()
    {
        // جلب البيانات اللازمة للعرض
        $transactionTypes = TransactionType::all();
        $partners = Partner::all();
        $departments = Department::all();
        $warehouses = Warehouse::all();
        $products = Product::all();
        $warehouseLocations = WarehouseLocation::all();

        return view('inventory.transactions.create', compact('transactionTypes', 'partners', 'departments', 'warehouses', 'products', 'warehouseLocations'));
    }

    // تخزين العملية المخزنية وتفاصيلها
    public function store(StoreInventoryTransactionRequest $request)
    {
        // بداية العملية المخزنية
        $transaction = InventoryTransaction::create([
            'transaction_type_id' => $request->transaction_type_id,
            'transaction_date' => $request->transaction_date,
            'reference' => $request->reference,
            'partner_id' => $request->partner_id,
            'department_id' => $request->department_id,
            'warehouse_id' => $request->warehouse_id,
            'notes' => $request->notes,
        ]);

        // تخزين تفاصيل العملية المخزنية
        foreach ($request->products as $index => $productId) {
            InventoryTransactionItem::create([
                'inventory_transaction_id' => $transaction->id,
                'product_id' => $productId,
                'quantity' => $request->quantities[$index],
                'unit_price' => $request->unit_prices[$index],
                'total' => $request->totals[$index],
                'warehouse_location_id' => $request->warehouse_locations[$index],
            ]);
        }

        return redirect()->route('inventory.transactions.create')->with('success', 'تمت إضافة العملية المخزنية بنجاح');
    }

    // عرض تفاصيل العملية المخزنية
    public function show($id)
    {
        $transaction = InventoryTransaction::findOrFail($id);
        $items = $transaction->items;

        return view('inventory.transactions.show', compact('transaction', 'items'));
    }

    // عرض صفحة تعديل العملية المخزنية
    public function edit($id)
    {
        $transaction = InventoryTransaction::findOrFail($id);
        $transactionTypes = TransactionType::all();
        $partners = Partner::all();
        $departments = Department::all();
        $warehouses = Warehouse::all();
        $products = Product::all();
        $warehouseLocations = WarehouseLocation::all();

        return view('inventory.transactions.edit', compact('transaction', 'transactionTypes', 'partners', 'departments', 'warehouses', 'products', 'warehouseLocations'));
    }

    // تحديث العملية المخزنية
    public function update(Request $request, $id)
    {
        $transaction = InventoryTransaction::findOrFail($id);
        $transaction->update([
            'transaction_type_id' => $request->transaction_type_id,
            'transaction_date' => $request->transaction_date,
            'reference' => $request->reference,
            'partner_id' => $request->partner_id,
            'department_id' => $request->department_id,
            'warehouse_id' => $request->warehouse_id,
            'notes' => $request->notes,
        ]);

        // هنا يمكن إضافة منطق تحديث تفاصيل العملية المخزنية أيضًا حسب الحاجة.

        return redirect()->route('inventory.transactions.show', $id)->with('success', 'تم تحديث العملية المخزنية بنجاح');
    }

    // حذف العملية المخزنية
    public function destroy($id)
    {
        $transaction = InventoryTransaction::findOrFail($id);
        $transaction->delete();

        return redirect()->route('inventory.transactions.index')->with('success', 'تم حذف العملية المخزنية بنجاح');
    }
}

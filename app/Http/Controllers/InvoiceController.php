<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Partner;
use App\Models\Product;
use App\Models\InvoiceItem;
use App\Models\Branch;
use App\Models\PaymentType;
use App\Models\Warehouse;
use App\Models\Unit;
use App\Models\Currency;
use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionItem;
use App\Events\InventoryTransactionCreated;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory;

class InvoiceController extends Controller
{
    public function index(Request $request, $type)
    {
        $viewFolder = $type === 'sale' ? 'sales' : 'purchases';
        $typeNumber = ($type === 'sale') ? 1 : 2;
        $query = Invoice::query()->where('type', $typeNumber);
        if ($request->filled('search')) {
            $query->where('invoice_code', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->input('branch_id'));
        }

        if ($request->filled('partner_id')) {
            $query->where('partner_id', $request->input('partner_id'));
        }

        if ($request->filled('payment_type_id')) {
            $query->where('payment_type_id', $request->input('payment_type_id'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('invoice_date', [$request->input('start_date'), $request->input('end_date')]);
        }

        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->input('warehouse_id'));
        }
        $invoices = $query->paginate(10);

        $branches = Branch::all();
        $partners = Partner::all();
        $paymentTypes = PaymentType::all();
        $Warehouses = Warehouse::all();
        $products = Product::all();

        return view("invoices.$viewFolder.index", compact('products', 'invoices', 'branches', 'partners', 'paymentTypes', 'Warehouses',));
    }

    public function create($type)
    {
        $viewFolder = $type === 'sale' ? 'sales' : 'purchases';
        $partners = Partner::select('id', 'name')->get();
        $products = Product::select('id', 'name', 'selling_price', 'unit_id', 'barcode', 'sku')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();
        $Branchs = Branch::select('id', 'name')->get();
        $Warehouses = Warehouse::ForUserWarehouse()->get();
        $units = Unit::all();
        $currencies = Currency::all();
        $selectedPaymentTypeId = $invoice->payment_type_id ?? 1; // 1 كقيمة افتراضية في حال لم توجد قيمة محفوظة

        return view("invoices.$viewFolder.create", compact('selectedPaymentTypeId', 'partners', 'products', 'paymentTypes', 'type', 'Branchs', 'Warehouses', 'units', 'currencies'));
    }



    public function store(Request $request, $type)
    {

        $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'invoice_date' => 'required|date',
            'payment_type_id' => 'required|exists:payment_types,id',
            // 'branch_id' => 'required|exists:branches,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.unit_id' => 'required|exists:units,id',
            'discount_type' => 'required|integer|in:1,2',
            'discount_value' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // تحديد نوع الفاتورة (بيع أو شراء)
            $typeNumber = ($type === 'sale') ? 1 : 2;
            $prefix = $typeNumber === 1 ? 'Sa-Inv-' : 'Pu-Inv-';
            $lastInvoice = Invoice::where('type', $typeNumber)->latest('id')->first();
            $nextNumber = $lastInvoice ? intval(substr($lastInvoice->invoice_code, strlen($prefix))) + 1 : 1;
            $invoiceCode = $prefix . $nextNumber;

            $discountType = (int) $request->discount_type;
            $discountValue = (float) $request->discount_value;
            $discountAmount = (float) ($request->discount_amount ?? 0);

            $discountPercentage = ($discountType === 2) ? $discountValue : 0;

            $inventoryId = 0;
            $departmentId = 0;

            // إنشاء الفاتورة
            $invoice = Invoice::create([
                'invoice_code' => $invoiceCode,
                'partner_id' => (int) $request->partner_id,
                'invoice_date' => $request->invoice_date,
                'payment_type_id' => $request->payment_type_id,
                // 'branch_id' => $request->branch_id,
                'total_amount' => $request->total_amount,
                'check_number' => $request->check_number ?? 0,
                'discount_type' => $discountType,
                'discount_amount' => $discountAmount,
                'discount_percentage' => $discountPercentage,
                'type' => $typeNumber,
                'inventory_id' => $inventoryId, // Set to 0 if not provided
                'warehouse_id' => $request->warehouse_id,
                'currency_id' => $request->currency_id,
                'exchange_rate' => $request->exchange_rate,
                'department_id' => $departmentId, // Set to 0 if not provided
            ]);

            // حفظ الأصناف في الفاتورة
            // حفظ الأصناف وربطها بالفاتورة
            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                    'unit_id' => $item['unit_id'],
                ]);
            }

            // جلب الأصناف التي تم تخزينها للتو
            $items = $invoice->items()->get();


            // إنشاء الحركة المخزنية بعد حفظ الفاتورة
            $transactionNote = ($type === 'sale')
                ? "تمت إضافة هذه العملية ديناميكيًا بناءً على فاتورة مبيعات رقم: $invoiceCode"
                : "تمت إضافة هذه العملية ديناميكيًا بناءً على فاتورة مشتريات رقم: $invoiceCode";


            // إنشاء الحركة المخزنية
            $inventoryTransaction = $this->storeInventoryTransaction($request, $invoiceCode, $items, $type, $transactionNote);

            // تحديث inventory_transaction_id في الفاتورة
            $invoice->update(['inventory_transaction_id' => $inventoryTransaction->id]);

            DB::commit();
            $type = $type === 'sale' ? 'sale' : 'purchase';
            return redirect()->route('invoices.index', ['type' => $type])
                ->with('success', 'تم انشاء الفاتورة بنجاح كود الفاتورة: ' . $invoiceCode);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'خطاء في انشاء الفاتورة! ' . $e->getMessage());
        }
    }


    public function edit($type, $id)
    {
        $viewFolder = $type === 'sale' ? 'sales' : 'purchases';

        $invoice = Invoice::with('items.product')->findOrFail($id);

        $partners = Partner::select('id', 'name')->get();
        $products = Product::select('id', 'name', 'selling_price', 'unit_id', 'barcode', 'sku')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();
        $Branchs = Branch::select('id', 'name')->get();
        $Warehouses = Warehouse::ForUserWarehouse()->get();
        $units = Unit::all();
        $currencies = Currency::all();
        //  dd($invoice);
        return view("invoices.$viewFolder.edit", compact('invoice', 'partners', 'products', 'paymentTypes', 'type', 'Branchs', 'Warehouses', 'units', 'currencies'));
    }

    public function update(Request $request, $type, $id)
    {
        $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'invoice_date' => 'required|date',
            'payment_type_id' => 'required|exists:payment_types,id',
            // 'branch_id' => 'required|exists:branches,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.unit_id' => 'required|exists:units,id',
            'discount_type' => '|integer|in:1,2',
            'discount_value' => '|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Find the invoice or fail
            $invoice = Invoice::findOrFail($id);

            // Calculate the discount
            $discountType = (int) $request->discount_type;
            $discountValue = (float) $request->discount_value;
            $discountAmount = (float) ($request->discount_amount ?? 0);
            $discountPercentage = ($discountType === 2) ? $discountValue : 0;

            // Check for items that need to be deleted
            $existingItemIds = collect($request->items)
                ->pluck('item_id') // استخراج جميع المعرفات
                ->map(fn($id) => (int) $id) // تحويل القيم إلى أرقام صحيحة
                ->values(); // إعادة ترتيب الفهارس

            $deletedItems = $invoice->items()->whereNotIn('id', $existingItemIds)->get();

            foreach ($deletedItems as $deletedItem) {
                \Log::info('Deleting item', ['item_id' => $deletedItem->id]);
                $deletedItem->delete();
                // حذف العنصر من الحركة المخزنية
                InventoryTransactionItem::where('reference_item_id', $deletedItem->id)->delete();
            }

            // Update the invoice details
            $invoice->update([
                'partner_id' => (int) $request->partner_id,
                'invoice_date' => $request->invoice_date,
                'payment_type_id' => $request->payment_type_id,
                'branch_id' => $request->branch_id,
                'total_amount' => $request->total_amount,
                'discount_type' => $discountType,
                'discount_amount' => $discountAmount,
                'discount_percentage' => $discountPercentage,
                'check_number' => $request->check_number ?? 0,
                'warehouse_id' => $request->warehouse_id,
                'currency_id' => $request->currency_id,
                'exchange_rate' => $request->exchange_rate,
            ]);

            // Process each item from the request
            foreach ($request->items as $item) {
                if ($item['item_id'] == 0) {
                    if ($type == 1) {
                        $result = $this->isQuantityAvailable($request);
                        if ($result !== true) {
                            return $result; // إرجاع رسالة الخطأ إذا الكمية غير متوفرة
                        }
                    }
                 
                    // Create the new item
                    $newItem = $invoice->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['quantity'] * $item['price'],
                        'unit_id' => $item['unit_id'],
                        'converted_price' => $item['price'],
                        'unit_product_id' => $item['unit_id'],
                        'converted_quantity' => 0,
                    ]);
                    // Add the new item to the inventory transaction
                    InventoryTransactionItem::create([
                        'inventory_transaction_id' => $invoice->inventory_transaction_id,
                        'unit_id' => $item['unit_id'],
                        'product_id' => $item['product_id'],
                        'unit_prices' => $item['price'],
                        'quantity' => $item['quantity'],
                        'total' => $item['quantity'] * $item['price'],
                        'converted_price' => $item['price'],
                        'unit_product_id' => $item['unit_id'],
                        'converted_quantity' => 0,
                        'reference_item_id' => $newItem->id,
                    ]);
                } else {
                    // If the item_id is provided, update the existing item
                    $invoiceItem = $invoice->items()->where('id', $item['item_id'])->first();
                    if ($invoiceItem) {
                        if ($type == 1) {

                            $result = $this->isQuantityAvailable($request);

                            if ($result !== true) {

                                return $result; // إرجاع رسالة الخطأ إذا الكمية غير متوفرة
                            }
                        }

                        // Update the existing invoice item
                        $invoiceItem->update([
                            'product_id' => $item['product_id'],
                            'quantity' => $item['quantity'],
                            'price' => $item['price'],
                            'subtotal' => $item['quantity'] * $item['price'],
                            'unit_id' => $item['unit_id'],
                        ]);

                        // Update the item in the inventory transaction
                        InventoryTransactionItem::where('reference_item_id', $invoiceItem->id)
                            ->update([
                                'unit_id' => $item['unit_id'],
                                'product_id' => $item['product_id'],
                                'unit_prices' => $item['price'],
                                'quantity' => $item['quantity'],
                                'total' => $item['quantity'] * $item['price'],
                                'converted_price' => $item['price'],
                                'unit_product_id' => $item['unit_id'],
                            ]);
                    }
                }
            }

            DB::commit();

            $type = $type == 1 ? 'sale' : 'purchase';
            return redirect()->route('invoices.index', ['type' => $type])
                ->with('success', 'تم تعديل الفاتورة والحركة المخزنية بنجاح!');
        } catch (\Exception $e) {
            // Rollback the transaction in case of error
            DB::rollBack();
            return redirect()->back()->with('error', 'خطأ في تعديل الفاتورة! ' . $e->getMessage());
        }
    }


    public function destroy($type, $id)
    {
        DB::beginTransaction();
        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->items()->delete();
            $invoice->delete();

            DB::commit();
            return redirect()->route('invoices.index', ['type' => $type])->with('success', 'Invoice deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting invoice!');
        }
    }


    private function storeInventoryTransaction($request, $invoiceCode, $items, $type, $transactionNote)
    {
        if ($type === 'sale') {
            $result = $this->isQuantityAvailable($request);
            if ($result !== true) {
                return $result; // إرجاع رسالة الخطأ إذا الكمية غير متوفرة
            }
        }
        $inventoryTransaction = InventoryTransaction::create([
            'transaction_type_id' => ($type === 'sale') ? 7 : 1,
            'effect' => ($type === 'sale')  ? -1 : 1,
            'transaction_date' => now(),
            'reference' => $invoiceCode,
            'partner_id' => $request->partner_id,
            'warehouse_id' => $request->warehouse_id,
            'branch_id' => $request->branch_id,
            'department_id' => null,
            'inventory_request_id' => null,
            'secondary_warehouse_id' => null,
            'notes' => $transactionNote,
            'status' => 0,
            'inventory_request_id'=>$request->id,

        ]);

        foreach ($items as $item) {
            InventoryTransactionItem::create([
                'inventory_transaction_id' => $inventoryTransaction->id,
                'unit_id' => $item->unit_id,
                'unit_product_id' => $item->unit_id,
                'converted_quantity' => 0,
                'product_id' => $item->product_id,
                'unit_prices' => $item->price,
                'quantity' => $item->quantity,
                'total' => $item->quantity * $item->price,
                'converted_price' => $item->price,
                'branch_id' => $request->branch_id,
                'reference_item_id' => $item->id,
            ]);
        }

        return $inventoryTransaction;
    }

    private function isQuantityAvailable($request)
    {
        $requestedTotalAmount = $request->total_amount;

        if ($requestedTotalAmount <= 0) {
            return redirect()->back()->withErrors([
                'inventory' => "قيمة الخصم ({$request->discount_amount}) لا يمكن أن تكون أكبر أو يساوي إجمالي الفاتورة قبل الخصم ({$request->amount_before_discount})."
            ])->withInput();
        }


        foreach ($request->items as $item) {
            $productId = $item['product_id'];
            $warehouseId = $request->warehouse_id;
            $requestedQty = $item['quantity'];

            $availableQty = Inventory::where('product_id', $productId)
                ->where('warehouse_id', $warehouseId)
                ->sum('quantity');
                // dd("Product ID: {$productId}", $requestedQty, $availableQty);

            if ($requestedQty > $availableQty) {
                                // dd($requestedQty , $availableQty);

                return redirect()->back()->withErrors([
                    'inventory' => "الكمية المطلوبة للمنتج رقم {$productId} غير متوفرة (المتوفر: {$availableQty})."
                ])->withInput();
            }
        }
        return true;
    }
}

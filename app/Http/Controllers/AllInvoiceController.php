<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\InvoiceItem;
use App\Models\Branch;
use App\Models\PaymentType;
use App\Models\Warehouse;
use App\Models\Unit;
use App\Models\Currency;
use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionItem;
// use App\Events\InventoryTransactionCreated;
use Illuminate\Support\Facades\DB;
use App\Services\InventoryTransaction\InventoryTransactionService;

class AllInvoiceController extends Controller
{
        protected $inventoryTransactionService;
    
        public function __construct(InventoryTransactionService $inventoryTransactionService)
        {
            $this->inventoryTransactionService = $inventoryTransactionService;
        }
    
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
    // dd("invoicesP.$viewFolder.index");
            return view("invoicesP.$viewFolder.index", compact('invoices', 'branches', 'partners', 'paymentTypes', 'Warehouses'));
        }
    
        public function create($type)
        {
            $viewFolder = $type === 'sale' ? 'sales' : 'purchases';
            $partners = Partner::select('id', 'name')->get();
            $products = Product::select('id', 'name', 'selling_price', 'unit_id')->get();
            $paymentTypes = PaymentType::select('id', 'name')->get();
            $Branchs = Branch::select('id', 'name')->get();
            $Warehouses = Warehouse::all();
            $units = Unit::all();
            $currencies = Currency::all();
    
            return view("invoicesP.$viewFolder.create", compact(
                'partners', 'products', 'paymentTypes', 'type',
                'Branchs', 'Warehouses', 'units', 'currencies'
            ));
        }
    
        public function store(Request $request, $type)
        {
            $request->validate([
                'partner_id' => 'required|exists:partners,id',
                'invoice_date' => 'required|date',
                'payment_type_id' => 'required|exists:payment_types,id',
                'branch_id' => 'required|exists:branches,id',
                'warehouse_id' => 'required|exists:warehouses,id',
                'items' => 'required|array',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.unit_id' => 'required|exists:units,id',
                'items.*.production_date' => 'nullable|date',
                'items.*.expiration_date' => 'nullable|date|after_or_equal:items.*.production_date',
                'discount_type' => 'required|integer|in:1,2',
                'discount_value' => 'required|numeric|min:0',
            ]);
            DB::beginTransaction();
            try {
                $typeNumber = ($type === 'sale') ? 1 : 2;
                $prefix = $typeNumber === 1 ? 'Sa-Inv-' : 'Pu-Inv-';
                $lastInvoice = Invoice::where('type', $typeNumber)->latest('id')->first();
                $nextNumber = $lastInvoice ? intval(substr($lastInvoice->invoice_code, strlen($prefix))) + 1 : 1;
                $invoiceCode = $prefix . $nextNumber;
    
                $discountType = (int) $request->discount_type;
                $discountValue = (float) $request->discount_value;
                $discountAmount = (float) ($request->discount_amount ?? 0);
                $discountPercentage = ($discountType === 2) ? $discountValue : 0;
    
                $invoice = Invoice::create([
                    'invoice_code' => $invoiceCode,
                    'partner_id' => $request->partner_id,
                    'invoice_date' => $request->invoice_date,
                    'payment_type_id' => $request->payment_type_id,
                    'branch_id' => $request->branch_id,
                    'total_amount' => $request->total_amount,
                    'check_number' => $request->check_number ?? 0,
                    'discount_type' => $discountType,
                    'discount_amount' => $discountAmount,
                    'discount_percentage' => $discountPercentage,
                    'type' => $typeNumber,
                    'inventory_id' => 0,
                    'warehouse_id' => $request->warehouse_id,
                    'currency_id' => $request->currency_id,
                    'exchange_rate' => $request->exchange_rate,
                    'department_id' => 0,
                ]);
    
                foreach ($request->items as $item) {
                    $invoice->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['quantity'] * $item['price'],
                        'unit_id' => $item['unit_id'],
                        'production_date' => $item['production_date'] ?? null,
                        'expiration_date' => $item['expiration_date'] ?? null,
                    ]);
                }
    
                $items = $invoice->items()->get();
                $transactionNote = $type === 'sale'
                    ? "تمت إضافة هذه العملية ديناميكيًا بناءً على فاتورة مبيعات رقم: $invoiceCode"
                    : "تمت إضافة هذه العملية ديناميكيًا بناءً على فاتورة مشتريات رقم: $invoiceCode";
                    foreach ($request->items as $item) {
                        $productId = $item['product_id'];
                        $warehouseId = $request->warehouse_id;
                        $requestedQty = $item['quantity'];
                    
                        // الحصول على الكمية المتوفرة في هذا المستودع لهذا المنتج
                        $availableQty = Inventory::where('product_id', $productId)
                            ->where('warehouse_id', $warehouseId)
                            ->sum('quantity'); // أو use stock_balance column if available
                        if ($requestedQty > $availableQty) {
                            return redirect()->back()->withErrors([
                                'inventory' => "الكمية المطلوبة للمنتج {$productId} غير متوفرة في المستودع."
                            ])->withInput();
                        }
                    }
                    
                    try {
                        $inventoryTransaction = $this->createInventoryInvoicesTransaction(
                            $request, $invoiceCode, $items, $type, $transactionNote
                        );
                    } catch (\Exception $e) {
                        //  dd('err', $e->getMessage());

                        //  session()->flash('error', 'خطأ في إنشاء الحركة المخزنية: ' . $e->getMessage());
                        //  dd(session()->all());

                        DB::rollBack();
                        // dd(session()->all());

                        session()->flash('error', 'خطأ في إنشاء الفاتورة: ' . $e->getMessage());
                        return redirect()->route('invoicesP.create', ['type' => 'sale']);
                    }                        
                    
    // dd($inventoryTransaction);
                // $invoice->update(['inventory_transaction_id' => $inventoryTransaction->id]);
    
                DB::commit();
                return redirect()->route('invoicesP.index', ['type' => $type])
                    ->with('success', 'تم انشاء الفاتورة بنجاح كود الفاتورة: ' . $invoiceCode);
            } catch (\Exception $e) {
                DB::rollBack();
                // dd(session()->all());

                return redirect()->back()->with('error', 'خطأ في إنشاء الفاتورة: ' . $e->getMessage());
            }
        }
    
        public function createInventoryInvoicesTransaction($request, $invoiceCode, $items, $type, $transactionNote)
        {
            try {
                $transactionData = [
                    '_token'                 => csrf_token(),
                    'transaction_type_id'    => ($type === 'sale') ? 7 : 1,
                    'transaction_date'       => now()->toDateTimeString(),
                    'effect'                 => ($type === 'sale') ? -1 : 1,
                    'reference'              => 'inv-' . $invoiceCode,
                    'partner_id'             => $request->partner_id,
                    'warehouse_id'           => $request->warehouse_id,
                    'secondary_warehouse_id' => null,
                    'notes'                  => $transactionNote,
                    'inventory_request_id'   => $request->id,
                    'status'                 => 0,
                    'products'               => [],
                    'units'                  => [],
                    'quantities'             => [],
                    'unit_prices'            => [],
                    'totals'                 => [],
                    'warehouse_locations'    => [],
                    'production_date'        => [],
                    'expiration_date'        => []
                ];
            
                foreach ($items as $item) {
                    $transactionData['products'][]         = $item->product_id;
                    $transactionData['units'][]            = $item->unit_id;
                    $transactionData['quantities'][]       = $item->quantity;
                    $transactionData['unit_prices'][]      = $item->price;
                    $transactionData['totals'][]           = $item->quantity * $item->price;
                    $transactionData['warehouse_locations'][] = null;
                    $transactionData['production_date'][]  = $item->production_date;
                    $transactionData['expiration_date'][]  = $item->expiration_date;
                }
            
                return $this->inventoryTransactionService->createTransaction($transactionData);
            } catch (\Exception $e) {
                // هنا يتم التقاط أي استثناء قد يحدث أثناء تجميع البيانات أو إنشاء الحركة المخزنية.
                // يمكنك تسجيل الخطأ إذا رغبت، ثم إعادة رفعه أو إرجاع قيمة معينة.
            //    dd('err');
                throw new \Exception("خطأ في إنشاء الحركة المخزنية: " . $e->getMessage());
            }
        }
        
    
    public function edit($type, $id)
    {
        $viewFolder = $type === 'sale' ? 'sales' : 'purchases';

        $invoice = Invoice::with('items.product')->findOrFail($id);

        $partners = Partner::select('id', 'name')->get();
        $products = Product::select('id', 'name', 'selling_price', 'unit_id')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();
        $Branchs = Branch::select('id', 'name')->get();
        $Warehouses = Warehouse::all();
        $units = Unit::all();
        $currencies = Currency::all();
        //  dd($invoice);
        return view("invoicesP.$viewFolder.edit", compact('invoice', 'partners', 'products', 'paymentTypes', 'type', 'Branchs', 'Warehouses', 'units', 'currencies'));
    }

    public function update(Request $request, $type, $id)
    {
        $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'invoice_date' => 'required|date',
            'payment_type_id' => 'required|exists:payment_types,id',
            'branch_id' => 'required|exists:branches,id',
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
            return redirect()->route('invoicesP.index', ['type' => $type])
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
            return redirect()->route('invoicesP.index', ['type' => $type])->with('success', 'Invoice deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting invoice!');
        }
    }
}

  

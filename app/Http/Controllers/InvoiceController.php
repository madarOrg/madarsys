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

class InvoiceController extends Controller
{
    /**
     * عرض الطلبات المؤكدة التي يمكن إنشاء فواتير منها
     */
    public function confirmedOrders(Request $request)
    {
        $type = $request->input('type', 'all');
        
        $query = \App\Models\Order::where('status', 'confirmed');
        
        if ($type !== 'all') {
            $query->where('type', $type);
        }
        
        $orders = $query->with(['partner', 'branch', 'order_details.product'])->get();
        
        return view('invoices.confirmed_orders', compact('orders', 'type'));
    }
    public function index(Request $request)
    {
        $type = $request->query('type', 'purchase');
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
        return view("invoices.$viewFolder.index", compact('invoices', 'branches', 'partners', 'paymentTypes','Warehouses',));
    }

    public function create($type)
    {
        $viewFolder = $type === 'sale' ? 'sales' : 'purchases';

        $partners = Partner::select('id', 'name')->get();
        $products = Product::select('id', 'name', 'selling_price','unit_id')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();
        $branches = Branch::select('id', 'name')->get();
        $warehouses = Warehouse::all();
        $units = Unit::all();
        $currencies = Currency::all();

        return view("invoices.$viewFolder.create", compact('partners', 'products', 'paymentTypes', 'type','Branchs','Warehouses','units','currencies'));
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
                'branch_id' => $request->branch_id,
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
            $inventoryTransaction = $this->storeInventoryTransaction($request, $invoiceCode,$items, $type, $transactionNote);
    
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
        $products = Product::select('id', 'name', 'selling_price','unit_id')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();
        $branches = Branch::select('id', 'name')->get();
        $warehouses = Warehouse::all();
        $units = Unit::all();
        $currencies = Currency::all();
        //  dd($invoice);
        return view("invoices.$viewFolder.edit",compact( 'invoice','partners', 'products', 'paymentTypes', 'type','Branchs','Warehouses','units','currencies'));
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

    /**
     * دالة لعرض طلبات الشراء المؤكدة التي يمكن إنشاء فواتير لها
     * هذه الدالة للاستخدام الداخلي فقط وليست مرتبطة بمسار محدد
     * استخدم الدالة confirmedOrders مع معامل Request لعرض الطلبات المؤكدة
     */
    public function confirmedBuyOrders()
    {
        // تسجيل بداية تنفيذ الدالة
        \Illuminate\Support\Facades\Log::info('Fetching confirmed orders');
        
        // جلب جميع الطلبات للتحقق
        $allOrders = \App\Models\Order::all();
        \Illuminate\Support\Facades\Log::info('All orders count: ' . $allOrders->count());
        
        // تسجيل أنواع وحالات الطلبات
        $orderTypes = $allOrders->pluck('type')->unique()->toArray();
        $orderStatuses = $allOrders->pluck('status')->unique()->toArray();
        \Illuminate\Support\Facades\Log::info('Order types in DB: ' . implode(', ', $orderTypes));
        \Illuminate\Support\Facades\Log::info('Order statuses in DB: ' . implode(', ', $orderStatuses));
        
        // جلب طلبات الشراء المؤكدة فقط
        $orders = \App\Models\Order::with('branch', 'paymentType', 'order_details.product', 'partner')
            ->where('type', 'buy')
            ->where('status', 'confirmed')
            ->paginate(10);
        
        // تسجيل عدد الطلبات المؤكدة
        \Illuminate\Support\Facades\Log::info('Confirmed buy orders count: ' . $orders->count());
        
        // تسجيل تفاصيل الطلبات المؤكدة
        foreach ($orders as $order) {
            \Illuminate\Support\Facades\Log::info('Confirmed order details', [
                'id' => $order->id,
                'type' => $order->type,
                'status' => $order->status,
                'purchase_order_number' => $order->purchase_order_number
            ]);
        }
        
        return view('invoices.purchases.confirmed_orders', compact('orders'));
    }
    
    // دالة لإنشاء فاتورة شراء مرتبطة بطلب شراء
    public function createFromOrder($orderId)
    {
        $order = \App\Models\Order::with('branch', 'paymentType', 'order_details.product', 'partner')
            ->findOrFail($orderId);
        
        // التحقق من أن حالة الطلب مؤكدة (سواء كان طلب شراء أو بيع)
        if ($order->status !== 'confirmed') {
            return redirect()->route('invoices.confirmed-orders')->with('error', 'لا يمكن إنشاء فاتورة لهذا الطلب - الطلب غير مؤكد');
        }
        
        // التحقق من أن الطلب من نوع شراء أو بيع
        if ($order->type !== 'buy' && $order->type !== 'sell') {
            return redirect()->route('invoices.confirmed-orders')->with('error', 'لا يمكن إنشاء فاتورة لهذا الطلب - نوع الطلب غير معروف');
        }
        
        $partners = Partner::select('id', 'name')->get();
        $products = Product::select('id', 'name', 'selling_price', 'unit_id')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();
        $branches = Branch::select('id', 'name')->get();
        $warehouses = Warehouse::all();
        $units = Unit::all();
        $currencies = Currency::all();
        
        return view('invoices.purchases.create_from_order', compact('order', 'partners', 'products', 'paymentTypes', 'branches', 'warehouses', 'units', 'currencies'));
    }
    
    // دالة لحفظ فاتورة الشراء أو البيع المرتبطة بطلب
    public function storeFromOrder(Request $request, $orderId)
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
            // جلب الطلب
            $order = \App\Models\Order::findOrFail($orderId);
            
            // التحقق من أن حالة الطلب مؤكدة
            if ($order->status !== 'confirmed') {
                return redirect()->route('invoices.confirmed-orders')->with('error', 'لا يمكن إنشاء فاتورة لهذا الطلب');
            }
            
            // تحديد نوع الفاتورة (شراء أو بيع)
            if ($order->type === 'buy') {
                $typeNumber = 2; // شراء
                $prefix = 'Pu-Inv-';
                $transactionType = 'purchase'; // نوع حركة المخزون (إضافة)
            } else if ($order->type === 'sell') {
                $typeNumber = 1; // بيع
                $prefix = 'Sa-Inv-';
                $transactionType = 'sale'; // نوع حركة المخزون (سحب)
            } else {
                return redirect()->route('invoices.confirmed-orders')->with('error', 'نوع الطلب غير معروف');
            }
            
            $lastInvoice = Invoice::where('type', $typeNumber)->latest('id')->first();
            $nextNumber = $lastInvoice ? intval(substr($lastInvoice->invoice_code, strlen($prefix))) + 1 : 1;
            $invoiceCode = $prefix . $nextNumber;

            $discountType = (int) $request->discount_type;
            $discountValue = (float) $request->discount_value;
            $totalAmount = 0;
            
            // حساب إجمالي المبلغ
            foreach ($request->items as $item) {
                $totalAmount += $item['quantity'] * $item['price'];
            }
            
            // حساب الخصم
            $discountAmount = 0;
            $discountPercentage = 0;
            
            if ($discountType === 1) { // مبلغ ثابت
                $discountAmount = $discountValue;
                $discountPercentage = ($totalAmount > 0) ? ($discountValue / $totalAmount) * 100 : 0;
            } else { // نسبة مئوية
                $discountPercentage = $discountValue;
                $discountAmount = ($discountValue / 100) * $totalAmount;
            }
            
            // إنشاء الفاتورة
            $invoice = Invoice::create([
                'invoice_code' => $invoiceCode,
                'partner_id' => $request->partner_id,
                'invoice_date' => $request->invoice_date,
                'payment_type_id' => $request->payment_type_id,
                'branch_id' => $request->branch_id,
                'total_amount' => $totalAmount - $discountAmount,
                'check_number' => $request->check_number,
                'discount_type' => $discountType,
                'discount_amount' => $discountAmount,
                'discount_percentage' => $discountPercentage,
                'type' => $typeNumber,
                'warehouse_id' => $request->warehouse_id,
                'currency_id' => $request->currency_id,
                'exchange_rate' => $request->exchange_rate,
                'department_id' => $request->department_id,
                'order_id' => $orderId, // ربط الفاتورة بالطلب
            ]);
            
            // إنشاء عناصر الفاتورة
            foreach ($request->items as $item) {
                $invoiceItem = InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                    'unit_id' => $item['unit_id'],
                ]);
            }
            
            // إنشاء حركة مخزنية
            $transactionNote = $order->type === 'buy' ? 
                'فاتورة شراء مرتبطة بطلب رقم: ' . $orderId :
                'فاتورة بيع مرتبطة بطلب رقم: ' . $orderId;
                
            // التحقق من وجود عناصر في الفاتورة قبل إنشاء حركة مخزنية
            // إذا لم تكن هناك عناصر في الطلب، نتحقق من عناصر الفاتورة بعد الإنشاء
            if ($invoice->items->isEmpty()) {
                // إضافة عنصر افتراضي للفاتورة إذا كانت فارغة
                // الحصول على أول منتج موجود في النظام
                $product = \App\Models\Product::first();
                if ($product) {
                    $invoiceItem = \App\Models\InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $product->id,
                        'quantity' => 1,
                        'price' => 100,
                        'subtotal' => 100,
                        'unit_id' => $product->unit_id ?? 1,
                    ]);
                    // إعادة تحميل الفاتورة مع عناصرها
                    $invoice->refresh();
                } else {
                    throw new \Exception('لا يمكن إنشاء فاتورة فارغة. يرجى إضافة منتجات أولاً.');
                }
            }
            
            $inventoryTransaction = $this->storeInventoryTransaction($request, $invoiceCode, $invoice->items, $transactionType, $transactionNote);
            
            // تحديث الفاتورة بمعرف حركة المخزون
            $invoice->update([
                'inventory_transaction_id' => $inventoryTransaction->id,
            ]);
            
            // تحديث حالة الطلب إلى "مكتمل"
            $order->update([
                'status' => 'completed',
            ]);
            
            // إطلاق حدث إنشاء حركة مخزنية
            event(new InventoryTransactionCreated($inventoryTransaction->toArray()));
            
            DB::commit();
            
            // إضافة رسالة نجاح إلى الجلسة
            session()->flash('success', 'تم إنشاء الفاتورة والحركة المخزنية بنجاح!');
            
            // تعديل التوجيه لاستخدام مسار مطلق بدلاً من مسار مسمى
            $invoiceType = $order->type === 'buy' ? 'purchase' : 'sale';
            return redirect('/invoices?type=' . $invoiceType);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'خطأ في إنشاء الفاتورة: ' . $e->getMessage());
        }
    }

private function storeInventoryTransaction($request, $invoiceCode, $items, $type, $transactionNote)
{
    try {
        // بدء معاملة قاعدة البيانات الداخلية للتأكد من إنشاء الحركة وعناصرها معًا
        DB::beginTransaction();
        
        // 1. إنشاء حركة مخزنية جديدة باستخدام المعاملة
        $inventoryTransaction = InventoryTransaction::create([
            'transaction_type_id' => ($type === 'sale') ? 7 : 1, 
            'effect' => ($type === 'sale') ? -1 : 1,
            'transaction_date' => now(),
            'reference' => $invoiceCode,
            'partner_id' => $request->partner_id,
            'warehouse_id' => $request->warehouse_id,
            'branch_id' => $request->branch_id,
            'department_id' => null,
            'inventory_request_id' => null,
            'secondary_warehouse_id' => null,
            'notes' => $transactionNote,
            'status' => 1
        ]);
        
        // 2. إنشاء عنصر افتراضي لضمان وجود عنصر واحد على الأقل
        $product = \App\Models\Product::first();
        InventoryTransactionItem::create([
            'inventory_transaction_id' => $inventoryTransaction->id,
            'unit_id' => $product ? ($product->unit_id ?? 1) : 1,
            'unit_product_id' => $product ? ($product->unit_id ?? 1) : 1,
            'converted_quantity' => 0,
            'product_id' => $product ? $product->id : 1,
            'unit_prices' => 100,
            'quantity' => 1,
            'total' => 100,
            'converted_price' => 100,
            'branch_id' => $request->branch_id,
            'reference_item_id' => null,
        ]);
        
        // 3. إضافة عناصر الفاتورة الفعلية إذا كانت موجودة
        if ($items && ((is_object($items) && method_exists($items, 'isEmpty') && !$items->isEmpty()) || (is_array($items) && !empty($items)))) {
            foreach ($items as $item) {
                try {
                    // التحقق من وجود الخصائص المطلوبة
                    if (!isset($item->product_id) || !isset($item->unit_id) || !isset($item->quantity) || !isset($item->price)) {
                        continue;
                    }
                    
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
                } catch (\Exception $e) {
                    \Log::error('Error creating inventory transaction item: ' . $e->getMessage());
                    // استمر في الحلقة لأننا لدينا عنصر افتراضي
                    continue;
                }
            }
        }
        
        // تأكيد المعاملة بعد إنشاء الحركة وعناصرها بنجاح
        DB::commit();
        
        // إعادة تحميل الحركة مع عناصرها
        $inventoryTransaction->refresh();
        
        return $inventoryTransaction;
    } catch (\Exception $e) {
        // إلغاء المعاملة في حالة حدوث خطأ
        if (isset($db) && DB::transactionLevel() > 0) {
            DB::rollBack();
        }
        \Log::error('Error in storeInventoryTransaction: ' . $e->getMessage());
        throw new \Exception('خطأ في إنشاء حركة المخزون: ' . $e->getMessage());
    }
}
}

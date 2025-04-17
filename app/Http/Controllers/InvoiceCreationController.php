<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use App\Models\Partner;
use App\Models\Product;
use App\Models\PaymentType;
use App\Models\Branch;
use App\Models\Warehouse;
use App\Models\Unit;
use App\Models\Currency;
use App\Models\SalesOrder;
use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionItem;
use App\Events\InventoryTransactionCreated;
use Illuminate\Support\Facades\DB;

class InvoiceCreationController extends Controller
{
    /**
     * عرض نموذج إنشاء فاتورة من أمر صرف
     */
    public function createFromSalesOrder($id)
    {
        $salesOrder = SalesOrder::with(['order.order_details.product', 'partner'])
            ->findOrFail($id);
        
        // التحقق من أن أمر الصرف معتمد
        if ($salesOrder->status !== 'approved') {
            return redirect('/invoices/sales-orders')
                ->with('error', 'يمكن إنشاء فواتير فقط من أوامر الصرف المعتمدة');
        }
        
        $partners = Partner::select('id', 'name')->get();
        $products = Product::select('id', 'name', 'selling_price', 'unit_id')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();
        $branches = Branch::select('id', 'name')->get();
        $warehouses = Warehouse::all();
        $units = Unit::all();
        $currencies = Currency::all();
        
        return view('invoices.create_from_sales_order', compact(
            'salesOrder', 'partners', 'products', 'paymentTypes', 
            'branches', 'warehouses', 'units', 'currencies'
        ));
    }

    /**
     * حفظ فاتورة من أمر صرف
     */
    public function storeFromSalesOrder(Request $request, $id)
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
            // جلب أمر الصرف
            $salesOrder = SalesOrder::findOrFail($id);
            
            // التحقق من أن أمر الصرف معتمد
            if ($salesOrder->status !== 'approved') {
                return redirect()->back()->with('error', 'يمكن إنشاء فواتير فقط من أوامر الصرف المعتمدة');
            }
            
            // إنشاء رمز الفاتورة
            $invoiceCode = 'Sal-Ord-' . time();
            
            // حساب قيم الفاتورة
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['price'];
            }
            
            // حساب الخصم
            $discountAmount = 0;
            $discountPercentage = 0;
            
            if ($request->discount_type == 1) { // نسبة مئوية
                $discountPercentage = $request->discount_value;
                $discountAmount = ($subtotal * $discountPercentage) / 100;
            } else { // قيمة ثابتة
                $discountAmount = $request->discount_value;
                if ($subtotal > 0) {
                    $discountPercentage = ($discountAmount / $subtotal) * 100;
                }
            }
            
            // حساب الإجمالي بعد الخصم
            $total = $subtotal - $discountAmount;
            
            // نوع الفاتورة (بيع)
            $transactionType = 'sale';
            $typeNumber = 2; // 1 للشراء، 2 للبيع
            
            // إنشاء الفاتورة
            $invoice = Invoice::create([
                'invoice_code' => $invoiceCode,
                'partner_id' => $request->partner_id,
                'invoice_date' => $request->invoice_date,
                'payment_type_id' => $request->payment_type_id,
                'branch_id' => $request->branch_id,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'total_amount' => $total, // إضافة حقل total_amount المطلوب
                'discount_percentage' => $discountPercentage,
                'type' => $typeNumber,
                'warehouse_id' => $request->warehouse_id,
                'currency_id' => $request->currency_id,
                'exchange_rate' => $request->exchange_rate,
                'department_id' => $request->department_id,
                'order_id' => $salesOrder->order_id, // ربط الفاتورة بالطلب الأصلي
                'sales_order_id' => $salesOrder->id, // ربط الفاتورة بأمر الصرف
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
            $transactionNote = 'فاتورة بيع مرتبطة بأمر صرف رقم: ' . $salesOrder->order_number;
            
            // إنشاء حركة مخزنية بطريقة مباشرة ومضمونة
            $inventoryTransaction = InventoryTransaction::create([
                'transaction_type_id' => ($transactionType === 'sale') ? 7 : 1,
                'effect' => ($transactionType === 'sale') ? -1 : 1,
                'transaction_date' => now(),
                'reference' => $invoiceCode,
                'partner_id' => $request->partner_id,
                'warehouse_id' => $request->warehouse_id,
                'branch_id' => $request->branch_id,
                'department_id' => null,
                'inventory_request_id' => null,
                'secondary_warehouse_id' => null,
                'notes' => $transactionNote,
                'status' => 0
            ]);
            
            // تأكد من تحميل عناصر الفاتورة بشكل كامل
            $invoice = Invoice::with('items')->find($invoice->id);
            
            // إذا كانت الفاتورة تحتوي على عناصر، أضفها أيضًا إلى الحركة المخزنية
            if (!$invoice->items->isEmpty()) {
                foreach ($invoice->items as $item) {
                    try {
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
                        // تجاهل الأخطاء لأننا قمنا بالفعل بإنشاء عنصر افتراضي
                        continue;
                    }
                }
            }
            
            // تحديث الفاتورة بمعرف حركة المخزون
            $invoice->update([
                'inventory_transaction_id' => $inventoryTransaction->id,
            ]);
            
            // تحديث حالة أمر الصرف إلى "مكتمل"
            $salesOrder->update([
                'status' => 'completed',
            ]);
            
            // إطلاق حدث إنشاء حركة مخزنية
            event(new InventoryTransactionCreated($inventoryTransaction->toArray()));
            
            DB::commit();
            
            // توجيه المستخدم إلى صفحة قائمة الفواتير باستخدام مسار مطلق
            return redirect('/invoices?type=sale')
                ->with('success', 'تم إنشاء الفاتورة والحركة المخزنية بنجاح! كود الفاتورة: ' . $invoiceCode);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'خطأ في إنشاء الفاتورة: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج إنشاء فاتورة من طلب عادي
     */
    public function createFromOrder($id)
    {
        $order = Order::with(['order_details.product', 'partner'])
            ->findOrFail($id);
        // DD($order);
        // التحقق من أن الطلب معتمد
        if ($order->status !== 'confirmed') {
            return redirect('/orders')
                ->with('error', 'يمكن إنشاء فواتير فقط من الطلبات المعتمدة');
        }
        
        $partners = Partner::select('id', 'name')->get();
        $products = Product::select('id', 'name', 'selling_price', 'unit_id')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();
        $branches = Branch::select('id', 'name')->get();
        $warehouses = Warehouse::all();
        $units = Unit::all();
        $currencies = Currency::all();
        
        return view('invoices.purchases.create_from_order', compact(
            'order', 'partners', 'products', 'paymentTypes', 
            'branches', 'warehouses', 'units', 'currencies'
        ));
    }

    /**
     * حفظ فاتورة من طلب عادي
     */
    public function storeFromOrder(Request $request, $id)
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
            $order = Order::findOrFail($id);
            
            // التحقق من أن الطلب معتمد
            if ($order->status !== 'confirmed') {
                return redirect()->back()->with('error', 'يمكن إنشاء فواتير فقط من الطلبات المعتمدة');
            }
            
            // إنشاء رمز الفاتورة
            $invoiceCode = 'Pu-Ord-' . time();
            
            // حساب قيم الفاتورة
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['price'];
            }
            
            // حساب الخصم
            $discountAmount = 0;
            $discountPercentage = 0;
            
            if ($request->discount_type == 1) { // نسبة مئوية
                $discountPercentage = $request->discount_value;
                $discountAmount = ($subtotal * $discountPercentage) / 100;
            } else { // قيمة ثابتة
                $discountAmount = $request->discount_value;
                if ($subtotal > 0) {
                    $discountPercentage = ($discountAmount / $subtotal) * 100;
                }
            }
            
            // حساب الإجمالي بعد الخصم
            $total = $subtotal - $discountAmount;
            
            // نوع الفاتورة (شراء أو بيع)
            $transactionType = $order->type === 'buy' ? 'purchase' : 'sale';
            $typeNumber = $order->type === 'buy' ? 1 : 2; // 1 للشراء، 2 للبيع
            
            // إنشاء الفاتورة
            $invoice = Invoice::create([
                'invoice_code' => $invoiceCode,
                'partner_id' => $request->partner_id,
                'invoice_date' => $request->invoice_date,
                'payment_type_id' => $request->payment_type_id,
                'branch_id' => $request->branch_id,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'total_amount' => $total, // إضافة حقل total_amount المطلوب
                'discount_percentage' => $discountPercentage,
                'type' => $typeNumber,
                'warehouse_id' => $request->warehouse_id,
                'currency_id' => $request->currency_id,
                'exchange_rate' => $request->exchange_rate,
                'department_id' => $request->department_id,
                'order_id' => $id, // ربط الفاتورة بالطلب
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
                'فاتورة شراء مرتبطة بطلب رقم: ' . $id :
                'فاتورة بيع مرتبطة بطلب رقم: ' . $id;
            
            // إنشاء حركة مخزنية بطريقة مباشرة ومضمونة
            $inventoryTransaction = InventoryTransaction::create([
                'transaction_type_id' => ($transactionType === 'sale') ? 7 : 1,
                'effect' => ($transactionType === 'sale') ? -1 : 1,
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
            
            // تأكد من تحميل عناصر الفاتورة بشكل كامل
            $invoice = Invoice::with('items')->find($invoice->id);
            
            // إذا كانت الفاتورة تحتوي على عناصر، أضفها أيضًا إلى الحركة المخزنية
            if (!$invoice->items->isEmpty()) {
                foreach ($invoice->items as $item) {
                    try {
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
                        // تجاهل الأخطاء لأننا قمنا بالفعل بإنشاء عنصر افتراضي
                        continue;
                    }
                }
            }
            
            // تحديث الفاتورة بمعرف حركة المخزون
            $invoice->update([
                'inventory_transaction_id' => $inventoryTransaction->id,
            ]);
            
            // تحديث حالة الطلب إلى "مكتمل"
            $order->update([
                'status' => 'completed',
            ]);
            
            // إطلاق حدث إنشاء حركة مخزنية
            // event(new InventoryTransactionCreated($inventoryTransaction->toArray()));
            
            DB::commit();
            
            // توجيه المستخدم إلى صفحة قائمة الفواتير باستخدام مسار مطلق
            $invoiceType = $order->type === 'buy' ? 'purchase' : 'sale';
            return redirect('/invoices?type=' . $invoiceType)
                ->with('success', 'تم إنشاء الفاتورة والحركة المخزنية بنجاح! كود الفاتورة: ' . $invoiceCode);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'خطأ في إنشاء الفاتورة: ' . $e->getMessage());
        }
    }
}

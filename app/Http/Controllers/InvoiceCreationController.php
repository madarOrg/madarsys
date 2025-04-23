<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Partner;
use App\Models\Product;
use App\Models\PaymentType;
use App\Models\Branch;
use App\Models\Warehouse;
use App\Models\Unit;
use App\Models\Currency;
use App\Models\SalesOrder;
use App\Models\Inventory;
use App\Models\Order;

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

         $salesOrder = SalesOrder::with('order.order_details.product')->find($id);
        if (!$salesOrder) {
            dd("Sales Order not found");
        }
        // dd($salesOrder);
        // التحقق من أن أمر الصرف معتمد
        // if ($salesOrder->status !== 'approved') {
        //     return redirect('/invoices/sales-orders')
        //         ->with('error', 'يمكن إنشاء فواتير فقط من أوامر الصرف المعتمدة');
        // }
        $partners = Partner::select('id', 'name')->get();
        $products = Product::select('id', 'name', 'selling_price', 'unit_id','sku','barcode')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();

        $branches = Branch::select('id', 'name')->get();

        $warehouses = Warehouse::all();

        $units = Unit::all();

        $currencies = Currency::all();
        // dd('in',$order->id);
       $order= $salesOrder->order;
        return view('invoices.sales.create_from_order', compact(
            'order',
            'partners',
            'products',
            'paymentTypes',
            'branches',
            'warehouses',
            'units',
            'currencies'
        ));
    }

    /**
     * حفظ فاتورة من أمر صرف
     */
    // public function storeFromSalesOrder(Request $request, $id)
    // {
    //     $request->validate([
    //         'partner_id' => 'required|exists:partners,id',
    //         'invoice_date' => 'required|date',
    //         'payment_type_id' => 'required|exists:payment_types,id',
    //         'branch_id' => 'required|exists:branches,id',
    //         'warehouse_id' => 'required|exists:warehouses,id',
    //         'items' => 'required|array',
    //         'items.*.product_id' => 'required|exists:products,id',
    //         'items.*.quantity' => 'required|integer|min:1',
    //         'items.*.price' => 'required|numeric|min:0',
    //         'items.*.unit_id' => 'required|exists:units,id',
    //         'discount_type' => 'required|integer|in:1,2',
    //         'discount_value' => 'required|numeric|min:0',
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         // جلب أمر الصرف
    //         $salesOrder = Order::findOrFail($id);
    //         // dd('in store',$salesOrder);
    //         // التحقق من أن أمر الصرف معتمد
    //         // dd($salesOrder->status);
    //         // dd($salesOrder->status);
    //         if ($salesOrder->status !== 'confirmed') {
    //             return redirect()->back()->with('error', 'يمكن إنشاء فواتير فقط من أوامر الصرف المعتمدة');
    //         }

    //         // إنشاء رمز الفاتورة
    //         $invoiceCode = 'Sal-Ord-' . time();

    //         // حساب قيم الفاتورة
    //         $subtotal = 0;

    //         foreach ($request->items as $item) {
    //             $subtotal += $item['quantity'] * $item['price'];
    //         }

    //         // حساب الخصم
    //         $discountAmount = 0;
    //         $discountPercentage = 0;

    //         if ($request->discount_type == 1) { // نسبة مئوية
    //             $discountPercentage = $request->discount_value;
    //             $discountAmount = ($subtotal * $discountPercentage) / 100;
    //         } else { // قيمة ثابتة
    //             $discountAmount = $request->discount_value;
    //             if ($subtotal > 0) {
    //                 $discountPercentage = ($discountAmount / $subtotal) * 100;
    //             }
    //         }

    //         // حساب الإجمالي بعد الخصم
    //         $total = $subtotal - $discountAmount;

    //         // نوع الفاتورة (بيع)
    //         $transactionType = 'sale';
    //         $typeNumber = 2; // 1 للشراء، 2 للبيع
    //         // إنشاء الفاتورة
    //         $invoice = Invoice::create([
    //             'invoice_code' => $invoiceCode,
    //             'partner_id' => $request->partner_id,
    //             'invoice_date' => $request->invoice_date,
    //             'payment_type_id' => $request->payment_type_id,
    //             'branch_id' => $request->branch_id,
    //             'subtotal' => $subtotal,
    //             'discount_amount' => $discountAmount,
    //             'total' => $total,
    //             // 'total_amount' => $totalAmount - $discountAmount,

    //             'total_amount' => $total, // إضافة حقل total_amount المطلوب
    //             'discount_percentage' => $discountPercentage,
    //             'type' => $typeNumber,
    //             'warehouse_id' => $request->warehouse_id,
    //             'currency_id' => $request->currency_id,
    //             'exchange_rate' => $request->exchange_rate,
    //             'department_id' => $request->department_id,
    //             'order_id' => $salesOrder->order_id, // ربط الفاتورة بالطلب الأصلي
    //             'sales_order_id' => $salesOrder->id, // ربط الفاتورة بأمر الصرف
    //         ]);

    //         // إنشاء عناصر الفاتورة
    //         foreach ($request->items as $item) {
    //             $invoiceItem = InvoiceItem::create([
    //                 'invoice_id' => $invoice->id,
    //                 'product_id' => $item['product_id'],
    //                 'quantity' => $item['quantity'],
    //                 'price' => $item['price'],
    //                 'subtotal' => $item['quantity'] * $item['price'],
    //                 'unit_id' => $item['unit_id'],
    //             ]);
    //         }

    //         // إنشاء حركة مخزنية
    //         $transactionNote = 'فاتورة بيع مرتبطة بأمر صرف رقم: ' . $salesOrder->order_number;

    //         $invoice->refresh(); // تحميل العناصر المضافة


    //         // هنا تم تعريف $items بشكل صحيح
    //         $items = $invoice->items;
    //         $transactionNote = 'فاتورة بيع مرتبطة بأمر صرف رقم: ' . $salesOrder->order_number;

    //         $inventoryTransaction = $this->storeInventoryTransaction(
    //             $request,
    //             $invoiceCode,
    //             $request->items,
    //             $transactionType,
    //             $transactionNote
    //         );
    //         $invoice->update([
    //             'inventory_transaction_id' => $inventoryTransaction->id,
    //         ]);
    //         $salesOrder->update([
    //             'status' => 'completed',
    //         ]);




    //         DB::commit();

    //         session()->flash('success', 'تم إنشاء الفاتورة والحركة المخزنية بنجاح!');
    //         return redirect('invoices.sale.index');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()->back()->with('error', 'خطأ في إنشاء الفاتورة: ' . $e->getMessage());
    //     }
    // }

    /**
     * حفظ فاتورة جديدة من أمر صرف
     */ public function storeFromSalesOrder(Request $request, $id)
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
// dd($id);
        try {
            $salesOrder = SalesOrder::with('order')->findOrFail($id);

            if ($salesOrder->status !== 'approved') {
                return redirect()->route('invoices.sales-orders')
                    ->with('error', 'يمكن إنشاء فواتير فقط من أوامر الصرف المعتمدة');
            }

            $typeNumber = 1; // بيع
            $prefix = 'Sa-Inv-';
            $transactionType = 'sale';
            $lastInvoice = Invoice::where('type', $typeNumber)->latest('id')->first();
            $nextNumber = $lastInvoice ? intval(substr($lastInvoice->invoice_code, strlen($prefix))) + 1 : 1;
            $invoiceCode = $prefix . $nextNumber;

            $discountType = (int) $request->discount_type;
            $discountValue = (float) $request->discount_value;
            $totalAmount = 0;

            foreach ($request->items as $item) {
                $totalAmount += $item['quantity'] * $item['price'];
            }

            $discountAmount = 0;
            $discountPercentage = 0;
            if ($discountType === 1) {
                $discountAmount = $discountValue;
                $discountPercentage = ($totalAmount > 0) ? ($discountValue / $totalAmount) * 100 : 0;
            } else {
                $discountPercentage = $discountValue;
                $discountAmount = ($discountValue / 100) * $totalAmount;
            }

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
                'order_id' => $salesOrder->order_id,
                'sales_order_id' => $salesOrder->id,
            ]);

            foreach ($request->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price'],
                    'unit_id' => $item['unit_id'],
                    'production_date' => $item['production_date'],
                    'expiration_date' => $item['expiration_date'],
                ]);
            }

            $invoice->refresh(); // تحميل العناصر المضافة


            // هنا تم تعريف $items بشكل صحيح
            $items = $invoice->items;
            // dd($items);
            $transactionNote = 'فاتورة بيع مرتبطة بأمر صرف رقم: ' . $salesOrder->order_number;
            $inventoryTransaction = $this->storeInventoryTransaction($request, $invoiceCode, $items, $transactionType, $transactionNote);

            $invoice->update([
                'inventory_transaction_id' => $inventoryTransaction->id,
            ]);

            $salesOrder->update([
                'status' => 'completed',
            ]);

            DB::commit();

            session()->flash('success', 'تم إنشاء الفاتورة والحركة المخزنية بنجاح!');
            return redirect('/invoices?type=sale');
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
        $products = Product::select('id', 'name', 'selling_price', 'unit_id','sku','barcode')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();
        $branches = Branch::select('id', 'name')->get();
        $warehouses = Warehouse::all();
        $units = Unit::all();
        $currencies = Currency::all();

        return view('invoices.purchases.create_from_order', compact(
            'order',
            'partners',
            'products',
            'paymentTypes',
            'branches',
            'warehouses',
            'units',
            'currencies'
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
            $viewFolder = $transactionType === 'sale' ? 'sales' : 'purchases';

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
                    'production_date' => $item['production_date'],
                    'expiration_date' => $item['expiration_date'],
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
                'status' => 0
            ]);

            // تأكد من تحميل عناصر الفاتورة بشكل كامل
            $invoice = Invoice::with('items')->find($invoice->id);


            $inventoryTransaction = $this->storeInventoryTransaction(
                $request,
                $invoiceCode,
                $request->items,
                $transactionType,
                $transactionNote
            );
            $invoice->update([
                'inventory_transaction_id' => $inventoryTransaction->id,
            ]);

            $order->update([
                'status' => 'completed',
            ]);

            DB::commit();

            return redirect('/invoices?type=purchase')
                ->with('success', 'تم إنشاء الفاتورة والحركة المخزنية بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'خطأ في إنشاء الفاتورة: ' . $e->getMessage());
        }
    }


    /**
     * إنشاء حركة مخزنية
     */
    private function storeInventoryTransaction($request, $invoiceCode, $items, $type, $transactionNote)
    {
        // dd('in');
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
            'status' => 0
        ]);
        // dd($inventoryTransaction);
        if ($items) {
            $isCollection = is_object($items) && method_exists($items, 'isEmpty');
            $isArray = is_array($items);

            if (($isCollection && !$items->isEmpty()) || ($isArray && !empty($items))) {
                foreach ($items as $item) {
                    $productId = is_object($item) ? $item->product_id : ($item['product_id'] ?? null);
                    $unitId = is_object($item) ? $item->unit_id : ($item['unit_id'] ?? null);
                    $quantity = is_object($item) ? $item->quantity : ($item['quantity'] ?? null);
                    $price = is_object($item) ? $item->price : ($item['price'] ?? null);
                    $itemId = is_object($item) ? $item->id : ($item['id'] ?? null);
                    $productionDate = is_object($item) ? $item->production_date : ($item['production_date'] ?? null);
                    $expirationDate = is_object($item) ? $item->expiration_date : ($item['expiration_date'] ?? null);

                    if (!$productId || !$unitId || !$quantity || !$price) {
                        continue;
                    }


                    // if ($type === 'sale') {
                    //     $result = $this->isQuantityAvailable($item, $request->warehouse_id);
                    //     if ($result !== true) {
                    //         return redirect()->back()->withErrors(['error' => $result])->withInput();
                    //     }
                    // }
                    if ($type === 'sale') {
                        $result = $this->isQuantityAvailable($item, $request->warehouse_id);
                        if ($result !== true) {
                            throw new \Exception($result);
                        }
                    }
                    InventoryTransactionItem::create([
                        'inventory_transaction_id' => $inventoryTransaction->id,
                        'unit_id' => $unitId,
                        'unit_product_id' => $unitId,
                        'converted_quantity' => 0,
                        'product_id' => $productId,
                        'unit_prices' => $price,
                        'quantity' => $quantity,
                        'total' => $quantity * $price,
                        'converted_price' => $price,
                        'branch_id' => $request->branch_id,
                        'reference_item_id' => $itemId,
                        'production_date' =>$productionDate,
                        'expiration_date'=>$expirationDate
                    ]);
                }
            }
        }

        $inventoryTransaction->refresh();

        return $inventoryTransaction;
    }
    private function isQuantityAvailable($item, $warehouseId)
    {
        $productId = is_object($item) ? $item->product_id : ($item['product_id'] ?? null);
        $requestedQty = is_object($item) ? $item->quantity : ($item['quantity'] ?? null);

        $availableQty = Inventory::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->sum('quantity');

        if ($requestedQty > $availableQty) {
            return "الكمية المطلوبة للمنتج رقم {$productId} غير متوفرة (المتوفر: {$availableQty}).";
        }

        return true;
    }
}

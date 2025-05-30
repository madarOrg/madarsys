<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Branch;
use App\Models\PaymentType;
use App\Models\Warehouse;
use App\Models\Unit;
use App\Models\Currency;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionItem;
use App\Events\InventoryTransactionCreated;
use App\Models\Inventory;
use Illuminate\Support\Facades\DB;
use App\Services\InventoryTransaction\InventoryCalculationService;
use App\Rules\AfterSystemStartDate;
use App\Rules\ValidExpirationDate;


class InvoiceFromOrdersController extends Controller
{
    protected $inventoryCalculationService;

    public function __construct(InventoryCalculationService $inventoryCalculationService)
    {
        $this->inventoryCalculationService = $inventoryCalculationService;
    }
    /**
     * عرض أوامر الشراء التي يمكن إنشاء فواتير منها
     */
    public function purchaseOrders()
    {
        $purchaseOrders = PurchaseOrder::with(['order', 'partner'])
            ->where('status', 'approved')
            ->get();

        return view('invoices.purchase_orders', compact('purchaseOrders'));
    }

    /**
     * عرض أوامر الصرف التي يمكن إنشاء فواتير منها
     */
    public function salesOrders()
    {
        $salesOrders = SalesOrder::with(['order', 'partner'])
            ->where('status', 'approved')
            ->get();

        return view('invoices.sales_orders', compact('salesOrders'));
    }

    /**
     * عرض نموذج إنشاء فاتورة من أمر شراء
     */
    public function createFromPurchaseOrder($id)
    {
        $purchaseOrder = PurchaseOrder::with(['order.order_details.product', 'partner'])
            ->findOrFail($id);
        // التحقق من أن أمر الشراء معتمد
        if ($purchaseOrder->status !== 'approved') {
            return redirect()->route('invoices.purchase-orders')
                ->with('error', 'يمكن إنشاء فواتير فقط من أوامر الشراء المعتمدة');
        }

        $partners = Partner::select('id', 'name')->get();
        $products = Product::select('id', 'name', 'selling_price', 'unit_id', 'barcode', 'sku')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();
        $branches = Branch::select('id', 'name')->get();
        $warehouses = Warehouse::ForUserWarehouse()->get();
        $units = Unit::all();
        $currencies = Currency::all();

        return view('invoices.create_from_purchase_order', compact(
            'purchaseOrder',
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
     * حفظ فاتورة جديدة من أمر شراء
     */
    public function storeFromPurchaseOrder(Request $request, $id)
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

            // التحقق من تاريخ الإنتاج
            'items.*.production_date' => ['nullable', 'date', new AfterSystemStartDate()],

            // التحقق من تاريخ الانتهاء
            'items.*.expiration_date' => ['nullable', 'date', new AfterSystemStartDate(), new ValidExpirationDate()],

        ]);

        DB::beginTransaction();

        try {
            // تحقق من تواريخ الإنتاج والانتهاء لكل صنف
            foreach ($request->items as $item) {
                if ($item['production_date'] && $item['expiration_date']) {
                    if (strtotime($item['expiration_date']) < strtotime($item['production_date'])) {
                        return redirect()->back()->with('error', 'تاريخ الانتهاء يجب أن يكون بعد تاريخ الإنتاج.');
                    }
                }
            }
            $purchaseOrder = PurchaseOrder::with('order')->findOrFail($id);

            if ($purchaseOrder->status !== 'approved') {
                return redirect()->route('invoices.purchase-orders')
                    ->with('error', 'يمكن إنشاء فواتير فقط من أوامر الشراء المعتمدة');
            }

            $typeNumber = 2; // شراء
            $prefix = 'Pu-Inv-';
            $transactionType = 'purchase';

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
                // 'branch_id' => $request->branch_id,
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
                'order_id' => $purchaseOrder->order_id,
                'purchase_order_id' => $purchaseOrder->id,
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

            $transactionNote = 'فاتورة شراء مرتبطة بأمر شراء رقم: ' . $purchaseOrder->order_number;

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

            $purchaseOrder->update([
                'status' => 'completed',
            ]);

            DB::commit();

            // return redirect('/invoices?type=purchase')
            return redirect()->route('invoices.purchase-orders')
                ->with('success', 'تم إنشاء الفاتورة والحركة المخزنية بنجاح!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'خطأ في إنشاء الفاتورة: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج إنشاء فاتورة من أمر صرف
     */
    public function createFromSalesOrder($id)
    {
        $salesOrder = SalesOrder::with(['order.order_details.product', 'partner'])
            ->findOrFail($id);

        // التحقق من أن أمر الصرف معتمد
        if ($salesOrder->status !== 'approved') {
            return redirect()->route('invoices.sales-orders')
                ->with('error', 'يمكن إنشاء فواتير فقط من أوامر الصرف المعتمدة');
        }

        $partners = Partner::select('id', 'name')->get();
        $products = Product::select('id', 'name', 'selling_price', 'unit_id')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();
        $branches = Branch::select('id', 'name')->get();
        $warehouses = Warehouse::ForUserWarehouse()->get();
        // dd($warehouses);
        $units = Unit::all();
        $currencies = Currency::all();

        return view('invoices.create_from_sales_order', compact(
            'salesOrder',
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
     * حفظ فاتورة جديدة من أمر صرف
     */ 
/**
 * تنشئ حركة مخزنية وتحدث المخزون (زيادة blocked_quantity عند البيع).
 */
private function storeInventoryTransaction($request, $invoiceCode, $items, $type, $transactionNote)
{
    $inventoryTransaction = InventoryTransaction::create([
        'transaction_type_id'   => ($type === 'sale') ? 7 : 1,
        'effect'                => ($type === 'sale') ? -1 : 1,
        'transaction_date'      => now(),
        'reference'             => $invoiceCode,
        'partner_id'            => $request->partner_id,
        'warehouse_id'          => $request->warehouse_id,
        'branch_id'             => $request->branch_id,
        'department_id'         => null,
        'inventory_request_id'  => null,
        'secondary_warehouse_id'=> null,
        'notes'                 => $transactionNote,
        'status'                => 0,
    ]);

    $effect = ($type === 'sale') ? -1 : 1;

    if (is_iterable($items) && count($items) > 0) {
        foreach ($items as $item) {
            // استخراج البيانات من $item
            $productId      = $item->product_id ?? $item['product_id'] ?? null;
            $unitId         = $item->unit_id    ?? $item['unit_id']    ?? null;
            $quantity       = $item->quantity   ?? $item['quantity']   ?? null;
            $price          = $item->price      ?? $item['price']      ?? null;
            $itemId         = $item->id         ?? $item['id']         ?? null;
            $productionDate = $item->production_date ?? $item['production_date'] ?? null;
            $expirationDate = $item->expiration_date ?? $item['expiration_date'] ?? null;

            if (! $productId || ! $unitId || ! $quantity || ! $price) {
                continue;
            }

            // تحقق من التوفر قبل البيع
            if ($type === 'sale') {
                $result = $this->isQuantityAvailable($item, $request->warehouse_id);
                if ($result !== true) {
                    if (DB::transactionLevel() > 0) {
                        DB::rollBack();
                    }
                    throw new \Exception("الكمية المطلوبة للمنتج رقم {$productId} غير متوفرة.");
                }
            }

            // تحويل الكمية للوحدة الأساسية
            $product = Product::findOrFail($productId);
            $baseUnitId = $product->unit_id;
            $convertedOutQuantity = $this->inventoryCalculationService
                ->calculateConvertedQuantity($quantity, $unitId, $baseUnitId);

            // إنشاء بند الحركة
            InventoryTransactionItem::create([
                'inventory_transaction_id' => $inventoryTransaction->id,
                'unit_id'                  => $unitId,
                'unit_product_id'          => $baseUnitId,
                'target_warehouse_id'      => $request->warehouse_id,
                'converted_quantity'       => $effect * $convertedOutQuantity,
                'product_id'               => $productId,
                'unit_prices'              => $effect * $price,
                'quantity'                 => $effect * $quantity,
                'total'                    => $effect * $quantity * $price,
                'converted_price'          => $effect * $price,
                'branch_id'                => $request->branch_id,
                'reference_item_id'        => $itemId,
                'production_date'          => $productionDate,
                'expiration_date'          => $expirationDate,
            ]);

            // تحديث المخزون: زيادة blocked_quantity عند البيع
            $inventory = Inventory::where('product_id', $productId)
                                  ->where('warehouse_id', $request->warehouse_id)
                                  ->first();

            if ($inventory) {
                if ($type === 'sale') {
                    $inventory->update([
                        'blocked_quantity' => $inventory->blocked_quantity + $convertedOutQuantity,
                    ]);
                } 
            }
        }
    }

    return $inventoryTransaction;
}

/**
 * تنشئ فاتورة من أمر صرف جاهز، وتربطها بحركة مخزنية.
 */
public function storeFromSalesOrder(Request $request, $id)
{
    $request->validate([
        'partner_id'        => 'required|exists:partners,id',
        'invoice_date'      => 'required|date',
        'payment_type_id'   => 'required|exists:payment_types,id',
        'warehouse_id'      => 'required|exists:warehouses,id',
        'items'             => 'required|array',
        'items.*.product_id'=> 'required|exists:products,id',
        'items.*.quantity'  => 'required|integer|min:1',
        'items.*.price'     => 'required|numeric|min:0',
        'items.*.unit_id'   => 'required|exists:units,id',
        'discount_type'     => 'required|integer|in:1,2',
        'discount_value'    => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();

    try {
        $salesOrder = SalesOrder::with('order')->findOrFail($id);
        if ($salesOrder->status !== 'approved') {
            return redirect()->route('invoices.sales-orders')
                             ->with('error', 'يمكن إنشاء فواتير فقط من أوامر الصرف المعتمدة');
        }

        // إعداد ترميز الفاتورة
        $typeNumber      = 1; // بيع
        $prefix          = 'Sa-Inv-';
        $transactionType = 'sale';
        $lastInvoice     = Invoice::where('type', $typeNumber)
                                  ->latest('id')
                                  ->first();
        $nextNumber      = $lastInvoice
            ? intval(substr($lastInvoice->invoice_code, strlen($prefix))) + 1
            : 1;
        $invoiceCode = $prefix . $nextNumber;

        // حساب المجموع والخصم
        $totalAmount = array_reduce($request->items, function($sum, $i) {
            return $sum + ($i['quantity'] * $i['price']);
        }, 0);

        $discountType  = (int)$request->discount_type;
        $discountValue = (float)$request->discount_value;

        if ($discountType === 1) {
            $discountAmount     = $discountValue;
            $discountPercentage = $totalAmount > 0
                ? ($discountValue / $totalAmount) * 100
                : 0;
        } else {
            $discountPercentage = $discountValue;
            $discountAmount     = ($discountValue / 100) * $totalAmount;
        }

        // إنشاء الفاتورة
        $invoice = Invoice::create([
            'invoice_code'       => $invoiceCode,
            'partner_id'         => $request->partner_id,
            'invoice_date'       => $request->invoice_date,
            'payment_type_id'    => $request->payment_type_id,
            'total_amount'       => $totalAmount - $discountAmount,
            'check_number'       => $request->check_number,
            'discount_type'      => $discountType,
            'discount_amount'    => $discountAmount,
            'discount_percentage'=> $discountPercentage,
            'type'               => $typeNumber,
            'warehouse_id'       => $request->warehouse_id,
            'currency_id'        => $request->currency_id,
            'exchange_rate'      => $request->exchange_rate,
            'department_id'      => $request->department_id,
            'order_id'           => $salesOrder->order_id,
            'sales_order_id'     => $salesOrder->id,
        ]);

        // إنشاء بنود الفاتورة
        foreach ($request->items as $item) {
            InvoiceItem::create([
                'invoice_id'      => $invoice->id,
                'product_id'      => $item['product_id'],
                'quantity'        => $item['quantity'],
                'price'           => $item['price'],
                'subtotal'        => $item['quantity'] * $item['price'],
                'unit_id'         => $item['unit_id'],
                'production_date' => $item['production_date'],
                'expiration_date' => $item['expiration_date'],
            ]);
        }

        // ربط الحركة المخزنية
        $transactionNote       = 'فاتورة بيع مرتبطة بأمر صرف رقم: ' . $salesOrder->order_number;
        $inventoryTransaction  = $this->storeInventoryTransaction(
            $request,
            $invoiceCode,
            $invoice->items,
            $transactionType,
            $transactionNote
        );

        // مسح الحقل الوحيد المتبقي
        $invoice->update([
            'inventory_transaction_id' => $inventoryTransaction->id,
        ]);

        // إتمام أمر البيع
        $salesOrder->update(['status' => 'completed']);

        DB::commit();
        session()->flash('success', 'تم إنشاء الفاتورة والحركة المخزنية بنجاح!');
        return redirect()->route('invoices.sales.index');

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'خطأ في إنشاء الفاتورة: ' . $e->getMessage());
    }
}

    /* التحقق من توفر الكمية المطلوبة في المخزون
     */
    private function isQuantityAvailable($item, $warehouseId)
    {
        $productId = is_object($item) ? $item->product_id : ($item['product_id'] ?? null);
        $requestedQty = is_object($item) ? $item->quantity : ($item['quantity'] ?? null);

        if (!$productId || !$requestedQty) {
            return true;
        }
        
        $availableBlkQty = Inventory::where('product_id', $productId)
        ->where('warehouse_id', $warehouseId)
        ->sum('blocked_quantity');

        $availableBlkQty = $availableBlkQty ?? 0; // إذا كانت null أو غير معرفة، يتم إسناد 0

        $availableQty = Inventory::where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->sum('quantity');

        if ($requestedQty - $availableBlkQty > $availableQty) {
            return "الكمية المطلوبة للمنتج رقم {$productId} غير متوفرة (المتوفر: {$availableQty}).";
        }

        return true;
    }
}

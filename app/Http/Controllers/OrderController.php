<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Branch;
use App\Models\PaymentType;
use App\Models\OrderDetail;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\Unit;
use App\Models\InventoryProduct;
use App\Models\Warehouse;

use DB;

class OrderController extends Controller
{
    // دالة لعرض جميع الطلبات
    public function index(Request $request)
    {
        $search = $request->input('search');

        $orders = Order::with('branch', 'paymentType', 'order_details.product', 'order_details.unit', 'warehouse')
            ->where(function ($query) use ($search) {
                $query->where('type', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }


    // دالة لإظهار نموذج إضافة طلب جديد
    public function create(Request $request)
    {
        $products = collect();
        $warehouseId = $request->get('warehouse_id');
        $type = $request->get('type');
        if ($warehouseId) {
            if ($type == 'buy') {

                $products = Product::select('id', 'name', 'selling_price', 'unit_id', 'barcode', 'sku')
                    ->with('unit')
                    ->get();
            } else {

                $products = InventoryProduct::with('product')
                    ->where('warehouse_id', $warehouseId)
                    ->where('quantity', '>', 0)
                    ->get()
                    ->map(function ($item) {
                        return (object) [
                            'id' => $item->product->id,
                            'name' => $item->product->name,
                            'barcode' => $item->product->barcode,
                            'sku' => $item->product->sku,
                            'selling_price' => $item->product->selling_price,
                            'unit_id' => $item->product->unit_id,
                            'production_date' => $item->production_date,
                            'expiration_date' => $item->expiration_date,
                        ];
                    });
            }
        }
        $units = Unit::select('id', 'name')->get();

        $paymentTypes = PaymentType::select('id', 'name')->get();
        $warehouses = Warehouse::ForUserWarehouse()->select('id', 'name')->get();
        $partners = \App\Models\Partner::select('id', 'name')->get();


        return view('orders.create', compact('products', 'units', 'warehouses', 'paymentTypes', 'partners'));
    }

    // دالة لحفظ الطلب الجديد
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'type' => 'required|in:buy,sell',
            'status' => 'required|in:pending,confirmed,completed,canceled',
            'payment_type_id' => 'required|exists:payment_types,id',
            'partner_id' => 'nullable|exists:partners,id',
            'order_details' => 'required|array|min:1',
            'order_details.*.product_id' => 'required|exists:products,id',
            'order_details.*.quantity' => 'required|integer|min:1',
            'order_details.*.price' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            // إنشاء رقم أمر شراء فريد
            $purchaseOrderNumber = null;
            if ($request->type === 'buy') {
                $purchaseOrderNumber = 'PO-' . date('Ymd') . '-' . rand(1000, 9999);
            }

            $order = Order::create([
                'type' => $request->type,
                'status' => 'pending',
                'payment_type_id' => $request->payment_type_id,
                'branch_id' => $request->branch_id,
                'partner_id' => $request->partner_id,
                'purchase_order_number' => $purchaseOrderNumber,
                'is_printed' => false,
                'warehouse_id' => $request->warehouse_id,
            ]);

            foreach ($request->order_details as $detail) {
                $order->order_details()->create([
                    'product_id' => $detail['product_id'],
                    'unit_id' => $detail['unit_id'],
                    'quantity' => $detail['quantity'],
                    'price' => $detail['price'],

                ]);
            }

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'تم إضافة الطلب بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error creating order: ' . $e->getMessage());
        }
    }

    // دالة لتعديل الطلب (إظهار النموذج للتعديل)
    public function edit($id)
    {
        // جلب الطلب المراد تعديله
        $order = Order::findOrFail($id);
        $units = \App\Models\Unit::select('id', 'name')->get();

        // جلب جميع البيانات المطلوبة للتعديل مثل المنتجات والفروع وأنواع الدفع
        $products = Product::select('id', 'name', 'selling_price', 'unit_id')->with('unit')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();
        $Branchs = Branch::select('id', 'name')->get();
        $warehouses = Warehouse::ForUserWarehouse()->select('id', 'name')->get();

        $partners = \App\Models\Partner::select('id', 'name')->get();

        // عرض النموذج مع البيانات الحالية
        return view('orders.edit', compact('order', 'products', 'Branchs', 'paymentTypes', 'partners', 'units', 'warehouses'));
    }

    // دالة لتحديث الطلب بعد التعديل
    public function update(Request $request, $id)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'type' => 'required|in:buy,sell',
            'status' => 'required|in:pending,confirmed,completed,canceled',
            'payment_type_id' => 'required|exists:payment_types,id',
            // 'branch_id' => 'required|exists:branches,id',
            'partner_id' => 'nullable|exists:partners,id',
            'order_details' => 'required|array|min:1',
            'order_details.*.product_id' => 'required|exists:products,id',
            'order_details.*.quantity' => 'required|integer|min:1',
            'order_details.*.price' => 'required|numeric|min:0.01',
        ]);

        try {
            DB::beginTransaction();

            // جلب الطلب المراد تحديثه
            $order = Order::findOrFail($id);

            // تحديث رقم أمر الشراء إذا تغير النوع إلى شراء ولم يكن هناك رقم سابق
            $purchaseOrderNumber = $order->purchase_order_number;
            if ($request->type === 'buy' && !$purchaseOrderNumber) {
                $purchaseOrderNumber = 'PO-' . date('Ymd') . '-' . rand(1000, 9999);
            } elseif ($request->type !== 'buy') {
                $purchaseOrderNumber = null;
            }

            $order->update([
                'type' => $request->type,
                'status' => $request->status,
                'payment_type_id' => $request->payment_type_id,
                'branch_id' => $request->branch_id,
                'partner_id' => $request->partner_id,
                'purchase_order_number' => $purchaseOrderNumber,
            ]);

            // تحديث تفاصيل الطلب
            $order->order_details()->delete();  // حذف التفاصيل القديمة
            foreach ($request->order_details as $detail) {
                $order->order_details()->create([
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'price' => $detail['price'],
                    'unit_id' => $detail['unit_id'],
                ]);
            }

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'تم تحديث الطلب بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Error updating order: ' . $e->getMessage());
        }
    }

    // دالة لحذف الطلب
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);
            $order->order_details()->delete(); // حذف تفاصيل الطلب المرتبطة بالطلب

            $order->delete();  // حذف الطلب

            DB::commit();
            return redirect()->route('orders.index')->with('success', 'Order deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting order: ' . $e->getMessage());
        }
    }

    // دالة لعرض طلبات الشراء والبيع التي تحتاج إلى موافقة
    public function pendingApproval(Request $request)
    {
        $type = $request->input('type', 'buy'); // القيمة الافتراضية هي 'buy'

        // جلب الطلبات المعلقة حسب النوع
        $orders = Order::with('branch', 'paymentType', 'order_details.product', 'partner')
            ->where('type', $type)
            ->where('status', 'pending')
            ->paginate(10);

        return view('orders.pending_approval', compact('orders', 'type'));
    }

    // دالة للموافقة على الطلب
    public function approve($id)
    {
        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);

            // تسجيل معلومات الطلب قبل التحديث
            \Illuminate\Support\Facades\Log::info('Order before approval', [
                'id' => $order->id,
                'type' => $order->type,
                'status' => $order->status,
                'purchase_order_number' => $order->purchase_order_number
            ]);

            // التحقق من أن حالة الطلب معلقة
            if ($order->status !== 'pending') {
                \Illuminate\Support\Facades\Log::warning('Cannot approve order', [
                    'id' => $order->id,
                    'type' => $order->type,
                    'status' => $order->status
                ]);
                return back()->with('error', 'لا يمكن الموافقة على هذا الطلب');
            }

            // معالجة طلب الشراء
            if ($order->type === 'buy') {
                // إنشاء رقم أمر شراء
                $prefix = 'PO-';
                $lastOrder = Order::where('type', 'buy')
                    ->where('purchase_order_number', 'LIKE', $prefix . '%')
                    ->orderBy('id', 'desc')
                    ->first();

                $nextNumber = 1;
                if ($lastOrder && $lastOrder->purchase_order_number) {
                    $lastNumber = intval(substr($lastOrder->purchase_order_number, strlen($prefix)));
                    $nextNumber = $lastNumber + 1;
                }

                $orderNumber = $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }
            // معالجة طلب البيع
            else if ($order->type === 'sell') {
                // إنشاء رقم أمر صرف
                $prefix = 'SO-';
                $lastOrder = Order::where('type', 'sell')
                    ->where('purchase_order_number', 'LIKE', $prefix . '%')
                    ->orderBy('id', 'desc')
                    ->first();

                $nextNumber = 1;
                if ($lastOrder && $lastOrder->purchase_order_number) {
                    $lastNumber = intval(substr($lastOrder->purchase_order_number, strlen($prefix)));
                    $nextNumber = $lastNumber + 1;
                }

                $orderNumber = $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            } else {
                \Illuminate\Support\Facades\Log::warning('Unknown order type', [
                    'id' => $order->id,
                    'type' => $order->type
                ]);
                return back()->with('error', 'نوع الطلب غير معروف');
            }

            // تغيير حالة الطلب إلى مؤكد وإضافة رقم الطلب
            $order->update([
                'status' => 'confirmed',
                'purchase_order_number' => $orderNumber,
            ]);

            // تسجيل معلومات الطلب بعد التحديث
            \Illuminate\Support\Facades\Log::info('Order after approval', [
                'id' => $order->id,
                'type' => $order->type,
                'status' => $order->status,
                'purchase_order_number' => $orderNumber
            ]);

            DB::commit();
            // تغيير وجهة الانتقال بعد الموافقة على الطلب
            return redirect()->route('orders.check-confirmed')->with('success', 'تمت الموافقة على الطلب بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error approving order', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'خطأ في الموافقة على الطلب: ' . $e->getMessage());
        }
    }

    // دالة لطباعة أمر الشراء
    public function printPurchaseOrder($id)
    {
        $order = Order::with('branch', 'paymentType', 'order_details.product', 'partner')
            ->findOrFail($id);

        // التحقق من أن الطلب هو طلب شراء وحالته مؤكدة
        if ($order->type !== 'buy' || $order->status !== 'confirmed') {
            return back()->with('error', 'لا يمكن طباعة أمر شراء لهذا الطلب');
        }

        // تحديث حالة الطباعة
        $order->update([
            'is_printed' => true,
        ]);

        return view('orders.print_purchase_order', compact('order'));
    }

    // دالة لطباعة أمر الصرف
    public function printSalesOrder($id)
    {
        $order = Order::with('branch', 'paymentType', 'order_details.product', 'partner')
            ->findOrFail($id);

        if ($order->type !== 'sell' || $order->status !== 'confirmed') {
            return back()->with('error', 'لا يمكن طباعة أمر صرف لهذا الطلب');
        }

        // تحديث حالة الطباعة
        $order->update([
            'is_printed' => true
        ]);

        return view('orders.print_sales_order', compact('order'));
    }

    // دالة للتحقق من الطلبات المؤكدة
    public function checkConfirmedOrders(Request $request)
    {
        $type = $request->input('type', 'all'); // القيمة الافتراضية هي 'all'

        // جلب جميع الطلبات
        $allOrders = Order::all();

        // تسجيل معلومات جميع الطلبات
        foreach ($allOrders as $order) {
            \Illuminate\Support\Facades\Log::info('Order details', [
                'id' => $order->id,
                'type' => $order->type,
                'status' => $order->status,
                'purchase_order_number' => $order->purchase_order_number
            ]);
        }

        // تحديث جميع طلبات الشراء المعلقة إلى مؤكدة (للاختبار فقط)
        $pendingOrders = Order::where('status', 'pending');

        if ($type !== 'all') {
            $pendingOrders->where('type', $type);
        }

        $pendingOrders = $pendingOrders->get();

        foreach ($pendingOrders as $order) {
            // إنشاء رقم أمر شراء أو بيع
            $prefix = $order->type === 'buy' ? 'PO-' : 'SO-';
            $lastOrder = Order::where('type', $order->type)
                ->where('purchase_order_number', 'LIKE', $prefix . '%')
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = 1;
            if ($lastOrder && $lastOrder->purchase_order_number) {
                $lastNumber = intval(substr($lastOrder->purchase_order_number, strlen($prefix)));
                $nextNumber = $lastNumber + 1;
            }

            $orderNumber = $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

            // تحديث الطلب
            $order->update([
                'status' => 'confirmed',
                'purchase_order_number' => $orderNumber,
            ]);

            \Illuminate\Support\Facades\Log::info('Updated order', [
                'id' => $order->id,
                'type' => $order->type,
                'status' => 'confirmed',
                'purchase_order_number' => $orderNumber
            ]);
        }

        // جلب الطلبات المؤكدة بعد التحديث
        $confirmedOrdersQuery = Order::where('status', 'confirmed')
            ->with('branch', 'paymentType', 'order_details.product', 'partner');

        if ($type !== 'all') {
            $confirmedOrdersQuery->where('type', $type);
        }

        $confirmedOrders = $confirmedOrdersQuery->get();

        return view('orders.confirmed_orders', [
            'orders' => $confirmedOrders,
            'allOrders' => $allOrders,
            'pendingCount' => $pendingOrders->count(),
            'confirmedCount' => $confirmedOrders->count(),
            'type' => $type
        ]);
    }

    // دالة لإضافة الحركة للمخزون (تم الإبقاء عليها كما هي)
    protected function updateInventory($item, $transactionType)
    {
        InventoryTransaction::create([
            'order_id' => $item->order_id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price' => $item->price,
            'transaction_type_id' => 1, // إما 'in' أو 'out'
        ]);

        // تحديث المخزون الفعلي للمنتج
        $product = Product::find($item->product_id);
        if ($transactionType === 'in') {
            $product->stock += $item->quantity; // زيادة المخزون
        } else {
            $product->stock -= $item->quantity; // تقليل المخزون
        }
        $product->save();
    }
}

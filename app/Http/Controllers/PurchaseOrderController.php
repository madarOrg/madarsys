<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PurchaseOrder;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    /**
     * عرض قائمة أوامر الشراء
     */
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['order', 'partner'])->latest()->get();
        return view('purchase-orders.index', compact('purchaseOrders'));
    }

    /**
     * عرض نموذج إنشاء أمر شراء جديد من طلب موجود
     */
    public function create($orderId)
    {
        $order = Order::with(['order_details.product', 'partner'])->findOrFail($orderId);
        
        // التحقق من أن الطلب من نوع شراء
        if ($order->type !== 'buy') {
            return redirect()->back()->with('error', 'يمكن إنشاء أوامر الشراء فقط من طلبات الشراء');
        }
        
        // التحقق من أن الطلب مؤكد
        if ($order->status !== 'confirmed') {
            return redirect()->back()->with('error', 'يجب أن يكون الطلب في حالة مؤكدة لإنشاء أمر شراء');
        }
        
        return view('purchase-orders.create', compact('order'));
    }

    /**
     * تخزين أمر شراء جديد في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'partner_id' => 'required|exists:partners,id',
            'issue_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:issue_date',
            'notes' => 'nullable|string',
        ]);
        
        // التحقق من الطلب
        $order = Order::findOrFail($request->order_id);
        if ($order->type !== 'buy' || $order->status !== 'confirmed') {
            return redirect()->back()->with('error', 'الطلب غير صالح لإنشاء أمر شراء');
        }
        
        // إنشاء رقم فريد لأمر الشراء
        $lastPurchaseOrder = PurchaseOrder::latest('id')->first();
        $lastId = $lastPurchaseOrder ? $lastPurchaseOrder->id + 1 : 1;
        $orderNumber = 'PO-' . date('Ymd') . '-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);
        
        // إنشاء أمر الشراء
        $purchaseOrder = PurchaseOrder::create([
            'order_number' => $orderNumber,
            'order_id' => $request->order_id,
            'partner_id' => $request->partner_id,
            'status' => 'pending',
            'issue_date' => $request->issue_date,
            'expected_delivery_date' => $request->expected_delivery_date,
            'notes' => $request->notes,
            'created_user' => Auth::id(),
        ]);
        
        // تحديث الطلب الأصلي بإضافة رقم أمر الشراء
        $order->update([
            'purchase_order_number' => $orderNumber,
        ]);
        
        return redirect()->route('purchase-orders.show', $purchaseOrder->id)
            ->with('success', 'تم إنشاء أمر الشراء بنجاح');
    }

    /**
     * عرض تفاصيل أمر شراء محدد
     */
    public function show($id)
    {
        $purchaseOrder = PurchaseOrder::with(['order.order_details.product', 'partner'])->findOrFail($id);
        
        // البحث عن فاتورة مرتبطة بأمر الشراء إذا كان مكتملاً
        $invoice = null;
        if ($purchaseOrder->status === 'completed') {
            $invoice = \App\Models\Invoice::where('purchase_order_id', $purchaseOrder->id)->first();
        }
        
        return view('purchase-orders/show', compact('purchaseOrder', 'invoice'));
    }

    /**
     * عرض نموذج تعديل أمر شراء
     */
    public function edit($id)
    {
        $purchaseOrder = PurchaseOrder::with(['order.order_details.product', 'partner'])->findOrFail($id);
        
        // التحقق من أن أمر الشراء ليس مكتملاً أو ملغياً
        if (in_array($purchaseOrder->status, ['completed', 'canceled'])) {
            return redirect()->back()->with('error', 'لا يمكن تعديل أمر شراء مكتمل أو ملغي');
        }
        
        return view('purchase-orders.edit', compact('purchaseOrder'));
    }

    /**
     * تحديث أمر شراء محدد في قاعدة البيانات
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'partner_id' => 'required|exists:partners,id',
            'status' => 'required|in:pending,approved,completed,canceled',
            'issue_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date|after_or_equal:issue_date',
            'notes' => 'nullable|string',
        ]);
        
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        
        // التحقق من أن أمر الشراء ليس مكتملاً أو ملغياً
        if (in_array($purchaseOrder->status, ['completed', 'canceled'])) {
            return redirect()->back()->with('error', 'لا يمكن تعديل أمر شراء مكتمل أو ملغي');
        }
        
        $purchaseOrder->update([
            'partner_id' => $request->partner_id,
            'status' => $request->status,
            'issue_date' => $request->issue_date,
            'expected_delivery_date' => $request->expected_delivery_date,
            'notes' => $request->notes,
            'updated_user' => Auth::id(),
        ]);
        
        return redirect()->route('purchase-orders.show', $purchaseOrder->id)
            ->with('success', 'تم تحديث أمر الشراء بنجاح');
    }

    /**
     * حذف أمر شراء محدد من قاعدة البيانات
     */
    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        
        // التحقق من أن أمر الشراء ليس مرتبطاً بفواتير
        if ($purchaseOrder->invoices()->exists()) {
            return redirect()->back()->with('error', 'لا يمكن حذف أمر شراء مرتبط بفواتير');
        }
        
        // إزالة رقم أمر الشراء من الطلب الأصلي
        $order = $purchaseOrder->order;
        $order->update([
            'purchase_order_number' => null,
        ]);
        
        $purchaseOrder->delete();
        
        return redirect()->route('purchase-orders.index')
            ->with('success', 'تم حذف أمر الشراء بنجاح');
    }

    /**
     * طباعة أمر الشراء
     */
    public function print($id)
    {
        $purchaseOrder = PurchaseOrder::with(['order.order_details.product', 'partner'])->findOrFail($id);
        
        // تحديث حالة الطباعة
        $purchaseOrder->update([
            'is_printed' => true,
        ]);
        
        return view('purchase-orders.print', compact('purchaseOrder'));
    }

    /**
     * تغيير حالة أمر الشراء إلى معتمد
     */
    public function approve($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        
        // التحقق من أن أمر الشراء ليس مكتملاً أو ملغياً
        if (in_array($purchaseOrder->status, ['completed', 'canceled'])) {
            return redirect()->back()->with('error', 'لا يمكن اعتماد أمر شراء مكتمل أو ملغي');
        }
        
        $purchaseOrder->update([
            'status' => 'approved',
            'updated_user' => Auth::id(),
        ]);
        
        return redirect()->route('purchase-orders.show', $purchaseOrder->id)
            ->with('success', 'تم اعتماد أمر الشراء بنجاح');
    }
}

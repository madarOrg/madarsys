<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\SalesOrder;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesOrderController extends Controller
{
    /**
     * عرض قائمة أوامر الصرف (البيع)
     */
    public function index()
    {
        $salesOrders = SalesOrder::with(['order', 'partner'])->latest()->get();
        return view('sales-orders.index', compact('salesOrders'));
    }

    /**
     * عرض نموذج إنشاء أمر صرف جديد من طلب موجود
     */
    public function create($orderId)
    {
        $order = Order::with(['order_details.product', 'partner'])->findOrFail($orderId);
        
        // التحقق من أن الطلب من نوع بيع
        if ($order->type !== 'sell') {
            return redirect()->back()->with('error', 'يمكن إنشاء أوامر الصرف فقط من طلبات البيع');
        }
        
        // التحقق من أن الطلب مؤكد
        if ($order->status !== 'confirmed') {
            return redirect()->back()->with('error', 'يجب أن يكون الطلب في حالة مؤكدة لإنشاء أمر صرف');
        }
        
        return view('sales-orders.create', compact('order'));
    }

    /**
     * تخزين أمر صرف جديد في قاعدة البيانات
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
        if ($order->type !== 'sell' || $order->status !== 'confirmed') {
            return redirect()->back()->with('error', 'الطلب غير صالح لإنشاء أمر صرف');
        }
        
        // إنشاء رقم فريد لأمر الصرف
        $lastSalesOrder = SalesOrder::latest('id')->first();
        $lastId = $lastSalesOrder ? $lastSalesOrder->id + 1 : 1;
        $orderNumber = 'SO-' . date('Ymd') . '-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);
        
        // إنشاء أمر الصرف
        $salesOrder = SalesOrder::create([
            'order_number' => $orderNumber,
            'order_id' => $request->order_id,
            'partner_id' => $request->partner_id,
            'status' => 'pending',
            'issue_date' => $request->issue_date,
            'expected_delivery_date' => $request->expected_delivery_date,
            'notes' => $request->notes,
            'created_user' => Auth::id(),
        ]);
        
        // تحديث الطلب الأصلي بإضافة رقم أمر الصرف
        // نضيف رقم أمر الصرف في حقل مناسب إذا كان موجوداً
        // في هذه الحالة نحفظ رقم أمر الصرف في نفس حقل رقم أمر الشراء
        $order->update([
            'purchase_order_number' => $orderNumber,
        ]);
        
        return redirect()->route('sales-orders.show', $salesOrder->id)
            ->with('success', 'تم إنشاء أمر الصرف بنجاح');
    }

    /**
     * عرض تفاصيل أمر صرف محدد
     */
    public function show($id)
    {
        $salesOrder = SalesOrder::with(['order.order_details.product', 'partner'])->findOrFail($id);
        
        // البحث عن فاتورة مرتبطة بأمر الصرف إذا كان مكتملاً
        $invoice = null;
        if ($salesOrder->status === 'completed') {
            $invoice = \App\Models\Invoice::where('sales_order_id', $salesOrder->id)->first();
        }
        
        return view('sales-orders/show', compact('salesOrder', 'invoice'));
    }

    /**
     * عرض نموذج تعديل أمر صرف
     */
    public function edit($id)
    {
        $salesOrder = SalesOrder::with(['order.order_details.product', 'partner'])->findOrFail($id);
        
        // التحقق من أن أمر الصرف ليس مكتملاً أو ملغياً
        if (in_array($salesOrder->status, ['completed', 'canceled'])) {
            return redirect()->back()->with('error', 'لا يمكن تعديل أمر صرف مكتمل أو ملغي');
        }
        
        return view('sales-orders.edit', compact('salesOrder'));
    }

    /**
     * تحديث أمر صرف محدد في قاعدة البيانات
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
        
        $salesOrder = SalesOrder::findOrFail($id);
        
        // التحقق من أن أمر الصرف ليس مكتملاً أو ملغياً
        if (in_array($salesOrder->status, ['completed', 'canceled'])) {
            return redirect()->back()->with('error', 'لا يمكن تعديل أمر صرف مكتمل أو ملغي');
        }
        
        $salesOrder->update([
            'partner_id' => $request->partner_id,
            'status' => $request->status,
            'issue_date' => $request->issue_date,
            'expected_delivery_date' => $request->expected_delivery_date,
            'notes' => $request->notes,
            'updated_user' => Auth::id(),
        ]);
        
        return redirect()->route('sales-orders.show', $salesOrder->id)
            ->with('success', 'تم تحديث أمر الصرف بنجاح');
    }

    /**
     * حذف أمر صرف محدد من قاعدة البيانات
     */
    public function destroy($id)
    {
        $salesOrder = SalesOrder::findOrFail($id);
        
        // التحقق من أن أمر الصرف ليس مرتبطاً بفواتير
        if ($salesOrder->invoices()->exists()) {
            return redirect()->back()->with('error', 'لا يمكن حذف أمر صرف مرتبط بفواتير');
        }
        
        // إزالة رقم أمر الصرف من الطلب الأصلي
        $order = $salesOrder->order;
        $order->update([
            'purchase_order_number' => null,
        ]);
        
        $salesOrder->delete();
        
        return redirect()->route('sales-orders.index')
            ->with('success', 'تم حذف أمر الصرف بنجاح');
    }

    /**
     * طباعة أمر الصرف
     */
    public function print($id)
    {
        $salesOrder = SalesOrder::with(['order.order_details.product', 'partner'])->findOrFail($id);
        
        // تحديث حالة الطباعة
        $salesOrder->update([
            'is_printed' => true,
        ]);
        
        return view('sales-orders.print', compact('salesOrder'));
    }

    /**
     * تغيير حالة أمر الصرف إلى معتمد
     */
    public function approve($id)
    {
        $salesOrder = SalesOrder::findOrFail($id);
        
        // التحقق من أن أمر الصرف ليس مكتملاً أو ملغياً
        if (in_array($salesOrder->status, ['completed', 'canceled'])) {
            return redirect()->back()->with('error', 'لا يمكن اعتماد أمر صرف مكتمل أو ملغي');
        }
        
        $salesOrder->update([
            'status' => 'approved',
            'updated_user' => Auth::id(),
        ]);
        
        return redirect()->route('sales-orders.show', $salesOrder->id)
            ->with('success', 'تم اعتماد أمر الصرف بنجاح');
    }
}

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\InventoryTransaction;
use App\Models\Product;

class OrderController extends Controller
{
    // دالة لعرض جميع الطلبات
    public function index(Request $request)
    {
        // التحقق من وجود قيمة بحث
        $search = $request->input('search');

        // جلب الطلبات مع إمكانية البحث
        $orders = Order::where('type', 'like', "%{$search}%")
            ->orWhere('status', 'like', "%{$search}%")
            ->paginate(10);  // استخدم pagination لجلب الطلبات

        // عرض الصفحة مع تمرير الطلبات
        return view('orders.index', compact('orders'));
    }

    // دالة لإظهار نموذج إضافة طلب جديد
    public function create()
    {
        return view('orders.create');
    }

    // دالة لحفظ الطلب الجديد
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'type' => 'required|in:buy,sell',
            'status' => 'required|in:pending,confirmed,completed,canceled',
            'order_details.*.product_id' => 'required|exists:products,id',
            'order_details.*.quantity' => 'required|integer|min:1',
            'order_details.*.price' => 'required|numeric|min:1',
        ]);

        // إنشاء طلب جديد
        $order = Order::create([
            'type' => $request->type,
            'status' => $request->status,
            'branch_id' => auth()->user()->branch_id, // افتراضياً أن المستخدم مرتبط بفرع
        ]);

        // إضافة تفاصيل الطلب
        foreach ($request->order_details as $detail) {
            $orderDetail = $order->orderDetails()->create([
                'product_id' => $detail['product_id'],
                'quantity' => $detail['quantity'],
                'price' => $detail['price'],
                'total_price' => $detail['quantity'] * $detail['price'],
            ]);

            // تحديث المخزون بناءً على نوع الطلب
            if ($request->type === 'buy') {
                $this->updateInventory($orderDetail, 'in');
            } else {
                $this->updateInventory($orderDetail, 'out');
            }
        }

        return redirect()->route('orders.index')->with('success', 'تم إضافة الطلب بنجاح');
    }

    // دالة لتعديل الطلب (إذا كانت موجودة)
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('orders.edit', compact('order'));
    }

    // دالة لإضافة الحركة للمخزون
    protected function updateInventory(OrderDetail $item, $transactionType)
    {
        // تحديث أو إضافة الحركة المخزنية
        InventoryTransaction::create([
            'order_id' => $item->order_id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price' => $item->price,
            'transaction_type' => $transactionType, // إما 'in' أو 'out'
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


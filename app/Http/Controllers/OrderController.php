<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Branch;
use App\Models\PaymentType;
use App\Models\OrderDetail;
use App\Models\InventoryTransaction;
use App\Models\Product;
use DB;

class OrderController extends Controller
{
    // دالة لعرض جميع الطلبات
    public function index(Request $request)
    {
        // التحقق من وجود قيمة بحث
        $search = $request->input('search');

        // جلب الطلبات مع إمكانية البحث
        $orders = Order::with('branch', 'paymentType', 'order_details.product')
            ->where('type', 'like', "%{$search}%")
            ->orWhere('status', 'like', "%{$search}%")
            ->paginate(10);  // استخدم pagination لجلب الطلبات

        // عرض الصفحة مع تمرير الطلبات
        return view('orders.index', compact('orders'));
    }

    // دالة لإظهار نموذج إضافة طلب جديد
    public function create()
    {  
        $products = Product::select('id', 'name', 'selling_price', 'unit_id')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();
        $Branchs = Branch::select('id', 'name')->get(); 
        
        return view('orders.create', compact('products','Branchs','paymentTypes'));
    }

    // دالة لحفظ الطلب الجديد
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'type' => 'required|in:buy,sell',
            'status' => 'required|in:pending,confirmed,completed,canceled',
            'payment_type_id' => 'required|exists:payment_types,id',
            'branch_id' => 'required|exists:branches,id',
            'order_details' => 'required|array|min:1',
            'order_details.*.product_id' => 'required|exists:products,id',
            'order_details.*.quantity' => 'required|integer|min:1',
            'order_details.*.price' => 'required|numeric|min:0.01',
        ]);
    
        try {
            DB::beginTransaction();
    
            $order = Order::create([
                'type' => $request->type,
                'status' => 'pending',
                'payment_type_id' => $request->payment_type_id,
                'branch_id' => $request->branch_id,
            ]);
    
            foreach ($request->order_details as $detail) {
                 $order->order_details()->create([
                    'product_id' => $detail['product_id'],
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
        
        // جلب جميع البيانات المطلوبة للتعديل مثل المنتجات والفروع وأنواع الدفع
        $products = Product::select('id', 'name', 'selling_price', 'unit_id')->get();
        $paymentTypes = PaymentType::select('id', 'name')->get();
        $Branchs = Branch::select('id', 'name')->get();
        
        // عرض النموذج مع البيانات الحالية
        return view('orders.edit', compact('order', 'products', 'Branchs', 'paymentTypes'));
    }

    // دالة لتحديث الطلب بعد التعديل
    public function update(Request $request, $id)
    {
        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'type' => 'required|in:buy,sell',
            'status' => 'required|in:pending,confirmed,completed,canceled',
            'payment_type_id' => 'required|exists:payment_types,id',
            'branch_id' => 'required|exists:branches,id',
            'order_details' => 'required|array|min:1',
            'order_details.*.product_id' => 'required|exists:products,id',
            'order_details.*.quantity' => 'required|integer|min:1',
            'order_details.*.price' => 'required|numeric|min:0.01',
        ]);
    
        try {
            DB::beginTransaction();
    
            // جلب الطلب المراد تحديثه
            $order = Order::findOrFail($id);
            $order->update([
                'type' => $request->type,
                'status' => $request->status,
                'payment_type_id' => $request->payment_type_id,
                'branch_id' => $request->branch_id,
            ]);
    
            // تحديث تفاصيل الطلب
            $order->order_details()->delete();  // حذف التفاصيل القديمة
            foreach ($request->order_details as $detail) {
                $order->order_details()->create([
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'price' => $detail['price'],
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


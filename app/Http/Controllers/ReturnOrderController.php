<?php

namespace App\Http\Controllers;
use App\Models\ReturnOrder;
use App\Models\ReturnOrderItem;

use Illuminate\Http\Request;

class ReturnOrderController extends Controller
{
    public function index(Request $request)
    {
        $returnOrders = ReturnOrder::with('customer', 'items.product') // جلب بيانات العميل والعناصر مع المنتجات

            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;

                // البحث داخل جدول `return_orders`
                $query->where('return_number', 'like', '%' . $search . '%')
                    ->orWhere('return_reason', 'like', '%' . $search . '%')
                    ->orWhere('return_date', 'like', '%' . $search . '%')

                    // البحث في اسم العميل (Partner model)
                    ->orWhereHas('customer', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })

                    // البحث في المنتجات المرتبطة عبر الجدول الوسيط
                    ->orWhereHas('items.product', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->paginate(10);  // تحديد عدد العناصر لكل صفحة

        return view('returns-management.index', compact('returnOrders'));

    }
    public function show(string $id, Request $request)
{
    // جلب المرتجع مع العناصر المرتبطة
    $returnOrder = ReturnOrder::with('items') 
        ->where('id', $id) // تصفية حسب رقم المرتجع
        ->firstOrFail(); // جلب أول مرتجع أو إرجاع خطأ إذا لم يتم العثور عليه

    // بناء الاستعلام لتصفية العناصر المرتبطة بالمرتجع مع المنتج
    $returnOrderItems = ReturnOrderItem::with('product')
        ->where('return_order_id', $id) // تصفية حسب رقم المرتجع
        ->where('Is_Send', 0); // تصفية حسب حالة الإرسال

    // إذا كان هناك نص بحث، قم بتصفية العناصر بناءً على اسم المنتج
    if ($request->has('search') && $request->search) {
        $search = strtolower($request->search); // تحويل البحث إلى أحرف صغيرة للمقارنة
        $returnOrderItems = $returnOrderItems->whereHas('product', function ($query) use ($search) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%']);
        });
    }

    $items = $returnOrderItems->paginate(10); // استخدم paginate على الاستعلام

    // إذا لم يتم العثور على العناصر
    if ($items->isEmpty()) {
        return redirect()->route('returns/process')->with('error', 'المرتجع غير موجود');
    }

    // إرجاع العرض مع البيانات
    return view('returns-management.show', compact('returnOrder', 'items'));
}


    public function create()
    {
        return "إنشاء مرتجع جديد";
    }


    public function edit($id)
    {
        return "تعديل المرتجع: " . $id;
    }
}

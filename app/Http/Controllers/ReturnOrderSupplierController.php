<?php

namespace App\Http\Controllers;
use App\Models\ReturnOrder;
use App\Models\ReturnOrderItem;
use App\Models\Partner;
use App\Models\ReturnSuppliers;
use App\Models\ReturnSuppliersOrder;
use App\Models\ReturnSuppliersOrderItem;
use Mpdf\Mpdf;

use Illuminate\Http\Request;

class ReturnOrderSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $returnOrders = ReturnSuppliers::with('supplier', 'product') // جلب بيانات المورد والمنتج
            ->where('Is_Send', 1)
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;

                // البحث داخل جدول `return_suppliers`
                $query->where('return_reason', 'like', '%' . $search . '%')
                    ->orWhere('quantity', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')

                    // البحث في اسم المورد (Partner model)
                    ->orWhereHas('supplier', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })

                    // البحث في المنتجات المرتبطة عبر الجدول الوسيط
                    ->orWhereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->paginate(10);  // تحديد عدد العناصر لكل صفحة

        return view('returns-suppliers.index', compact('returnOrders')); // تمرير البيانات إلى العرض
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get only 'id' and 'name' for customers
        $suppliers = Partner::whereHas('partnerType', function ($query) {
            $query->where('type', 1);  // Assuming '2' refers to the customer type
        })
            ->select('id', 'name')  // Only 'id' and 'name'
            ->get();

        $products = ReturnSuppliers::with('product') //   جلب المنتجات المرتبطة واظهار المنتجات التي حددت انها سترجع الى الموردين
            ->where('Is_Send', 1)
            ->get()
            ->pluck('product'); // Pluck only the products from the result

        return view('returns-suppliers.create', compact('suppliers', 'products'));

    }

    /**
     * ارسال طلب الارجاع بعد تحديد المنتجات التي تحتاج الى الارجاع
     */
    public function store(Request $request)
    {
        // التحقق من صحة البيانات المدخلة
        $validatedData = $request->validate([
            'supplier_id' => 'required|exists:partners,id',
            'return_reason' => 'nullable|string',
            'order_date' => 'nullable|date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.status' => 'required|integer'
        ]);

        // إنشاء سجل جديد في جدول return_suppliers_orders
        $returnOrder = ReturnSuppliersOrder::create([
            'supplier_id' => $validatedData['supplier_id'],
            'status' => 1, // 1 = "Pending" (الحالة المعلقة)
            'return_reason' => $validatedData['return_reason'] ?? null,
            'return_date' => $validatedData['order_date'] ?? null,
        ]);

        // إضافة المنتجات المرتجعة إلى جدول return_suppliers_order_items
        foreach ($validatedData['items'] as $item) {
            ReturnSuppliersOrderItem::create([
                'return_supplier_order_id' => $returnOrder->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'status' => $item['status'], // الحالة التي اختارها المستخدم
            ]);

            $returnSupplierProduct = ReturnSuppliers::where('product_id', $item['product_id'])->first();
            $returnSupplierProduct->status = 'تم ارسال المنتج في طلب ارجاع';
            $returnSupplierProduct->save();
        }

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('return_suppliers.create')->with('success', 'تم إرسال طلب الإرجاع إلى المورد بنجاح');
    }


    /**
     * ارسال المنتجات التالفة او التي تستدعي الارسال الى الى شاشة ارسال طلبات الموردين
     */
    public function sendToSupplier(Request $request, string $id)
    {

        // جلب العنصر بناءً على id
        $returnOrderItem = ReturnOrderItem::where('product_id', $id)->first();


        if ($returnOrderItem) {

            $returnOrderItem->Is_Send = 1;
            $returnOrderItem->save();


            // إدخال البيانات في جدول المرتجعات المرسلة
            ReturnSuppliers::create([
                'return_order_id' => $returnOrderItem->return_order_id,
                'supplier_id' => $returnOrderItem->returnOrder->customer_id,
                'product_id' => $returnOrderItem->product_id,
                'quantity' => $returnOrderItem->quantity,
                'status' => 'معلقة',
                'return_reason' => 'منتجات تالفة',
                'Is_Send' => 1,
            ]);
        } else {
            return redirect()->route('returns_process.index')->with('error', 'المنتج غير موجود.');
        }


        return redirect()->route('return_suppliers.index');

    }

    /**
     * عرض كافة الطلبات التي تم ارسالها الى الموردين مع تتبع حالتها
     */
    public function ordersendToSupplier(Request $request)
    {
        $returnOrders = ReturnSuppliersOrder::with('supplier', 'items.product') // جلب بيانات المورد والمنتج
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;

                // البحث داخل جدول `return_suppliers_orders`
                $query->where('status', 'like', '%' . $search . '%')

                    // البحث في اسم المورد (Partner model)
                    ->orWhereHas('supplier', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })

                    // البحث في المنتجات المرتبطة عبر الجدول الوسيط
                    ->orWhereHas('items.product', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->paginate(10);  // تحديد عدد العناصر لكل صفحة

        return view('returns-suppliers.returnOrders', compact('returnOrders')); // تمرير البيانات إلى العرض
    }


    public function generatePdf($id)
    {
        // جلب تفاصيل الطلب
        $returnOrder = ReturnSuppliersOrder::with('supplier', 'items.product')->findOrFail($id);

        // إنشاء مثيل من MPDF
        $mpdf = new Mpdf();

        // تحميل المحتوى من العرض (الـ View) مع البيانات
        $html = view('returns-suppliers.returnOrderspdf', compact('returnOrder'))->render();

        // إضافة المحتوى إلى الـ PDF
        $mpdf->WriteHTML($html);

        // تحديد مسار الملف في مجلد public
        $filePath = public_path('return_orders/طلب_الإرجاع_' . $returnOrder->id . '.pdf');

        // حفظ الملف في المسار المحدد
        $mpdf->Output($filePath, 'F'); // 'F' تعني حفظه في ملف

        // إرجاع الملف للمستخدم ليتم تحميله مباشرة
        return response()->download($filePath, 'طلب_الإرجاع_' . $returnOrder->id . '.pdf')->deleteFileAfterSend(true);
    }


}


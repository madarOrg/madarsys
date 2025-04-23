<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Product;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    // public function index()
    // {
    //     // عرض جميع الشحنات
    //     $shipments = Shipment::with('product')->get();
    //     return view('shipments.index', compact('shipments'));
    // }
    public function index()
    {
        // التحقق من وجود قيمة بحث
        $search = request('search');

        // جلب الشحنات مع البحث إذا كانت هناك قيمة في مربع البحث
        $shipments = Shipment::with('product')
            ->when($search, function ($query) use ($search) {
                return $query->where('shipment_number', 'like', '%' . $search . '%')
                    ->orWhereHas('product', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    });
            })
            ->get();

        return view('shipments.index', compact('shipments'));
    }


    public function create()
    {
        // عرض قائمة المنتجات عند إنشاء شحنة جديدة
        $products = Product::all();
        return view('shipments.create', compact('products'));
    }

    public function store(Request $request)
    {
        // التحقق من البيانات المدخلة
        $validated = $request->validate([
            'shipment_number' => 'required|unique:shipments',
            'shipment_date' => 'required|date',
            'status' => 'required',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
        ]);

        // إنشاء الشحنة الجديدة
        $shipment = Shipment::create($validated);

        // تحديث مخزون المنتج
        $product = Product::findOrFail($request->product_id);
        // $product->decrement('quantity', $request->quantity);  // يمكنك استخدام هذه السطر لتقليص الكمية في المخزون

        return redirect()->route('shipments.index')
            ->with('success', 'تم إنشاء الشحنة بنجاح!');
    }

    public function show($id)
    {
        // عرض تفاصيل الشحنة
        $shipment = Shipment::findOrFail($id);
        return view('shipments.show', compact('shipment'));
    }

    public function edit($id)
    {
        // عرض صفحة تعديل الشحنة
        $shipment = Shipment::findOrFail($id);
        $products = Product::all();
        return view('shipments.edit', compact('shipment', 'products'));
    }

    public function update(Request $request, $id)
    {
        // التحقق من البيانات المدخلة
        $validated = $request->validate([
            'shipment_number' => 'required|unique:shipments,shipment_number,' . $id,
            'shipment_date' => 'required|date',
            'status' => 'required',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
        ]);

        // تحديث الشحنة
        $shipment = Shipment::findOrFail($id);
        $shipment->update($validated);

        // تحديث مخزون المنتج
        $product = Product::findOrFail($request->product_id);
        // $product->decrement('quantity', $request->quantity);  // يمكنك استخدام هذه السطر لتقليص الكمية في المخزون

        return redirect()->route('shipments.index')
            ->with('success', 'تم تحديث الشحنة بنجاح!');
    }

    public function destroy($id)
    {
        // حذف الشحنة
        $shipment = Shipment::findOrFail($id);

        // يمكنك إضافة تحديث المخزون هنا إذا أردت استعادة الكمية في المخزون.
        // $product = $shipment->product;
        // $product->increment('quantity', $shipment->quantity);  // استعادة الكمية في المخزون عند حذف الشحنة

        $shipment->delete();

        return redirect()->route('shipments.index')
            ->with('success', 'تم حذف الشحنة بنجاح!');
    }

    /**
     * عرض صفحة استلام الشحنة
     */
    public function showReceiveForm($id)
    {
        $shipment = Shipment::findOrFail($id);

        // التحقق من أن الشحنة لم يتم استلامها بعد
        if ($shipment->status === 'delivered') {
            return redirect()->route('shipments.index')
                ->with('error', 'تم استلام هذه الشحنة مسبقاً!');
        }

        return view('shipments.receive', compact('shipment'));
    }

    /**
     * معالجة استلام الشحنة وتحديث المخزون
     */
    public function receive(Request $request, $id)
    {
        $shipment = Shipment::findOrFail($id);

        // التحقق من أن الشحنة لم يتم استلامها بعد
        if ($shipment->status === 'delivered') {
            return redirect()->route('shipments.index')
                ->with('error', 'تم استلام هذه الشحنة مسبقاً!');
        }

        // التحقق من البيانات المدخلة
        $validated = $request->validate([
            'received_quantity' => 'required|integer|min:1|max:' . $shipment->quantity,
            'received_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // تحديث حالة الشحنة
        $shipment->update([
            'status' => 'delivered', // تغيير من 'received' إلى 'delivered' لتتوافق مع تعريف ENUM
            'received_quantity' => $validated['received_quantity'],
            'received_date' => $validated['received_date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        // تحديث مخزون المنتج (إضافة الكمية المستلمة)
        $product = $shipment->product;
        $product->increment('stock_quantity', $validated['received_quantity']);

        // إنشاء حركة مخزنية
        // $inventoryTransaction = new \App\Models\InventoryTransaction([
        //     'product_id' => $product->id,
        //     'quantity' => $validated['received_quantity'],
        //     'type' => 'shipment_receive',
        //     'reference_id' => $shipment->id,
        //     'reference_type' => 'App\Models\Shipment',
        //     'transaction_type_id' => 1, // نوع الحركة: استلام شحنة
        //     'warehouse_id' => 1, // المخزن الافتراضي
        //     'notes' => 'استلام شحنة رقم: ' . $shipment->shipment_number,
        // ]);
        // $inventoryTransaction->save();

        return redirect()->route('shipments.index')
            ->with('success', 'تم استلام الشحنة !');
    }

    /**
     * عرض صفحة الشحنات المنتظرة للاستلام
     */
    public function receiveIndex()
    {
        // جلب الشحنات التي لم يتم استلامها بعد
        $shipments = Shipment::with('product')
            ->where('status', '!=', 'delivered')
            ->get();

        return view('shipments.receive_index', compact('shipments'));
    }

    /**
     * عرض صفحة إرسال الشحنات
     */
    public function sendIndex()
    {
        // جلب الشحنات التي يمكن إرسالها
        $shipments = Shipment::with('product')
            ->where('status', 'confirmed')
            ->get();

        return view('shipments.send_index', compact('shipments'));
    }

    /**
     * عرض صفحة تتبع الشحنات
     */
    // public function trackIndex()
    // {
    //     // جلب جميع الشحنات للتتبع
    //     $shipments = Shipment::with('product')->get();

    //     return view('shipments.track_index', compact('shipments'));
    // }
    public function trackIndex(Request $request)
    {
        $query = Shipment::with('product');

        // فلترة برقم الشحنة إذا تم إدخاله
        if ($request->filled('search')) {
            $query->where('shipment_number', 'like', '%' . $request->search . '%');
        }

        // فلترة بالحالة إذا تم تحديدها
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $shipments = $query->get();

        return view('shipments.track_index', compact('shipments'));
    }
}

<?php

namespace App\Http\Controllers;
use App\Models\Shipment;
use App\Models\Product;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index()
    {
        // عرض جميع الشحنات
        $shipments = Shipment::with('product')->get();
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
}

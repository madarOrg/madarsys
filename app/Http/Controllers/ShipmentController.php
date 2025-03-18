<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    // دالة عرض الشحنات
    public function index()
    {
        $shipments = Shipment::all(); // جلب جميع الشحنات
        return view('shipments.index', compact('shipments'));
    }

    // دالة عرض تفاصيل شحنة معينة
    public function show($id)
    {
        $shipment = Shipment::findOrFail($id); // العثور على الشحنة حسب id
        return view('shipments.show', compact('shipment')); // عرض تفاصيل الشحنة
    }

    // دالة عرض نموذج إضافة شحنة جديدة
    public function create()
    {
        return view('shipments.create'); // عرض نموذج إضافة شحنة جديدة
    }

    // دالة حفظ الشحنة
    public function store(Request $request)
    {
        // التحقق من المدخلات
        $request->validate([
            'tracking_number' => 'required|unique:shipments',
            'recipient_name' => 'required',
            'address' => 'required',
            'shipment_date' => 'required|date',
        ]);

        // حفظ الشحنة في قاعدة البيانات
        Shipment::create($request->all());

        // إعادة التوجيه إلى صفحة إدارة الشحنات بعد إضافة الشحنة
        return redirect()->route('shipments.index')->with('success', 'تم إضافة الشحنة بنجاح');
    }

    // دالة عرض نموذج تعديل شحنة
    public function edit($id)
    {
        $shipment = Shipment::findOrFail($id);
        return view('shipments.edit', compact('shipment'));
    }

    // دالة تحديث الشحنة
    public function update(Request $request, $id)
    {
        $request->validate([
            'tracking_number' => 'required',
            'recipient_name' => 'required',
            'address' => 'required',
            'shipment_date' => 'required|date',
        ]);

        $shipment = Shipment::findOrFail($id);
        $shipment->update($request->all());

        return redirect()->route('shipments.index')->with('success', 'تم تحديث الشحنة بنجاح');
    }

    // دالة حذف الشحنة
    public function destroy($id)
    {
        $shipment = Shipment::findOrFail($id);
        $shipment->delete();

        return redirect()->route('shipments.index')->with('success', 'تم حذف الشحنة بنجاح');
    }
}

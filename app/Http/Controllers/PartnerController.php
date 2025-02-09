<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\PartnerType;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    // عرض قائمة الشركاء
    public function index()
    {
        // استرجاع 7 سجلات في كل صفحة
        $partners = Partner::paginate(7);
    
        // تمرير البيانات إلى العرض
        return view('partners.index', compact('partners'));
    }
    
    // عرض نموذج إضافة شريك جديد
    public function create()
    {
        $partnerTypes = PartnerType::all(); // جلب جميع أنواع الشركاء
        return view('partners.create', compact('partnerTypes'));
    }

    // تخزين شريك جديد في قاعدة البيانات
    public function store(Request $request)
    {
        // التحقق من صحة المدخلات
        $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|integer|exists:partner_types,id',
            'contact_person' => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:15',
            'email'          => 'nullable|email|max:255|unique:partners',
            'address'        => 'nullable|string|max:255',
            'tax_number'     => 'nullable|string|max:50|unique:partners',
            'is_active'      => 'required|boolean',
        ]);

        // إنشاء شريك جديد
        $partner = Partner::create($request->all());

        return redirect()->route('partners.index')->with('success', 'Partner created successfully');
    }

    // عرض بيانات الشريك للتعديل
    public function edit($id)
    {
        $partner = Partner::findOrFail($id);
        $partnerTypes = PartnerType::all(); // جلب جميع الأنواع لعرضها في القائمة المنسدلة
        return view('partners.edit', compact('partner', 'partnerTypes'));
    }
    
    // تحديث الشريك في قاعدة البيانات
    public function update(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);

        // التحقق من صحة المدخلات
        $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|integer|exists:partner_types,id',
            'contact_person' => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:15',
            'email'          => 'nullable|email|max:255|unique:partners,email,' . $partner->id,
            'address'        => 'nullable|string|max:255',
            'tax_number'     => 'nullable|string|max:50|unique:partners,tax_number,' . $partner->id,
            'is_active'      => 'required|boolean',
        ]);

        // تحديث بيانات الشريك
        $partner->update($request->all());

        return redirect()->route('partners.index')->with('success', 'Partner updated successfully');
    }

    // حذف الشريك
    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->delete();

        return redirect()->route('partners.index')->with('success', 'Partner deleted successfully');
    }
}

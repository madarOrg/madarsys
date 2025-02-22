<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\PartnerType;
use Illuminate\Http\Request;
use Exception;

class PartnerController extends Controller
{
    // عرض قائمة الشركاء
    public function index(Request $request)
    {
        try {
            // استرجاع القيمة المدخلة في مربع البحث
            $search = $request->input('search');

            // بناء استعلام بحث يعتمد على المدخلات
            $partners = Partner::with('partnerType')  // تحميل علاقة partnerType
                ->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orWhereHas('partnerType', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                })
                ->paginate(7);

            // تمرير البيانات إلى العرض
            return view('partners.index', compact('partners'));
        } catch (Exception $e) {
            return redirect()->route('partners.index')->with('error', 'حدث خطأ أثناء تحميل قائمة الشركاء: ' . $e->getMessage());
        }
    }


    // عرض نموذج إضافة شريك جديد
    public function create()
    {
        try {
            $partnerTypes = PartnerType::all(); // جلب جميع أنواع الشركاء
            return view('partners.create', compact('partnerTypes'));
        } catch (Exception $e) {
            return redirect()->route('partners.index')->with('error', 'حدث خطأ أثناء تحميل نموذج إضافة الشريك: ' . $e->getMessage());
        }
    }

    // تخزين شريك جديد في قاعدة البيانات
    public function store(Request $request)
    {
        try {
            // التحقق من صحة المدخلات
            $request->validate([
                'name'           => 'required|string|max:255',
                'type'           => 'required|integer|exists:partner_types,id',
                'contact_person' => 'nullable|string|max:255',
                'phone'          => 'nullable|string|max:15',
                'email'          => 'nullable|email|max:255|unique:partners',
                'address'        => 'nullable|string|max:255',
                'tax_number'     => 'nullable|string|max:50|unique:partners',
                'is_active'      => 'nullable|boolean',
            ]);

            // تعيين القيمة الافتراضية لـ 'is_active' إذا لم يتم تحديدها
            $is_active = $request->has('is_active') ? 1 : 0;

            // إنشاء شريك جديد
            $partner = Partner::create(array_merge($request->all(), [
                'is_active' => $is_active
            ]));

            return redirect()->route('partners.index')->with('success', 'تم إنشاء الشريك بنجاح.');
        } catch (Exception $e) {
            return redirect()->route('partners.index')->with('error', 'حدث خطأ أثناء إنشاء الشريك: ' . $e->getMessage());
        }
    }

    // عرض بيانات الشريك للتعديل
    public function edit($id)
    {
        try {
            $partner = Partner::findOrFail($id);
            $partnerTypes = PartnerType::all(); // جلب جميع الأنواع لعرضها في القائمة المنسدلة
            return view('partners.edit', compact('partner', 'partnerTypes'));
        } catch (Exception $e) {
            return redirect()->route('partners.index')->with('error', 'حدث خطأ أثناء تحميل بيانات الشريك للتعديل: ' . $e->getMessage());
        }
    }

    // تحديث الشريك في قاعدة البيانات
    public function update(Request $request, $id)
    {
        try {
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
                'is_active'      => 'nullable|boolean',
            ]);

            $is_active = $request->has('is_active') ? 1 : 0;

            // تحديث بيانات الشريك
            $partner->update(array_merge($request->all(), ['is_active' => $is_active]));

            return redirect()->route('partners.index')->with('success', 'تم تحديث الشريك بنجاح.');
        } catch (Exception $e) {
            return redirect()->route('partners.index')->with('error', 'حدث خطأ أثناء تحديث الشريك: ' . $e->getMessage());
        }
    }

    // حذف الشريك
    public function destroy($id)
    {
        try {
            $partner = Partner::findOrFail($id);
            $partner->delete();

            return redirect()->route('partners.index')->with('success', 'تم حذف الشريك بنجاح.');
        } catch (Exception $e) {
            return redirect()->route('partners.index')->with('error', 'حدث خطأ أثناء حذف الشريك: ' . $e->getMessage());
        }
    }
}

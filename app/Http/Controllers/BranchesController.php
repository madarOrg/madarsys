<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use Illuminate\Http\Request;
use Exception;

class BranchesController extends Controller
{
    /**
     * عرض قائمة الفروع.
     */
    public function index(Request $request)
    {
        try {
            // الحصول على كلمة البحث من الطلب
            $search = $request->input('search');

            // جلب الفروع مع الشركات مع إمكانية البحث في جميع الحقول
            $branches = Branch::with('company')
                ->when($search, function ($query, $search) {
                    $query->where('name', 'LIKE', "%{$search}%") // البحث في اسم الفرع
                        ->orWhere('address', 'LIKE', "%{$search}%") // البحث في عنوان الفرع
                        ->orWhere('contact_info', 'LIKE', "%{$search}%") // البحث في معلومات الاتصال
                        ->orWhereHas('company', function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%{$search}%"); // البحث في اسم الشركة
                        });
                })
                ->get();

            // إرجاع النتائج إلى الـ view
            return view('branches.index', compact('branches', 'search'));
        } catch (Exception $e) {
            return redirect()->route('branches.index')->with('error', 'حدث خطأ أثناء تحميل الفروع: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج إضافة فرع جديد.
     */
    public function create()
    {
        try {
            $user = auth()->user();

            // تصفية الشركات بناءً على صلاحيات المستخدم وتحميل الفروع
            // $companies = $user->allowedCompanies()->with('branches')->get();
            $companies = $user->allowedCompanies(); // الحصول على الشركات المتاحة للمستخدم

            return view('branches.create', compact('companies'));
        } catch (Exception $e) {
            return redirect()->route('branches.index')->with('error', 'حدث خطأ أثناء تحميل نموذج إضافة الفرع: ' . $e->getMessage());
        }
    }

    /**
     * تخزين فرع جديد.
     */
    public function store(Request $request)
    {
        try {
            // تحقق من وجود الشركة
            $company = Company::find($request->company_id);

            if (!$company) {
                return redirect()->back()->withErrors(['company_id' => 'الشركة المحددة غير موجودة']);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'contact_info' => 'nullable|string',
                'company_id' => 'required|exists:companies,id',
            ]);

            Branch::create([
                'name' => $request->name,
                'address' => $request->address,
                'contact_info' => $request->contact_info,
                'company_id' => $request->company_id,
            ]);

            return redirect()->route('branches.create')->with('success', 'تم إضافة الفرع بنجاح');
        } catch (Exception $e) {
            return redirect()->route('branches.create')->with('error', 'حدث خطأ أثناء إضافة الفرع: ' . $e->getMessage());
        }
    }

    /**
     * عرض بيانات الفرع للتعديل.
     */
    public function show(string $id)
    {
        try {
            $branch = Branch::findOrFail($id);
            return view('branches.show', compact('branch'));
        } catch (Exception $e) {
            return redirect()->route('branches.index')->with('error', 'حدث خطأ أثناء تحميل بيانات الفرع: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج تعديل الفرع.
     */
    public function edit(string $id)
    {
        try {
            $branch = Branch::findOrFail($id);
            $companies = Company::all();
            return view('branches.edit', compact('branch', 'companies'));
        } catch (Exception $e) {
            return redirect()->route('branches.index')->with('error', 'حدث خطأ أثناء تحميل نموذج تعديل الفرع: ' . $e->getMessage());
        }
    }

    /**
     * تحديث بيانات الفرع.
     */
    public function update(Request $request, string $id)
    {
        try {
            $branch = Branch::findOrFail($id);

            // تحقق من وجود الشركة
            $company = Company::find($request->company_id);

            if (!$company) {
                return redirect()->back()->withErrors(['company_id' => 'الشركة المحددة غير موجودة']);
            }

            $request->validate([
                'name' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'contact_info' => 'nullable|string',
                'company_id' => 'required|exists:companies,id',
            ]);

            $branch->update([
                'name' => $request->name,
                'address' => $request->address,
                'contact_info' => $request->contact_info,
                'company_id' => $request->company_id,
            ]);

            return redirect()->route('branches.index')->with('success', 'تم تحديث الفرع بنجاح');
        } catch (Exception $e) {
            return redirect()->route('branches.index')->with('error', 'حدث خطأ أثناء تحديث بيانات الفرع: ' . $e->getMessage());
        }
    }

    /**
     * حذف الفرع.
     */
    public function destroy(string $id)
    {
        try {
            $branch = Branch::findOrFail($id);
            $branch->delete();

            return redirect()->route('branches.index')->with('success', 'تم حذف الفرع بنجاح');
        } catch (Exception $e) {
            return redirect()->route('branches.index')->with('error', 'حدث خطأ أثناء حذف الفرع: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Company;
use Illuminate\Http\Request;

class BranchesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = Branch::with('company')->get(); // جلب الفروع مع بيانات الشركات
        return view('branches.index', compact('branches'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $user = auth()->user();

    // تصفية الشركات بناءً على صلاحيات المستخدم وتحميل الفروع
    // $companies = $user->allowedCompanies()->with('branches')->get();
    $companies = $user ->allowedCompanies(); // الحصول على الشركات المتاحة للمستخدم

    return view('branches.create', compact('companies'));
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $branch = Branch::findOrFail($id);
        return view('branches.show', compact('branch'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $branch = Branch::findOrFail($id);
        $companies = Company::all();
        return view('branches.edit', compact('branch', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return redirect()->route('companies.index')->with('success', 'تم حذف الفرع بنجاح');
    }
}

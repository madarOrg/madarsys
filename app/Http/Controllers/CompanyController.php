<?php

namespace App\Http\Controllers;

use App\Models\{Company, Branch, Warehouse};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
   
    public function index(Request $request)
{
    // التأكد من وجود مستخدم مسجل الدخول
    if (!auth()->check()) {
        return redirect()->route('login')->with('error', 'You must be logged in to access this page.');
    }

    $user = auth()->user();

    // التحقق إذا كان المستخدم لديه دور admin
    $isAdmin = $user->roles->contains(function ($role) {
        return $role->is_admin; // التحقق من إذا كان أي دور هو دور admin
    });

    // الحصول على كلمة البحث من الطلب
    $search = $request->input('search'); // تأكد أن $request يتم تمريره للطريقة

    if ($isAdmin) {
        $companies = Company::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%");
            }) 
            ->get();

        $branches = Branch::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('address', 'LIKE', "%{$search}%");
            })
            ->get();

        $warehouses = Warehouse::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('address', 'LIKE', "%{$search}%")
                      ->orWhereHas('branch', function ($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            })
            ->get();
    } else {
        $roles = $user->roles()->with('branches.warehouses')->get();

        $branches = $roles->flatMap(function ($role) {
            return $role->branches;
        });

        $companyIds = $branches->pluck('company_id')->unique();

        $companies = Company::whereIn('id', $companyIds)
            ->when($search, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->get();

        $warehouses = $branches->flatMap(function ($branch) use ($search) {
            return $branch->warehouses()->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('address', 'LIKE', "%{$search}%")
                      ->orWhere('supervisor_name', 'LIKE', "%{$search}%");
            })->get();
        });
    }

    // إرجاع البيانات إلى الـ view
    return view('companies.index', compact('companies', 'branches', 'warehouses'));
}



    /**
     * عرض نموذج إضافة شركة جديدة.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * تخزين شركة جديدة.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'logo' => 'nullable|image',
            'phone_number' => 'nullable|string',
            'email' => 'sometimes|email',
            'settings' => 'nullable|json',
        ]);

        // Handle file upload if logo exists
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        Company::create($validated);
        return redirect()->route('companies.index');
    }

    /**
     * عرض تفاصيل الشركة.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        return view('companies.show', compact('company'));
    }

    /**
     * عرض نموذج تعديل الشركة.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    /**
     * تحديث بيانات الشركة.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
{
    try {
        // Validation for the update
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'logo' => 'nullable|image',
            'phone_number' => 'nullable|string',
            'email' => 'sometimes|email',
            'settings' => 'nullable|json',
        ]);

        // Handle logo upload and delete the old one
        if ($request->hasFile('logo')) {
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        // Update company data
         $company->update($validated);
        // $company->update($request->all());
        // Redirect back with success message
        return redirect()->route('companies.edit', $company->id)->with('success', 'تم تحديث بيانات الشركة بنجاح');
    } catch (\Exception $e) {
        // Handle exceptions
        return redirect()->route('companies.edit', $company->id)->with('error', 'حدث خطأ أثناء تحديث بيانات الشركة');
    }
}

    /**
     * حذف الشركة.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index');
    }
}

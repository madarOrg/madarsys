<?php

namespace App\Http\Controllers;

use App\Models\{Company, Branch, Warehouse};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class CompanyController extends Controller
{
    // عرض قائمة الشركات
    public function index(Request $request)
    {
        try {
            // التأكد من وجود مستخدم مسجل الدخول
            if (!auth()->check()) {
                return redirect()->route('login')->with('error', 'يجب أن تكون مسجلاً للدخول للوصول إلى هذه الصفحة.');
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
                            ->orWhere('address', 'LIKE', "%{$search}%");
                            // ->orWhere('supervisor_name', 'LIKE', "%{$search}%");
                    })->get();
                });
            }

            // إرجاع البيانات إلى الـ view
            return view('companies.index', compact('companies', 'branches', 'warehouses'));
        } catch (Exception $e) {
            return redirect()->route('companies.index')->with('error', 'حدث خطأ أثناء تحميل البيانات: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج إضافة شركة جديدة.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('companies.create');
        } catch (Exception $e) {
            return redirect()->route('companies.index')->with('error', 'حدث خطأ أثناء تحميل نموذج إضافة الشركة: ' . $e->getMessage());
        }
    }

    /**
     * تخزين شركة جديدة.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
            'logo' => 'nullable|image',
            'phone_number' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'additional_info' => 'nullable|string',
            'settings' => 'nullable|json',
                
            ]);

            // Handle file upload if logo exists
            if ($request->hasFile('logo')) {
                $validated['logo'] = $request->file('logo')->store('logos', 'public');
            }

            Company::create($validated);
            return redirect()->route('companies.index')->with('success', 'تم إضافة الشركة بنجاح.');
        } catch (Exception $e) {
            return redirect()->route('companies.index')->with('error', 'حدث خطأ أثناء إضافة الشركة: ' . $e->getMessage());
        }
    }

    /**
     * عرض تفاصيل الشركة.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        try {
            return view('companies.show', compact('company'));
        } catch (Exception $e) {
            return redirect()->route('companies.index')->with('error', 'حدث خطأ أثناء تحميل تفاصيل الشركة: ' . $e->getMessage());
        }
    }

    /**
     * عرض نموذج تعديل الشركة.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        try {
            return view('companies.edit', compact('company'));
        } catch (Exception $e) {
            return redirect()->route('companies.index')->with('error', 'حدث خطأ أثناء تحميل نموذج تعديل الشركة: ' . $e->getMessage());
        }
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
                'name' => 'required|string|max:255',
                'logo' => 'nullable|image',
                'phone_number' => 'nullable|string',
                'email' => 'nullable|email',
                'address' => 'nullable|string',
                'additional_info' => 'nullable|string',
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
            // Redirect back with success message
            return redirect()->route('companies.index', $company->id)->with('success', 'تم تحديث بيانات الشركة بنجاح');
        } catch (Exception $e) {
            // Handle exceptions
            return redirect()->route('companies.index', $company->id)->with('error', 'حدث خطأ أثناء تحديث بيانات الشركة: ' . $e->getMessage());
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
        try {
            $company->delete();
            return redirect()->route('companies.index')->with('success', 'تم حذف الشركة بنجاح.');
        } catch (Exception $e) {
            return redirect()->route('companies.index')->with('error', 'حدث خطأ أثناء حذف الشركة: ' . $e->getMessage());
        }
    }
}

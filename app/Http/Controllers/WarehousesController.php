<?php

namespace App\Http\Controllers;


use App\Models\
{Warehouse,
    Company,
    User
 } ; // تأكد من إضافة النموذج المناسب
use Illuminate\Http\Request;

class WarehousesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = Warehouse::query();

    // تطبيق البحث
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('address', 'like', '%' . $request->search . '%')
              ->orWhere('code', 'like', '%' . $request->search . '%')
              ->orWhereHas('branch', function ($q) use ($request) {
                  $q->where('name', 'like', '%' . $request->search . '%');
              });
    }

    // جلب المستودعات
    $warehouses = $query->paginate(10);

    return view('warehouses.index', compact('warehouses'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
    
        // تصفية الشركات بناءً على صلاحيات المستخدم
        $companies = $user ->allowedCompanies(); // الحصول على الشركات المتاحة للمستخدم
        $users = User::all();
        return view('warehouses.create', compact('companies','users'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         try{

            // التحقق من صحة البيانات المدخلة
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|unique:warehouses,code|max:255',
                'address' => 'nullable|string|max:255',
                'contact_info' => 'nullable|string',
                'branch_id' => 'required|exists:branches,id',
                'supervisor_id' => 'nullable|exists:users,id',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'capacity' => 'nullable|numeric|min:0',
                'is_smart' => 'boolean',
                'has_cctv' => 'boolean',
                'temperature' => 'nullable|numeric',
                'humidity' => 'nullable|numeric',
            ]);
            
            // تخزين البيانات في قاعدة البيانات
            Warehouse::create($validated);
            // Warehouse::create($request->all());

            // إعادة التوجيه مع رسالة نجاح
            return redirect()->route('warehouses.create')->with('success', 'تم إضافة المستودع بنجاح');
         } catch (\Exception $e) {
            // التحقق إذا كان الخطأ ليس بسبب الفاليديشن
            if ($e instanceof \Illuminate\Validation\ValidationException) {
                return redirect()->route('warehouses.create')->withErrors($e->validator)->withInput();
            }
    
            // في حالة حدوث أي خطأ أثناء عملية الحفظ
            return redirect()->route('warehouses.create')->with('error', 'حدث خطأ أثناء إضافة المستودع');
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // استرجاع المستودع باستخدام الـ ID
        $warehouse = Warehouse::findOrFail($id);

        return view('warehouses.show', compact('warehouse')); // عرض تفاصيل المستودع
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // استرجاع المستودع وتوجيهه إلى نموذج التعديل
        $warehouse = Warehouse::findOrFail($id);
        $companies = Company::with('branches')->get(); // Assuming your Company model has a relation called 'branches'

        return view('warehouses.edit', compact('warehouse', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    /**
 * Update the specified resource in storage.
 */
public function update(Request $request, string $id)
{
    try {
        // التحقق من صحة البيانات المدخلة
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:warehouses,code,' . $id . '|max:255',
            'address' => 'nullable|string|max:255',
            'contact_info' => 'nullable|string',
            'branch_id' => 'required|exists:branches,id',
            'supervisor_id' => 'nullable|exists:users,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'capacity' => 'nullable|numeric|min:0',
            'is_smart' => 'boolean',
            'has_cctv' => 'boolean',
            'temperature' => 'nullable|numeric',
            'humidity' => 'nullable|numeric',
        ]);

        // العثور على المستودع وتحديثه
        $warehouse = Warehouse::findOrFail($id);

        // تحديث البيانات في قاعدة البيانات
        $warehouse->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'address' => $validated['address'],
            'contact_info' => $validated['contact_info'],
            'branch_id' => $validated['branch_id'],
            'supervisor_id' => $validated['supervisor_id'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'capacity' => $validated['capacity'],
            'is_smart' => $request->has('is_smart') ? $validated['is_smart'] : false,
            'has_cctv' => $request->has('has_cctv') ? $validated['has_cctv'] : false,
            'temperature' => $validated['temperature'],
            'humidity' => $validated['humidity'],
        ]);

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('warehouses.edit', $id)->with('success', 'تم تحديث المستودع بنجاح');
    } catch (\Exception $e) {
        // التحقق إذا كان الخطأ ليس بسبب الفاليديشن
        if ($e instanceof \Illuminate\Validation\ValidationException) {
            return redirect()->route('warehouses.edit', $id)->withErrors($e->validator)->withInput();
        }

        // في حالة حدوث أي خطأ أثناء عملية التحديث
        return redirect()->route('warehouses.edit', $id)->with('error', 'حدث خطأ أثناء تحديث المستودع');
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // العثور على المستودع وحذفه
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();

        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('companies.index')->with('success', 'تم حذف المستودع بنجاح');
    }
}

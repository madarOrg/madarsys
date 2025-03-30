<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InventoryAudit;
use App\Models\Category;
use App\Models\User;
use App\Models\Warehouse;

use Illuminate\Http\Request;
use Carbon\Carbon;

class InventoryAuditController extends Controller
{
    public function index(Request $request)
    {
        // جلب القيم من الطلب
        $category    = $request->input('category_id');
        $stockStatus = $request->input('stock_status');
        $filterType  = $request->input('filter');     // يمكن أن يكون: 'recent', 'never', أو 'not_since'
        $givenDate   = $request->input('given_date');   // التاريخ المطلوب في حالة 'not_since'

        // جلب التصنيفات لعرضها في الفلترة
        $categories = Category::all();

        // بناء الاستعلام مع العلاقات الضرورية (يجب تحميل العلاقة الخاصة بالجرد والحركات)
        $query = Product::with(['inventory', 'inventoryTransactions']);

        // فلترة حسب حالة المخزون
        if ($request->filled('stock_status')) {
            $query->whereHas('inventory', function ($q) use ($stockStatus) {
                switch ($stockStatus) {
                    case 'low':
                        $q->whereColumn('quantity', '<', 'min_stock_level');
                        break;
                    case 'excess':
                        $q->whereColumn('quantity', '>', 'max_stock_level');
                        break;
                    case 'out':
                        $q->where('quantity', 0);
                        break;
                }
            });
        }

        // فلترة حسب التصنيف
        if ($request->filled('category_id')) {
            $query->where('category_id', $category);
        }

        // فلترة حسب حركة الجرد (معرف النوع = 8)
        if ($request->filled('filter')) {
            if ($filterType === 'never') {
                // المنتجات التي لم تُجرَد أبداً: ليس لها أي حركة جرد من النوع 8
                $query->whereDoesntHave('inventoryTransactions', function($q) {
                    $q->where('transaction_type_id', 8);
                });
            } else {
                // المنتجات التي لها حركة جرد
                $query->whereHas('inventoryTransactions', function($q) use ($filterType, $givenDate) {
                    $q->where('transaction_type_id', 8);
                    if ($filterType === 'recent') {
                        // الحركة حديثة: خلال آخر 30 يومًا (يمكن تعديل الفترة حسب الحاجة)
                        $q->where('transaction_date', '>=', Carbon::now()->subDays(30));
                    } elseif ($filterType === 'not_since' && $givenDate) {
                        // الحركة كانت قبل التاريخ المدخل
                        $q->where('transaction_date', '<', $givenDate);
                    }
                });
            }
        }

        // تنفيذ الاستعلام مع استرجاع البيانات
        $products = $query->get();

        // تمرير النتائج إلى العرض
        return view('inventory.audit.index', compact('products', 'categories'));
    }
 /**
     * عرض صفحة إنشاء عملية جرد جديدة.
     */
    public function create()
    {
        $categories = Category::all();
        $users = User::all();
        $warehouses = Warehouse::all();
        return view('inventory.audit.create', compact('categories', 'users', 'warehouses'));
    }

    /**
     * حفظ عملية الجرد الجديدة في قاعدة البيانات.
     */
    public function store(Request $request)
    {
        $request->validate([
            'inventory_code'         => 'required|unique:inventory_audits,inventory_code',
            'inventory_type'         => 'required|integer',
            'start_date'             => 'nullable|date',
            'end_date'               => 'nullable|date|after_or_equal:start_date',
            'expected_products_count'=> 'nullable|integer',
            'counted_products_count' => 'nullable|integer',
            // يمكن إضافة المزيد من قواعد التحقق حسب الحاجة
        ]);

        $data = $request->all();
        // تحديد المستخدم الذي أنشأ العملية (يفترض وجود Auth)
        $data['created_by'] = auth()->id();

        $audit = InventoryAudit::create($data);

        // ربط المستخدمين المسؤولين عن الجرد (متعدد الاختيارات)
        if ($request->has('users')) {
            $audit->users()->sync($request->input('users'));
        }

        // ربط المستودعات المستهدفة
        if ($request->has('warehouses')) {
            $audit->warehouses()->sync($request->input('warehouses'));
        }

        return redirect()->route('inventory.audit.index')->with('success', 'تمت إضافة عملية الجرد بنجاح');
    }

    /**
     * عرض صفحة تعديل عملية الجرد.
     */
    public function edit($id)
    {
        $audit = InventoryAudit::with(['users', 'warehouses'])->findOrFail($id);
        $categories = Category::all();
        $users = User::all();
        $warehouses = Warehouse::all();
        return view('inventory.audit.edit', compact('audit', 'categories', 'users', 'warehouses'));
    }

    /**
     * تحديث بيانات عملية الجرد.
     */
    public function update(Request $request, $id)
    {
        $audit = InventoryAudit::findOrFail($id);

        $request->validate([
            'inventory_code' => 'required|unique:inventory_audits,inventory_code,'.$audit->id,
            'inventory_type' => 'required|integer',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
        ]);

        $data = $request->all();
        $audit->update($data);

        if ($request->has('users')) {
            $audit->users()->sync($request->input('users'));
        }

        if ($request->has('warehouses')) {
            $audit->warehouses()->sync($request->input('warehouses'));
        }

        return redirect()->route('inventory.audit.index')->with('success', 'تم تحديث عملية الجرد بنجاح');
    }

    /**
     * حذف عملية الجرد.
     */
    public function destroy($id)
    {
        $audit = InventoryAudit::findOrFail($id);
        $audit->delete();
        return redirect()->route('inventory.audit.index')->with('success', 'تم حذف عملية الجرد بنجاح');
    }
}


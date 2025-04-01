<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\InventoryAudit;
use App\Models\InventoryAuditUser;
use App\Models\InventoryAuditWarehouse;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Carbon\Carbon;

class InventoryAuditController extends Controller
{

    public function report(Request $request)
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
                $query->whereDoesntHave('inventoryTransactions', function ($q) {
                    $q->where('transaction_type_id', 8);
                });
            } else {
                // المنتجات التي لها حركة جرد
                $query->whereHas('inventoryTransactions', function ($q) use ($filterType, $givenDate) {
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
        return view('inventory.audit.report', compact('products', 'categories'));
    }

    public function warehouseReport(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate   = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $inventoryCode = $request->input('inventory_code'); // إذا لم يُرسل سيظل null
        $groupByBatch = $request->input('group_by_batch', false); // إذا تم تمرير القيمة (مثلاً 1)، فسيتم التجميع حسب الدفعة

        $query = DB::table('inventory_products as ip')
            ->join('products as p', 'ip.product_id', '=', 'p.id')
            ->join('warehouses as w', 'ip.warehouse_id', '=', 'w.id')
            ->join('inventory_transaction_items as iti', 'ip.inventory_transaction_item_id', '=', 'iti.id')
            ->join('inventory_transactions as it', 'iti.inventory_transaction_id', '=', 'it.id')
            ->join('inventory_audit_warehouses as iaw', 'w.id', '=', 'iaw.warehouse_id')
            ->join('inventory_audits as ia', 'iaw.inventory_audit_id', '=', 'ia.id')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                // استخدام تواريخ إدخال المنتجات بين الفترة المحددة
                $query->whereBetween('ip.created_at', [$startDate, $endDate]);
            })
            ->when($inventoryCode, function ($query) use ($inventoryCode) {
                $query->where('ia.inventory_code', $inventoryCode);
            });

        if ($groupByBatch) {
            // في حالة تجميع النتائج حسب الدفعة، نضيف batch_number إلى ال SELECT ونستخدمه في GROUP BY
            $query->select(
                'w.id as warehouse_id',
                'w.name as warehouse_name',
                'p.id as product_id',
                'p.name as product_name',
                'ip.batch_number',
                DB::raw('SUM(ip.converted_quantity * ip.distribution_type) as total_quantity')
            )->groupBy('w.id', 'w.name', 'p.id', 'p.name', 'ip.batch_number');
        } else {
            // الحالة الافتراضية، التجميع حسب المستودع والمنتج فقط
            $query->select(
                'w.id as warehouse_id',
                'w.name as warehouse_name',
                'p.id as product_id',
                'p.name as product_name',
                DB::raw('SUM(ip.converted_quantity * ip.distribution_type) as total_quantity')
            )->groupBy('w.id', 'w.name', 'p.id', 'p.name');
        }

        $warehouseReports = $query->orderBy('w.id')->get();

        return view('inventory.audit.warehouse_report', compact('warehouseReports', 'startDate', 'endDate', 'groupByBatch'));
    }


    public function index(Request $request)
    {
        // جلب القيم من الطلب
        $startDate   = $request->input('start_date');
        $endDate     = $request->input('end_date');
        $inventoryType = $request->input('inventory_type');
        $status      = $request->input('status');  // 1 أو 0 (معلق أو مكتمل)

        // بناء الاستعلام مع الفلاتر
        $query = InventoryAudit::query();

        if ($startDate) {
            $query->where('start_date', '>=', Carbon::parse($startDate));
        }

        if ($endDate) {
            $query->where('end_date', '<=', Carbon::parse($endDate));
        }

        if ($inventoryType) {
            $query->where('inventory_type', $inventoryType);
        }

        if ($status !== null) {
            $query->where('status', $status);  // يعرض الجرد المعلق أو المكتمل
        }

        // استرجاع النتائج بناءً على الفلاتر
        $audits = $query->with(['users', 'warehouses'])->get();

        // تمرير النتائج إلى العرض
        return view('inventory.audit.index', compact('audits'));
    }

    public function create()
    {
        $users = User::all();
        $warehouses = Warehouse::all();
        return view('inventory.audit.create', compact('users', 'warehouses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'inventory_type' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'notes' => 'nullable|string',
            'users' => 'required|array',
            'warehouses' => 'required|array',
        ]);

        $auditCode = InventoryAudit::generateAuditCode();

        $inventoryAudit = InventoryAudit::create([
            'inventory_code' => $auditCode, // تعديل الاسم ليطابق الجدول
            'inventory_type' => $validated['inventory_type'], // تعديل الاسم
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'notes' => $validated['notes'], // تعديل الاسم ليطابق الجدول
            'status' => 1, // تعيين الحالة إلى "معلق" بشكل افتراضي

        ]);

        foreach ($validated['users'] as $userId) {
            // تأكد من أن القيمة هي قيمة مفردة (بناءً على الصلاحيات المختارة للمستخدم)
            $operationType = $request->input("user_permissions.{$userId}");

            // تحقق من أن قيمة operation_type ليست فارغة أو مصفوفة
            if (is_array($operationType)) {
                $operationType = $operationType[0]; // إذا كانت مصفوفة، اختر العنصر الأول
            }

            if ($operationType !== null) {
                InventoryAuditUser::create([
                    'inventory_audit_id' => $inventoryAudit->id,
                    'user_id' => $userId,
                    'operation_type' => $operationType, // يجب أن تكون قيمة مفردة هنا

                ]);
            }
        }


        foreach ($validated['warehouses'] as $warehouseId) {
            InventoryAuditWarehouse::create([
                'inventory_audit_id' => $inventoryAudit->id,
                'warehouse_id' => $warehouseId,

            ]);
        }

        return redirect()->route('inventory.audit.index')->with('success', 'تم إنشاء عملية الجرد بنجاح.');
    }

    public function edit($id)
    {
        $audit = InventoryAudit::findOrFail($id);
        $categories = Category::all();
        $users = User::all();
        $warehouses = Warehouse::all();

        $selectedUsers = InventoryAuditUser::where('inventory_audit_id', $id)->pluck('user_id')->toArray();
        $selectedWarehouses = InventoryAuditWarehouse::where('inventory_audit_id', $id)->pluck('warehouse_id')->toArray();

        return view('inventory.audit.edit', compact('audit', 'categories', 'users', 'warehouses', 'selectedUsers', 'selectedWarehouses'));
    }

    public function update(Request $request, $id)
    {
        $audit = InventoryAudit::findOrFail($id);

        $request->validate([
            'audit_code' => 'required|unique:inventory_audits,audit_code,' . $audit->id,
            'audit_type' => 'required|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $audit->update($request->all());

        InventoryAuditUser::where('inventory_audit_id', $audit->id)->delete();
        if ($request->has('users')) {
            foreach ($request->input('users') as $userId) {
                InventoryAuditUser::create([
                    'inventory_audit_id' => $audit->id,
                    'user_id' => $userId
                ]);
            }
        }

        InventoryAuditWarehouse::where('inventory_audit_id', $audit->id)->delete();
        if ($request->has('warehouses')) {
            foreach ($request->input('warehouses') as $warehouseId) {
                InventoryAuditWarehouse::create([
                    'inventory_audit_id' => $audit->id,
                    'warehouse_id' => $warehouseId
                ]);
            }
        }

        return redirect()->route('inventory.audit.index')->with('success', 'تم تحديث عملية الجرد بنجاح');
    }

    public function destroy($id)
    {
        InventoryAuditUser::where('inventory_audit_id', $id)->delete();
        InventoryAuditWarehouse::where('inventory_audit_id', $id)->delete();
        InventoryAudit::findOrFail($id)->delete();

        return redirect()->route('inventory.audit.index')->with('success', 'تم حذف عملية الجرد بنجاح');
    }

    public function generateAuditCode($id)
    {
        $date = now()->format('Y-m-d');
        return 'audit-' . $date . '-' . $id;
    }
}

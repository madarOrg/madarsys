<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\InventoryAudit;
use App\Models\InventoryAuditUser;
use App\Models\InventoryAuditWarehouse;
use App\Models\Company;
use App\Models\Unit;
use App\Models\InventoryTransactionSubtype;
use App\Models\InventoryTransaction;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\InventoryTransaction\InventoryTransactionService;

class InventoryAuditController extends Controller
{
    protected $inventoryTransactionService;
    public $warehouse = null;

    // حقن الخدمة في الـ constructor
    public function __construct(InventoryTransactionService $inventoryTransactionService)
    {
        $this->inventoryTransactionService = $inventoryTransactionService;
    }

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
        $warehouses = Warehouse::ForUserWarehouse()->get();
        $company = Company::forUserCompany()->first();

        $query = DB::table('inventory_products as ip')
            ->join('products as p', 'ip.product_id', '=', 'p.id')
            ->join('warehouses as w', 'ip.warehouse_id', '=', 'w.id')
            ->join('inventory_transaction_items as iti', 'ip.inventory_transaction_item_id', '=', 'iti.id')
            ->join('inventory_transactions as it', 'iti.inventory_transaction_id', '=', 'it.id')
            ->join('inventory_audit_warehouses as iaw', 'w.id', '=', 'iaw.warehouse_id')
            ->join('inventory_audits as ia', 'iaw.inventory_audit_id', '=', 'ia.id')
            ->join('warehouse_storage_areas as ws', 'ip.storage_area_id', '=', 'ws.id')
            ->join('warehouse_locations as wl', 'ip.location_id', '=', 'wl.id')

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
                'p.sku',
                'wl.rack_code',
                'ws.area_name',
                DB::raw('SUM(ip.converted_quantity * ip.distribution_type) as total_quantity')
            )->groupBy('w.id', 'w.name', 'p.id', 'p.name', 'p.sku','wl.rack_code',
                'ws.area_name','ip.batch_number');
        } else {
            // الحالة الافتراضية، التجميع حسب المستودع والمنتج فقط
            $query->select(
                'w.id as warehouse_id',
                'w.name as warehouse_name',
                'p.id as product_id',
                'p.name as product_name',
                'p.sku',
                
                
                DB::raw('SUM(ip.converted_quantity * ip.distribution_type) as total_quantity')
            )->groupBy('w.id', 'w.name', 'p.id', 'p.sku', 'p.name' );
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
        $audits = $query->with(['users', 'warehouses', 'subType'])->get();
       
        
        // استخراج الأنواع المستخدمة في الجرد (distinct)
        $usedTypeIds = InventoryAudit::distinct()->pluck('inventory_type');
        
        $subTypes = InventoryTransactionSubtype::where('transaction_type_id', 8)
        ->whereIn('id', $usedTypeIds)
        ->get();
    
        $subTypeOptions = $subTypes->pluck('name', 'id'); // [id => name]

        return view('inventory.audit.index', compact('audits', 'subTypeOptions'));
        
    }

    public function createInventoryAuditTransaction($auditId, int $warehouseId, $groupByBatch = true)
    {

        // try {
            $query = DB::table('inventory_products')
            ->where('warehouse_id', $warehouseId)
            ->select(
                'product_id',
                'unit_product_id',
                'price',
                'production_date',
                'expiration_date',
                DB::raw('SUM(converted_quantity * distribution_type) as total_quantity'),
                DB::raw('MIN(created_at) as created_at')
            )
            ->groupBy('product_id', 'unit_product_id', 'price', 'production_date', 'expiration_date')
            ->orderBy('created_at','ASC');
        

        // تطبيق التجميع بناءً على قيمة groupByBatch
        if ($groupByBatch) {
            $query->addSelect('batch_number')->groupBy('product_id', 'batch_number', 'unit_product_id', 'price', 'production_date', 'expiration_date');
        } else {
            $query->groupBy('product_id', 'unit_product_id', 'price', 'production_date', 'expiration_date');
        }

        //  dd($query);  

        $products = $query->get();
        // dd($products);
        // 2. تجهيز بيانات الحركة الرئيسية
        // تجهيز بيانات الحركة الرئيسية
        $transactionData = [
            '_token'             => csrf_token(),
            'transaction_type_id' => 8, // نوع الحركة (الجرد)
            'transaction_date'   => now()->toDateTimeString(), // تأكد من تنسيق التاريخ
            'effect'             => 0,
            'reference'          => 'audt-' . $warehouseId . '-' . $auditId, // المرجع بناءً على ID المستودع وID الجرد
            'partner_id'         => 36, // ID الشريك (يمكن تعديله بناءً على الحاجة)
            'warehouse_id'       => $warehouseId,
            'secondary_warehouse_id' => null, // إذا كان لديك مستودع ثانوي، يمكنك تحديده هنا
            'notes'              => 'عملية جرد تلقائية ',
            'products'           => [],
            'units'              => [],
            'quantities'         => [],
            'batchs'         => [],
            'unit_prices'        => [],
            'totals'             => [],
            'warehouse_locations' => [],
            'production_date'    => [],
            'expiration_date'    => []
        ];

        // دمج بيانات المنتجات مع الحركة
        foreach ($products as $product) {
            $transactionData['products'][]           = $product->product_id;
            $transactionData['units'][]              = $product->unit_product_id;
            $transactionData['quantities'][]         = $product->total_quantity; // الكمية الإجمالية التي تم حسابها
            $transactionData['batchs'][]         = $product->batch_number ?? null; // الكمية الإجمالية التي تم حسابها
            $transactionData['unit_prices'][]        = $product->price;
            $transactionData['totals'][]             = $product->total_quantity * $product->price; // حساب الإجمالي
            $transactionData['warehouse_locations'][] = null; // يمكنك إضافة الموقع إذا كان لديك بيانات عنه
            $transactionData['production_date'][]    = $product->production_date;
            $transactionData['expiration_date'][]    = $product->expiration_date;
        }
        // dd($transactionData);
        // استدعاء الخدمة لإنشاء العملية المخزنية وإطلاق الحدث
        $transaction = $this->inventoryTransactionService->createTransaction($transactionData);

        // إرجاع استجابة JSON إذا كان الطلب من نوع JSON
        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'تمت إضافة العملية المخزنية بنجاح',
                'transaction' => $transaction
            ], 201);
        }

        return $transaction;
        // } catch (\Exception $e) {
        //     return redirect()->back()
        //         ->withInput()
        //         ->withErrors(['error' => 'حدث خطأ أثناء إضافة العملية المخزنية: ' . $e->getMessage()]);
        // }
    }


    public function create()
{
    $users = User::all();
    $warehouses = Warehouse::all();
    $subTypes = InventoryTransactionSubtype::where('transaction_type_id', 8)->get();

    return view('inventory.audit.create', compact('users', 'warehouses', 'subTypes'));
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

     // عرض صفحة تعديل العملية المخزنية
     public function editTrans($id)
     {
         try {
             // $transaction = InventoryTransaction::findOrFail($id);
             // $transactionTypes = TransactionType::all();
             // $partners = Partner::all();
             // $departments = Department::all();
             // // $warehouses = Warehouse::all();
             $warehouses = Warehouse::ForUserWarehouse()->get();
             $units = Unit::all(); // جلب جميع الوحدات
 
             $products = Product::all();
             // $warehouseLocations = WarehouseLocation::all();
             $selectedTransaction = InventoryTransaction::with(['items.product', 'items.unit'])->find($id);
             $items = $selectedTransaction->items()->paginate(6);
 
             return view('inventory.audit.editTrans', compact('selectedTransaction', 'products', 'warehouses', 'units', 'items'));
         } catch (\Exception $e) {
             return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء تحميل بيانات العملية المخزنية: ' . $e->getMessage()]);
         }
     }
     public function updateTrans(Request $request, $id)
     {
         try {
             // $transaction = InventoryTransaction::findOrFail($id);
             // dd($request);
             $transaction = $this->inventoryTransactionService->updateTransaction($id, $request->all());
             // إرجاع استجابة بناءً على نوع الطلب (JSON أو View)
             if ($request->expectsJson()) {
                 return response()->json([
                     'message' => 'تمت إضافة العملية المخزنية بنجاح',
                     'transaction' => $transaction
                 ], 201);
             }
             return redirect()->route('inventory.transactions.editTrans', $id)->with('success', 'تم تحديث العملية المخزنية بنجاح');
         } catch (\Exception $e) {
             return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء تحديث العملية المخزنية: ' . $e->getMessage()]);
         }
     }
 
 
}

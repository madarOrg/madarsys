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
use App\Models\InventoryTransactionItem;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\InventoryTransaction\InventoryTransactionService;
use App\Rules\AfterSystemStartDate;

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
            )->groupBy(
                'w.id',
                'w.name',
                'p.id',
                'p.name',
                'p.sku',
                'wl.rack_code',
                'ws.area_name',
                'ip.batch_number'
            );
        } else {
            // الحالة الافتراضية، التجميع حسب المستودع والمنتج فقط
            $query->select(
                'w.id as warehouse_id',
                'w.name as warehouse_name',
                'p.id as product_id',
                'p.name as product_name',
                'p.sku',


                DB::raw('SUM(ip.converted_quantity * ip.distribution_type) as total_quantity')
            )->groupBy('w.id', 'w.name', 'p.id', 'p.sku', 'p.name');
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
        $subTypes = InventoryTransactionSubtype::where('transaction_type_id', 8)->get();

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

        return view('inventory.audit.index', compact('audits', 'subTypeOptions','subTypes'));
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
        // DD('S');

        $validated = $request->validate([
            'inventory_type' => 'required|integer',
            'start_date' => ['required', 'date', new AfterSystemStartDate],
            'end_date' => ['required', 'date', new AfterSystemStartDate, 'after_or_equal:start_date'],
            'notes' => 'nullable|string',
            'users' => 'required|array',
            'warehouses' => 'required|array',
        ]);
        // DD(new AfterSystemStartDate);

        $auditCode = InventoryAudit::generateAuditCode();
        // DD($auditCode);

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

        // ربط المستودعات بالجرد
        foreach ($validated['warehouses'] as $warehouseId) {
            InventoryAuditWarehouse::create([
                'inventory_audit_id' => $inventoryAudit->id,
                'warehouse_id' => $warehouseId,
            ]);
        }

        return redirect()->route('inventory.audit.index')->with('success', 'تم إنشاء عملية الجرد بنجاح.');
    }

    public function updateItem(Request $request)
{
    // التحقق من صحة البيانات المرسلة
    $validated = $request->validate([
        'product_id' => 'required|exists:inventory_transaction_items,id',
        'quantity' => 'required|numeric',
        'quantity_expected' => 'required|numeric',
        'unit_price' => 'required|numeric',
        'total' => 'required|numeric',
    ]);

    // جلب الصنف المطلوب تحديثه
    $item = InventoryTransactionItem::find($request->product_id);

    if (!$item) {
        return response()->json(['error' => 'الصنف غير موجود'], 404);
    }

    // تحديث بيانات الصنف
    // dd( $request->quantity);
    $item->quantity = $request->quantity;
    $item->quantity_expected = $request->quantity_expected;
    $item->unit_price = $request->unit_price;
    $item->total = $request->total;
    $item->save();

    return response()->json(['message' => 'تم تحديث الصنف بنجاح!']);
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
            $warehouses = Warehouse::ForUserWarehouse()->get();
            $units = Unit::all(); // جلب جميع الوحدات
    
            // جلب العملية المخزنية المطلوبة
            $transOfAudit = DB::table('inventory_transactions')
                ->where('inventory_request_id', $id)
                ->where('transaction_type_id', 8)
                ->first(); // تصحيح هنا: استخدام first بدلاً من select
    
            if (!$transOfAudit) {
                return redirect()->back()->withErrors(['error' => 'لم يتم العثور على العملية المطلوبة']);
            }
    
            // الآن استخدم find مع id الصحيح وجلب العلاقات
            $selectedTransaction = InventoryTransaction::with(['products', 'items.product', 'items.unit'])->find($transOfAudit->id);
    
            if (!$selectedTransaction) {
                return redirect()->back()->withErrors(['error' => 'لم يتم العثور على بيانات العملية']);
            }
    
            $products = $selectedTransaction->products; // المنتجات المرتبطة بالعملية
            $items = $selectedTransaction->items()->paginate(6); // العناصر المرتبطة بالعملية
    // dd('selectedTransaction',$selectedTransaction->id);
            return view('inventory.audit.editTrans', compact('selectedTransaction', 'products', 'warehouses', 'units', 'items'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء تحميل بيانات العملية المخزنية-editTrans: ' . $e->getMessage()]);
        }
    }
    

    /**
     * عرض صفحة تعديل المنتج المخزني.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */

     public function updateTrans(Request $request, $id)
     {
         \Log::info($request->all()); // your debugging
     
         $data = $request->validate([
           'items'              => 'nullable|array|min:1',
           'items.*.id'         => 'nullable|integer|exists:inventory_transaction_items,id',
           'items.*.quantity'   => 'nullable|numeric',
           'items.*.expected'   => 'nullable|numeric',
           'items.*.unit_price'      => 'nullable|numeric',
         ]);
     
         foreach ($data['items'] as $row) {
           $item = InventoryTransactionItem::findOrFail($row['id']);
           $item->quantity               = $row['quantity'];
           $item->expected_audit_quantity= $row['expected'];
           $item->unit_prices                  = $row['unit_price'];
           $item->save();
         }
     
         return response()->json(['message'=>'تم تحديث الجرد بنجاح']);
     }
     
    public function replaceAuditTransaction(Request $request)
    {
        $transactionId = $request->transaction_id;
        $auditId = $request->audit_id;

        $transaction = InventoryTransaction::findOrFail($transactionId);

        DB::beginTransaction();

        try {
            // حذف العناصر المرتبطة أولاً
            $transaction->items()->delete();
            $transaction->delete();

            DB::commit();

            return response()->json([
                'message' => 'تم حذف الحركة القديمة بنجاح، يمكنك الآن إنشاء حركة جديدة.',
                'audit_id' => $auditId,
                'status' => 'success'
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'error' => 'فشل حذف الحركة: ' . $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }

    public function storeInventoryTransaction(object $transactionData, array|Collection $items)
    {
        return DB::transaction(function () use ($transactionData, $items) {
            try {
                $transaction = InventoryTransaction::create([
                    'transaction_type_id'   => 8,
                    'effect'                => 0,
                    'transaction_date'      => now(),
                    'reference'             => $transactionData->reference,
                    'partner_id'            => $transactionData->partner_id,
                    'warehouse_id'          => $transactionData->warehouse_id,
                    'branch_id'             => $transactionData->branch_id,
                    'department_id'         => null,
                    'inventory_request_id'  => $transactionData->inventory_request_id ?? null,
                    'secondary_warehouse_id' => null,
                    'notes'                 => $transactionData->notes,
                    'status'                => 0,
                    'sub_type_id'           => $transactionData->inventory_type ?? null,
                ]);

                // لو نجح الإنشاء، هنا سينفّذ الـ dd
                // dd('تمّ الإنشاء بنجاح:', $transaction);
            } catch (\Throwable $e) {
                // يطبع لك رسالة الخطأ وسطر الكود
                dd('خطأ أثناء الإنشاء:', $e->getMessage(), $e->getTraceAsString());
            }

            // dd($items);


            // إنشاء العناصر المرتبطة بالحركة
            foreach ($items as $item) {
                $transaction->items()->create([
                    'product_id'      => $item['product_id'],
                    'unit_id'         => $item['unit_id'],
                    'expected_audit_quantity' => $item['quantity'],
                    'quantity'        => 0,
                    'price'           => $item['price'],
                    'batch_number'    => $item['batch_number'],
                    'production_date' => $item['production_date'],
                    'expiration_date' => $item['expiration_date'],
                ]);
            }
        });
    }

    public function createInventoryAuditTransaction(Request $request, $auditId, int $warehouseId, $groupByBatch = true)
    {
        // DD($auditId);

        $audit = InventoryAudit::findOrFail($auditId);
        // DD($audit);
        try {
            // 1. تجهيز بيانات الأصناف من جدول inventory_products
            $query = DB::table('inventory_products')
                ->where('warehouse_id', $warehouseId)
                // ->where('created_at', '<=', $audit->end_date) // شرط الفترة
                ->select(
                    'product_id',
                    'unit_product_id as unit_id',
                    'unit_product_id',
                    'price',
                    'production_date',
                    'expiration_date',
                    DB::raw('SUM(converted_quantity * distribution_type) as quantity'),
                    DB::raw('MIN(created_at) as created_at')
                );

            if ($groupByBatch) {
                $query->addSelect('batch_number')
                    ->groupBy('product_id', 'unit_product_id', 'price', 'production_date', 'expiration_date', 'batch_number');
            } else {
                $query->groupBy('product_id', 'unit_product_id', 'price', 'production_date', 'expiration_date');
            }
            // dd($query);
            $items = $query->get()
                ->map(function ($i) use ($warehouseId) {
                    return [
                        'product_id'           => $i->product_id,
                        'unit_id'              => $i->unit_id,
                        'unit_product_id'      => $i->unit_id,
                        'quantity'             => $i->quantity,
                        'converted_quantity'   => $i->quantity,
                        'price'                => $i->price,
                        'batch_number'         => $i->batch_number ?? null,
                        'production_date'      => $i->production_date ?? null,
                        'expiration_date'      => $i->expiration_date ?? null,
                        'target_warehouse_id'  => $warehouseId
                    ];
                })
                ->toArray();
            // dd($request->all());
            // dd($audit);


            // 3. إعداد بيانات الحركة
            $transactionData = (object)[
                'partner_id' => $audit->partner_id ?? 36, // قيمة افتراضية
                'warehouse_id' => $warehouseId,
                'inventory_type' => $audit->inventory_type,
                'branch_id'      => $audit->branch_id   ?? null,
                'reference'             => $audit->inventory_code,
                'notes' => 'عملية جرد تلقائية مرتبطة بامر جرد رقم: ' . $auditId,
                'inventory_request_id' => $auditId
            ];
            // dd($transactionData);
            $existingTransaction = InventoryTransaction::where('reference', $audit->inventory_code)->first();
            // dd($existingTransaction);

            if ($existingTransaction) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'يوجد بالفعل حركة مرتبطة بنفس رمز الجرد (reference)',
                        'action_required' => true,
                        'options' => [
                            'delete_old' => route('inventory.audit.replace'),
                            'transaction_id' => $existingTransaction->id,
                            'audit_id' => $auditId,
                            'cancel' => 'cancel',
                        ]
                    ]);
                }

                return redirect()->back()->with([
                    'error' => 'يوجد بالفعل حركة مرتبطة بنفس رمز الجرد',
                    'replace_route' => route('inventory.audit.replace'),
                    'transaction_id' => $existingTransaction->id,
                    'audit_id' => $auditId,
                ]);
            }

            // 4. حفظ الحركة عبر نفس الدالة المستخدمة في الفواتير
            $inventoryTransaction = $this->storeInventoryTransaction(
                $transactionData,
                $items,
            );
            // dd('end');
            DB::commit();

            // if ($request->expectsJson()) {
            //     return response()->json([
            //         'message'      => 'تمت إضافة حركة الجرد بنجاح',
            //         'transaction'  => $inventoryTransaction,
            //         'redirect_url' => route('inventory.audit.editTrans', $inventoryTransaction->id),
            //         'status' => 'success'
            //     ], 200);
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'تمت إضافة العملية المخزنية بنجاح',
                    'transaction' => $inventoryTransaction,
                    'redirect_url' => route('inventory.audit.editTrans', $inventoryTransaction->id),

                ], 200); // تغيير الكود إلى 200 لأنه تم تحديث العملية بنجاح
            
                
            }

            // الباقي كما هو للرد العادي (redirect back أو view)

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء عملية الجرد: ' . $e->getMessage()]);
        }
    }

    // /////////////////////////////////////////
    

    /**
     * عرض تقرير الجرد بناءً على الرقم المدخل.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    
     public function showAuditReport($id)
     {
         $auditId = $id;
         if (!$auditId) {
             return redirect()->back()->withErrors(['error' => 'لم يتم تحديد رقم الجرد']);
         }
     
         // جلب جميع الحركات المخزنية المرتبطة برقم الجرد
         $transactions = InventoryTransaction::with(['products', 'items.product', 'items.unit', 'warehouse','items.product.Category'])
             ->where('inventory_request_id', $auditId)
             ->where('transaction_type_id', 8)
             ->get();
     
         if ($transactions->isEmpty()) {
             return redirect()->back()->withErrors(['error' => 'لم يتم العثور على أي حركات مخزنية مرتبطة بالجرد']);
         }
     
         return view('inventory.audit.audit_report', compact('transactions'));
     }
     
}



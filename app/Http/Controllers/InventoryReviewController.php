<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\Unit;
use App\Models\WarehouseLocation;
use App\Models\TransactionType;
use App\Models\Partner;
use App\Models\Department;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreInventoryTransactionRequest;
use App\Services\InventoryTransaction\InventoryTransactionService;


class InventoryReviewController extends Controller
{
    protected $inventoryTransactionService;

    public function __construct(InventoryTransactionService $inventoryTransactionService)
    {
        $this->inventoryTransactionService = $inventoryTransactionService;
    }

    public function getEffectByTransactionType($transactionTypeId)
    {
        try {
            $transactionType = TransactionType::find($transactionTypeId);
            if ($transactionType) {
                return response()->json([
                    'effect' => $transactionType->effect ?? '-'
                ], 200, ['Content-Type' => 'application/json']);
            }

            // في حال لم يتم العثور على نوع العملية، ارجع 0 كقيمة افتراضية
            return response()->json([
                'effect' => '-'
            ], 200, ['Content-Type' => 'application/json']);
        } catch (\Exception $e) {
            return response()->json([
                'effect' => 0,
                'message' => 'حدث خطأ أثناء جلب التأثير: ' . $e->getMessage()
            ]);
        }
    }
    public function index()
    {
        try {
            $transactions = $this->inventoryTransactionService->getAllTransactions();
            //   dd($transactions);
            // التحقق مما إذا كان الطلب API أم صفحة عرض
            if (request()->expectsJson()) {
                return response()->json(['transactions' => $transactions], 200);
            }

            return view('inventory-review.index', compact('transactions'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء جلب العمليات المخزنية: ' . $e->getMessage()]);
        }
    }


    // عرض النموذج لإنشاء عملية مخزنية جديدة
    public function create()
    {
        try {
            // جلب البيانات اللازمة للعرض
            $transactionTypes = TransactionType::all();
            $partners = Partner::all();
            $departments = Department::all();
            // $warehouses = Warehouse::all();
            $warehouses = Warehouse::ForUserWarehouse()->get();

            $products = Product::with('unit')->get(); // جلب المنتجات مع الوحدات
            $units = Unit::all(); // جلب جميع الوحدات
            $warehouseLocations = WarehouseLocation::all();

            return view('inventory-review.create', compact('transactionTypes', 'partners', 'departments', 'warehouses', 'products', 'warehouseLocations'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل البيانات: ' . $e->getMessage()]);
        }
    }

    public function store(StoreInventoryTransactionRequest $request)
    {
        try {
            // dd($request->all());
            // استدعاء الخدمة لإنشاء العملية المخزنية
            $transaction = $this->inventoryTransactionService->createTransaction($request->all());

            // إرجاع استجابة بناءً على نوع الطلب (JSON أو View)
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'تمت إضافة العملية المخزنية بنجاح',
                    'transaction' => $transaction
                ], 201);
            }

            return redirect()->route('inventory-review.create')->with('success', 'تمت إضافة العملية المخزنية بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'حدث خطأ أثناء إضافة العملية المخزنية: ' . $e->getMessage()]);
        }
    }

    // عرض تفاصيل العملية المخزنية
    public function show($id)
    {
        try {
            $transaction = InventoryTransaction::findOrFail($id);
            $items = $transaction->items;

            return view('inventory-review.show', compact('transaction', 'items'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء تحميل تفاصيل العملية المخزنية: ' . $e->getMessage()]);
        }
    }

    // عرض صفحة تعديل العملية المخزنية
    public function edit($id)
    {
        try {
            $transaction = InventoryTransaction::findOrFail($id);
            $transactionTypes = TransactionType::all();
            $partners = Partner::all();
            $departments = Department::all();
            // $warehouses = Warehouse::all();
            $warehouses = Warehouse::ForUserWarehouse()->get();

            $products = Product::all();
            $warehouseLocations = WarehouseLocation::all();

            return view('inventory-review.edit', compact('transaction', 'transactionTypes', 'partners', 'departments', 'warehouses', 'products', 'warehouseLocations'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء تحميل بيانات العملية المخزنية: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $transaction = InventoryTransaction::findOrFail($id);
            $transaction = $this->inventoryTransactionService->updateTransaction($id, $request->all());
            // إرجاع استجابة بناءً على نوع الطلب (JSON أو View)
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'تمت إضافة العملية المخزنية بنجاح',
                    'transaction' => $transaction
                ], 201);
            }
            return redirect()->route('inventory-review.show', $id)->with('success', 'تم تحديث العملية المخزنية بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء تحديث العملية المخزنية: ' . $e->getMessage()]);
        }
    }

    // حذف العملية المخزنية
    public function destroy($id)
    {
        try {
            $transaction = InventoryTransaction::findOrFail($id);
            $transaction->delete();

            return redirect()->route('inventory-review.index')->with('success', 'تم حذف العملية المخزنية بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء حذف العملية المخزنية: ' . $e->getMessage()]);
        }
    }
}



// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\InventoryTransaction;

// class InventoryReviewController extends Controller
// {
//     // // قائمة الحركات المعلقة مؤقتًا في الذاكرة
//     // protected $pendingReviews = [
//     //     ['id' => 1, 'transaction_number' => 'T123', 'status' => 'pending', 'reviewer' => 'Admin'],
//     //     ['id' => 2, 'transaction_number' => 'T124', 'status' => 'pending', 'reviewer' => 'User'],
//     //     ['id' => 3, 'transaction_number' => 'T125', 'status' => 'pending', 'reviewer' => 'Admin'],
//     // ];

//     // عرض الحركات المراجعة المعلقة
//     public function index()
//     {
//         $transactions = InventoryTransaction::with('inventoryItems.branch', 'inventoryItems.targetWarehouse', 'inventoryItems.inventoryProducts.location', 'inventoryItems.inventoryProducts.storageArea')
//             ->latest()
//             ->get();
    
//         return view('inventory-review.index', compact('transactions'));
//     }
    

//     // تحديث حالة المراجعة
//     public function updateStatus(Request $request, $id)
//     {
//         try {
//             // البحث عن الحركة المعلقة بواسطة ID
//             $review = InventoryTransaction::find($id);
//             if ($review) {
//                 // تحديث الحالة بناءً على القيمة المرسلة من العميل
//                 $review->status = $request->status;
//                 $review->save(); // حفظ التغييرات في قاعدة البيانات
//             }
    
//             return response()->json([
//                 'message' => 'تم تحديث الحالة بنجاح',
//                 'status' => $request->status,
//                 'id' => $id
//             ], 200);
//         } catch (\Exception $e) {
//             return response()->json([
//                 'message' => 'حدث خطأ أثناء تحديث الحالة: ' . $e->getMessage()
//             ], 500);
//         }
//     }
    
// }

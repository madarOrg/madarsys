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


class InventoryTransactionController extends Controller
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
    // dd($transactions);
        // التحقق مما إذا كان الطلب API أم صفحة عرض
        if (request()->expectsJson()) {
            return response()->json(['transactions' => $transactions], 200);
        }
    
        return view('inventory.transactions.index', compact('transactions'));
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء جلب العمليات المخزنية: ' . $e->getMessage()]);
    }
}

    
    // عرض النموذج لإنشاء عملية مخزنية جديدة
    public function create(Request $request)
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
            session()->flash('products', $request->products);

            return view('inventory.transactions.create', compact('transactionTypes', 'partners', 'departments', 'warehouses', 'products', 'warehouseLocations'));
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

            return redirect()->route('inventory.transactions.create')->with('success', 'تمت إضافة العملية المخزنية بنجاح');
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

            return view('inventory.transactions.show', compact('transaction', 'items'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء تحميل تفاصيل العملية المخزنية: ' . $e->getMessage()]);
        }
    }

    // عرض صفحة تعديل العملية المخزنية
    public function edit($id)
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

            return view('inventory.transactions.edit', compact('selectedTransaction','products','warehouses','units','items'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء تحميل بيانات العملية المخزنية: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
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
            return redirect()->route('inventory.transactions.edit', $id)->with('success', 'تم تحديث العملية المخزنية بنجاح');
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

            return redirect()->route('inventory.transactions.index')->with('success', 'تم حذف العملية المخزنية بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء حذف العملية المخزنية: ' . $e->getMessage()]);
        }
    }


    
}

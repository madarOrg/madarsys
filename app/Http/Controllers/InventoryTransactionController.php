<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionItem;
use App\Models\Product;
use App\Models\Unit;
use App\Models\WarehouseLocation;
use App\Models\TransactionType;
use App\Models\Partner;
use App\Models\Department;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreInventoryTransactionRequest;

use App\Services\InventoryCalculationService;


class InventoryTransactionController extends Controller
{    protected $inventoryTransactionService;

    public function __construct(InventoryCalculationService $inventoryTransactionService)
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
                'message' => 'Error retrieving effect: ' . $e->getMessage()
            ]);
        }
    }
    
    
    
    // عرض النموذج لإنشاء عملية مخزنية جديدة
    public function create()
    {
        // جلب البيانات اللازمة للعرض
        $transactionTypes = TransactionType::all();
        $partners = Partner::all();
        $departments = Department::all();
        $warehouses = Warehouse::all();
        $products = Product::with('unit')->get(); // جلب المنتجات مع الوحدات
        $units = Unit::all(); // جلب جميع الوحدات
        $warehouseLocations = WarehouseLocation::all();

        return view('inventory.transactions.create', compact('transactionTypes', 'partners', 'departments', 'warehouses', 'products', 'warehouseLocations'));
    }

  
    public function store(StoreInventoryTransactionRequest $request)
{       
   try {
            // إنشاء العملية المخزنية
            $transaction = InventoryTransaction::create([
                'transaction_type_id'  => $request->transaction_type_id,
                'effect'               => $request->effect,
                'transaction_date'     => $request->transaction_date,
                'reference'            => $request->reference,
                'partner_id'           => $request->partner_id,
                'department_id'        => $request->department_id,
                'warehouse_id'         => $request->warehouse_id,
                'notes'                => $request->notes,
                'inventory_request_id' => $request->inventory_request_id,
            ]);
    
            // التكرار على المنتجات المخزنية
            foreach ($request->products as $index => $productId) {
                $quantity = $request->quantities[$index];
                $unitId = $request->units[$index] ?? null;
                $productUnit=Product::find($productId)->unit_id; // الوحدة الأساسية للمنتج

                //  dd($productUnit);
                $unitPrice = $request->unit_prices[$index] ?? 0;
                $totalPrice = $request->totals[$index] ?? 0;

                // تطبيق التأثير على الكمية (إدخال أو إخراج)
                $quantity = $this->inventoryTransactionService->applyEffectToQuantity($quantity, $request->effect);
                
                // حساب الكمية المحولة بناءً على معامل التحويل للوحدة
                $convertedQuantity = $this->inventoryTransactionService->calculateConvertedQuantity($quantity, $unitId);
                
                // حساب إجمالي السعر
                $totalPrice = $this->inventoryTransactionService->calculateTotalPrice($convertedQuantity, $unitPrice,$totalPrice);
        
                // حفظ تفاصيل العملية المخزنية
                InventoryTransactionItem::create([
                    'inventory_transaction_id' => $transaction->id,
                    'unit_id'                   => $unitId,
                    'product_id'                => $productId,
                    'quantity'                  => $quantity,
                    'unit_prices'               => $unitPrice,
                    'total'                     => $totalPrice,
                    'warehouse_location_id'     => $request->warehouse_locations[$index] ?? null,
                    'converted_quantity'        => $convertedQuantity,
                    'unit_product_id'           => $productUnit,//Product::find($productId)->unit_id, // الوحدة الأساسية للمنتج

                ]);
            }
    
            // إعادة التوجيه مع رسالة نجاح
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
        $transaction = InventoryTransaction::findOrFail($id);
        $items = $transaction->items;

        return view('inventory.transactions.show', compact('transaction', 'items'));
    }

    // عرض صفحة تعديل العملية المخزنية
    public function edit($id)
    {
        $transaction = InventoryTransaction::findOrFail($id);
        $transactionTypes = TransactionType::all();
        $partners = Partner::all();
        $departments = Department::all();
        $warehouses = Warehouse::all();
        $products = Product::all();
        $warehouseLocations = WarehouseLocation::all();

        return view('inventory.transactions.edit', compact('transaction', 'transactionTypes', 'partners', 'departments', 'warehouses', 'products', 'warehouseLocations'));
    }

    // تحديث العملية المخزنية
    public function update(Request $request, $id)
    {
        $transaction = InventoryTransaction::findOrFail($id);
        $transaction->update([
            'transaction_type_id' => $request->transaction_type_id,
            'transaction_date' => $request->transaction_date,
            'reference' => $request->reference,
            'partner_id' => $request->partner_id,
            'department_id' => $request->department_id,
            'warehouse_id' => $request->warehouse_id,
            'notes' => $request->notes,
        ]);

        // هنا يمكن إضافة منطق تحديث تفاصيل العملية المخزنية أيضًا حسب الحاجة.

        return redirect()->route('inventory.transactions.show', $id)->with('success', 'تم تحديث العملية المخزنية بنجاح');
    }

    // حذف العملية المخزنية
    public function destroy($id)
    {
        $transaction = InventoryTransaction::findOrFail($id);
        $transaction->delete();

        return redirect()->route('inventory.transactions.index')->with('success', 'تم حذف العملية المخزنية بنجاح');
    }


}
   




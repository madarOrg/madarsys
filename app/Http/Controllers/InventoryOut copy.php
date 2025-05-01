<?php

namespace App\Http\Controllers;

use App\Models\InventoryProduct;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class InventoryOut extends Controller
{
    public function approveTransactionManual(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|integer|exists:inventory_transactions,id',
        ]);

        $result = $this->approveTransaction($request->transaction_id,$request->method);

        return back()->with('message', $result);
    }

    public function approveTransaction($transactionId,$method)
    {
        DB::beginTransaction();

        try {
            $transaction = InventoryTransaction::with('items')->findOrFail($transactionId);

            if ($transaction->status === 2) {
                throw new Exception("تم اعتماد هذه الحركة مسبقًا.");
            }

            foreach ($transaction->items as $item) {

                $selectedBatchId = $request->input("selected_batches.{$item->id}");

                if ($selectedBatchId) {
                    // سحب يدوي من دفعة معينة
                    $batch = InventoryProduct::where('id', $selectedBatchId)
                        ->where('product_id', $item->product_id)
                        ->where('warehouse_id', $transaction->warehouse_id)
                        ->where('distribution_type', 1)
                        ->with(['withdrawals' => function ($query) {
                            $query->where('distribution_type', -1);
                        }])
                        ->firstOrFail();
                
                    $withdrawnQty = abs($batch->withdrawals->sum('quantity'));
                    $availableQty = $batch->quantity - $withdrawnQty;
                
                    $requiredQty = abs($item->quantity);
                
                    if ($availableQty < $requiredQty) {
                        throw new Exception("الدفعة المحددة لا تحتوي على كمية كافية للمنتج ID {$item->product_id}.");
                    }
                
                    InventoryProduct::create([
                        'item_source_id' => $batch->id,
                        'batch_number' => $batch->batch_number,
                        'production_date' => $batch->production_date,
                        'expiration_date' => $batch->expiration_date,
                        'product_id' => $item->product_id,
                        'branch_id' => $batch->branch_id,
                        'warehouse_id' => $transaction->warehouse_id,
                        'storage_area_id' => $batch->storage_area_id,
                        'location_id' => $batch->location_id,
                        'inventory_transaction_item_id' => $item->id,
                        'quantity' => -$requiredQty,
                        'temporary_transfer_expiry_date' => $batch->temporary_transfer_expiry_date,
                        'distribution_type' => -1,
                        'unit_id' => $batch->unit_id,
                        'unit_product_id' => $batch->unit_product_id,
                        'converted_quantity' => $batch->converted_quantity,
                        'price' => $batch->price,
                        'created_user' => Auth::id(),
                        'updated_user' => Auth::id(),
                    ]);
                
                    $inventory->decrement('quantity', $requiredQty);
                
                    continue; // انتقل للمنتج التالي
                }
                
                // $remainingQty = abs($item->quantity);
                $alreadyWithdrawnQty = InventoryProduct::where('inventory_transaction_item_id', $item->id)
                    ->where('distribution_type', -1)
                    ->sum(DB::raw('ABS(quantity)'));

                $remainingQty = abs($item->quantity) - $alreadyWithdrawnQty;

                if ($remainingQty <= 0) {
                    continue; // لا حاجة لسحب جديد
                }
                // التحقق من الرصيد التجميعي قبل أي عملية
                $inventory = Inventory::where('product_id', $item->product_id)
                    ->where('warehouse_id', $transaction->warehouse_id)
                    ->lockForUpdate()
                    ->first();

                if (!$inventory || $inventory->quantity < $remainingQty) {
                    throw new Exception("الرصيد غير كافٍ للمنتج ID {$item->product_id} في المستودع.");
                }

                // جلب الدفعات المتاحة مع تصفية الانسحابات السابقة
                $batches = InventoryProduct::with(['withdrawals' => function ($query) {
                    $query->where('distribution_type', -1);
                }])
                    ->where('product_id', $item->product_id)
                    ->where('warehouse_id', $transaction->warehouse_id)
                    ->where('distribution_type', 1)
                    // ->orderBy('production_date')
                    ->get()
                    ->filter(function ($batch) {
                        $withdrawnQty = abs($batch->withdrawals->sum('quantity'));
                        $availableQty = $batch->quantity - $withdrawnQty;
                        return $availableQty > 0;
                    });

                    
                    if ($method == 1) { // FEFO
                        // ترتيب حسب تاريخ انتهاء الصلاحية أولاً
                        $batchesQuery = $batches->sortBy('expiration_date')->sortBy('production_date');
                    } elseif ($method == 2) { // FIFO
                        // ترتيب حسب تاريخ الإنتاج أولاً
                        $batchesQuery = $batches->sortBy('production_date');
                    }
        
                    // إذا كانت تواريخ الإنتاج أو انتهاء الصلاحية فارغة، يتم الاعتماد على الدفعات بدون ترتيب
                    if ($batchesQuery->isEmpty()) {
                        // إذا لم توجد دفعات بالتواريخ المحددة، استخدم الدفعات المتاحة بدون ترتيب
                        $batchesQuery = $batches->sortByDesc('created_at'); // ترتيب حسب تاريخ الإضافة (أو حسب ترتيب دخول المخزون)
                    }
        
                foreach ($batchesQuery as $batch) {
                    if ($remainingQty <= 0) break;

                    $withdrawnQty = abs($batch->withdrawals->sum('quantity'));
                    $availableQty = $batch->quantity - $withdrawnQty;

                    if ($availableQty <= 0) continue;

                    $takeQty = min($availableQty, $remainingQty);

                    InventoryProduct::create([
                        'item_source_id' => $batch->id,
                        'batch_number' => $batch->batch_number,
                        'production_date' => $batch->production_date,
                        'expiration_date' => $batch->expiration_date,
                        'product_id' => $item->product_id,
                        'branch_id' => $batch->branch_id,
                        'warehouse_id' => $transaction->warehouse_id,
                        'storage_area_id' => $batch->storage_area_id,
                        'location_id' => $batch->location_id,
                        'inventory_transaction_item_id' => $item->id,
                        'quantity' => -$takeQty,
                        'temporary_transfer_expiry_date' => $batch->temporary_transfer_expiry_date,
                        'distribution_type' => -1,
                        'unit_id' => $batch->unit_id,
                        'unit_product_id' => $batch->unit_product_id,
                        'converted_quantity' => $batch->converted_quantity,
                        'price' => $batch->price,
                        'created_user' => Auth::id(),
                        'updated_user' => Auth::id(),
                    ]);

                    $remainingQty -= $takeQty;
                }

                if ($remainingQty > 0) {
                    throw new Exception("الكمية غير كافية للمنتج ID {$item->product_id} بعد محاولة السحب من جميع الدفعات.");
                }

                // خصم من الرصيد التجميعي
                $inventory->decrement('quantity', abs($item->quantity));
            }

            // اعتماد الحركة
            $transaction->status = 2;
            $transaction->approved_at = now();
            $transaction->save();

            DB::commit();
            return "تم اعتماد الحركة بنجاح.";
        } catch (\Exception $e) {
            DB::rollBack();
            return "فشل الاعتماد: " . $e->getMessage();
        }
    
}
public function printWithdrawal($id)
{
    $transaction = InventoryTransaction::with(['items.product', 'items.inventoryProducts' => function($query) {
        $query->where('distribution_type', -1); // فقط السحوبات
    }])->findOrFail($id);

    return view('inventory-products.inventory_withdrawals_print', compact('transaction'));
}
}
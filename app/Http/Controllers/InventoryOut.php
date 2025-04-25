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

        $result = $this->approveTransaction($request, $request->method);
        return back()->with('message', $result);
    }

    public function approveTransaction(Request $request, $method)
    {
        DB::beginTransaction();

        try {
            $transaction = InventoryTransaction::with('items')->findOrFail($request->transaction_id);

            if ($transaction->status === 2) {
                throw new Exception("تم اعتماد هذه الحركة مسبقًا.");
            }

            foreach ($transaction->items as $item) {
                $remainingQty = abs($item->quantity);

                $alreadyWithdrawnQty = InventoryProduct::where('inventory_transaction_item_id', $item->id)
                    ->where('distribution_type', -1)
                    ->sum(DB::raw('ABS(quantity)'));

                $remainingQty -= $alreadyWithdrawnQty;

                if ($remainingQty <= 0) continue;

                $selectedBatchId = $request->input("selected_batches.{$item->id}");

                if ($selectedBatchId) {
                    $batch = $this->getBatch($selectedBatchId, $item->product_id, $transaction->warehouse_id);

                    $availableQty = $this->getAvailableQty($batch);

                    if ($availableQty < $remainingQty) {
                        throw new Exception("الدفعة المحددة لا تحتوي على كمية كافية للمنتج ID {$item->product_id}.");
                    }

                    $this->createWithdrawal($batch, $item, $remainingQty, $transaction->warehouse_id);
                    $this->decrementInventory($item, $transaction);
                    continue;
                }

                $inventory = Inventory::where('product_id', $item->product_id)
                    ->where('warehouse_id', $transaction->warehouse_id)
                    ->lockForUpdate()
                    ->first();

                if (!$inventory || $inventory->quantity < $remainingQty) {
                    throw new Exception("الرصيد غير كافٍ للمنتج ID {$item->product_id} في المستودع.");
                }

                $batches = $this->getAvailableBatches($item->product_id, $transaction->warehouse_id);

                $sortedBatches = match ($method) {
                    1 => $batches->sortBy('expiration_date')->sortBy('production_date'),
                    2 => $batches->sortBy('production_date'),
                    default => $batches->sortByDesc('created_at'),
                };

                foreach ($sortedBatches as $batch) {
                    if ($remainingQty <= 0) break;

                    $availableQty = $this->getAvailableQty($batch);
                    if ($availableQty <= 0) continue;

                    $takeQty = min($availableQty, $remainingQty);
                    $this->createWithdrawal($batch, $item, $takeQty, $transaction->warehouse_id);
                    $remainingQty -= $takeQty;
                }

                if ($remainingQty > 0) {
                    throw new Exception("الكمية غير كافية للمنتج ID {$item->product_id} بعد محاولة السحب من جميع الدفعات.");
                }

                $this->decrementInventory($item, $transaction);
            }

            $transaction->update([
                'status' => 2,
                'approved_at' => now(),
            ]);

            DB::commit();
            return "تم اعتماد الحركة بنجاح.";
        } catch (\Exception $e) {
            DB::rollBack();
            return "فشل الاعتماد: " . $e->getMessage();
        }
    }

    private function getBatch($batchId, $productId, $warehouseId)
    {
        return InventoryProduct::where('id', $batchId)
            ->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->where('distribution_type', 1)
            ->with(['withdrawals' => function ($query) {
                $query->where('distribution_type', -1);
            }])
            ->firstOrFail();
    }
    public function getTransactionBatches($id)
    {
        $transaction = InventoryTransaction::with(['items.product'])->findOrFail($id);
    
        foreach ($transaction->items as $item) {
            $item->available_batches = InventoryProduct::where('product_id', $item->product_id)
                ->where('warehouse_id', $transaction->warehouse_id)
                ->where('distribution_type', 1)
                ->get();
        }
    
        return view('inventory-products.partials.batches', compact('transaction'));
    }
    
    private function getAvailableQty($batch)
    {
        $withdrawnQty = abs($batch->withdrawals->sum('quantity'));
        return $batch->quantity - $withdrawnQty;
    }

    private function createWithdrawal($batch, $item, $qty, $warehouseId)
    {
        InventoryProduct::create([
            'item_source_id' => $batch->id,
            'batch_number' => $batch->batch_number,
            'production_date' => $batch->production_date,
            'expiration_date' => $batch->expiration_date,
            'product_id' => $item->product_id,
            'branch_id' => $batch->branch_id,
            'warehouse_id' => $warehouseId,
            'storage_area_id' => $batch->storage_area_id,
            'location_id' => $batch->location_id,
            'inventory_transaction_item_id' => $item->id,
            'quantity' => -$qty,
            'temporary_transfer_expiry_date' => $batch->temporary_transfer_expiry_date,
            'distribution_type' => -1,
            'unit_id' => $batch->unit_id,
            'unit_product_id' => $batch->unit_product_id,
            'converted_quantity' => $batch->converted_quantity,
            'price' => $batch->price,
            'created_user' => Auth::id(),
            'updated_user' => Auth::id(),
        ]);
    }

    private function decrementInventory($item, $transaction)
    {
        Inventory::where('product_id', $item->product_id)
            ->where('warehouse_id', $transaction->warehouse_id)
            ->decrement('quantity', abs($item->quantity));
    }

    private function getAvailableBatches($productId, $warehouseId)
    {
        return InventoryProduct::with(['withdrawals' => function ($query) {
                $query->where('distribution_type', -1);
            }])
            ->where('product_id', $productId)
            ->where('warehouse_id', $warehouseId)
            ->where('distribution_type', 1)
            ->get()
            ->filter(function ($batch) {
                return $this->getAvailableQty($batch) > 0;
            });
    }

    public function printAllWithdrawals(Request $request)
    {
        $onlyUnprinted = $request->has('only_unprinted');
        $markAsPrinted = $request->has('mark_as_printed');
    
        // جلب السحوبات من جدول InventoryProduct المرتبطة بحركات سحب
        $query = \App\Models\InventoryProduct::whereHas('transactionItem.inventoryTransaction', function ($q) {
            $q->where('distribution_type', -1);
        });
        if ($onlyUnprinted) {
            $query->where('is_printed', false);
        }
        $withdrawals = $query->with([
            'transactionItem.inventoryTransaction',
            'transactionItem.product',
            'transactionItem.unit',
            'unit',
        ])->get();
        // dd($withdrawals);

        // $withdrawals = $query->with(['transactionItem.inventoryTransaction', 'unit', 'transactionItem.product'])->get();
    
        // تعليم كمطبوعة إذا طلب المستخدم
        if ($markAsPrinted) {
            foreach ($withdrawals as $withdrawal) {
                $withdrawal->update(['is_printed' => true]);
            }
        }
    
        return view('inventory-products.inventory_withdrawals_print', compact('withdrawals'));
    }
    
}

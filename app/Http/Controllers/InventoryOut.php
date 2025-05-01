<?php

namespace App\Http\Controllers;

use App\Models\InventoryProduct;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Services\InventoryTransaction\InventoryService;


class InventoryOut extends Controller {
    // protected $inventoryService;

//     // حقن خدمة InventoryService في الـ Controller عبر الـ constructor
//     public function __construct(InventoryService $inventoryService)
//     {
//         $this->inventoryService = $inventoryService;
//     }

    public function approveTransactionManual(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|integer|exists:inventory_transactions,id',
        ]);

        // $result = $this->approveTransaction($request, $request->method);
        // return back()->with('message', $result);
        $result = $this->approveTransaction($request, $request->method);

if (is_string($result)) {
    return back()->with('message', $result);
}

return $result;
    }

    public function approveTransaction(Request $request, $method)
{
    DB::beginTransaction();

    try {
        $transaction = InventoryTransaction::with('items')->findOrFail($request->transaction_id);

        if ($transaction->status === 2) {
            throw new Exception("تم اعتماد هذه الحركة مسبقًا.");
        }

        $errors = [];
        $successfulWithdrawals = [];

        foreach ($transaction->items as $item) {
            try {
                $remainingQty = abs($item->converted_quantity);
                $alreadyWithdrawnQty = InventoryProduct::where('inventory_transaction_item_id', $item->id)
                    ->where('distribution_type', -1)
                    ->sum(DB::raw('ABS(converted_quantity)'));

                $remainingQty -= $alreadyWithdrawnQty;
                if ($remainingQty <= 0) continue;

                // التحقق من دفعة مختارة
                $selectedBatchId = $request->input('selected_batches.' . $item->id);
                $batches = $this->getAvailableBatches($item->product_id, $transaction->warehouse_id);

                if ($selectedBatchId) {
                    // إذا تم اختيار دفعة يدويًا، نأخذ الدفعة المختارة فقط
                    $batch = $batches->firstWhere('id', $selectedBatchId);
                    if ($batch) {
                        $batches = collect([$batch]);
                    } else {
                        $errors[] = "الدفعة المحددة غير متاحة.";
                        continue;
                    }
                }

                // ترتيب الدفعات بناءً على مبدأ السحب
                $sortedBatches = match ($method) {
                    1 => $batches->sortBy('expiration_date')->sortBy('production_date'), // FEFO
                    2 => $batches->sortBy('production_date'), // FIFO
                    3 => $batches->sortByDesc('created_at'), // LIFO
                    default => $batches->sortByDesc('created_at'), // LIFO
                };

                $totalWithdrawn = 0;
                $withdrawals = [];

                foreach ($sortedBatches as $batch) {
                    if ($remainingQty <= 0) break;

                    $availableQty = $this->getAvailableQty($batch);
                    if ($availableQty <= 0) continue;

                    $takeQty = min($availableQty, $remainingQty);

                    $withdrawals[] = [
                        'batch' => $batch,
                        'qty' => $takeQty,
                    ];

                    $remainingQty -= $takeQty;
                    $totalWithdrawn += $takeQty;
                }

                if ($totalWithdrawn <= 0) {
                    $errors[] = "لا توجد دفعات متاحة للمنتج ID {$item->product_id}.";
                    continue;
                }

                foreach ($withdrawals as $w) {
                    $this->createWithdrawal($w['batch'], $item, $w['qty'], $transaction->warehouse_id);
                }

                if ($totalWithdrawn > 0) {
                     // استدعاء دالة updateInventoryStock من الخدمة
        
                    // $this->inventoryService->updateInventoryStock(
                    //     $request->warehouse_id,
                    //     $item->product_id,
                    //      - $totalWithdrawn,
                    //     $item->unit_prices
                    // );     
                    $inventory = Inventory::where('product_id', $item->product_id)
                                          ->where('warehouse_id', $transaction->warehouse_id)
                                          ->first();

                    if ($inventory) {
                        $inventory->update([
                            'quantity' => $inventory->quantity - $totalWithdrawn,
                            'blocked_quantity' => $inventory->blocked_quantity - $totalWithdrawn,
                        ]);                    }
                }

                $successfulWithdrawals[] = $item->id;

            } catch (\Exception $e) {
                $errors[] = $e->getMessage();
                continue;
            }
        }

        if (empty($successfulWithdrawals)) {
            DB::rollBack();
            return "فشلت العملية بالكامل. لم يتم سحب أي كميات.";
        }

        if (!empty($errors)) {
            DB::commit();
            return "تم سحب بعض العناصر بنجاح، لكن فشلت العناصر التالية:\n" . implode("\n", $errors);
        }

        $transaction->update([
            'status' => 2,
            'approved_at' => now(),
        ]);

        DB::commit();
        return $this->printAllWithdrawals($request);
        // return "تم اعتماد الحركة بنجاح.";
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
        $withdrawnQty = abs($batch->withdrawals->sum('converted_quantity'));
        return $batch->converted_quantity - $withdrawnQty;
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
            'quantity' => -$qty, // raw quantity optional
            'converted_quantity' => -$qty,
            'temporary_transfer_expiry_date' => $batch->temporary_transfer_expiry_date,
            'distribution_type' => -1,
            'unit_id' => $batch->unit_id,
            'unit_product_id' => $batch->unit_product_id,
            'price' => $batch->price,
            'created_user' => Auth::id(),
            'updated_user' => Auth::id(),
        ]);
        // dd('g');
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
        $transactionId = $request->input('transaction_id'); // الطلب الذي يحتوي على رقم الحركة
    // dd($transactionId);
        // $query = InventoryProduct::whereHas('transactionItem.inventoryTransaction', function ($q) use ($transactionId) {
        //     $q->where('distribution_type', -1);
    
        //     if ($transactionId) {
        //         $q->where('id', $transactionId); // مطابقة رقم الحركة
        //     }
        // });
        $query = InventoryProduct::where('distribution_type', -1)
        ->whereHas('transactionItem.inventoryTransaction', function ($q) use ($transactionId) {
            if ($transactionId) {
                $q->where('id', $transactionId);
            }
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
    
        if ($markAsPrinted) {
            foreach ($withdrawals as $withdrawal) {
                $this->markAsPrinted($withdrawal->id);
            }
        }
    
        return view('inventory-products.inventory_withdrawals_print', compact('withdrawals'));
    }
        
    private function markAsPrinted($itemId)
    {
        // تحديث حالة الحركة على أنها تم طباعتها
        InventoryProduct::where('id', $itemId)->update(['is_printed' => true]);
    }
    
}

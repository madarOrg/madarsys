<?php

namespace App\Services\InventoryTransaction;

use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionItem;
use App\Models\Product;

use Illuminate\Support\Facades\DB;

class InventoryTransactionService
{
    protected $inventoryCalculationService;

    public function __construct(InventoryCalculationService $inventoryCalculationService)
    {
        $this->inventoryCalculationService = $inventoryCalculationService;
    }

    /**
     * إنشاء عملية مخزنية جديدة
     */
    public function createTransaction(array $data)
    {
        DB::beginTransaction();

        try {
            // إنشاء السجل الرئيسي للعملية المخزنية
            $transaction = InventoryTransaction::create([
                'transaction_type_id'  => $data['transaction_type_id'],
                'effect'               => $data['effect'],
                'transaction_date'     => $data['transaction_date'],
                'reference'            => $data['reference'],
                'partner_id'           => $data['partner_id'],
                'department_id'        => $data['department_id'],
                'warehouse_id'         => $data['warehouse_id'],
                'secondary_warehouse_id'         => $data['secondary_warehouse_id'],
                'notes'                => $data['notes'] ?? null,
                'inventory_request_id' => $data['inventory_request_id'] ?? null,
            ]);

            // جلب بيانات نوع العملية لمعرفة عدد الحركات المخزنية
            $transactionType = DB::table('transaction_types')
                ->select('inventory_movement_count')
                ->where('id', $data['transaction_type_id'])
                ->first();

            // إضافة العناصر (المنتجات المرتبطة بالعملية المخزنية)
            foreach ($data['products'] as $index => $productId) {
                $quantity  = $data['quantities'][$index];
                $quantityInput = $data['effect'] !== null ? 
                $this->inventoryCalculationService->applyEffectToQuantity($quantity, $data['effect']) 
                : $quantity;
            
                $unitId = $data['units'][$index] ?? null;
                $productUnit = Product::find($productId)->unit_id; // الوحدة الأساسية للمنتج
                $pricePerUnit = $data['unit_prices'][$index] ?: ($data['totals'][$index] / ($data['quantities'][$index] ?: 1));
                $priceTotal = $data['totals'][$index] ?: ($data['quantities'][$index] * $pricePerUnit);

                // إذا كان نوع العملية تحويل مخزني (inventory_movement_count == 2)
                if ($transactionType && $transactionType->inventory_movement_count == 2) {

                    // الحركة الأولى: خروج (كمية سالبة)
                    $outQuantity = -$quantityInput;
                    $convertedOutQuantity = $this->inventoryCalculationService->calculateConvertedQuantity($outQuantity, $unitId);

                    InventoryTransactionItem::create([
                        'inventory_transaction_id' => $transaction->id,
                        'target_warehouse_id'         => $data['warehouse_id'],
                        'unit_id'                   => $unitId,
                        'product_id'                => $productId,
                        'quantity'                  => $outQuantity,
                        'unit_prices'               => -$pricePerUnit,
                        'total'                     => -$priceTotal,
                        'warehouse_location_id'     => $data['warehouse_locations'][$index] ?? null, // موقع المخزن المصدر
                        'converted_quantity'        => $convertedOutQuantity,
                        'unit_product_id'           => $productUnit,
                    ]);

                    // الحركة الثانية: دخول (كمية موجبة)
                    $inQuantity = abs($quantityInput);
                    $convertedInQuantity = $this->inventoryCalculationService->calculateConvertedQuantity($inQuantity, $unitId);

                    InventoryTransactionItem::create([
                        'inventory_transaction_id' => $transaction->id,
                        'target_warehouse_id'         => $data['secondary_warehouse_id'],
                        'unit_id'                   => $unitId,
                        'product_id'                => $productId,
                        'quantity'                  => $inQuantity,
                        'unit_prices'               => abs($pricePerUnit),
                        'total'                     => abs($priceTotal),
                        'warehouse_location_id'     => $data['warehouse_locations'][$index] ?? null, // موقع المخزن الوجهة
                        'converted_quantity'        => $convertedInQuantity,
                        'unit_product_id'           => $productUnit,
                    ]);
                } else {
                    // للحركات العادية (inventory_movement_count == 1)
                    $convertedQuantity = $this->inventoryCalculationService->calculateConvertedQuantity($quantity, $unitId);

                    InventoryTransactionItem::create([
                        'inventory_transaction_id' => $transaction->id,
                        'target_warehouse_id'             => $data['warehouse_id'][$index], // المخزن المصدر
                        'unit_id'                   => $unitId,
                        'product_id'                => $productId,
                        'quantity'                  => $quantity,
                        'unit_prices'               => $pricePerUnit,
                        'total'                     => $priceTotal,
                        'warehouse_location_id'     => $data['warehouse_locations'][$index] ?? null,
                        'converted_quantity'        => $convertedQuantity,
                        'unit_product_id'           => $productUnit,
                    ]);
                }
            }

            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("حدث خطأ أثناء إنشاء العملية المخزنية: " . $e->getMessage());
        }
    }
}

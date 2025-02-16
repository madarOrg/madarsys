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
                'notes'                => $data['notes'] ?? null,
                'inventory_request_id' => $data['inventory_request_id'] ?? null,
            ]);

            // إضافة العناصر (المنتجات المرتبطة بالحركة المخزنية)
            foreach ($data['products'] as $index => $productId) {
                $quantity = $data['quantities'][$index];
                $unitId = $data['units'][$index] ?? null;
                $productUnit = Product::find($productId)->unit_id; // الوحدة الأساسية للمنتج
                $unitPrice = $data['unit_prices'][$index] ?? 0;
                $totalPrice = $data['totals'][$index] ?? 0;

                // تطبيق التأثير على الكمية (إدخال أو إخراج)
                $quantity = $this->inventoryCalculationService->applyEffectToQuantity($quantity, $data['effect']);
                
                // حساب الكمية المحولة بناءً على معامل التحويل للوحدة
                $convertedQuantity = $this->inventoryCalculationService->calculateConvertedQuantity($quantity, $unitId);
                
                // حساب إجمالي السعر
                $totalPrice = $this->inventoryCalculationService->calculateTotalPrice($convertedQuantity, $unitPrice, $totalPrice);
        
                // حفظ تفاصيل العملية المخزنية
                InventoryTransactionItem::create([
                    'inventory_transaction_id' => $transaction->id,
                    'unit_id'                   => $unitId,
                    'product_id'                => $productId,
                    'quantity'                  => $quantity,
                    'unit_prices'               => $unitPrice,
                    'total'                     => $totalPrice,
                    'warehouse_location_id'     => $data['warehouse_locations'][$index] ?? null,
                    'converted_quantity'        => $convertedQuantity,
                    'unit_product_id'           => $productUnit,
                ]);
            }

            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("حدث خطأ أثناء إنشاء العملية المخزنية: " . $e->getMessage());
        }
    }
}

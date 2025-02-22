<?php

namespace App\Listeners;

use App\Events\InventoryTransactionCreated;
use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionItem;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Services\InventoryTransaction\InventoryCalculationService;

class CreateInventoryTransactionListener
{
    protected $inventoryCalculationService;

    public function __construct(InventoryCalculationService $inventoryCalculationService)
    {
        $this->inventoryCalculationService = $inventoryCalculationService;
    }
    //////////////////
    public function handle(InventoryTransactionCreated $event)
    {
        $data = $event->data;
        DB::beginTransaction();

        try {
            // إنشاء السجل الرئيسي للحركة المخزنية
            $transaction = InventoryTransaction::create([
                'transaction_type_id'  => $data['transaction_type_id'],
                'effect'               => $data['effect'],
                'transaction_date'     => $data['transaction_date'],
                'reference'            => $data['reference'],
                'partner_id'           => $data['partner_id'],
                'department_id'        => $data['department_id'],
                'warehouse_id'         => $data['warehouse_id'],
                'secondary_warehouse_id' => $data['secondary_warehouse_id'] ?? null,
                'notes'                => $data['notes'] ?? null,
                'inventory_request_id' => $data['inventory_request_id'] ?? null,
            ]);

            foreach ($data['products'] as $index => $productId) {
                $quantity = $data['quantities'][$index];
                $unitId = $data['units'][$index] ?? null;
                $productUnit = Product::find($productId)->unit_id;
                $pricePerUnit = $data['unit_prices'][$index] ?: ($data['totals'][$index] / ($quantity ?: 1));
                $priceTotal = $data['totals'][$index] ?: ($quantity * $pricePerUnit);

                // استدعاء الدالة لمعالجة العملية
                $this->createInventoryMovement($transaction, $data, $productId, $quantity, $unitId, $pricePerUnit, $priceTotal, $productUnit, $index);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception("خطأ أثناء إنشاء الحركة المخزنية: " . $e->getMessage());
        }
    }
    /////////////////create Inventory Movement
    private function createInventoryMovement($transaction, $data, $productId, $quantityInput, $unitId, $pricePerUnit, $priceTotal, $productUnit, $index)
    {
        $transactionType = DB::table('transaction_types')
        ->select('inventory_movement_count', 'effect') 
        ->where('id', $data['transaction_type_id'])
            ->first();

        if ($transactionType && $transactionType->inventory_movement_count == 2) {
            // إنشاء سجل خروج من المخزن المصدر
            $outQuantity = -$quantityInput;
            $convertedOutQuantity = $this->inventoryCalculationService->calculateConvertedQuantity($outQuantity, $unitId);

            InventoryTransactionItem::create([
                'inventory_transaction_id' => $transaction->id,
                'target_warehouse_id'      => $data['warehouse_id'],
                'unit_id'                  => $unitId,
                'product_id'               => $productId,
                'quantity'                 => $outQuantity,
                'unit_prices'              => -$pricePerUnit,
                'total'                    => -$priceTotal,
                'warehouse_location_id'    => $data['warehouse_locations'][$index] ?? null,
                'converted_quantity'       => $convertedOutQuantity,
                'unit_product_id'          => $productUnit,
            ]);
            // تحديث الكميات في جدول المخزون
            $this->updateInventoryStock($data['warehouse_id'], $productId, $outQuantity, -$pricePerUnit);

            // إنشاء سجل دخول في المخزن الوجهة
            $inQuantity = abs($quantityInput);
            $convertedInQuantity = $this->inventoryCalculationService->calculateConvertedQuantity($inQuantity, $unitId);

            InventoryTransactionItem::create([
                'inventory_transaction_id' => $transaction->id,
                'target_warehouse_id'      => $data['secondary_warehouse_id'],
                'unit_id'                  => $unitId,
                'product_id'               => $productId,
                'quantity'                 => $inQuantity,
                'unit_prices'              => abs($pricePerUnit),
                'total'                    => abs($priceTotal),
                'warehouse_location_id'    => $data['warehouse_locations'][$index] ?? null,
                'converted_quantity'       => $convertedInQuantity,
                'unit_product_id'          => $productUnit,
            ]);
            // تحديث الكميات في جدول المخزون

            $this->updateInventoryStock($data['secondary_warehouse_id'], $productId, $inQuantity, $pricePerUnit);
        } else {

            // إنشاء حركة عادية مثل بيع أو شراء
            if ($transactionType && $transactionType->effect != 0) {
                $effect = $transactionType->effect; 
                $quantityInput=$quantityInput * $effect;  
                $pricePerUnit=$pricePerUnit * $effect;  
                $priceTotal=$priceTotal * $effect;  
                }
            $convertedQuantity = $this->inventoryCalculationService->calculateConvertedQuantity($quantityInput, $unitId);
            InventoryTransactionItem::create([
                'inventory_transaction_id' => $transaction->id,
                'target_warehouse_id'      => $data['warehouse_id'],
                'unit_id'                  => $unitId,
                'product_id'               => $productId,
                'quantity'                 => $quantityInput,
                'unit_prices'              => $pricePerUnit,
                'total'                    => $priceTotal,
                'warehouse_location_id'    => $data['warehouse_locations'][$index] ?? null,
                'converted_quantity'       => $convertedQuantity,
                'unit_product_id'          => $productUnit,
            ]);
            // تحديث الكميات في جدول المخزون

            $this->updateInventoryStock($data['warehouse_id'], $productId, $quantityInput, $pricePerUnit);
        }
    }

        // تحديث أو إضافة الكمية التراكمية في جدول المخزون
        private function updateInventoryStock($warehouseId, $productId, $quantity, $pricePerUnit)
{
    // جلب السجل الحالي للمخزون لهذا المنتج في هذا المستودع
    $inventory = Inventory::where('warehouse_id', $warehouseId)
                          ->where('product_id', $productId)
                          ->first();
    
    if ($inventory) {
        // تحديث الكمية
        $newQuantity = $inventory->quantity + $quantity;

        // التأكد من عدم أن تكون الكمية سالبة (لا يمكن أن يصبح المخزون بالسالب)
        if ($newQuantity < 0) {
            throw new \Exception("خطأ: الكمية في المخزون لا يمكن أن تكون سالبة.");
        }

        // تحديث السعر الإجمالي
        $newTotalValue = ($inventory->total_value + ($quantity * $pricePerUnit));

        // تحديث المخزون
        $inventory->update([
            'quantity' => $newQuantity,
            'unit_price' => $newQuantity > 0 ? $newTotalValue / $newQuantity : 0, // تجنب القسمة على صفر
            'total_value' => $newTotalValue,
        ]);
    } else {
        // إذا لم يكن هناك سجل، أنشئ سجلًا جديدًا
        if ($quantity < 0) {
            throw new \Exception("خطأ: لا يمكن إخراج منتج غير موجود في المخزون.");
        }

        Inventory::create([
            'warehouse_id' => $warehouseId,
            'product_id'   => $productId,
            'quantity'     => $quantity,
            'unit_price'   => $pricePerUnit,
            'total_value'  => $quantity * $pricePerUnit,
        ]);
    }
}

        // private function updateInventoryStock($warehouseId, $productId, $quantity, $pricePerUnit)
        // {
        //     // التحقق من وجود السجل في جدول المخزون للمستودع والمنتج
        //     $inventory = Inventory::where('warehouse_id', $warehouseId)
        //         ->where('product_id', $productId)
        //         ->first();
    
        //     if ($inventory) {
        //         // إذا كان السجل موجودًا، قم بتحديث الكمية التراكمية
        //         $inventory->quantity += $quantity;
        //         $inventory->total_value = $inventory->quantity * $pricePerUnit;
        //         $inventory->save();
        //     } else {
        //         // إذا لم يكن السجل موجودًا، أنشئ سجلًا جديدًا
        //         Inventory::create([
        //             'warehouse_id' => $warehouseId,
        //             'product_id'   => $productId,
        //             'quantity'     => $quantity,
        //             'unit_price'   => $pricePerUnit,
        //             'total_value'  => $quantity * $pricePerUnit,
        //         ]);
        //     }
        // }
    
}

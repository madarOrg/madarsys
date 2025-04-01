<?php

namespace App\Listeners;

use App\Events\InventoryTransactionUpdated;
use App\Models\InventoryTransactionItem;
use App\Models\Inventory;
use App\Models\Product;

use App\Services\InventoryTransaction\InventoryCalculationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateInventoryTransactionListener
{
    protected $inventoryCalculationService;

    public function __construct(InventoryCalculationService $inventoryCalculationService)
    {
        $this->inventoryCalculationService = $inventoryCalculationService;
    }


    public function handle(InventoryTransactionUpdated $event)
    {
        $transaction = $event->transaction;
        $oldData = $event->oldData;
        $newData = $event->newData;
        DB::beginTransaction();

        try {
            // dd($oldData);

            // جلب بيانات المخزون مسبقًا لتقليل الاستعلامات المتكررة
            $inventoryItems = Inventory::whereIn('product_id', $newData['products'])
                ->where('warehouse_id', $newData['warehouse_id'])
                ->get()
                ->keyBy('product_id');

            // جلب العناصر القديمة المرتبطة بالمعاملة
            $oldItems = InventoryTransactionItem::where('inventory_transaction_id', $transaction->id)
                ->get()
                ->keyBy('product_id');

            foreach ($newData['products'] as $index => $productId) {
                $quantity = $newData['quantities'][$index];
                $unitId = $newData['units'][$index] ?? null;
                // dd($unitId);
                $pricePerUnit = $newData['unit_prices'][$index] ?: ($newData['totals'][$index] / max($quantity, 1));
                $priceTotal = $newData['totals'][$index] ?: ($quantity * $pricePerUnit);
                $warehouseId = $newData['warehouse_id'];
                // dd($newData['units'][$index]);
// dd($productId);
                // Retrieve the product's unit_id using the productId
                $product = Product::where('id', $productId)->first();
                // dd($product);
         
                if ($product) {
                    $baseUnitId = $product->unit_id;
                } else {
                    Log::error("Product not found in the transaction for product_id: " . $productId);
                    throw new \Exception("Product not found in the transaction for product_id: " . $productId);
                }

                // Debugging purposes: Show the unit_id
                // dd($baseUnitId);

                if (isset($oldItems[$productId])) {
                    $item = $oldItems[$productId];
                    // Further code for updating the inventory item
                }
                // dd($newData['units'][$index]);
                if (isset($oldItems[$productId])) {
                    $item = $oldItems[$productId];
                    // $this->updateInventoryStock($item->target_warehouse_id, $item->product_id, -$item->quantity, -$item->unit_prices);

                    $item->update([
                        'quantity' => $quantity,
                        'unit_id' => $unitId,
                        'unit_prices' => $pricePerUnit,
                        'total' => $priceTotal,
                        'warehouse_location_id' => $newData['warehouse_locations'][$index] ?? null,
                        'converted_quantity' => $this->inventoryCalculationService->calculateConvertedQuantity($quantity, $unitId, $baseUnitId),
                        'unit_product_id' => $baseUnitId,
                    ]);
                } else {

                    InventoryTransactionItem::create([
                        'inventory_transaction_id' => $transaction->id,
                        'target_warehouse_id' => $warehouseId,
                        'unit_id' => $newData['units'][$index] ?? null,
                        'product_id' => $productId,
                        'quantity' => $quantity,
                        'unit_prices' => $pricePerUnit,
                        'total' => $priceTotal,
                        'warehouse_location_id' => $newData['warehouse_locations'][$index] ?? null,
                        'converted_quantity' => $this->inventoryCalculationService->calculateConvertedQuantity($quantity, $unitId, $baseUnitId),
                        'unit_product_id' => $baseUnitId,
                    ]);
                }


                // $this->updateInventoryStock($warehouseId, $productId, $quantity, $pricePerUnit);
            }

            $newStatus = in_array($transaction->status, [0, null]) ? 0 : 3;

            // تحديث بيانات العملية المخزنية (مثل المورد والرقم المرجعي) وتحديث الحالة إلى 3
            $transaction->update([
                // 'supplier_id'      => $newData['supplier_id'] ?? $transaction->supplier_id,
                // 'reference_number' => $newData['reference_number'] ?? $transaction->reference_number,
                'status'           => $newStatus,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("خطأ أثناء تحديث الحركة المخزنية: " . $e->getMessage(), [
                'transaction_id' => $transaction->id,
                'old_data' => $oldData,
                'new_data' => $newData,
            ]);
            throw new \Exception("خطأ أثناء تحديث الحركة المخزنية: " . $e->getMessage());
        }
    }
    public function checkProductInOtherMovements($productId)
    {
        // استعلام للتحقق من وجود المنتج في حركات أخرى (يمكنك تعديل الاستعلام حسب هيكل قاعدة البيانات)
        return Inventory::where('product_id', $productId)->where('status', '!=', 'deleted')->exists();
    }


    private function updateInventoryStock($warehouseId, $productId, $quantity, $pricePerUnit)
    {
        $inventory = Inventory::where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->first();

        if ($inventory) {
            $newQuantity = $inventory->quantity + $quantity;

            if ($newQuantity < 0) {
                throw new \Exception("خطأ: لا يمكن أن يكون المخزون أقل من الصفر.");
            }

            $newTotalValue = ($inventory->total_value + ($quantity * $pricePerUnit));

            $inventory->update([
                'quantity' => $newQuantity,
                'unit_price' => $newQuantity > 0 ? $newTotalValue / $newQuantity : 0,
                'total_value' => $newTotalValue,
            ]);
        } else {
            if ($quantity < 0) {
                throw new \Exception("خطأ: لا يمكن إخراج منتج غير موجود في المخزون.");
            }

            Inventory::create([
                'warehouse_id' => $warehouseId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'unit_price' => $pricePerUnit,
                'total_value' => $quantity * $pricePerUnit,
            ]);
        }
    }
}

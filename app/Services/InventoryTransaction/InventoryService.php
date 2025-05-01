<?php
namespace App\Services\InventoryTransaction;

use App\Models\Inventory;
use Exception;

class InventoryService
{
    /**
     * تحديث المخزون للمنتج في المستودع
     *
     * @param int $warehouseId
     * @param int $productId
     * @param int $quantity
     * @param float $pricePerUnit
     * @throws Exception
     */
    public function updateInventoryStock($warehouseId, $productId, $quantity, $pricePerUnit)
    {
        
        // جلب السجل الحالي للمخزون لهذا المنتج في هذا المستودع
        $inventory = Inventory::where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->first();
        
            //  dd($quantity);
        if ($inventory) {
            // تحديث الكمية
            $newQuantity = $inventory->quantity + $quantity;
            if ($quantity<0){
                $newQuantityBlocked = $inventory->blocked_quantity + abs($quantity);
            }

            // التأكد من عدم أن تكون الكمية سالبة
            if ($newQuantity < 0) {
                throw new Exception("خطأ: الكمية في المخزون لا يمكن أن تكون سالبة.");
            }

            // تحديث السعر الإجمالي
            $newTotalValue =(float)$inventory->total_value +  (float)$pricePerUnit;
            //  ((float)$inventory->total_value + ((float)$newQuantity * (float)$pricePerUnit));
            // dump($newTotalValue);


            // تحديث المخزون
            $inventory->update([
                'quantity' => $newQuantity,
                'blocked_quantity' => $newQuantityBlocked,
                'unit_price' => $newQuantity > 0 ? $newTotalValue / $newQuantity : 0, // تجنب القسمة على صفر
                'total_value' => $newTotalValue,
            ]);
        } else {
            // إذا لم يكن هناك سجل، أنشئ سجلًا جديدًا
            if ($quantity < 0) {
                throw new Exception("خطأ: لا يمكن إخراج منتج غير موجود في المخزون.");
            }

            Inventory::create([
                'warehouse_id' => $warehouseId,
                'product_id'   => $productId,
                'quantity'     => $quantity,
                'unit_price'   => $pricePerUnit,
                'total_value'  => $quantity * $pricePerUnit,
                'blocked_quantity' => 0,
            ]);
        }
    }
    
    public function updateInventoryBlockedStock($warehouseId, $productId, $quantity, $pricePerUnit)
    {
        
        // جلب السجل الحالي للمخزون لهذا المنتج في هذا المستودع
        $inventory = Inventory::where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->first();
            //  dd($quantity);
        if ($inventory) {
            // تحديث الكمية
            $newQuantity = $inventory->quantity + $quantity;
            // dd($newQuantity);

            // التأكد من عدم أن تكون الكمية سالبة
            if ($newQuantity < 0) {
                throw new Exception("خطأ: الكمية في المخزون لا يمكن أن تكون سالبة.");
            }

            // تحديث السعر الإجمالي
            $newTotalValue =(float)$inventory->total_value +  (float)$pricePerUnit;
            //  ((float)$inventory->total_value + ((float)$newQuantity * (float)$pricePerUnit));
            // dump($newTotalValue);


            // تحديث المخزون
            $inventory->update([
                'blocked_quantity' => $newQuantity,
                'unit_price' => $newQuantity > 0 ? $newTotalValue / $newQuantity : 0, // تجنب القسمة على صفر
                'total_value' => $newTotalValue,
            ]);
        } else {
            // إذا لم يكن هناك سجل، أنشئ سجلًا جديدًا
            if ($quantity < 0) {
                throw new Exception("خطأ: لا يمكن إخراج منتج غير موجود في المخزون.");
            }

            Inventory::create([
                'warehouse_id' => $warehouseId,
                'product_id'   => $productId,
                'blocked_quantity'     => $quantity,
                'unit_price'   => $pricePerUnit,
                'total_value'  => $quantity * $pricePerUnit,
            ]);
        }
    }
}

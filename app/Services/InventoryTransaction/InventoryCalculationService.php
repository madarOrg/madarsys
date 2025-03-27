<?php

namespace App\Services\InventoryTransaction;

use App\Models\Unit;
use App\Models\Product;
use App\Models\TransactionType;

class InventoryCalculationService

{
    public function addStock($productId, $quantity)
    {
        $product = Product::find($productId);
        $product->stock += $quantity;
        $product->save();
    }
    public function reduceStock($productId, $quantity)
    {
        $product = Product::find($productId);

        if ($product->stock >= $quantity) {
            $product->stock -= $quantity;
            $product->save();
            return "تمت العملية بنجاح!";
        } else {
            return "عذرًا، الكمية غير متوفرة!";
        }
    }


   /**
 * تحويل كمية من الوحدة المُدخلة إلى وحدة المنتج الأساسية
 *
 * @param float $quantity الكمية المدخلة
 * @param int $inputUnitId الوحدة التي بواسطتها تم إدخال الكمية
 * @param int $baseUnitId معرف المنتج للحصول على وحدة المنتج الأساسية
 * @return float الكمية المحولة إلى وحدة المنتج الأساسية
 */
function calculateConvertedQuantity($quantity, $inputUnitId, $baseUnitId)
{
    

    $conversionFactor = 1;
    $currentUnitId = $inputUnitId;

    // حلقة التحويل: استمر حتى تصل إلى وحدة المنتج الأساسية
    while ($currentUnitId != $baseUnitId) {
        $unit = Unit::find($currentUnitId);

        if (!$unit || !$unit->conversion_factor || !$unit->parent_unit_id) {
            throw new Exception('لا يمكن تحويل الوحدة إلى وحدة المنتج الأساسية.');
        }

        // تحديث معامل التحويل
        $conversionFactor *= $unit->conversion_factor;
        // الانتقال إلى الوحدة الأم
        $currentUnitId = $unit->parent_unit_id;
    }

    return $quantity * $conversionFactor;
}


    /**
     * حساب السعر  بناءً على معامل التحويل للوحدة
     */
   /**
 * تحويل سعر الوحدة من الوحدة المُدخلة إلى وحدة المنتج الأساسية
 *
 * @param float $price السعر بوحدة الإدخال (x)
 * @param int $inputUnitId معرف الوحدة المُدخلة
 * @param int $baseUnitId معرف المنتج للحصول على وحدة المنتج الأساسية
 * @return float السعر بوحدة المنتج الأساسية
 * @throws Exception في حال عدم إمكانية تحويل الوحدة
 */
function calculateConvertedPrice($price, $inputUnitId, $baseUnitId)
{
    
    $conversionFactor = 1;
    $currentUnitId = $inputUnitId;
    
    // حلقة التحويل: الاستمرار حتى الوصول إلى وحدة المنتج الأساسية
    while ($currentUnitId != $baseUnitId) {
        $unit = Unit::find($currentUnitId);
        if (!$unit || !$unit->conversion_factor || !$unit->parent_unit_id) {
            throw new Exception('لا يمكن تحويل الوحدة إلى وحدة المنتج الأساسية.');
        }
        // تحديث معامل التحويل: ضرب كل معاملات التحويل للوصول إلى الوحدة الأساسية
        $conversionFactor *= $unit->conversion_factor;
        $currentUnitId = $unit->parent_unit_id;
    }
    
    // حساب السعر بوحدة المنتج الأساسية من خلال قسمة السعر على معامل التحويل
    $pricePerBaseUnit = $price / $conversionFactor;
    
    return $pricePerBaseUnit;
}


    /**
     * حساب الكمية مع التأثير (إدخال أو إخراج)
     */
    public function applyEffectToQuantity($quantity, $effect)
    {
        return ($effect === '-1') ? -abs($quantity) : abs($quantity);
    }

    public function calculateUnitPrice($quantity, $pricePerUnit, $priceTotal)
    {
        return $pricePerUnit ?: ($priceTotal / $quantity);
    }

    public function calculateTotalPrice($quantity, $pricePerUnit, $priceTotal)
    {
        return $priceTotal ? $quantity * $priceTotal : $quantity * $pricePerUnit;
    }
    // دالة لجلب تأثير نوع العملية
    public function getEffectByTransactionType($transactionTypeId)
    {
        // البحث عن نوع العملية باستخدام 'id' بدلاً من 'name'
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
    }
}

<?php

namespace App\Services;

use App\Models\Unit;
use App\Models\Product;
use App\Models\TransactionType;

class InventoryCalculationService
{
    /**
     * حساب الكمية المحولة بناءً على معامل التحويل للوحدة
     */
    public function calculateConvertedQuantity($quantity, $unitId)
    {
        $unit = Unit::find($unitId);
        if ($unit && $unit->conversion_factor) {
            return $quantity * $unit->conversion_factor;
        }
        return $quantity;
    }

    /**
     * حساب الكمية مع التأثير (إدخال أو إخراج)
     */
    public function applyEffectToQuantity($quantity, $effect)
    {
        return ($effect === '-1') ? -abs($quantity) : abs($quantity);
    }

    /**
     * حساب إجمالي السعر بناءً على الكمية والسعر لكل وحدة
     *
     * @param float $quantity الكمية
     * @param float $pricePerUnit السعر لكل وحدة
     * @return float إجمالي السعر
     */
    public function calculateTotalPrice($quantity, $pricePerUnit, $priceTotal)
{
    if ($quantity == 0) {
        return 0; // تجنب القسمة على صفر
    }

    if ($pricePerUnit == 0 && $priceTotal != 0) {
        return round($priceTotal / abs($quantity), 6);
    }

    if ($priceTotal == 0 && $pricePerUnit != 0) {
        return round(abs($quantity) * $pricePerUnit, 6);
    }

    return null; // حالة غير محددة
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

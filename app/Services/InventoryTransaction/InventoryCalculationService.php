<?php

namespace App\Services\InventoryTransaction;

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

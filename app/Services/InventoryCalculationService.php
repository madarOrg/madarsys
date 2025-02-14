<?php

namespace App\Services;

use App\Models\Unit;

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
        return ($effect === '-') ? -abs($quantity) : abs($quantity);
    }
}

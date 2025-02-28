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
     * حساب السعر  بناءً على معامل التحويل للوحدة
     */
    public function calculateConvertedPrice($pricePerUnit, $unitId)
    {
        $unit = Unit::find($unitId);
        if ($unit && $unit->conversion_factor) {
            // إذا كانت الوحدة لها معامل تحويل، نحسب السعر للوحدة الأساسية بقسمة السعر على معامل التحويل
            return $pricePerUnit * $unit->conversion_factor;
        }
        // إذا لم توجد وحدة أو معامل تحويل، يرجع السعر كما هو
        return $pricePerUnit;
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

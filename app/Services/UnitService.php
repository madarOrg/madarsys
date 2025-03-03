<?php

namespace App\Services;

use App\Models\Product;

class UnitService
{
    /**
     * تحديث قائمة الوحدات بناءً على معرف المنتج.
     *
     * @param int $productId
     * @return array
     */
    public function updateUnits($productId)
    {
        // إذا لم يتم تحديد منتج، يتم إرجاع مصفوفة فارغة
        if (!$productId) {
            return [];
        }

        // جلب المنتج مع العلاقة للوحدة الأساسية فقط
        $product = Product::with('unit')->find($productId);

        // التأكد من وجود المنتج والوحدة الأساسية
        if (!$product || !$product->unit) {
            return [];
        }

        // بدء تجميع الوحدات، نبدأ بالوحدة الأساسية
        $units = [];
        $units[] = $product->unit;

        // الحصول على جميع الوحدات الفرعية (الأبناء)
        $this->getAllChildren($product->unit, $units);

        // الحصول على جميع الوحدات العليا (الآباء)
        $this->getAllParents($product->unit, $units);

        // تجنب التكرار باستخدام unique على أساس الـ id
        $units = collect($units)->unique('id')->values();

        // تحويل البيانات إلى مصفوفة تحتوي على id واسم الوحدة
        return $units->map(function ($unit) {
            return [
                'id'   => $unit->id,
                'name' => $unit->name,
            ];
        })->toArray();
    }

    /**
     * دالة للحصول على جميع الوحدات الفرعية (الأبناء) بشكل متداخل
     */
    private function getAllChildren($unit, &$units)
    {
        if (isset($unit->children) && $unit->children->count()) {
            foreach ($unit->children as $child) {
                $units[] = $child;
                $this->getAllChildren($child, $units);
            }
        }
    }

    /**
     * دالة للحصول على جميع الوحدات العليا (الآباء) بشكل متداخل
     */
    private function getAllParents($unit, &$units)
    {
        if ($unit->parent) {
            $units[] = $unit->parent;
            $this->getAllParents($unit->parent, $units);
        }
    }
}

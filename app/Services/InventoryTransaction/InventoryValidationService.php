<?php

namespace App\Services\InventoryTransaction;

use App\Models\
{Warehouse,InventoryTransaction};
use App\Models\Setting;
use Carbon\Carbon;

class InventoryValidationService
{
    /**
     * التحقق مما إذا كان المستودع نشطًا.
     *
     * @param int $warehouseId
     * @return bool
     */
    public static function isWarehouseActive($warehouseId)
    {
        $warehouse = Warehouse::find($warehouseId);
        return $warehouse && $warehouse->is_active;
    }

    /**
     * التحقق مما إذا كان تاريخ العملية المخزنية صالحًا بناءً على الإعدادات.
     *
     * @param string $transactionDate
     * @return bool|string إرجاع false إذا كان صحيحًا، أو رسالة خطأ إذا كان غير صحيح.
     */
    public static function validateTransactionDate($transactionDate, $warehouseId)
    {  
        // جلب الحد الأدنى المسموح به من جدول الإعدادات
        $minDays = Setting::where('key', 'inventory_transaction_min_date')->value('value') ?? 30;
        $sysStart = Setting::where('key', 'system_start_date')->value('value') ?? '2025-01-01';
        // حساب التاريخ الأدنى المسموح به
        $minDate = Carbon::now()->subDays($minDays)->toDateString();
    
        // جلب آخر عملية مخزنية لهذا المستودع
        $lastTransactionDate = InventoryTransaction::where('warehouse_id', $warehouseId)
            ->orderBy('transaction_date', 'desc')
            ->value('transaction_date');
           
        // التحقق من القيود الزمنية
        if ($transactionDate < $minDate) {
            return "لا يمكن إدخال عملية مخزنية بتاريخ أقدم من $minDate.";
        }
        if ($transactionDate < $sysStart) {
            return "لا يمكن إدخال عملية مخزنية بتاريخ أقدم من تاريخ بداية النظام $sysStart.";
        }
        if ($lastTransactionDate && $transactionDate < $lastTransactionDate) {

            return "تاريخ العملية يجب أن يكون أحدث من آخر عملية مخزنية تم تسجيلها بتاريخ: $lastTransactionDate.";
        }

        return true;
    }
    
    public static function validateMaxOperationsBeforeRepeat($transactionDate)
    {

        $maxOperationsBeforeRepeat = 5; // السماح بالتكرار بعد 5 عمليات أخرى

        $operationsCount = DB::table('inventory_transactions')
            ->where('product_id', $product_id)
            ->where('warehouse_id', $warehouse_id)
            ->where('type', $type)
            ->where('date', now()->toDateString())
            ->count();

        if ($operationsCount >= $maxOperationsBeforeRepeat) {
            if ($transactionDate < $sysStart) {
                return " لا يمكن إدخال عملية مخزنية بتاريخ أقدم من تاريخ بداية النظام$sysStart.";
            }
        }
        return false;
    }
}

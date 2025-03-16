<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\InventoryTransaction;

class ValidInventoryTransaction implements Rule
{
    public function passes($attribute, $value)
    {
        // التحقق من وجود الحركة بحالة قابلة للتوزيع (status = 1)
        return InventoryTransaction::join('inventory_transaction_items', 'inventory_transaction_items.inventory_transaction_id', '=', 'inventory_transactions.id')
        ->where('inventory_transaction_items.id', $value)  // قيمة من جدول inventory_transaction_items
        ->where('inventory_transactions.status', 1)         // حالة الحركة
        ->exists();
    }
    public function message()
    {
        return 'الحركة المخزنية غير قابلة للتوزيع أو غير موجودة.';
    }
}

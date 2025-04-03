<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\InventoryTransaction;


class ValidInventoryTransaction implements Rule
{
    protected $distributionType;

    public function __construct($distributionType)
    {
        $this->distributionType = $distributionType;
    }

    public function passes($attribute, $value)
    {
        $query = InventoryTransaction::join('inventory_transaction_items', 'inventory_transaction_items.inventory_transaction_id', '=', 'inventory_transactions.id')
            ->where('inventory_transaction_items.id', $value);

        // التحقق بناءً على نوع التوزيع
        if ($this->distributionType == 1) {
            $query->where('inventory_transactions.status', 1); // يجب أن تكون قابلة للتوزيع
        } elseif ($this->distributionType == -1) {
            $query->where('inventory_transactions.status', '!=', 0); // لا يجب أن تكون حالتها 0
        }
        return $query->exists();
        dd($query);

    }

    public function message()
    {
        return 'الحركة المخزنية غير متاحة لهذه العملية.';
    }
}

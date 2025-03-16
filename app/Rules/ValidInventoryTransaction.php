<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\InventoryTransaction;

class ValidInventoryTransaction implements Rule
{
    public function passes($attribute, $value)
    {
        // التحقق من وجود الحركة بحالة قابلة للتوزيع (status = 1)
        return InventoryTransaction::where('id', $value)
            ->where('status', 1)
            ->exists();
    }

    public function message()
    {
        return 'الحركة المخزنية غير قابلة للتوزيع أو غير موجودة.';
    }
}

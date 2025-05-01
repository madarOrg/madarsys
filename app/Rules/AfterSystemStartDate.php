<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Setting;
use Carbon\Carbon;

class AfterSystemStartDate implements Rule
{
    public function passes($attribute, $value)
    {
        $startDate = Setting::where('key', 'system_start_date')->value('value');

        if (!$startDate) {
            return true; // أو false حسب منطقك إذا لم يوجد تاريخ بداية معرف
        }

        return Carbon::parse($value)->greaterThanOrEqualTo(Carbon::parse($startDate));
    }

    public function message()
    {
        return 'يجب أن يكون تاريخ :attribute بعد أو يساوي تاريخ بداية النظام.';
    }
}


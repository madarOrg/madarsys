<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;
use App\Models\Setting;
use Carbon\Carbon;

class ValidExpirationDate implements Rule
{
    public function passes($attribute, $value)
    {
        $acceptExpired =  Setting::where('key', 'accept_products_expiry')->value('value');
        $expirationDate = Carbon::parse($value)->startOfDay();

        if (!$acceptExpired && $expirationDate->lt(Carbon::now()->startOfDay())) {
            return false;
            // dd($expirationDate,$expirationDate);

        }
 
        return true;
    }

    public function message()
    {
        return 'تاريخ انتهاء الصلاحية غير مسموح لأن المنتج منتهي والنظام لا يقبل المنتجات المنتهية.';
    }
}

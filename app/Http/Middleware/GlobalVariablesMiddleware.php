<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Models\Warehouse;
use App\Models\Company;
use App\Models\Branch; // تأكد من استيراد موديل الفرع

class GlobalVariablesMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // جلب بيانات المستودع والشركة
        $userWarehouse = Warehouse::ForUserWarehouse()->first();
        $userCompany   = Company::forUserCompany()->first();
        // جلب بيانات الفرع الخاص بالمستخدم
        // $userBranch    = Branch::ForUserBranch()->first();
        
        // استخراج الشعار من الشركة (إذا وُجد)
        $companyLogo   = $userCompany ? $userCompany->logo : null;

        // مشاركة البيانات مع جميع الـ Views
        View::share(compact('userWarehouse', 'userCompany', 'companyLogo'));

        // تمرير البيانات إلى جميع الـ Controllers
        $request->merge(compact('userWarehouse', 'userCompany', 'companyLogo'));

        return $next($request);
    }
}

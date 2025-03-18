<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UserAccessScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // الحصول على المستخدم الحالي
        $user = Auth::user();
        
        // إذا لم يكن هناك مستخدم، العودة بدون إضافة أي شروط
        if (!$user) {
            return;
        }
    
        // اسم الجدول الحالي لتجنب الغموض في الأعمدة
        $table = $model->getTable();
        
        // جلب جميع الأذونات المرتبطة بالمستخدم عبر الأدوار (الشركات، الفروع، المستودعات)
        $accessibleCompanyIds = DB::table('role_company')
            ->join('role_user', 'role_company.role_id', '=', 'role_user.role_id')
            ->where('role_user.user_id', $user->id)
            ->pluck('company_id')
            ->toArray();
    
        $accessibleBranchIds = DB::table('role_branch')
            ->join('role_user', 'role_branch.role_id', '=', 'role_user.role_id')
            ->where('role_user.user_id', $user->id)
            ->pluck('branch_id')
            ->toArray();
    
        $accessibleWarehouseIds = DB::table('role_warehouse')
            ->join('role_user', 'role_warehouse.role_id', '=', 'role_user.role_id')
            ->where('role_user.user_id', $user->id)
            ->pluck('warehouse_id')
            ->toArray();
    
        // التحقق من وجود الأعمدة في الجدول الحالي لتحديد ما إذا كان يجب إضافة الشروط
        $columns = Schema::getColumnListing($model->getTable());
    
        // إضافة الشروط بناءً على الأذونات المستخرجة
        $builder->where(function ($query) use ($table, $columns, $accessibleCompanyIds, $accessibleBranchIds, $accessibleWarehouseIds) {
            // إضافة شرط للشركات إذا كانت هناك قيم متاحة
            if (!empty($accessibleCompanyIds) && in_array('company_id', $columns)) {
                $query->whereIn("$table.company_id", $accessibleCompanyIds);
            }
    
            // إضافة شرط للفروع إذا كانت هناك قيم متاحة
            if (!empty($accessibleBranchIds) && in_array('branch_id', $columns)) {
                $query->whereIn("$table.branch_id", $accessibleBranchIds);
            }
    
            // إضافة شرط للمستودعات إذا كانت هناك قيم متاحة
            if (!empty($accessibleWarehouseIds) && in_array('id', $columns)) {
                $query->whereIn("$table.id", $accessibleWarehouseIds);
            }
        });
    // dump( $accessibleCompanyIds, $accessibleBranchIds, $accessibleWarehouseIds);
        // إضافة تفريغ للاختبار
        // dump('w:', $builder->toSql()); // طباعة الاستعلام النهائي للمراجعة
    }
    
}
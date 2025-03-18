<?php
namespace App\Traits;

use App\Models\Branch;

trait HasBranch
{
    protected static function bootHasBranch()
    {
        static::creating(function ($model) {
            if (auth()->check() && empty($model->branch_id)) {
                // استخراج معرفات الأدوار الخاصة بالمستخدم
                $roleIds = auth()->user()->roles()->pluck('id');
                
                // استخراج الفرع الافتراضي بناءً على العلاقة في جدول role_branch
                $defaultBranch = Branch::whereIn('id', function($query) use ($roleIds) {
                    $query->select('branch_id')
                          ->from('role_branch')
                          ->whereIn('role_id', $roleIds);
                })->first();
                
                if ($defaultBranch) {
                    $model->branch_id = $defaultBranch->id;
                }
            }
        });
    }
}

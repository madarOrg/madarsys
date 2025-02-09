<?php

namespace App\Traits;
use App\Models\Branch;
trait HasBranch
{
    protected static function bootHasBranch()
    {
        static::creating(function ($model) {
            if (auth()->check() && empty($model->branch_id)) {
                $model->branch_id = auth()->user()->branch_id; // تعيين الفرع تلقائيًا عند إنشاء السجل
            }
        });
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}

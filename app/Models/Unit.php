<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\
{
HasBranch,
HasUser
};

class Unit extends Model
{
    use HasUser,HasBranch,HasFactory;

    protected $fillable = ['name', 'parent_unit_id', 'conversion_factor','branch_id','created_user', 'updated_user'
];
public function branch()
{
    return $this->belongsTo(Branch::class);
}
    // تعريف العلاقة مع الوحدة الأصلية
    public function parentUnit()
    {
        return $this->belongsTo(Unit::class, 'parent_unit_id');
    }

   // علاقة الوحدة الابن
   public function children()
   {
       return $this->hasMany(Unit::class, 'parent_unit_id');
   }
   public function childrenRecursive()
   {
       return $this->children()->with('childrenRecursive');
   }
   
   // علاقة الوحدة الأب
   public function parent()
   {
       return $this->belongsTo(Unit::class, 'parent_unit_id');
   }
   public function parentRecursive()
   {
       return $this->parent()->with('parentRecursive');
   }
   

}

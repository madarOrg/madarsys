<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\
{
HasBranch,
HasUser
};

class Category extends Model
{
    use HasUser,HasBranch,HasFactory;

    // الحقول القابلة للتعيين
    protected $fillable = [
        'name', 
        'code',
        'description',
        'created_at',
        'created_user',
        'updated_at',
        'updated_user',
        'branch_id',
    ];
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    
    // علاقة التصنيف بالمنتجات
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

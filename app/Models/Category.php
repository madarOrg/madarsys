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
        'description',
        'branch_id',
        'created_user', 'updated_user'
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

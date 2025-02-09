<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBranch;

class Category extends Model
{
    use HasBranch,HasFactory;

    // الحقول القابلة للتعيين
    protected $fillable = [
        'name', 
        'description',
        'branch_id'
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // الحقول القابلة للتعيين
    protected $fillable = [
        'name', 
        'description'
    ];

    // علاقة التصنيف بالمنتجات
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

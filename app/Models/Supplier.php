<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    // تعريف العلاقة العكسية إذا كان المورد يمتلك العديد من المنتجات
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'description'];

    // علاقة مع المستودعات 
    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }
}

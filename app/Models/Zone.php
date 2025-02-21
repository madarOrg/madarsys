<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\
{
HasUser
};
class Zone extends Model
{
    use HasUser,HasFactory;

    protected $fillable = ['name', 'code', 'description','warehouse_id', 'created_user', 'updated_user'];

    // علاقة مع المستودعات 
    public function warehouses()
    {
        return $this->hasMany(Warehouse::class);
    }
}

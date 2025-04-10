<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\
{
HasBranch,
HasUser
};
class ManufacturingCountry extends Model
{
    use HasUser,HasBranch,HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'created_user',
        'updated_user',
        'branch_id',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);  
    }
}

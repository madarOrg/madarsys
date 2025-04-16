<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\
{
HasBranch,
HasUser
};

class PartnerType extends Model
{
    use HasUser,HasBranch,HasFactory;

    protected $table = 'partner_types'; // تحديد اسم الجدول

    protected $fillable = ['name','branch_id','created_user', 'updated_user']; // السماح بالتعبئة الجماعية
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    /**
     * العلاقة بين نوع الشريك والشركاء (كل نوع له عدة شركاء).
     */
    public function partners()
    {
        return $this->hasMany(Partner::class, 'type', 'id');
    }
   // في Partner.php
public function inventoryTransactions()
{
    return $this->hasMany(InventoryTransaction::class);
}

public function products()
{
    return $this->hasMany(Product::class);
}

 
}

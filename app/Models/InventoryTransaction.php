<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBranch;
use App\Traits\HasUser;

class InventoryTransaction extends Model
{
    use HasUser,HasBranch,HasFactory;
    
    protected $table = 'inventory_transactions';

    protected $fillable = [
        'transaction_type_id',
        'sub_type_id',
        'effect',
        'transaction_date', 
        'reference',
        'partner_id',
        'department_id',
        'warehouse_id',
        'secondary_warehouse_id',
        'notes',
        'branch_id',
        'inventory_request_id',
        'created_user', 
        'updated_user',
        'status',
        'transaction_date',   
        'approved_at',
    ];
    
    protected $casts = [
        'transaction_date' => 'datetime',
    ];
    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            InventoryTransactionItem::class,
            'inventory_transaction_id', // المفتاح الأجنبي في InventoryTransactionItem
            'id',                       // المفتاح الرئيسي في Product
            'id',                       // المفتاح الرئيسي في InventoryTransaction
            'product_id'                // المفتاح الأجنبي في InventoryTransactionItem
        );
    }
// public function product()
// {
//     return $this->belongsTo(Product::class);
// }

        
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    
    public function items()
    {
        return $this->hasMany(InventoryTransactionItem::class);
    }
    public function audit()
    {
        return $this->belongsTo(InventoryAudit::class, 'id','inventory_transaction_id');
    }
  
    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id');
    }

public function subType()
{
    return $this->belongsTo(InventoryTransactionSubtype::class, 'sub_type_id');
}

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function secondaryWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'secondary_warehouse_id');
    }
    public function inventoryItems()
    {
        return $this->hasMany(InventoryTransactionItem::class, 'inventory_transaction_id');
    }
    public function createdUser()
{
    return $this->belongsTo(User::class, 'created_user');
}

public function updatedUser()
{
    return $this->belongsTo(User::class, 'updated_user');
}

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\
{
HasBranch,
HasUser
};

class InventoryTransaction extends Model
{
    use HasUser,HasBranch,HasFactory;
    
    protected $table = 'inventory_transactions';

    protected $fillable = [
        'transaction_type_id',
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
    ];
    
    protected $casts = [
        'transaction_date' => 'datetime',
    ];
    
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    
    public function items()
    {
        return $this->hasMany(InventoryTransactionItem::class);
    }
    
    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id');
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
    
}

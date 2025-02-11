<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBranch;

class InventoryTransaction extends Model
{
    use HasBranch,HasFactory;

    protected $fillable = [
        'transaction_type_id',
        'effect',
        'transaction_date',
        'partner_id',
        'department_id',
        'warehouse_id',
        'notes',
        'branch_id',
        'inventory_request_id'
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
}

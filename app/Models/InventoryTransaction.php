<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_type_id',
        'transaction_date',
        'partner_id',
        'department_id',
        'warehouse_id',
        'notes',
    ];

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

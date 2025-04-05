<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\
{
HasBranch,
HasUser
};


class InventoryTransactionSubtype extends Model
{
    use HasUser,HasBranch,HasFactory;

    protected $table = 'inventory_transaction_subtypes';

    protected $fillable = [
        'id',
        'transaction_type_id',
        'name',
        'description',
        'branch_id',
        'effect',
        'created_user', 'updated_user'
    ];

    public function transactionType()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id');
    }
   
    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'sub_type_id');
    }

    public function inventoryAudit(){
    
        return $this->hasMany(InventoryAudit::class, 'inventory_type');
    }
}

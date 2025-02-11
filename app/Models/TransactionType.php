<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasBranch;

class TransactionType extends Model
{
    use HasBranch,HasFactory;

    // تحديد اسم الجدول (إذا كان مختلفًا عن الاسم الافتراضي)
    protected $table = 'transaction_types';

    // تحديد الحقول القابلة للتعبئة
    protected $fillable = [
        'name',
        'description',
        'branch_id',
        'effect'
    ];

    // للحصول على علاقة مع العمليات المخزنية
    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class, 'transaction_type_id');
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}

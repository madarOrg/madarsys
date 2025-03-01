<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'template_id',
        'product_id',
        'inventory_request_id',
        'quantity',
        'status',
        'priority',
        'due_date',
        'department_id',
        'warehouse_id',
        'created_user',
        'updated_user',
    ];

    public function template()
    {
        return $this->belongsTo(NotificationTemplate::class, 'template_id');
    }
}

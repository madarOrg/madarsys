<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\
{

HasUser
};

class WarehouseReport extends Model
{
    use HasUser,HasFactory;

    protected $fillable = [
        'warehouse_id',
        'report_type',
        'report_data',
        'report_date',
        'generated_by', 'created_user', 'updated_user'
    ];

    protected $casts = [
        'report_data' => 'array',
        'report_date' => 'datetime'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}

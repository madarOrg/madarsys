<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'warehouse_id',
        'report_type',
        'report_data',
        'report_date',
        'generated_by'
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

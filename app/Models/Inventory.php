<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    // تحديد اسم الجدول في قاعدة البيانات
    protected $table = 'inventory';

    // الحقول التي يمكن تعيينها
    protected $fillable = [
        'warehouse_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_value',
        'created_user', 'updated_user'
    ];

    /**
     * علاقة مع نموذج Warehouse (مستودع)
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * علاقة مع نموذج Product (منتج)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * تخصيص الوصول إلى الكميات التراكمية (total_value)
     */
    public function getTotalValueAttribute($value)
    {
        return number_format($value, 2);
    }
}

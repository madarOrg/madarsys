<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    // الحقول القابلة للتعيين
    protected $fillable = [
        'name', 
        'type', 
        'contact_person',
        'phone', 
        'email', 
        'address',
        'tax_number',
        'is_active'
    ];

    // الأنواع المختلفة للشركاء
    const TYPE_SUPPLIER = 'supplier';
    const TYPE_CUSTOMER = 'customer';
    const TYPE_REPRESENTATIVE = 'representative';

    // تحديد نوع الشريك
    public function isSupplier()
    {
        return $this->type === self::TYPE_SUPPLIER;
    }

    public function isCustomer()
    {
        return $this->type === self::TYPE_CUSTOMER;
    }

    public function isRepresentative()
    {
        return $this->type === self::TYPE_REPRESENTATIVE;
    }

    // العلاقات مع المنتجات (إذا كان المورد)
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

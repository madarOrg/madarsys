<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value'];

    // دالة لاسترجاع القيم بسهولة
    public static function get($key, $default = null)
    {
        return self::where('key', $key)->value('value') ?? $default;
    }
}

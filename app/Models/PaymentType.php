<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'created_user', 'updated_user'];

    // Relationship with User (Assuming a User model exists)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_user');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_user');
    }
}

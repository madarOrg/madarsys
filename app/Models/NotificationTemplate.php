<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'type',
        'message_template',
    ];

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'template_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleCompany extends Model
{
    protected $table = 'role_company'; // اسم الجدول

    protected $fillable = ['role_id', 'company_id'];
}

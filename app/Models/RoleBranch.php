<?php
// في المسار: app/Models/RoleBranch.php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class RoleBranch extends Pivot
{
    protected $table = 'role_branch'; // تأكد من أن هذا هو اسم الجدول في قاعدة البيانات

    protected $fillable = ['role_id', 'branch_id', 'company_id']; // تأكد من أن هذه الأعمدة موجودة في الجدول
}

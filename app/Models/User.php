<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
   
    protected $fillable = ['name', 'email', 'password', 'branch_id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }  
    
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
     /**
     * العلاقة بين المستخدمين والأدوار (Many-to-Many)
     */
  
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')
            ->using(RoleUser::class) // استخدام النموذج المخصص
            ->withTimestamps(); // إذا كنت تستخدم الطوابع الزمنية
    }
    public function companies()
    {
        return $this->hasManyThrough(Company::class, Role::class, 'role_user', 'role_id', 'id', 'id');
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class, 'supervisor_id');
    }
  

    public function allowedCompanies()
{
    // التحقق إذا كان المستخدم لديه دور admin
    $isAdmin = $this->roles()->where('is_admin', true)->exists();

    if ($isAdmin) {
        // إذا كان المستخدم admin، عرض جميع الشركات
        return Company::all();
    }

    // جمع معرفات الشركات المرتبطة بالمستخدم بناءً على الأدوار
    $roles = $this->roles()->with('rolecompanies')->get(); // جلب الأدوار مع الشركات المرتبطة بهم
    $companyIds = $roles->flatMap(function ($role) {
        return $role->rolecompanies->pluck('id'); // استخراج معرّفات الشركات
    })->unique();

    // استرجاع الشركات بناءً على المعرفات
    return Company::whereIn('id', $companyIds)->get();
}

        
}

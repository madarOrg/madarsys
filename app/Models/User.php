<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\
{
HasBranch,
HasUser
};

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasBranch,HasUser,HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
   
    protected $fillable = ['name', 'email', 'password', 'branch_id','status','created_user', 'updated_user'];

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
    
    public function branches()
{
    // الحصول على الأدوار المرتبطة بالمستخدم
    return $this->belongsToMany(Branch::class, 'role_branch')
                ->using(RoleBranch::class)
                ->withPivot('role_id', 'company_id')
                ->withTimestamps();
}

     /**
     * العلاقة بين المستخدمين والأدوار (Many-to-Many)
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    
    public function hasPermission($permissionKey)
    {
        return $this->role->permissions()->where('permission_key', $permissionKey)->exists();
    }
    
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
    $roles = $this->roles()->with('companies')->get(); // جلب الأدوار مع الشركات المرتبطة بهم
    $companyIds = $roles->flatMap(function ($role) {
        return $role->companies->pluck('id'); // استخراج معرّفات الشركات
    })->unique();

    // استرجاع الشركات بناءً على المعرفات
    return Company::whereIn('id', $companyIds)->get();
}

        
}

<?php
namespace App\Http\Controllers;

use App\Services\NavbarService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class NavbarController extends Controller
{
    protected $navbarService;

    public function __construct(NavbarService $navbarService)
    {
        $this->navbarService = $navbarService;
    }

    public function showNavbar()
    {
        // جلب المستخدم والصلاحيات
        $user = Auth::user();
        $role = $user ? $user->roles()->first() : null;
        $permissions = $role ? $role->permissions->pluck('permission_key')->toArray() : [];

        // استدعاء الخدمة للحصول على روابط الـ Navbar باستخدام الصلاحيات
        $NavbarLinks = $this->navbarService->getNavbarLinks($permissions);

        // تمرير روابط الـ Navbar والصلاحيات إلى الصفحة
        return view('dashboard', compact('NavbarLinks', 'permissions'));
    }
}

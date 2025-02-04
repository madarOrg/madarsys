<?php
namespace App\Http\Controllers;

use App\Services\NavbarService;
use App\Services\UserPermissionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class NavbarController extends Controller
{
    protected $navbarService;
    protected $userPermissionService;

    public function __construct(NavbarService $navbarService, UserPermissionService $userPermissionService)
    {
        $this->navbarService = $navbarService;
        $this->userPermissionService = $userPermissionService;
    }

    public function showNavbar()
    {
        // جلب الصلاحيات من الكاش
        $permissions = $this->userPermissionService->getUserPermissions();

        // جلب روابط القائمة من الكاش
        $NavbarLinks = $this->navbarService->getNavbarLinks($permissions);

        // تمرير روابط الـ Navbar والصلاحيات إلى الصفحة
        return view('dashboard', compact('NavbarLinks', 'permissions'));
    }
}

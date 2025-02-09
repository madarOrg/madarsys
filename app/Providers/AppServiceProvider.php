<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use App\Services\NavbarService;
use App\Services\UserPermissionService;
use App\View\Components\Navbar; 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(NavbarService $navbarService, UserPermissionService $userPermissionService): void
    {    Blade::component('navbar', Navbar::class);

        // تحديد القوالب التي تحتاج إلى Navbar
        $viewsNeedingNavbar = [
            
            'dashboard',
            'navbar.*'
        ];

        View::composer($viewsNeedingNavbar, function ($view) use ($navbarService, $userPermissionService) {
            if (!auth()->check()) {
                $view->with('NavbarLinks', []);
                return;
            }

            // تخزين النتيجة في الذاكرة المؤقتة للطلب الحالي
            static $navbarLinks = null;
            
            if ($navbarLinks === null) {
                $permissions = $userPermissionService->getUserPermissions(auth()->id());
                $navbarLinks = $navbarService->getNavbarLinks($permissions);
            }

            $view->with('NavbarLinks', $navbarLinks);
        });
    }
}

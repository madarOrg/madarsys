<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Services\NavbarService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(NavbarService::class, function ($app) {
            return new NavbarService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(NavbarService $navbarService)
    {
        //
        // Livewire::component('user-steps', \App\Http\Livewire\UserSteps::class);
        Blade::component('navbar.navbar', 'navbar');

        View::composer('*', function ($view) use ($navbarService) {
            $user = Auth::user();
            $role = $user ? $user->roles()->first() : null;
            $permissions = $role ? $role->permissions->pluck('permission_key')->toArray() : [];
    
            $NavbarLinks = $navbarService->getNavbarLinks($permissions);
    
            $view->with('NavbarLinks', $NavbarLinks);
        });

    }

   


}

<?php

namespace App\Listeners;

use App\Services\UserPermissionService;
use App\Services\NavbarService;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class ClearUserPermissionsCache
{
    protected $userPermissionService;
    protected $navbarService;

    public function __construct(UserPermissionService $userPermissionService, NavbarService $navbarService)
    {
        $this->userPermissionService = $userPermissionService;
        $this->navbarService = $navbarService;
    }

    /**
     * Handle user login events.
     */
    public function handleLogin(Login $event)
    {
        if ($event->user) {
            $this->userPermissionService->clearUserPermissionsCache($event->user->id);
            
            // Get fresh permissions and clear navbar cache
            $permissions = $event->user->roles()->first()?->permissions->pluck('permission_key')->toArray() ?? [];
            $this->navbarService->clearNavbarCache($permissions);
        }
    }

    /**
     * Handle user logout events.
     */
    public function handleLogout(Logout $event)
    {
        if ($event->user) {
            $this->userPermissionService->clearUserPermissionsCache($event->user->id);
            
            // Clear navbar cache with user's last known permissions
            $permissions = $event->user->roles()->first()?->permissions->pluck('permission_key')->toArray() ?? [];
            $this->navbarService->clearNavbarCache($permissions);
        }
    }
}

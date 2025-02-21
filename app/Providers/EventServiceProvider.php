<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // أحداث تسجيل الدخول والخروج
        Login::class => [
            \App\Listeners\ClearUserPermissionsCache::class . '@handleLogin',
        ],
        Logout::class => [
            \App\Listeners\ClearUserPermissionsCache::class . '@handleLogout',
        ],

        // حدث إنشاء حركة مخزنية
        \App\Events\InventoryTransactionCreated::class => [
            \App\Listeners\CreateInventoryTransactionListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use App\Events\NotificationCreated;
use App\Events\InventoryTransactionCreated;
use App\Events\InventoryTransactionUpdated;
use App\Listeners\SendNotificationCreatedNotification;
use App\Listeners\CreateInventoryTransactionListener;
use App\Listeners\UpdateInventoryTransactionListener;
use App\Listeners\ClearUserPermissionsCache;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Models\InventoryTransaction;
use App\Observers\InventoryTransactionObserver;


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
            ClearUserPermissionsCache::class . '@handleLogin',
        ],
        Logout::class => [
            ClearUserPermissionsCache::class . '@handleLogout',
        ],

        // حدث إنشاء حركة مخزنية
        InventoryTransactionCreated::class => [
            CreateInventoryTransactionListener::class,
        ],
        InventoryTransactionUpdated::class => [
            UpdateInventoryTransactionListener::class,
        ],

        // حدث إنشاء التنبيه
        NotificationCreated::class => [
            SendNotificationCreatedNotification::class,
        ],
    ];
    public function boot(): void
    {
        InventoryTransaction::observe(InventoryTransactionObserver::class);
    }
  
}

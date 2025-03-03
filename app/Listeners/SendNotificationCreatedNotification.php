<?php

namespace App\Listeners;

use App\Events\NotificationCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserNotification;

class SendNotificationCreatedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(NotificationCreated $event)
    {
        $user = $event->notification->user;
        if ($user) {
            $user->notify(new UserNotification($event->notification));
        }
    }
}

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

    // public function handle(NotificationCreated $event)
    // {
    //     $user = $event->notification->user;
    //     if ($user) {
    //         // $user->notify(new UserNotification($event->notification));
    //         $user->notify(new UserNotification($event->notification->data, $user->id)); // مرر معرف المستخدم للإشعار

    //     }
    // }
    public function handle(NotificationCreated $event)
{
    $user = $event->notification->user;
    if ($user) {
        // تأكد من أن البيانات عبارة عن مصفوفة؛ إذا كانت سلسلة، قم بتحويلها
        $data = is_array($event->notification->data)
                ? $event->notification->data
                : json_decode($event->notification->data, true);
        
        $user->notify(new UserNotification($data, $user->id));
    }
}

}

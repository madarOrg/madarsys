<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;

class NotificationCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    /**
     * إنشاء نسخة جديدة من الحدث.
     *
     * @param Notification $notification
     * @return void
     */
    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * تحدد القنوات التي سيتم بث الحدث عليها.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // بث الحدث على قناة خاصة بالمستخدم المحدد باستخدام user_id
        return new PrivateChannel('notifications.' . $this->notification->user_id);
    }

    /**
     * اسم الحدث عند البث.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'create';
    }

    /**
     * البيانات التي سيتم بثها مع الحدث.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'message' => "إشعار جديد من {$this->notification->user_id}",
            'notification_id' => $this->notification->id,
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use Illuminate\Broadcasting\PrivateChannel;
class UserNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    protected $data;
    protected $userId;
    public $notification;

    public function __construct(array $data, $userId)
    {
        $this->data = $data;
        $this->userId = $userId; // حفظ معرف المستخدم
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // سيتم تخزين الإشعار في قاعدة البيانات وبثه عبر WebSockets
    }

    public function toArray($notifiable)
    {
        return [
            'message' => $this->data['message'],
            'type' => $this->data['type'],
            'priority' => $this->data['priority'],
            'due_date' => $this->data['due_date'],
            'product_id' => $this->data['product_id'],
            'inventory_request_id' => $this->data['inventory_request_id'],
            'quantity' => $this->data['quantity'],
            'status' => 'new',
            'department_id' => $this->data['department_id'],
            'warehouse_id' => $this->data['warehouse_id'],
        ];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('notifications.' . $this->notification->user_id);
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->data['message'],
            'type' => $this->data['type'],
            'priority' => $this->data['priority'],
            'warehouse_id' => $this->data['warehouse_id'],
        ];
    }
}

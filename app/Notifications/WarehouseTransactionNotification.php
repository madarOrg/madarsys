<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use App\Models\InventoryTransaction;

class WarehouseTransactionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $transaction;

    public function __construct(InventoryTransaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'transaction_id' => $this->transaction->id,
            'type' => $this->transaction->transaction_type_id,
            'warehouse_id' => $this->transaction->warehouse_id,
            'reference' => $this->transaction->reference,
            'date' => $this->transaction->transaction_date,
            'title' => 'معاملة مخزنية جديدة',
            'message' => "تم إجراء معاملة جديدة في المخزن {$this->transaction->warehouse->name}",
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
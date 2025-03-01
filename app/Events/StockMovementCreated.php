<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\InventoryTransaction;

class StockMovementCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $stockMovement;

    /**
     * Create a new event instance.
     */
    public function __construct(InventoryTransaction $stockMovement)
    {
        $this->stockMovement = $stockMovement;
    }

    /**
     * تحديد القناة التي سيتم البث إليها
     */
    public function broadcastOn()
    {
        return new Channel('stock-movements');
    }

    /**
     * تحديد اسم الحدث
     */
    public function broadcastAs()
    {
        return 'stock.created';
    }

    /**
     * البيانات التي سيتم إرسالها
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->stockMovement->id,
            'type' => $this->stockMovement->transaction_type_id, // نوع الحركة (إضافة/صرف)
            'quantity' => $this->stockMovement->quantity,
            'created_at' => $this->stockMovement->created_at->format('Y-m-d H:i:s'),
            'message' => "تم تسجيل حركة مخزنية جديدة ({$this->stockMovement->type}) بكمية ({$this->stockMovement->quantity})"
        ];
    }
}

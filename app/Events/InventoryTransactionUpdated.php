<?php

namespace App\Events;

use App\Models\InventoryTransaction;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InventoryTransactionUpdated
{
    use Dispatchable, SerializesModels;

    public $transaction;
    public $oldData;
    public $newData;

    public function __construct(InventoryTransaction $transaction, $oldData, $newData)
    {
        $this->transaction = $transaction;
        $this->oldData = $oldData;
        $this->newData = $newData;
    }
}

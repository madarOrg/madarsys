<?php
namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InventoryTransactionCreated
{
    use Dispatchable, SerializesModels;

    public $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
}

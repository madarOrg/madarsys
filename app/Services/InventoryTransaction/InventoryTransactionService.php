<?php
namespace App\Services\InventoryTransaction;

use App\Events\InventoryTransactionCreated;

class InventoryTransactionService
{
    public function createTransaction(array $data)
    {
        event(new InventoryTransactionCreated($data));
    }
}

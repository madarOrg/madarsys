<?php
namespace App\Services\InventoryTransaction;

use App\Events\{
    InventoryTransactionCreated,
    InventoryTransactionUpdated
};
use App\Models\InventoryTransaction;

class InventoryTransactionService
{
    public function createTransaction(array $data)
    {
        event(new InventoryTransactionCreated($data));
    }
    public function updateTransaction($id, array $data)
    {
        try {
            $transaction = InventoryTransaction::findOrFail($id);
            $oldData = $transaction->toArray();
    
            $transaction->update($data);
    
            event(new InventoryTransactionUpdated($transaction, $oldData, $data));
    
            return $transaction;
        } catch (\Exception $e) {
            throw new \Exception("خطأ أثناء تحديث العملية المخزنية: " . $e->getMessage());
        }
    }
    

}

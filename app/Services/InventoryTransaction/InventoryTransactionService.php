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
            // dd(request()->all());
            $transaction->update($data);

            event(new InventoryTransactionUpdated($transaction, $oldData, $data));

            return $transaction;
        } catch (\Exception $e) {
            throw new \Exception("خطأ أثناء تحديث العملية المخزنية: " . $e->getMessage());
        }
    }
    public function getAllTransactions($search = null)
    {
        $query = InventoryTransaction::with([
            'partner',
            'department',
            'warehouse.storageAreas', // جلب مناطق التخزين
            'warehouse.warehouseLocations', // جلب المواقع التخزينية
            'items'
        ]);    

        if ($search) {
            $query->where('reference', 'like', '%' . $search . '%')
                ->orWhereHas('partner', fn($q) => $q->where('name', 'like', '%' . $search . '%'))
                ->orWhereHas('department', fn($q) => $q->where('name', 'like', '%' . $search . '%'))
                ->orWhereHas('warehouse', fn($q) => $q->where('name', 'like', '%' . $search . '%'));
        }
    
        return $query->latest()->get();
    }
    
}

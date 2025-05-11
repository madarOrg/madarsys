<?php

namespace App\Observers;

use App\Models\InventoryTransaction;
use App\Models\RoleWarehouse;
use App\Models\User;
use App\Notifications\WarehouseTransactionNotification;

class InventoryTransactionObserver
{
    public function created(InventoryTransaction $transaction)
    {
        $this->notifyWarehouseUsers($transaction);
    }

    protected function notifyWarehouseUsers(InventoryTransaction $transaction)
    {
        // Get all users who have access to this warehouse through role_warehouse
        $warehouseUsers = User::whereHas('roles', function ($query) use ($transaction) {
            $query->whereHas('warehousesUsers', function ($q) use ($transaction) {
                $q->where('warehouse_id', $transaction->warehouse_id);
            });
        })->get();

        // Notify each user
        foreach ($warehouseUsers as $user) {
            $user->notify(new WarehouseTransactionNotification($transaction));
        }
    }
}
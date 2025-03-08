<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\UserNotification;
use Carbon\Carbon;

class NotificationService
{
    /**
     * إرسال إشعار للمستخدمين المرتبطين بمستودع معين
     *
     * @param int $warehouseId
     * @param string $message
     * @param string|null $type
     * @param int $priority
     * @param int|null $productId
     * @param int|null $inventoryRequestId
     */
    public function sendWarehouseUsersNotification(
        int $warehouseId,
        string $message,
        string $type = 'restocking',
        int $priority = 1,
        ?int $productId = null,
        ?int $inventoryRequestId = null,
        ?int $departmentId = null // add department_id here
    ) {
        // Use $departmentId where needed
        // Example: log it, pass it into the notification, etc.
   
        // جلب جميع المستخدمين المرتبطين بالمستودع
        $users = User::whereIn('id', function ($query) use ($warehouseId) {
            $query->select('user_id')
                ->from('role_user')
                ->whereIn('role_id', function ($subQuery) use ($warehouseId) {
                    $subQuery->select('role_id')
                        ->from('role_warehouse')
                        ->where('warehouse_id', $warehouseId);
                });
        })->get();

        // إرسال الإشعار لكل مستخدم
        foreach ($users as $user) {
            $user->notify(new UserNotification([
                'message' => $message, //'تم حفظ عملية جديدة في مستودعك',
                'type' => $type,
                'priority' => $priority,
                'due_date' => Carbon::now()->addDays(1),
                'product_id' => null,
                'inventory_request_id' => null,
                'quantity' => null,
                'status' => 'new',
                'department_id' => $departmentId, 
                'warehouse_id' => $warehouseId,
            ], $user->id)); // هنا يتم تمرير معرف المستخدم كمعامل ثانٍ
                    }
    }
}

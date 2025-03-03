<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserNotification extends Notification
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database']; // حفظ الإشعار في قاعدة البيانات
    }

    public function toArray($notifiable)
    {dd('in');
        dd($notifiable);
        // تحديد القالب والإشعار
        $template = DB::table('notification_templates')
            ->where('type', $this->data['type'])
            ->first();

        // إنشاء إشعار جديد في جدول `notifications`
        $notificationId = DB::table('notifications')->insertGetId([
            'branch_id' => $this->data['branch_id'],
            'template_id' => $template ? $template->id : null,
            'product_id' => $this->data['product_id'],
            'inventory_request_id' => $this->data['inventory_request_id'],
            'quantity' => $this->data['quantity'],
            'status' => 0,  // حالة التنبيه: غير مقروء
            'priority' => $this->data['priority'],
            'due_date' => $this->data['due_date'],
            'department_id' => $this->data['department_id'],
            'warehouse_id' => $this->data['warehouse_id'],
            'created_user' => $this->data['created_user'],
            'updated_user' => $this->data['updated_user'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // إدخال الإشعار في جدول `user_notifications`
        DB::table('user_notifications')->insert([
            'user_id' => $notifiable->id,
            'notification_id' => $notificationId,
            'message' => $this->data['message'],
            'is_read' => false, // الإشعار غير مقروء
            'created_user' => $this->data['created_user'],
            'updated_user' => $this->data['updated_user'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // إرجاع البيانات بالشكل المطلوب حتى يتمكن Laravel من حفظها
        return [
            'notification_id' => $notificationId,
            'message' => $this->data['message'],
            'priority' => $this->data['priority'],
            'due_date' => $this->data['due_date'],
            'status' => 0,  // غير مقروء
            'warehouse_id' => $this->data['warehouse_id'],
            'department_id' => $this->data['department_id'],
        ];
    }
}

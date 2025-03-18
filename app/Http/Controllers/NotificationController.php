<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // جلب الإشعارات غير المقروءة للمستخدم الحالي
    public function getUnreadNotifications()
    {
        $user = Auth::user();
        return response()->json([
            'notifications' => $user->unreadNotifications, // يجب أن يعمل الآن بشكل صحيح
        ]);
        
    }

    // تحديث حالة الإشعار إلى "مقروء"
    public function markAsRead($id)
    {
        $user = Auth::user();
    
        // البحث عن الإشعار في الإشعارات غير المقروءة
        $notification = $user->unreadNotifications()->where('id', $id)->first();
    
        if ($notification) {
            $notification->markAsRead(); // تحديث read_at
            return response()->json(['success' => true]);
        }
    
        return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
    }
    
}

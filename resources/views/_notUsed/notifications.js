import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// تعريف userId من بيانات الخادم (تأكد من تعريفه في Blade مثلاً)
const userId = window.Laravel ? window.Laravel.userId : null;

// استخدام متغيرات البيئة من Vite لتحديد الإعدادات حسب البيئة
const isProduction = import.meta.env.PROD; // true في بيئة الإنتاج
const pusherKey = isProduction ? import.meta.env.VITE_PUSHER_APP_KEY : 'your-pusher-key';
const pusherCluster = isProduction ? import.meta.env.VITE_PUSHER_APP_CLUSTER : 'mt1';

window.Echo = new Echo({
    broadcaster: 'pusher', // حتى لو كنت تستخدم laravel-websockets محليًا يمكنك استخدام إعدادات pusher هنا
    key: pusherKey,
    cluster: pusherCluster,
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: isProduction, // استخدم TLS في الإنتاج إذا كان موقعك يعمل بـ HTTPS
    disableStats: true,
    authEndpoint: '/broadcasting/auth', // نقطة المصادقة للقنوات الخاصة
    auth: {
         headers: {
            'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
         }
    }
});
console.log(userId);
console.log(    window.Echo.private('private-notifications.' + userId)
);
// الاشتراك في القناة الخاصة إذا كان userId موجودًا
if (userId) {
    window.Echo.private('private-notifications.' + userId)
        .listen('UserNotification', (event) => {
            console.log("📢 إشعار جديد:", event);
            // عرض الإشعار باستخدام SweetAlert
            Swal.fire({
                title: "إشعار ",
                text: event.message,
                icon: "info",
                confirmButtonText: "حسنًا"
            });
        })
        .subscribed(() => {
            console.log(`تم الاشتراك في القناة private-notifications.${userId}`);
        })
        .error((error) => {
            console.error("خطأ في الاشتراك بالقناة:", error);
        });
}

// جلب الإشعارات عند تحميل الصفحة
document.addEventListener("DOMContentLoaded", function () {
    fetch("/notifications")
        .then(response => response.json())
        .then(data => {
            if (data.notifications && data.notifications.length > 0) {
                data.notifications.forEach(notification => {
                    Swal.fire({
                        title: "إشعار جديد",
                        text: notification.data.message,
                        icon: "info",
                        confirmButtonText: "حسنًا"
                    });
                    // تحديد الإشعار كمقروء بعد عرضه
                    markAsRead(notification.id);
                });
            }
        });
});

// وظيفة لتحديد الإشعار كمقروء
function markAsRead(notificationId) {
    // تحديث الـ DOM لتغيير حالة الإشعار (اختياري)
    const notificationElement = document.getElementById(`notification-${notificationId}`);
    if (notificationElement) {
        notificationElement.classList.add("read");
    }
    // إرسال الطلب إلى السيرفر لتحديث حالة الإشعار
    fetch(`/mark-notification-as-read/${notificationId}`, { method: "POST" })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log("تم تحديد الإشعار كمقروء.");
            }
        });
}

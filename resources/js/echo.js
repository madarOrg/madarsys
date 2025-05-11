
import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });


// إعداد Socket.IO مباشرة
window.io = io;

// إعداد Echo لاستخدام Reverb
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: 'http://127.0.0.1:8080', // أو استخدم `${window.location.hostname}:8080` لو أردت
    transports: ['websocket'],
});


// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';
// import { io } from 'socket.io-client'; // For Socket.io v4.x
// // Make sure io is globally available
// window.io = io; 

// // Define userId, either from window.Laravel or another method
// const userId = window.Laravel ? window.Laravel.userId : null;

// // Use import.meta.env to determine settings from Vite
// const env = import.meta.env; // Vite environment variable

// // Check if the environment is production or development
// const isProduction = true; // set to `false` if not in production

// // Broadcasting type (can be "pusher" or "socket.io" depending on your environment)
// const broadcaster = 'Pusher'; 

// // Define the key and cluster depending on the environment
// const key = isProduction ? env.VITE_PUSHER_APP_KEY : 'your-pusher-key';
// const cluster = env.VITE_PUSHER_APP_CLUSTER || 'mt1';
// // const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// // Log the environment for debugging purposes
// console.log(isProduction);

// const token = localStorage.getItem('token'); // يجب ألا يكون null

// window.Echo = new Echo(
//     isProduction
//         ? {
//             broadcaster: 'socket.io',
//             host: window.location.hostname + ':6001',
//             // withCredentials: true, // للتأكد من إرسال الـ CSRF Token
//             auth: {
//                 headers: {
//                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')                }
//             }
//           }
//         : {
//             broadcaster: 'pusher',
//             key: key,
//             cluster: cluster,
//             wsHost: window.location.hostname,
//             wsPort: 6001,
//             forceTLS: isProduction, 
//             disableStats: true,
//             auth: {
//                 headers: {
//                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
//                 },
//             },
//         }
// );

// // Subscribe to the private channel if userId is available
// if (userId) {
//     console.log(window.Echo.private('notifications.' + userId));

//     window.Echo.private('notifications.' + userId)
//        .listen('UserNotification', (event) => {
//            console.log("📢 New Notification:", event);
//            Swal.fire({
//                title: "اشعار جديد",
//                text: event.message,
//                icon: "info",
//                confirmButtonText: "حسنا"
//            });
//        })
//        .subscribed(() => {
//            console.log(`Subscribed to the private-notifications.${userId} channel`);
//        })
//        .error((error) => {
//            console.error("Error subscribing to the channel:", error);
//        });
// }

// // Function to mark the notification as read
// function markAsRead(notificationId) {
//     // Update the element in the DOM to reflect its read state (optional)
//     const notificationElement = document.getElementById(`notification-${notificationId}`);
//     if (notificationElement) {
//         notificationElement.classList.add("read");
//     }

//     // Send a request to the server to update the notification state
//     fetch(`/mark-notification-as-read/${notificationId}`, { method: "POST" })
//         .then(response => response.json())
//         .then(data => {
//             if (data.success) {
//                 console.log("Notification marked as read.");
//             }
//         });
// }

// // Fetch notifications when the page is loaded
// document.addEventListener("DOMContentLoaded", function () {
//     fetch("/notifications")
//         .then(response => response.json())
//         .then(data => {
//             if (data.notifications && data.notifications.length > 0) {
//                 data.notifications.forEach(notification => {
//                     Swal.fire({
//                         title: "New Notification",
//                         text: notification.data.message,
//                         icon: "info",
//                         confirmButtonText: "Okay"
//                     });

//                     // Mark the notification as read after displaying it
//                     markAsRead(notification.id);
//                 });
//             }
//         });
// });

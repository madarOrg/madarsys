import axios from 'axios';
import Echo from 'laravel-echo';
import io from 'socket.io-client';

// تعيين Axios عالميًا
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// إعداد socket.io كـ global (ضروري لـ Echo مع Reverb)
window.io = io;

// تهيئة Echo مع Reverb
window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: 'http://127.0.0.1:8080',  // تأكد من أن Reverb يعمل على هذا المنفذ
    transports: ['websocket'],  // تأكد من دعم WebSocket فقط لتفادي fallback
});

// متابعة الأخطاء
window.Echo.connector.socket.on('connect_error', (err) => {
    console.error('Socket.IO connection error:', err);
});

// إعادة التهيئة بعد Livewire
document.addEventListener("livewire:load", () => {
    initializeTomSelects();
});

Livewire.hook('message.processed', (message, component) => {
    initializeTomSelects();
});

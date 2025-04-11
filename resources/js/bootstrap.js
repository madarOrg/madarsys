import axios from 'axios';

// تعيين Axios عالميًا
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
document.addEventListener("livewire:load", () => {
    initializeTomSelects();
});

Livewire.hook('message.processed', (message, component) => {
    initializeTomSelects();
});

// استيراد echo.js فقط
// import './echo';

import axios from 'axios';

// تعيين Axios عالميًا
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// استيراد echo.js فقط
// import './echo';

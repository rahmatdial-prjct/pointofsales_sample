import axios from 'axios';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Removed Laravel Echo and Pusher real-time chat setup as per user request

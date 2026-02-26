import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Request Interceptor: Attach JWT Token
window.axios.interceptors.request.use(config => {
    const token = sessionStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, error => {
    return Promise.reject(error);
});

// Response Interceptor: Handle 401 and Refresh Token
window.axios.interceptors.response.use(response => {
    return response;
}, async error => {
    const originalRequest = error.config;

    if (error.response && error.response.status === 401 && !originalRequest._retry) {
        originalRequest._retry = true;

        try {
            const res = await axios.post('/api/refresh');
            if (res.status === 200) {
                const newToken = res.data.access_token;
                sessionStorage.setItem('token', newToken);
                originalRequest.headers.Authorization = `Bearer ${newToken}`;
                return axios(originalRequest);
            }
        } catch (refreshError) {
            sessionStorage.removeItem('token');
            if (window.location.pathname.startsWith('/admin')) {
                window.location.href = '/admin/login';
            } else if (window.location.pathname.startsWith('/employee')) {
                window.location.href = '/employee/login';
            }
        }
    }

    return Promise.reject(error);
});


/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';

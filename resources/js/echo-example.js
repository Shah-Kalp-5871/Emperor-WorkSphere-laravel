// Echo Example
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Assume you got this token from your JWT login API
const token = 'YOUR_JWT_ACCESS_TOKEN';

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    // Authentication headers for Private Channels
    auth: {
        headers: {
            Authorization: `Bearer ${token}`
        }
    }
});

// Subscribe to employee's private channel (e.g., employee id 5)
const employeeId = 5;

window.Echo.private(`employee.${employeeId}`)
    .listen('.task.assigned', (e) => {
        console.log('New Task Notification!', e);
        // e contains: task_id, title, project_id, message
        alert(e.message);
    });

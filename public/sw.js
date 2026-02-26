// sw.js (Example Service Worker for Web Push)

self.addEventListener('push', function (event) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    const data = event.data ? event.data.json() : {};

    const title = data.title || 'New Notification';
    const message = data.message || 'You have new updates.';
    const icon = data.icon || '/favicon.ico';
    const url = data.url || '/';

    const options = {
        body: message,
        icon: icon,
        data: {
            url: url
        }
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    const url = event.notification.data.url;

    if (url) {
        event.waitUntil(
            clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (clientList) {
                // If a window tab is already open to the URL, focus it
                for (let i = 0; i < clientList.length; i++) {
                    let client = clientList[i];
                    if (client.url === url && 'focus' in client) {
                        return client.focus();
                    }
                }
                // Otherwise open a new window
                if (clients.openWindow) {
                    return clients.openWindow(url);
                }
            })
        );
    }
});

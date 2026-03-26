document.addEventListener('DOMContentLoaded', () => {
    // UI Elements
    const socket = io();
    const chatMessages = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const loadingOverlay = document.getElementById('loading-overlay');
    const statusDot = document.querySelector('.status-dot');
    const connectionStatus = document.getElementById('connection-status');
    const scrollBottomBtn = document.getElementById('scroll-bottom-btn');
    const unreadBadge = document.getElementById('unread-badge');
    const notiBtn = document.getElementById('noti-btn');
    const toastContainer = document.getElementById('toast-container');
    
    // State
    let isConnected = false;
    let unreadCount = 0;
    let notificationsEnabled = false;
    let localSessionIds = []; // Simple array to temporarily identify my own messages for UI coloring
    
    // --- Socket.IO Event Listeners ---
    
    socket.on('connect', () => {
        isConnected = true;
        statusDot.classList.add('connected');
        connectionStatus.textContent = 'Online';
        messageInput.disabled = false;
    });

    socket.on('disconnect', () => {
        isConnected = false;
        statusDot.classList.remove('connected');
        connectionStatus.textContent = 'Disconnected';
        messageInput.disabled = true;
    });

    socket.on('load_history', (data) => {
        // Clear loading state
        loadingOverlay.style.opacity = '0';
        setTimeout(() => loadingOverlay.style.display = 'none', 300);
        
        chatMessages.innerHTML = '';
        
        const messages = data.messages || [];
        messages.forEach(msg => {
            appendMessage(msg, false);
        });
        
        scrollToBottom();
    });

    socket.on('receive_message', (msg) => {
        appendMessage(msg, true);
    });

    socket.on('error', (data) => {
        showToast(data.message, 'error');
    });

    // --- Form Submission ---

    chatForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const content = messageInput.value.trim();
        if (!content || !isConnected) return;
        
        // Emit to server
        socket.emit('send_message', { message: content });
        
        // Add to local temporarily to color the bubble outbox style
        // We'll match against content and rough timestamp
        const tempId = 'msg_' + Date.now();
        localSessionIds.push(content);
        
        messageInput.value = '';
        messageInput.focus();
    });

    // --- UI Helper Functions ---

    function appendMessage(msg, animateScroll = true) {
        const wrapper = document.createElement('div');
        wrapper.className = 'message-wrapper';
        wrapper.id = `msg-${msg.id}`;
        
        // Check if I sent this (roughly, based on precise exact match of recent local sends)
        const isMine = localSessionIds.includes(msg.content);
        if (isMine) {
            wrapper.classList.add('outgoing');
            // Remove from array to avoid false positives if someone else sends same text
            localSessionIds.splice(localSessionIds.indexOf(msg.content), 1);
        }

        const bubble = document.createElement('div');
        bubble.className = 'message-bubble';
        
        const sender = document.createElement('span');
        sender.className = 'msg-sender';
        sender.textContent = isMine ? 'You (Anonymous)' : 'Anonymous';
        
        const contentStr = msg.content;
        const textNode = document.createElement('div');
        textNode.className = 'msg-content';
        // Content is escaped on the backend, so innerHTML is safe for text, 
        // but textContent is safer. We'll use innerHTML to allow newlines which backend maintains via <br> (if it did)
        textNode.textContent = contentStr; 
        
        const meta = document.createElement('span');
        meta.className = 'msg-meta';
        meta.textContent = formatTime(msg.timestamp);

        bubble.appendChild(sender);
        bubble.appendChild(textNode);
        bubble.appendChild(meta);
        wrapper.appendChild(bubble);
        
        chatMessages.appendChild(wrapper);
        
        handleNewMessageScroll(animateScroll);
        
        // Handle Notifications if tab is inactive
        if (document.hidden && !isMine) {
            unreadCount++;
            updateUnreadBadge();
            sendBrowserNotification(msg.content);
        }
    }

    function formatTime(isoString) {
        if (!isoString) return '';
        // SQLite returns UTC format typically 'YYYY-MM-DD HH:MM:SS'
        // Let's add 'Z' to ensure it's treated as UTC if format matches
        let dStr = isoString;
        if (!dStr.includes('Z') && dStr.includes(' ')) {
            dStr = dStr.replace(' ', 'T') + 'Z';
        }
        const d = new Date(dStr);
        if (isNaN(d)) return isoString.split(' ')[1] || ''; // Fallback
        
        return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    // --- Scrolling Logic ---

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function handleNewMessageScroll(animate) {
        // Auto scroll if user is near bottom
        const threshold = 100;
        const isNearBottom = chatMessages.scrollHeight - chatMessages.scrollTop - chatMessages.clientHeight < threshold;
        
        if (isNearBottom) {
            scrollToBottom();
            hideScrollButton();
        } else {
            showScrollButton();
            if(!document.hidden) {
                // Not at bottom but received message
                unreadCount++;
                updateUnreadBadge();
            }
        }
    }

    chatMessages.addEventListener('scroll', () => {
        const isNearBottom = chatMessages.scrollHeight - chatMessages.scrollTop - chatMessages.clientHeight < 50;
        if (isNearBottom) {
            hideScrollButton();
            unreadCount = 0;
            updateUnreadBadge();
        } else {
            if (scrollBottomBtn.classList.contains('hidden')) {
                scrollBottomBtn.classList.remove('hidden');
            }
        }
    });

    scrollBottomBtn.addEventListener('click', () => {
        scrollToBottom();
        hideScrollButton();
        messageInput.focus();
    });

    function showScrollButton() {
        scrollBottomBtn.classList.remove('hidden');
    }

    function hideScrollButton() {
        scrollBottomBtn.classList.add('hidden');
        unreadCount = 0;
        updateUnreadBadge();
    }

    function updateUnreadBadge() {
        if (unreadCount > 0) {
            unreadBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
            unreadBadge.classList.remove('hidden');
        } else {
            unreadBadge.classList.add('hidden');
        }
    }

    // --- Notifications ---

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            // Document became visible
            if (chatMessages.scrollHeight - chatMessages.scrollTop - chatMessages.clientHeight < 50) {
                unreadCount = 0;
                updateUnreadBadge();
            }
        }
    });

    notiBtn.addEventListener('click', () => {
        if ('Notification' in window) {
            if (Notification.permission === 'granted') {
                showToast('Notifications are already enabled', 'info');
                notiBtn.classList.add('active');
            } else if (Notification.permission !== 'denied') {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        showToast('Notifications enabled!', 'success');
                        notiBtn.classList.add('active');
                    }
                });
            } else {
                showToast('Notifications disabled in browser settings', 'error');
            }
        } else {
            showToast('Browser does not support notifications', 'error');
        }
    });

    // Check initial notification state
    if ('Notification' in window && Notification.permission === 'granted') {
        notiBtn.classList.add('active');
    }

    function sendBrowserNotification(msgText) {
        if ('Notification' in window && Notification.permission === 'granted') {
            const noti = new Notification('New Anonymous Message', {
                body: msgText.length > 50 ? msgText.substring(0, 50) + '...' : msgText,
                icon: 'https://cdn-icons-png.flaticon.com/512/2815/2815545.png' // generic avatar
            });
            noti.onclick = function() {
                window.focus();
                scrollToBottom();
                this.close();
            };
        } else {
            showToast('New message', 'info');
        }
    }

    // --- Toast Notifications ---

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        
        toastContainer.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'toastOut 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }
});

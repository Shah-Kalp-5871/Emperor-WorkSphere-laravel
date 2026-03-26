@extends('layouts.admin.master')

@section('title', 'Anonymous Chat — Admin Panel')

@section('content')
<div class="chat-container" style="animation: fadeUp 0.4s ease-out; display: flex; flex-direction: column; height: calc(100vh - 120px);">
    <!-- Header -->
    <div style="margin-bottom: 24px;">
        <h1 style="font-family: 'Syne', sans-serif; font-size: 32px; font-weight: 700; letter-spacing: -0.5px;">Anonymous Chat</h1>
        <p style="color: var(--text-3); font-size: 14px; margin-top: 4px;">Speak freely. Your identity is hidden.</p>
    </div>

    <!-- Message Feed -->
    <div class="message-feed" id="messageFeed" style="flex: 1; overflow-y: auto; padding-right: 10px; display: flex; flex-direction: column; gap: 16px; margin-bottom: 24px;">
        <!-- Messages will be injected here dynamically by Socket.IO -->
        <div style="text-align: center; color: var(--text-3); font-size: 14px; margin-top: auto; margin-bottom: auto;" id="loadingIndicator">
            Connecting to secure chat server...
        </div>
    </div>

    <!-- Input Section -->
    <div class="chat-input-wrapper">
        <div class="chat-input-panel">
            <button class="chat-action-btn" title="Add emoji">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 14C9.18131 14.4723 9.47841 14.8915 9.864 15.219C11.0903 16.2483 12.8748 16.2613 14.116 15.25C14.5069 14.9283 14.8109 14.5136 15 14.044" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M19 12C19 15.866 15.866 19 12 19C8.13401 19 5 15.866 5 12C5 8.13401 8.13401 5 12 5C13.8565 5 15.637 5.7375 16.9497 7.05025C18.2625 8.36301 19 10.1435 19 12Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M9 11V10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    <path d="M15 11V10" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                </svg>
            </button>
            <textarea id="chatInput" class="chat-textarea" placeholder="Type your anonymous message..." rows="1"></textarea>
            <button class="chat-send-btn-circle" onclick="sendMessage()" title="Send Message">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Footer Note -->
    <div style="text-align: center; margin-top: 16px; padding-bottom: 8px;">
        <p style="color: var(--text-3); font-size: 11px; letter-spacing: 0.02em;">All messages are monitored for safety.</p>
    </div>
</div>

@push('styles')
<style>
    .message-bubble {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 14px 18px;
        max-width: 80%;
        align-self: flex-start;
        box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
    }

    .message-bubble:hover {
        transform: translateY(-1px);
        box-shadow: var(--shadow);
    }

    .message-content {
        font-size: 14px;
        color: var(--text-1);
        line-height: 1.5;
        word-break: break-word;
    }

    .message-time {
        font-size: 10px;
        color: var(--text-3);
        margin-top: 8px;
        text-align: right;
    }

    .chat-input-wrapper {
        padding: 4px;
        background: var(--surface-2);
        border-radius: calc(var(--radius) + 4px);
        margin-bottom: 8px;
    }

    .chat-input-panel {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: var(--radius);
        padding: 10px 14px;
        display: flex;
        gap: 12px;
        align-items: center;
        box-shadow: var(--shadow);
        transition: box-shadow 0.2s, border-color 0.2s;
    }

    .chat-input-panel:focus-within {
        border-color: var(--accent);
        box-shadow: var(--shadow-md);
    }

    .chat-textarea {
        flex: 1;
        background: transparent;
        border: none;
        padding: 8px 0;
        font-size: 14px;
        font-family: inherit;
        color: var(--text-1);
        resize: none;
        outline: none;
        max-height: 120px;
        line-height: 1.4;
    }

    .chat-action-btn {
        background: transparent;
        border: none;
        color: var(--text-3);
        cursor: pointer;
        padding: 4px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .chat-action-btn:hover {
        color: var(--accent);
        background: var(--accent-lt);
    }

    .chat-send-btn-circle {
        width: 42px;
        height: 42px;
        background: var(--accent);
        color: #fff;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(45, 106, 79, 0.2);
    }

    .chat-send-btn-circle:hover {
        background: #245c42;
        transform: scale(1.05);
        box-shadow: 0 6px 14px rgba(45, 106, 79, 0.3);
    }

    .chat-send-btn-circle:active {
        transform: scale(0.95);
    }

    /* Message feed scrollbar */
    .message-feed::-webkit-scrollbar {
        width: 6px;
    }
    .message-feed::-webkit-scrollbar-track {
        background: transparent;
    }
    .message-feed::-webkit-scrollbar-thumb {
        background: var(--border-2);
        border-radius: 3px;
    }
</style>
@endpush

@push('scripts')
<!-- Socket.IO Client -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/4.7.1/socket.io.js"></script>
<script>
    const socket = io('http://127.0.0.1:5000'); // Connect to Flask Chat Server
    const feed = document.getElementById('messageFeed');
    const input = document.getElementById('chatInput');
    const loadingIndicator = document.getElementById('loadingIndicator');

    // Auto-resize textarea
    input.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Socket Events
    socket.on('connect', () => {
        if(loadingIndicator) loadingIndicator.textContent = 'Connected. Loading messages...';
    });

    socket.on('load_history', (data) => {
        feed.innerHTML = ''; // Clear loading
        const messages = data.messages || [];
        messages.forEach(msg => appendMessage(msg, false));
        scrollToBottom();
    });

    socket.on('receive_message', (msg) => {
        if (feed.querySelector('#loadingIndicator')) {
            feed.innerHTML = '';
        }
        appendMessage(msg, true);
    });

    socket.on('error', (data) => {
        alert(data.message); // Simple alert
    });

    socket.on('disconnect', () => {
        feed.innerHTML += `<div style="text-align: center; color: var(--text-3); font-size: 14px; margin: 10px 0;">Disconnected from server. Reconnecting...</div>`;
        scrollToBottom();
    });

    function sendMessage() {
        const text = input.value.trim();
        if (text === '') return;

        // Send to server
        socket.emit('send_message', { message: text });
        
        // Clear input
        input.value = '';
        input.style.height = 'auto';
        input.focus();
    }

    function appendMessage(msg, animate = false) {
        // Format time
        let timeStr = '';
        if (msg.timestamp) {
            let dStr = msg.timestamp;
            if (!dStr.includes('Z') && dStr.includes(' ')) {
                 dStr = dStr.replace(' ', 'T') + 'Z';
            }
            const d = new Date(dStr);
            if (!isNaN(d)) {
                timeStr = d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            }
        }

        const div = document.createElement('div');
        div.className = 'message-bubble';
        if (animate) {
            div.style.opacity = '0';
            div.style.transform = 'translateY(10px)';
            div.style.transition = 'all 0.3s ease-out';
        }
        
        div.innerHTML = `
            <div class="message-content">${escapeHtml(msg.content)}</div>
            <div class="message-time">${timeStr}</div>
        `;

        feed.appendChild(div);
        
        if (animate) {
            setTimeout(() => {
                div.style.opacity = '1';
                div.style.transform = 'translateY(0)';
            }, 10);
        }

        scrollToBottom();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function scrollToBottom() {
        feed.scrollTop = feed.scrollHeight;
    }

    // Enter to send (Shift+Enter for newline)
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
</script>
@endpush
@endsection

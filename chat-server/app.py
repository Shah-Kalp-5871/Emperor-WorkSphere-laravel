from flask import Flask, render_template, request
from flask_socketio import SocketIO, emit
from database import init_db, save_message, get_recent_messages
import time
from markupsafe import escape

app = Flask(__name__)
app.config['SECRET_KEY'] = 'anonymous_chat_secret_key'
socketio = SocketIO(app, cors_allowed_origins="*")

# Simple in-memory spam protection
# Dictionary to store last message time per session
user_last_message_time = {}

# Constants for spam protection
COOLDOWN_SECONDS = 2
MAX_MESSAGE_LENGTH = 500

# Basic bad word filter (expand as needed)
BAD_WORDS = ['spam', 'abuse', 'hate']

@app.route('/')
def index():
    return render_template('index.html')

@socketio.on('connect')
def handle_connect():
    # Send recent messages when a user connects
    messages = get_recent_messages(100)
    emit('load_history', {'messages': messages})

@socketio.on('send_message')
def handle_message(data):
    session_id = request.sid
    current_time = time.time()
    
    # Check spam protection cooldown
    last_time = user_last_message_time.get(session_id, 0)
    if current_time - last_time < COOLDOWN_SECONDS:
        emit('error', {'message': f'Please wait {COOLDOWN_SECONDS} seconds between messages.'})
        return
        
    raw_message = data.get('message', '').strip()
    
    # Check if empty
    if not raw_message:
        return
        
    # Check max length
    if len(raw_message) > MAX_MESSAGE_LENGTH:
        emit('error', {'message': f'Message too long. Maximum {MAX_MESSAGE_LENGTH} characters.'})
        return
        
    # Prevent basic XSS by escaping HTML
    safe_message = str(escape(raw_message))
    
    # Basic bad word filter
    for word in BAD_WORDS:
        # replace case insensitive
        import re
        safe_message = re.sub(f'(?i){word}', '*' * len(word), safe_message)
        
    # Update last message time
    user_last_message_time[session_id] = current_time
    
    # Save to database
    msg_record = save_message(safe_message)
    
    # Broadcast to all users
    socketio.emit('receive_message', msg_record)

if __name__ == '__main__':
    init_db()
    
    print("=========================================")
    print("🚀 Anonymous Global Chat Server Starting")
    print("🌐 Access via http://127.0.0.1:5000")
    print("=========================================")
    
    socketio.run(app, debug=True, host='0.0.0.0', port=5000)

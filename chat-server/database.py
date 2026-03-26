import sqlite3
import os

DB_PATH = os.path.join(os.path.dirname(__file__), 'chat.db')

def get_db_connection():
    conn = sqlite3.connect(DB_PATH)
    conn.row_factory = sqlite3.Row
    return conn

def init_db():
    conn = get_db_connection()
    c = conn.cursor()
    c.execute('''
        CREATE TABLE IF NOT EXISTS messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            content TEXT NOT NULL,
            timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ''')
    conn.commit()
    conn.close()

def save_message(content):
    conn = get_db_connection()
    c = conn.cursor()
    c.execute('INSERT INTO messages (content) VALUES (?)', (content,))
    msg_id = c.lastrowid
    
    c.execute('SELECT id, content, timestamp FROM messages WHERE id = ?', (msg_id,))
    msg = dict(c.fetchone())
    
    conn.commit()
    conn.close()
    return msg

def get_recent_messages(limit=100):
    conn = get_db_connection()
    c = conn.cursor()
    c.execute('''
        SELECT id, content, timestamp 
        FROM messages 
        ORDER BY id DESC 
        LIMIT ?
    ''', (limit,))
    
    messages = [dict(row) for row in c.fetchall()]
    conn.close()
    
    # Return in chronological order (oldest first)
    return messages[::-1]

if __name__ == '__main__':
    init_db()
    print("Database initialized.")

import json
import sqlite3

# Read the JSON data from class.json
with open('class.json', 'r') as f:
    data = json.load(f)

# Connect to the SQLite database (it will be created if it doesn't exist)
conn = sqlite3.connect('class.db')
cursor = conn.cursor()

# Create tables
cursor.execute('''
CREATE TABLE IF NOT EXISTS classes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    class_name TEXT UNIQUE
)
''')

cursor.execute('''
CREATE TABLE IF NOT EXISTS attributes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    class_id INTEGER,
    name TEXT,
    FOREIGN KEY (class_id) REFERENCES classes (id)
)
''')

cursor.execute('''
CREATE TABLE IF NOT EXISTS methods (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    class_id INTEGER,
    name TEXT,
    params_count INTEGER,
    FOREIGN KEY (class_id) REFERENCES classes (id)
)
''')

# Insert data into the tables
for class_info in data:
    class_name = class_info['class_name']
    
    # Insert the class name into the classes table
    cursor.execute('INSERT OR IGNORE INTO classes (class_name) VALUES (?)', (class_name,))
    conn.commit()
    
    # Get the class_id of the inserted or existing class
    cursor.execute('SELECT id FROM classes WHERE class_name = ?', (class_name,))
    class_id = cursor.fetchone()[0]

    # Insert attributes
    for attr_name in class_info.get('attributes', []):
        cursor.execute('INSERT INTO attributes (class_id, name) VALUES (?, ?)', (class_id, attr_name))

    # Insert methods
    for method_info in class_info.get('methods', []):
        method_name = method_info['name']
        params_count = method_info['params_count']
        cursor.execute('INSERT INTO methods (class_id, name, params_count) VALUES (?, ?, ?)', (class_id, method_name, params_count))

# Commit the transactions and close the connection
conn.commit()
conn.close()


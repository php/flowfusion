import json
import sqlite3

def load_apis_json(json_file):
    with open(json_file, 'r') as f:
        data = json.load(f)
    return data

def create_database(db_name):
    conn = sqlite3.connect(db_name)
    cursor = conn.cursor()

    # Create 'functions' table
    cursor.execute('''
        CREATE TABLE IF NOT EXISTS functions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            num_params INTEGER NOT NULL
        )
    ''')

    # Create 'parameters' table
    cursor.execute('''
        CREATE TABLE IF NOT EXISTS parameters (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            function_id INTEGER NOT NULL,
            name TEXT NOT NULL,
            type TEXT,
            is_optional INTEGER NOT NULL,
            default_value TEXT,
            FOREIGN KEY (function_id) REFERENCES functions (id)
        )
    ''')

    conn.commit()
    return conn

def insert_data(conn, data):
    cursor = conn.cursor()

    for function in data:
        function_name = function.get('name')
        num_params = function.get('num_params', 0)
        params = function.get('params', [])

        # Insert function into 'functions' table
        cursor.execute('''
            INSERT INTO functions (name, num_params)
            VALUES (?, ?)
        ''', (function_name, num_params))
        function_id = cursor.lastrowid

        # Insert parameters into 'parameters' table
        for param in params:
            param_name = param.get('name')
            param_type = param.get('type')
            is_optional = 1 if param.get('is_optional') else 0
            default_value = param.get('default_value')

            # Convert default_value to string for storage
            if default_value is not None:
                default_value = str(default_value)

            cursor.execute('''
                INSERT INTO parameters (function_id, name, type, is_optional, default_value)
                VALUES (?, ?, ?, ?, ?)
            ''', (function_id, param_name, param_type, is_optional, default_value))

    conn.commit()

def main():
    json_file = 'apis.json'     # Path to your JSON file
    db_name = 'apis.db'         # Name of the SQLite database file

    # Load data from JSON file
    data = load_apis_json(json_file)

    # Create database and tables
    conn = create_database(db_name)

    # Insert data into database
    insert_data(conn, data)

    # Close the connection
    conn.close()
    print(f"Data has been successfully imported into '{db_name}'.")

if __name__ == '__main__':
    main()

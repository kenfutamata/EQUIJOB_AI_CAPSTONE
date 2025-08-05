# scripts/test_db_connection.py

import psycopg2
import json
import sys

def main():
    print("--- Python Database Connection Test ---")
    
    db_config = {
        "host": "aws-0-ap-southeast-1.pooler.supabase.com",
        "port": "6543",
        "database": "postgres",
        "username": "postgres.zlusioxytbqhxohsfvyr",
        "password": "ABC@123" 
    }
    
    print(f"Attempting to connect to host: {db_config['host']} on port {db_config['port']}...")

    try:
        # Try to establish a connection
        conn = psycopg2.connect(
            dbname=db_config['database'],
            user=db_config['username'],
            password=db_config['password'],
            host=db_config['host'],
            port=db_config['port'],
            # Set a short timeout so we don't have to wait 60 seconds
            connect_timeout=10 
        )
        
        # If we get here, the connection was successful
        print("\n✅ SUCCESS: Connection to the database was successful!")
        
        # Clean up and close the connection
        conn.close()
        
    except Exception as e:
        # If any error occurs, print it
        print("\n❌ FAILED: Could not connect to the database.")
        print(f"Error details: {e}")
        sys.exit(1) # Exit with a failure code

if __name__ == "__main__":
    main()
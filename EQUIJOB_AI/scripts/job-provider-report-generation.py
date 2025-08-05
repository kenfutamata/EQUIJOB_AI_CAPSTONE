import sys
import json 
import pandas as pd 
import psycopg2 
from reportlab.pdfgen import canvas 
from reportlab.lib.units import inch 
from reportlab.lib.pagesizes import letter
import os 

def generateReport(provider_id, db_params):
    conn = None
    try:
        conn = psycopg2.connect(
            dbname=db_params['database'],
            user=db_params['username'],
            password=db_params['password'],
            host=db_params['host'],
            port=db_params['port']
        )
        
        # This SQL query correctly fetches all completed feedback for a job provider
        sql_Query = """
            SELECT 
                fb.rating, 
                fb."feedbackText"
            FROM feedbacks AS fb
            -- FIX #1: Enclose the mixed-case table name in double quotes
            JOIN "jobPosting" AS jp ON fb."jobPostingID" = jp.id
            -- FIX #2: Enclose the mixed-case column name in double quotes
            WHERE jp."jobProviderID" = %s AND fb.rating IS NOT NULL
        """    
        df = pd.read_sql_query(sql_Query, conn, params=(provider_id,))

        if df.empty:
            return {"status": "success", "filepath": None, "message": "No completed feedback has been submitted for your job postings yet."}

        # Ensure the 'rating' column is numeric before calculating the mean
        df['rating'] = pd.to_numeric(df['rating'])
        average_rating = df['rating'].mean()
        feedback_count = len(df)
        
        temp_dir = os.path.join(os.getcwd(), 'storage', 'app', 'public', 'reports')
        os.makedirs(temp_dir, exist_ok=True)
        
        filename = f"report_provider_{provider_id}_{pd.Timestamp.now().strftime('%Y%m%d%H%M%S')}.pdf"
        filepath = os.path.join(temp_dir, filename)
        
        c = canvas.Canvas(filepath, pagesize=letter)
        c.drawString(inch, 10 * inch, f"Feedback Report for Job Provider ID: {provider_id}")
        c.drawString(inch, 9.5 * inch, f"Total Feedbacks Received: {feedback_count}")
        c.drawString(inch, 9.0 * inch, f"Average Rating Across All Jobs: {average_rating:.2f}")
        c.save()
        
        relative_path = os.path.join('reports', filename)
        return {"status": "success", "filepath": relative_path}

    except Exception as e:
        return {"status": "error", "message": str(e)}

    finally:
        if conn is not None:
            conn.close()

def main():
    if len(sys.argv) < 3:
        print(json.dumps({"status": "error", "message": "Insufficient arguments."}))
        return 
    
    # **THE FIX**: Both arguments are now JSON strings.
    provider_id = json.loads(sys.argv[1]) 
    db_config = json.loads(sys.argv[2])
    
    result = generateReport(provider_id, db_config)
    print(json.dumps(result))

if __name__ == "__main__":
    main()
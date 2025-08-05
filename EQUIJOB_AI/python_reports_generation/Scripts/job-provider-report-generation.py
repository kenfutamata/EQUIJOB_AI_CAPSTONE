import sys
import json 
import pandas as pd 
import psycopg
from reportlab.pdfgen import canvas 
from reportlab.lib.units import inch 
import os 

def generateReport(userID, db_params):
    try:
        connString = f"dbname='{db_params['dbname']}' user='{db_params['user']}' host='{db_params['host']}' password='{db_params['password']}'"
        with psycopg.connect(connString) as conn:
            sql_Query = "SELECT \"feedbackType\", rating, \"feedbackText\", \"created_at\" FROM feedbacks WHERE \"applicantID\" = %s"
            df = pd.read_sql_query(sql_Query, conn, params=(userID,))
            if df.empty:
                return {"status": "error", "message": "No feedback found for the given user ID."}
            average_rating = df['rating'].mean()
            feedback_count = len(df)
            temp_dir = os.path.join(os.getcwd(), 'storage', 'app', 'public', 'reports')
            os.makedirs(temp_dir, exist_ok=True)
            filename = f"report{userID}.{pd.Timestamp.now().strftime('%Y%m%d%H%M%S')}.pdf"
            filepath = os.path.join(temp_dir, filename)
            c = canvas.Canvas(filepath, pagesize=letter)
            c.drawString(inch, 10 * inch, f"user Report for ID: {userID}")
            c.drawString(inch, 9.5 * inch, f"Feedbacks Submitted: {feedback_count:.2f}")
            c.drawString(inch, 9 * inch, f"Average Rating: {average_rating:.2f}")
            c.drawString(inch, 8.5 * inch, "Feedbacks:")
            c.save()
            relative_path = os.path.join('reports', filename)
            return {"status": "success", "filepath": relative_path}
    except Exception as e:
        return {"status": "error", "message": str(e)}
def main():
    if len(sys.argv) < 3:
        print(json.dumps({"status": "error", "message": "Insufficient arguments provided."}))
        return 
    user_id = json.loads(sys.argv[1])
    db_config = json.loads(sys.argv[2])
    result = generateReport(user_id, db_config)
    print(json.dumps(result))

if __name__ == "__main__":
    main()
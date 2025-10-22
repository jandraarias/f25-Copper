import argparse
import csv
import os
import mysql.connector
from dotenv import load_dotenv

def _db():
    return mysql.connector.connect(
        host=os.getenv("DB_HOST", "127.0.0.1"),
        port=int(os.getenv("DB_PORT", "3306")),
        user=os.getenv("BD_USERNAME","root"),
        password=os.getenv("DB_PASSWORD", ""),
        database=os.getenv("DB_DATABASE", "f25"),
    )

def import_places(csv_path):
    conn = _db()
    cur = conn.cursor()
    with open(csv_path, newline="", encoding="utf-8") as f:
        r = csv.DictReader(f)
        for row in r:
             cur.execute(
                 """
                INSERT INTO places (name, category, cuisine, address, city, state, country)
                VALUES (%s, %s, %s, %s, %s, %s, %s)
                """,
                (
                    row.get("name"),
                    row.get("category"),
                    row.get("cuisine"),
                    row.get("address"),
                    row.get("city"),
                    row.get("state"),
                    row.get("country"),
                ),
            )
        conn.commit()
        cur.close()
        conn.close()
        print("Places imported successfully.")

def import_reviews(csv_path):
    conn = _db()
    cur = conn.cursor()
    success = 0
    failed = 0

    with open(csv_path, newline="", encoding="utf-8") as f:
        r = csv.DictReader(f)
        for row in r:
            try:
                place_id = row.get("place_id")
                place_id = int(place_id) if place_id and place_id.isdigit() else None
                cur.execute( 
                """
                    INSERT INTO reviews (
                        place_id, source, source_id, rating, text,
                        author_name, author_profile_url, published_at, raw
                )
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, )
                """,
                (
                        place_id,
                        row.get("source"),
                        row.get("source_id"),
                        float(row.get("rating") or 0),
                        row.get("text"),
                        row.get("author_name"),
                        row.get("author_profile_url"),
                        row.get("published_at"),
                        row.get("raw"),
                        
                ),
             )
                success += 1
            except Exception as e:
               print(f"Skipped row due to error: {e}")
            failed += 1

    conn.commit()
    cur.close()
    conn.close()
    print(f"Reviews import completed. Success: {success}, Skipped: {failed}")

if __name__ == "__main__":
    load_dotenv()
    parser = argparse.ArgumentParser(description="Import CSV files into database.")
    parser.add_argument("--places", help="Path to places CSV")
    parser.add_argument("--reviews", help="Path to reviews CSV")
    args = parser.parse_args()

    if args.places:
        import_places(args.places)
    if args.reviews:
        import_reviews(args.reviews)
    if not args.places and not args.reviews:
        print("Nothing to import. Use --places <csv> and/or --reviews <csv>.")

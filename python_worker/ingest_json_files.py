import os
import json
import requests
import time
from dotenv import load_dotenv

load_dotenv()
LARAVEL_API_URL = os.getenv("LARAVEL_API_URL", "http://lexiscope.test/api/ingest")

def ingest_to_laravel(payload: dict):
    headers = {
        "Content-Type": "application/json",
        "Accept": "application/json"
    }
    try:
        response = requests.post(LARAVEL_API_URL, json=payload, headers=headers)
        response.raise_for_status()

        # print specific success info
        t = payload.get('type')
        h = payload.get('hierarchy', {})
        if t == 'chapter':
            print(f"Ingested Chapter {h.get('chapter')}")
        elif t == 'section':
            print(f"Ingested Section {h.get('section_number')}")
        elif t == 'subsection':
            print(f"Ingested Subsection {h.get('section_number')}{h.get('subsection_number')}")

    except requests.exceptions.RequestException as e:
        print(f"Error ingesting data: {e}")
        if hasattr(e, 'response') and e.response is not None:
            print("Response detail:", e.response.text)

if __name__ == "__main__":
    for i in range(1, 5):
        filename = f"chapter_{i}.json"
        if os.path.exists(filename):
            print(f"\n--- Processing {filename} ---")
            try:
                with open(filename, 'r', encoding='utf-8') as f:
                    data = json.load(f)
                    nodes = data.get("nodes", [])
                    print(f"Found {len(nodes)} nodes in {filename}.")
                    for idx, node in enumerate(nodes):
                        ingest_to_laravel(node)
                        time.sleep(0.1)  # tiny delay to avoid overwhelming the db just in case
            except json.JSONDecodeError:
                print(f"Warning: {filename} is not valid JSON. Skipping.")
        else:
            print(f"{filename} not found.")

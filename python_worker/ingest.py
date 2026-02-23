import os
import sys
import json
import requests
from docx import Document
from google import genai
from google.genai import types
from pydantic import BaseModel, Field
from typing import List, Optional
from dotenv import load_dotenv

# Load environment variables
load_dotenv()

# Setup Gemini Client
# Ensure you have GEMINI_API_KEY in your .env file
client = genai.Client(api_key=os.getenv("GEMINI_API_KEY"))

LARAVEL_API_URL = os.getenv("LARAVEL_API_URL", "http://lexiscope.test/api/ingest")

# Define Data Structures matching Laravel Backend requirements
class CaseLaw(BaseModel):
    case_title: str
    citation: str
    relevance_summary: Optional[str] = None
    url: Optional[str] = None

class InternationalComparison(BaseModel):
    country: str
    constitution_provision: Optional[str] = None
    similarity_note: Optional[str] = None
    related_link: Optional[str] = None

class CrossReferences(BaseModel):
    case_laws: List[CaseLaw] = Field(default_factory=list)
    international_comparisons: List[InternationalComparison] = Field(default_factory=list)

class Content(BaseModel):
    legal_text: str
    plain_english: Optional[str] = None
    keywords: List[str] = Field(default_factory=list)

class Hierarchy(BaseModel):
    chapter: Optional[str] = None
    chapter_title: Optional[str] = None
    section_number: Optional[str] = None
    section_title: Optional[str] = None
    subsection_number: Optional[str] = None

class ConstitutionNodeSchema(BaseModel):
    type: str = Field(description="Must be strictly one of: 'chapter', 'section', 'subsection'")
    hierarchy: Hierarchy
    content: Content
    cross_references: CrossReferences

class ConstitutionBatchSchema(BaseModel):
    nodes: List[ConstitutionNodeSchema] = Field(description="A list of all extracted constitutional nodes from the raw text")

def parse_constitutional_text(raw_text: str) -> dict:
    """Uses Gemini Structured Outputs to parse a large chunk of raw legal text into a list of nodes."""

    system_prompt = (
        "You are an expert legal analyst and tech-savvy developer building an interactive constitution app. "
        "Your job is to read the provided raw text from the Constitution, which may contain multiple sections or subsections, "
        "and extract ALL of them into a structured array. "
        "For each node, map it perfectly into the strictly typed JSON schema. "
        "Translate the legal text into understandable 'plain english', and extract intelligent keywords. "
        "Also generate highly relevant and plausible case laws and international comparisons (e.g., US, UK, India) that match the context."
    )

    response = client.models.generate_content(
        model='gemini-2.5-flash',
        contents=raw_text,
        config=types.GenerateContentConfig(
            system_instruction=system_prompt,
            response_mime_type="application/json",
            response_schema=ConstitutionBatchSchema,
            temperature=0.1,
        ),
    )

    return json.loads(response.text)

def ingest_to_laravel(payload: dict):
    """Sends the parsed JSON payload to the Laravel backend API."""
    print(f"\\n--- Sending data to Laravel API: {LARAVEL_API_URL} ---")
    headers = {
        "Content-Type": "application/json",
        "Accept": "application/json"
    }

    try:
        response = requests.post(LARAVEL_API_URL, json=payload, headers=headers)
        response.raise_for_status()
        print("Success! Node fully ingested to MySQL:")
        print(response.json())
    except requests.exceptions.RequestException as e:
        print(f"Error ingesting data: {e}")
        if hasattr(e, 'response') and e.response is not None:
            print("Response detail:", e.response.text)

def read_docx(file_path: str) -> str:
    """Extracts text from a .docx file."""
    doc = Document(file_path)
    return "\n".join([para.text for para in doc.paragraphs if para.text.strip()])

def chunk_text(text: str, max_chars: int = 15000) -> list:
    """Splits text intelligently by trying to break at 'Chapter', 'Part', or double newlines."""
    # Split by double newlines first (logical sections)
    blocks = [block.strip() for block in text.split('\n\n') if block.strip()]
    if not blocks:
        # Fallback to single newlines if no double newlines exist
        blocks = [block.strip() for block in text.split('\n') if block.strip()]

    chunks = []
    current_chunk = []
    current_length = 0

    for block in blocks:
        # If adding this block pushes us over the limit, and we already have content,
        # save the chunk and start a new one (unless this block is a header we want to attach to the next sequence)
        if current_length + len(block) > max_chars and current_length > 0:
            chunks.append("\n\n".join(current_chunk))
            current_chunk = [block]
            current_length = len(block)
        else:
            current_chunk.append(block)
            current_length += len(block)

    if current_chunk:
        chunks.append("\n\n".join(current_chunk))

    return chunks

if __name__ == "__main__":
    if len(sys.argv) > 1:
        # 1. Processing a file passed via terminal
        file_path = sys.argv[1]
        print(f"Reading file: {file_path}")

        try:
            if file_path.endswith('.docx'):
                raw_text = read_docx(file_path)
            else:
                with open(file_path, 'r', encoding='utf-8') as f:
                    raw_text = f.read()

            print(f"File loaded successfully. Total length: {len(raw_text)} characters.")

            # 2. Chunk text to avoid LLM Structured Output token limits
            batches = chunk_text(raw_text, max_chars=20000)
            print(f"Split document into {len(batches)} batches for processing.")

            for i, batch in enumerate(batches):
                print(f"\\n==========================================")
                print(f"Processing Batch {i+1}/{len(batches)}...")
                print(f"==========================================")
                try:
                    structured_data = parse_constitutional_text(batch)
                    nodes = structured_data.get("nodes", [])
                    print(f"Found {len(nodes)} nodes to ingest in this batch.")
                    for idx, node_payload in enumerate(nodes):
                        print(f"\\n--- Ingesting Node {idx+1}/{len(nodes)} (Batch {i+1}) ---")
                        ingest_to_laravel(node_payload)
                except Exception as e:
                    print(f"Error processing batch {i+1}: {e}")

        except Exception as e:
            print(f"Error reading file {file_path}: {e}")
            sys.exit(1)

    else:
        # Example raw text from the constitution to test the pipeline natively
        sample_raw_text = """
        45. (1) Nothing in sections 37, 38, 39, 40 and 41 of this Constitution shall invalidate any law that is reasonably justifiable in a democratic society (a) in the interest of defence, public safety, public order, public morality or public health; or (b) for the purpose of protecting the rights and freedom or other persons (2) An act of the National Assembly shall not be invalidated by reason only that it provides for the taking, during periods of emergency, of measures that derogate from the provisions of section 33 or 35 of this Constitution; but no such measures shall be taken in pursuance of any such act during any period of emergency save to the extent that those measures are reasonably justifiable for the purpose of dealing with the situation that exists during that period of emergency: Provided that nothing in this section shall authorise any derogation from the provisions of section 33 of this Constitution, except in respect of death resulting from acts of war or authorise any derogation from the provisions of section 36(8) of this Constitution. (3) In this section, a " period of emergency" means any period during which there is in force a Proclamation of a state of emergency declared by the President in exercise of the powers conferred on him under section 305 of this Constitution. 46. (1) Any person who alleges that any of the provisions of this Chapter has been, is being or likely to be contravened in any State in relation to him may apply to a High Court in that State for redress. (2) Subject to the provisions of this Constitution, a High Court shall have original jurisdiction to hear and determine any application made to it in pursuance of this section and may make such orders, issue such writs and give such directions as it may consider appropriate for the purpose of enforcement or securing the enforcing within that State of any right to which the person who makes the application may be entitled under this Chapter. (3) The Chief Justice of Nigeria may make rules with respect to the practice and procedure of a High Court for the purposes of this section. (4) The National Assembly - (a) may confer upon a High Court such powers in addition to those conferred by this section as may appear to the National Assembly to be necessary or desirable for the purpose of enabling the court more effectively to exercise the jurisdiction conferred upon it by this section; and (b) shall make provisions- (i) for the rendering of financial assistance to any indigent citizen of Nigeria where his right under this Chapter has been infringed or with a view to enabling him to engage the services of a legal practitioner to prosecute his claim, and (ii) for ensuring that allegations of infringement of such rights are substantial and the requirement or need for financial or legal aid is real
    """

    print("1. Sending raw text to Gemini for structured batch parsing...")
    structured_data = parse_constitutional_text(sample_raw_text)

    print("\\n2. Extracted Data Structure Generated by LLM (Batch):")
    print(json.dumps(structured_data, indent=2))

    # Iterate over the extracted nodes and send them to the local laravel server
    nodes = structured_data.get("nodes", [])
    print(f"\\nFound {len(nodes)} nodes to ingest.")
    for idx, node_payload in enumerate(nodes):
        print(f"\\n--- Ingesting Node {idx+1}/{len(nodes)} ---")
        ingest_to_laravel(node_payload)

import os
import json
from docx import Document
from google import genai
from google.genai import types
from pydantic import BaseModel, Field
from typing import List, Optional
from dotenv import load_dotenv

load_dotenv()
client = genai.Client(api_key=os.getenv("GEMINI_API_KEY"))

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
    system_prompt = (
        "You are an expert legal analyst and tech-savvy developer building an interactive constitution app. "
        "Your job is to read the provided raw text from Chapter III of the Constitution. "
        "Extract every section and subsection into a structured array. "
        "For each node, map it perfectly into the strictly typed JSON schema. "
        "Translate the legal text into understandable 'plain english', and extract intelligent keywords. "
        "Also generate highly relevant and plausible case laws and international comparisons (e.g., US, UK, India) that match the context."
    )

    response = client.models.generate_content(
        model='gemini-2.5-flash',
        contents=raw_text,
        config=types.GenerateContentConfig(
            system_instruction=system_prompt,
            response_schema=ConstitutionBatchSchema,
            response_mime_type="application/json",
            temperature=0.1,
        ),
    )

    return response.parsed.model_dump()

def extract_chapter_3(file_path):
    doc = Document(file_path)
    paragraphs = []
    for para in doc.paragraphs:
        if para.text.strip():
            paragraphs.append(para.text.strip())

    text = "\n\n".join(paragraphs)

    # Locate Chapter III and Chapter IV
    start_index = text.find("Chapter III")
    end_index = text.find("Chapter IV", start_index)

    if start_index == -1:
        print("Could not find Chapter III")
        return

    if end_index == -1:
        chapter_3_text = text[start_index:]
    else:
        chapter_3_text = text[start_index:end_index]

    print(f"Extracted {len(chapter_3_text)} characters for Chapter III.")

    print("Sending Chapter III to Gemini for parsing...")
    structured_data = parse_constitutional_text(chapter_3_text)

    output_file = "chapter_3.json"
    with open(output_file, "w", encoding="utf-8") as f:
        json.dump(structured_data, f, indent=4)

    print(f"Success! Chapter III data saved locally to {output_file}.")

if __name__ == "__main__":
    extract_chapter_3('nigeria_constitution.docx')

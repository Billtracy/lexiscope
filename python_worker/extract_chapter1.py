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
        "Your job is to read the provided raw text from Chapter I of the Constitution. "
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
            response_mime_type="application/json",
            response_schema=ConstitutionBatchSchema,
            temperature=0.1,
        ),
    )

    return json.loads(response.text)

def extract_chapter_1(file_path):
    doc = Document(file_path)
    paragraphs = []
    for para in doc.paragraphs:
        if para.text.strip():
            paragraphs.append(para.text.strip())

    text = "\n\n".join(paragraphs)

    # Very rough extraction: Just get the first ~25000 characters which easily covers Chapter I (Sections 1 to 12)
    chapter_1_text = text[:25000]
    # Try to cut cleanly at before Chapter II
    if "Chapter II" in chapter_1_text:
        chapter_1_text = chapter_1_text.split("Chapter II")[0]

    print(f"Extracted {len(chapter_1_text)} characters for Chapter I.")

    print("Sending to Gemini for parsing...")
    structured_data = parse_constitutional_text(chapter_1_text)

    output_file = "chapter_1.json"
    with open(output_file, "w", encoding="utf-8") as f:
        json.dump(structured_data, f, indent=4)

    print(f"Success! Chapter I data saved locally to {output_file}.")

if __name__ == "__main__":
    extract_chapter_1('nigeria_constitution.docx')

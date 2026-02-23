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
        "Your job is to read the provided raw text from Chapter IV of the Constitution (Fundamental Rights). "
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

    if response.parsed:
        return response.parsed.model_dump()
    else:
        print("response.parsed is None. Returning response.text parsed manually...")
        return json.loads(response.text)

def extract_chapter_4(file_path):
    doc = Document(file_path)
    paragraphs = []
    for para in doc.paragraphs:
        if para.text.strip():
            paragraphs.append(para.text.strip())

    text = "\n\n".join(paragraphs)

    # Locate Chapter IV and Chapter V
    start_index = text.find("Chapter IV")
    end_index = text.find("Chapter V", start_index)

    if start_index == -1:
        print("Could not find Chapter IV")
        return

    if end_index == -1:
        chapter_4_text = text[start_index:]
    else:
        chapter_4_text = text[start_index:end_index]

    print(f"Extracted {len(chapter_4_text)} characters for Chapter IV.")

    if len(chapter_4_text) > 30000:
        print("Chapter IV is quite large. Splitting into five parts for reliable extraction.")
        fifth = len(chapter_4_text) // 5

        split_point_1 = chapter_4_text.find("\n\n35.", fifth - 2000)
        if split_point_1 == -1: split_point_1 = fifth

        split_point_2 = chapter_4_text.find("\n\n38.", (fifth * 2) - 2000)
        if split_point_2 == -1: split_point_2 = fifth * 2

        split_point_3 = chapter_4_text.find("\n\n41.", (fifth * 3) - 2000)
        if split_point_3 == -1: split_point_3 = fifth * 3

        split_point_4 = chapter_4_text.find("\n\n44.", (fifth * 4) - 2000)
        if split_point_4 == -1: split_point_4 = fifth * 4

        part1 = chapter_4_text[:split_point_1]
        part2 = chapter_4_text[split_point_1:split_point_2]
        part3 = chapter_4_text[split_point_2:split_point_3]
        part4 = chapter_4_text[split_point_3:split_point_4]
        part5 = chapter_4_text[split_point_4:]

        print(f"Sending Part 1 to Gemini... ({len(part1)} chars)")
        data1 = parse_constitutional_text(part1)

        print(f"Sending Part 2 to Gemini... ({len(part2)} chars)")
        data2 = parse_constitutional_text(part2)

        print(f"Sending Part 3 to Gemini... ({len(part3)} chars)")
        data3 = parse_constitutional_text(part3)

        print(f"Sending Part 4 to Gemini... ({len(part4)} chars)")
        data4 = parse_constitutional_text(part4)

        print(f"Sending Part 5 to Gemini... ({len(part5)} chars)")
        data5 = parse_constitutional_text(part5)

        combined_nodes = data1["nodes"] + data2["nodes"] + data3["nodes"] + data4["nodes"] + data5["nodes"]
        structured_data = {"nodes": combined_nodes}
    else:
        print("Sending Chapter IV to Gemini for parsing...")
        structured_data = parse_constitutional_text(chapter_4_text)

    output_file = "chapter_4.json"
    with open(output_file, "w", encoding="utf-8") as f:
        json.dump(structured_data, f, indent=4)

    print(f"Success! Chapter IV data saved locally to {output_file}.")

if __name__ == "__main__":
    extract_chapter_4('nigeria_constitution.docx')

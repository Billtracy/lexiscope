import fs from 'fs';
import path from 'path';
import { ConstitutionSchema, Constitution, PartSchema, ChapterSchema, SectionSchema } from './types';

const rawPath = path.join(__dirname, 'raw', 'constitution_ng.txt');
const outPath = path.join(__dirname, '..', 'data', 'constitution.ng.json');

const text = fs.readFileSync(rawPath, 'utf8');

const partRegex = /^PART\s+(\w+)/gm;
const chapterRegex = /^CHAPTER\s+([IVXLC]+)\s+-\s+(.+)$/gm;
const sectionRegex = /^Section\s+(\d+)\s+(.+)$/gm;

const lines = text.split(/\n/);
let currentPart: any = null;
let currentChapter: any = null;
const parts: any[] = [];

for (let i = 0; i < lines.length; i++) {
  const line = lines[i].trim();
  if (!line) continue;
  const partMatch = line.match(/^PART\s+(.*)$/);
  if (partMatch) {
    if (currentPart) parts.push(currentPart);
    currentPart = { id: `part${parts.length + 1}`, title: line, chapters: [] };
    currentChapter = null;
    continue;
  }
  const chapterMatch = line.match(/^CHAPTER\s+(.*)$/);
  if (chapterMatch) {
    if (!currentPart) throw new Error('Chapter without part');
    currentChapter = {
      id: `chap${currentPart.chapters.length + 1}`,
      title: line,
      sections: [],
    };
    currentPart.chapters.push(currentChapter);
    continue;
  }
  const sectionMatch = line.match(/^Section\s+(\d+)\s+(.+)$/);
  if (sectionMatch) {
    if (!currentChapter) throw new Error('Section without chapter');
    const number = sectionMatch[1];
    const marginal = sectionMatch[2];
    const textLine = lines[++i];
    const section = {
      id: `s${number}`,
      number,
      marginal_title: marginal,
      text: textLine || '',
      keywords: [],
      notes: '',
    };
    currentChapter.sections.push(section);
  }
}
if (currentPart) parts.push(currentPart);

const constitution: Constitution = {
  jurisdiction: 'NG',
  title: 'Constitution of the Federal Republic of Nigeria',
  version: '1999 (as amended)',
  parts,
};

ConstitutionSchema.parse(constitution);
fs.writeFileSync(outPath, JSON.stringify(constitution, null, 2));
console.log('Wrote', outPath);

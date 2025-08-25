import fs from 'fs';
import path from 'path';
import { ConstitutionSchema, Constitution } from './types';

const rawPath = path.join(__dirname, 'raw', 'constitution_ng.csv');
const outPath = path.join(__dirname, '..', 'data', 'constitution.ng.json');

const csv = fs.readFileSync(rawPath, 'utf8').trim();
const [header, ...rows] = csv.split(/\n/);
const cols = header.split(',');

const partsMap: any = {};

rows.forEach((r) => {
  const values = r.split(',');
  const data: any = {};
  cols.forEach((c, i) => (data[c] = values[i]));
  if (!partsMap[data.part_id]) {
    partsMap[data.part_id] = {
      id: data.part_id,
      title: data.part_title,
      chapters: {},
    };
  }
  const part = partsMap[data.part_id];
  if (!part.chapters[data.chapter_id]) {
    part.chapters[data.chapter_id] = {
      id: data.chapter_id,
      title: data.chapter_title,
      sections: [],
    };
  }
  const chapter = part.chapters[data.chapter_id];
  chapter.sections.push({
    id: data.section_id,
    number: data.number,
    marginal_title: data.marginal_title,
    text: data.text,
    keywords: data.keywords ? data.keywords.split('|') : [],
    notes: data.notes || '',
  });
});

const parts = Object.values(partsMap).map((p: any) => ({
  id: p.id,
  title: p.title,
  chapters: Object.values(p.chapters),
}));

const constitution: Constitution = {
  jurisdiction: 'NG',
  title: 'Constitution of the Federal Republic of Nigeria',
  version: '1999 (as amended)',
  parts,
};

ConstitutionSchema.parse(constitution);
fs.writeFileSync(outPath, JSON.stringify(constitution, null, 2));
console.log('Wrote', outPath);

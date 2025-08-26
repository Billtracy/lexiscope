import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import { ConstitutionSchema } from './types.js';

const __dirname = path.dirname(fileURLToPath(import.meta.url));

const dataPath = path.join(__dirname, '..', 'data', 'constitution.ng.json');
const outPath = path.join(__dirname, '..', 'data', 'crossrefs.ng.json');

const constitution = ConstitutionSchema.parse(
  JSON.parse(fs.readFileSync(dataPath, 'utf8'))
);

const refs: any = {};
const sectionMap: Record<string, any> = {};
constitution.parts.forEach((p) =>
  p.chapters.forEach((c) =>
    c.sections.forEach((s) => {
      sectionMap[s.id] = s;
      refs[s.id] = { related_sections: [], topics: [], case_law: [] };
    })
  )
);

for (const s of Object.values(sectionMap)) {
  const matches = s.text.match(/S\.?(\d+)/g) || [];
  matches.forEach((m: string) => {
    const id = 's' + m.replace(/\D/g, '');
    if (sectionMap[id] && !refs[s.id].related_sections.includes(id)) {
      refs[s.id].related_sections.push(id);
    }
  });
}

fs.writeFileSync(outPath, JSON.stringify(refs, null, 2));
console.log('Wrote', outPath);

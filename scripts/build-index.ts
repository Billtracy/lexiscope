import fs from 'fs';
import path from 'path';
import elasticlunr from 'elasticlunr';
import { ConstitutionSchema } from './types';

const dataPath = path.join(__dirname, '..', 'data', 'constitution.ng.json');
const outPath = path.join(__dirname, '..', 'public', 'index.ng.json');

const constitution = ConstitutionSchema.parse(
  JSON.parse(fs.readFileSync(dataPath, 'utf8'))
);

const sections: any[] = [];
constitution.parts.forEach((p) =>
  p.chapters.forEach((c) => c.sections.forEach((s) => sections.push(s)))
);

const idx = elasticlunr(function () {
  this.setRef('id');
  this.addField('number');
  this.addField('marginal_title');
  this.addField('text');
  this.addField('keywords');
});
sections.forEach((s) =>
  idx.addDoc({ ...s, keywords: (s.keywords || []).join(' ') })
);

fs.writeFileSync(outPath, JSON.stringify(idx));
console.log('Wrote', outPath);

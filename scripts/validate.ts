import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import { ConstitutionSchema } from './types.js';

const __dirname = path.dirname(fileURLToPath(import.meta.url));

const dataPath = path.join(__dirname, '..', 'data', 'constitution.ng.json');

try {
  const json = JSON.parse(fs.readFileSync(dataPath, 'utf8'));
  ConstitutionSchema.parse(json);
  console.log('Validation passed');
} catch (e) {
  console.error('Validation failed', e);
  process.exit(1);
}

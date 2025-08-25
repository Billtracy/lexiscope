import { describe, it, expect } from 'vitest';
import fs from 'fs';
import elasticlunr from 'elasticlunr';

const indexData = JSON.parse(
  fs.readFileSync('public/index.ng.json', 'utf8')
);
const index = elasticlunr.Index.load(indexData);

describe('search index', () => {
  it('returns s33 for life', () => {
    const res = index.search('life', { expand: true });
    expect(res[0]?.ref).toBe('s33');
  });
});

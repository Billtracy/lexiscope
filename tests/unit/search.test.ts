import { describe, it, expect } from 'vitest';
import fs from 'fs';
import elasticlunr from 'elasticlunr';

const indexData = JSON.parse(
  fs.readFileSync('public/index.ng.json', 'utf8')
);

// Rebuild the elasticlunr index from stored documents instead of loading
// from JSON, which lacks the required internal index structure.
const index = elasticlunr(function () {
  this.setRef('id');
  this.addField('number');
  this.addField('marginal_title');
  this.addField('text');
  this.addField('keywords');
});

Object.values(indexData.documentStore.docs as Record<string, any>).forEach(
  (doc) => index.addDoc(doc)
);

describe('search index', () => {
  it('returns s33 for life', () => {
    const res = index.search('life');
    expect(res[0]?.ref).toBe('s33');
  });
});

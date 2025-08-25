'use client';

import { useEffect, useState } from 'react';
import elasticlunr from 'elasticlunr';
import Link from 'next/link';

interface Doc {
  id: string;
  number: string;
  marginal_title: string;
  text: string;
}

export function SearchBox() {
  const [index, setIndex] = useState<elasticlunr.Index | null>(null);
  const [query, setQuery] = useState('');
  const [results, setResults] = useState<Doc[]>([]);

  useEffect(() => {
    fetch('/index.ng.json')
      .then((res) => res.json())
      .then((data) => {
        const idx = elasticlunr.Index.load(data);
        setIndex(idx);
      });
  }, []);

  useEffect(() => {
    if (!index || !query) {
      setResults([]);
      return;
    }
    const res = index
      .search(query, { expand: true })
      .slice(0, 20)
      .map((r) => index.documentStore.getDoc(r.ref) as Doc);
    setResults(res);
  }, [query, index]);

  return (
    <div className="space-y-2">
      <input
        aria-label="Search"
        value={query}
        onChange={(e) => setQuery(e.target.value)}
        className="w-full border p-2 rounded"
        placeholder="Search sections..."
      />
      {results.length > 0 && (
        <ul className="border rounded divide-y">
          {results.map((r) => (
            <li key={r.id} className="p-2 hover:bg-gray-50">
              <Link href={`/section/${r.id}`}>
                <div className="font-semibold">S.{r.number}</div>
                <div className="text-sm text-gray-600 line-clamp-2">
                  {r.marginal_title}
                </div>
              </Link>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}

import Link from 'next/link';
import { Badge } from './Badge';

interface CrossRefData {
  related_sections: string[];
  topics: string[];
  case_law: Array<{ name: string; citation?: string; url?: string }>;
}

export function CrossRefs({ refs }: { refs: CrossRefData }) {
  if (!refs.related_sections.length && !refs.case_law.length) {
    return <p className="text-sm text-gray-500">No related sections.</p>;
  }
  return (
    <section className="space-y-4">
      {refs.related_sections.length > 0 && (
        <div>
          <h2 className="font-semibold mb-2">Related Sections</h2>
          <ul className="list-disc list-inside space-y-1">
            {refs.related_sections.map((id) => (
              <li key={id}>
                <Link href={`/section/${id}`} className="text-blue-600 hover:underline">
                  {id.toUpperCase()}
                </Link>
              </li>
            ))}
          </ul>
        </div>
      )}
      {refs.case_law.length > 0 && (
        <div>
          <h2 className="font-semibold mb-2">Case Law</h2>
          <ul className="list-disc list-inside space-y-1">
            {refs.case_law.map((c) => (
              <li key={c.name}>
                {c.url ? (
                  <a href={c.url} className="text-blue-600 hover:underline">
                    {c.name}
                  </a>
                ) : (
                  c.name
                )}
                {c.citation && <span className="ml-1">({c.citation})</span>}
              </li>
            ))}
          </ul>
        </div>
      )}
      {refs.topics.length > 0 && (
        <div className="flex gap-2 flex-wrap">
          {refs.topics.map((t) => (
            <Badge key={t}>{t}</Badge>
          ))}
        </div>
      )}
    </section>
  );
}

import Link from 'next/link';
import { Badge } from './Badge';

interface CrossRefData {
  related_sections: string[];
  topics: string[];
  case_law: Array<{ name: string; citation?: string; url?: string }>;
  alt_constitutions: Array<{ jurisdiction: string; section: string }>;
}

export function CrossRefs({ refs }: { refs: CrossRefData }) {
  return (
    <section className="space-y-4">
      <div>
        <h2 className="font-semibold mb-2">Related Sections</h2>
        {refs.related_sections.length > 0 ? (
          <ul className="list-disc list-inside space-y-1">
            {refs.related_sections.map((id) => (
              <li key={id}>
                <Link href={`/section/${id}`} className="text-blue-600 hover:underline">
                  {id.toUpperCase()}
                </Link>
              </li>
            ))}
          </ul>
        ) : (
          <p className="text-sm text-gray-500">None</p>
        )}
      </div>
      <div>
        <h2 className="font-semibold mb-2">Case Law</h2>
        {refs.case_law.length > 0 ? (
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
        ) : (
          <p className="text-sm text-gray-500">No case law references yet.</p>
        )}
      </div>
      <div>
        <h2 className="font-semibold mb-2">Alternative Constitutions</h2>
        {refs.alt_constitutions.length > 0 ? (
          <ul className="list-disc list-inside space-y-1">
            {refs.alt_constitutions.map((c) => (
              <li key={`${c.jurisdiction}-${c.section}`}>
                {c.jurisdiction}: {c.section}
              </li>
            ))}
          </ul>
        ) : (
          <p className="text-sm text-gray-500">No alternate constitution references yet.</p>
        )}
      </div>
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

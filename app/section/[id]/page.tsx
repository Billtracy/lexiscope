import { notFound } from 'next/navigation';
import { loadConstitution, loadCrossRefs } from '../../../lib/loadData';
import { Breadcrumbs } from '../../../components/Breadcrumbs';
import { CrossRefs } from '../../../components/CrossRefs';
import { Badge } from '../../../components/Badge';

interface Params {
  id: string;
}

export default async function SectionPage({ params }: { params: Params }) {
  const { id } = params;
  const constitution = await loadConstitution();
  const crossRefs = await loadCrossRefs();

  const part = constitution.parts.find((p) =>
    p.chapters.some((c) => c.sections.some((s) => s.id === id))
  );
  if (!part) return notFound();
  const chapter = part.chapters.find((c) => c.sections.some((s) => s.id === id));
  const section = chapter?.sections.find((s) => s.id === id);
  if (!section) return notFound();

  const refs = crossRefs[id] || { related_sections: [], topics: [], case_law: [] };

  return (
    <div className="space-y-6">
      <Breadcrumbs
        items={[
          { href: '/', label: 'Home' },
          { href: '#', label: part.title },
          { href: '#', label: chapter.title },
          { href: `/section/${id}`, label: `S.${section.number}` },
        ]}
      />
      <header className="sticky top-0 bg-gray-50 py-4 border-b">
        <h1 className="text-2xl font-bold">
          S.{section.number} — {section.marginal_title}
        </h1>
        <div className="mt-2 flex gap-2">
          {(section.keywords || []).map((k) => (
            <Badge key={k}>{k}</Badge>
          ))}
        </div>
      </header>
      <article className="prose max-w-none">
        {section.text}
      </article>
      <CrossRefs refs={refs} />
    </div>
  );
}

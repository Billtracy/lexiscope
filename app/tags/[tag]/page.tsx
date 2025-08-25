import Link from 'next/link';
import { loadTags, loadConstitution } from '../../../lib/loadData';
import { SectionCard } from '../../../components/SectionCard';
import { notFound } from 'next/navigation';

interface Params {
  tag: string;
}

export default async function TagPage({ params }: { params: Params }) {
  const { tag } = params;
  const tags = await loadTags();
  const constitution = await loadConstitution();
  const sectionIds = tags[tag];
  if (!sectionIds) return notFound();

  const sections = sectionIds
    .map((id) => {
      for (const part of constitution.parts) {
        for (const chapter of part.chapters) {
          const section = chapter.sections.find((s) => s.id === id);
          if (section) {
            return { part, chapter, section };
          }
        }
      }
      return undefined;
    })
    .filter(Boolean) as Array<{
    part: any;
    chapter: any;
    section: any;
  }>;

  return (
    <div className="space-y-6">
      <h1 className="text-2xl font-bold">Tag: {tag}</h1>
      <div className="space-y-4">
        {sections.map(({ section }) => (
          <SectionCard key={section.id} section={section} />
        ))}
      </div>
      <Link href="/">Back home</Link>
    </div>
  );
}

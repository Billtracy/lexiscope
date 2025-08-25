import Link from 'next/link';
import { SearchBox } from '../components/SearchBox';
import { loadTags } from '../lib/loadData';
import { Badge } from '../components/Badge';

export default async function HomePage() {
  const tags = await loadTags();
  const topics = Object.keys(tags);

  return (
    <div className="space-y-8">
      <header className="text-center space-y-2">
        <h1 className="text-4xl font-bold">Lexiscope</h1>
        <p className="text-gray-600">
          Interactive Nigerian Constitution Navigator
        </p>
      </header>
      <SearchBox />
      <section className="space-y-4">
        <h2 className="font-semibold">Browse Topics</h2>
        <div className="flex flex-wrap gap-2">
          {topics.map((tag) => (
            <Link key={tag} href={`/tags/${tag}`}>
              <Badge>{tag}</Badge>
            </Link>
          ))}
        </div>
      </section>
    </div>
  );
}

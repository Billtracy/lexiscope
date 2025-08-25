import Link from 'next/link';

interface Crumb {
  href: string;
  label: string;
}

export function Breadcrumbs({ items }: { items: Crumb[] }) {
  return (
    <nav className="text-sm text-gray-600" aria-label="Breadcrumb">
      <ol className="flex flex-wrap gap-1">
        {items.map((item, idx) => (
          <li key={idx} className="flex items-center gap-1">
            {idx > 0 && <span>/</span>}
            <Link href={item.href} className="hover:underline">
              {item.label}
            </Link>
          </li>
        ))}
      </ol>
    </nav>
  );
}

import Link from 'next/link';

interface Props {
  section: {
    id: string;
    number: string;
    marginal_title: string;
    text: string;
  };
}

export function SectionCard({ section }: Props) {
  return (
    <Link
      href={`/section/${section.id}`}
      className="block border rounded p-4 hover:bg-gray-50"
    >
      <h3 className="font-semibold mb-1">S.{section.number}</h3>
      <p className="text-sm text-gray-600 line-clamp-2">
        {section.marginal_title}
      </p>
    </Link>
  );
}

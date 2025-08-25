import clsx from 'clsx';

export function Badge({ children }: { children: React.ReactNode }) {
  return (
    <span
      className={clsx(
        'inline-block rounded-full bg-blue-100 text-blue-800 px-3 py-1 text-xs font-medium'
      )}
    >
      {children}
    </span>
  );
}

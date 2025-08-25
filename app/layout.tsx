import './globals.css';
import type { Metadata } from 'next';

export const metadata: Metadata = {
  title: 'Lexiscope',
  description: 'Interactive Nigerian Constitution Navigator',
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="en">
      <body className="min-h-screen bg-gray-50 text-gray-900">
        <main className="container mx-auto p-4">{children}</main>
      </body>
    </html>
  );
}

export default function ComparePage() {
  return (
    <div className="space-y-6">
      <h1 className="text-2xl font-bold">Compare Constitutions</h1>
      <p className="text-gray-600">
        Future feature: compare Nigerian provisions with India or US.
      </p>
      <form className="space-y-2">
        <label className="block">
          Topic
          <select className="mt-1 border p-2 rounded w-full">
            <option>Right to life</option>
          </select>
        </label>
      </form>
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div className="border p-4 min-h-[200px]">Nigeria (coming soon)</div>
        <div className="border p-4 min-h-[200px]">Other jurisdiction (coming soon)</div>
      </div>
    </div>
  );
}

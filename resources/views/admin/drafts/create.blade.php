<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Constitution Node') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were {{ $errors->count() }} errors with
                                your submission</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.drafts.store') }}" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Node Type</label>
                                <select id="type" name="type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="chapter" {{ old('type') == 'chapter' ? 'selected' : '' }}>Chapter
                                    </option>
                                    <option value="section" {{ old('type') == 'section' ? 'selected' : '' }}>Section
                                    </option>
                                    <option value="subsection" {{ old('type') == 'subsection' ? 'selected' : '' }}>
                                        Subsection</option>
                                </select>
                            </div>

                            <!-- Parent ID -->
                            <div>
                                <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent ID
                                    (Optional, for sections/subsections)</label>
                                <input type="number" name="parent_id" id="parent_id"
                                    value="{{ old('parent_id', request('parent_id')) }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="ID of parent chapter or section">
                            </div>

                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Initial
                                    Status</label>
                                <select id="status" name="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft
                                    </option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                        Published (Live immediately)</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-6">

                        <h3 class="text-lg font-medium">Hierarchy Data</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="chapter" class="block text-sm font-medium text-gray-700">Chapter (e.g.,
                                    "I")</label>
                                <input type="text" name="chapter" id="chapter" value="{{ old('chapter') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label for="chapter_title" class="block text-sm font-medium text-gray-700">Chapter
                                    Title</label>
                                <input type="text" name="chapter_title" id="chapter_title"
                                    value="{{ old('chapter_title') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="section_number" class="block text-sm font-medium text-gray-700">Section
                                    Number (e.g., "1")</label>
                                <input type="text" name="section_number" id="section_number"
                                    value="{{ old('section_number') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label for="section_title" class="block text-sm font-medium text-gray-700">Section
                                    Title</label>
                                <input type="text" name="section_title" id="section_title"
                                    value="{{ old('section_title') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="subsection_number"
                                    class="block text-sm font-medium text-gray-700">Subsection Number (e.g.,
                                    "(1)")</label>
                                <input type="text" name="subsection_number" id="subsection_number"
                                    value="{{ old('subsection_number') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <hr class="my-6">

                        <h3 class="text-lg font-medium mb-4">Content Data</h3>
                        <!-- Legal Text -->
                        <div>
                            <label for="legal_text" class="block text-sm font-medium text-gray-700 mb-2">Legal Text
                                (Required)</label>
                            <textarea id="legal_text" name="legal_text" rows="8"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('legal_text') }}</textarea>
                        </div>

                        <!-- Plain English -->
                        <div>
                            <label for="plain_english" class="block text-sm font-medium text-gray-700 mb-2">Plain
                                English Explanation</label>
                            <textarea id="plain_english" name="plain_english" rows="6"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('plain_english') }}</textarea>
                        </div>

                        <!-- Keywords -->
                        <div>
                            <label for="keywords" class="block text-sm font-medium text-gray-700 mb-2">Keywords (comma
                                separated)</label>
                            <input type="text" id="keywords" name="keywords" value="{{ old('keywords') }}"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="E.g. Citizenship, Rights, Freedom">
                        </div>

                        <div class="flex justify-end pt-5">
                            <a href="{{ route('admin.drafts.index') }}"
                                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            <button type="submit"
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Create Node
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

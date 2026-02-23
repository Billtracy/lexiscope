<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="section-heading">Admin</p>
                <h1 class="text-xl font-bold text-slate-800 dark:text-white mt-0.5">Contributors</h1>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn-primary">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span class="hidden sm:inline">Add Contributor</span>
                <span class="sm:hidden">Add</span>
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        @if (session('status'))
            <div class="alert-success mb-5">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l3-3z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error mb-5">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ── Mobile: Cards  /  Desktop: Table ─────── --}}
        {{-- Desktop Table (hidden on mobile) --}}
        <div class="hidden lg:block content-card overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 dark:divide-slate-700">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                            Contributor</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                            Role</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">
                            Joined</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @foreach ($users as $user)
                        @php
                            $parts = explode(' ', $user->name);
                            $initials = strtoupper(
                                substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''),
                            );
                            $colors = [
                                'bg-violet-500',
                                'bg-brand-500',
                                'bg-emerald-500',
                                'bg-amber-500',
                                'bg-rose-500',
                            ];
                            $colorClass = $colors[crc32($user->email) % count($colors)];
                        @endphp
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors duration-150">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="avatar text-white {{ $colorClass }} text-xs shrink-0">{{ $initials }}</span>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-800 dark:text-white truncate">
                                            {{ $user->name }}</p>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 truncate">
                                            {{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="{{ $user->role === 'admin' ? 'badge-admin' : 'badge-student' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-sm text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn-icon" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @if ($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Delete {{ addslashes($user->name) }}? This cannot be undone.')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="btn-icon hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30"
                                                title="Delete">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards (visible below lg) --}}
        <div class="lg:hidden space-y-3">
            @foreach ($users as $user)
                @php
                    $parts = explode(' ', $user->name);
                    $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                    $colors = ['bg-violet-500', 'bg-brand-500', 'bg-emerald-500', 'bg-amber-500', 'bg-rose-500'];
                    $colorClass = $colors[crc32($user->email) % count($colors)];
                @endphp
                <div class="content-card">
                    <div class="content-card-body flex items-start gap-3">
                        <span
                            class="avatar text-white {{ $colorClass }} text-sm shrink-0 w-11 h-11">{{ $initials }}</span>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="font-semibold text-slate-800 dark:text-white text-sm truncate">
                                        {{ $user->name }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500 truncate">{{ $user->email }}
                                    </p>
                                </div>
                                <span class="{{ $user->role === 'admin' ? 'badge-admin' : 'badge-student' }} shrink-0">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between mt-3">
                                <p class="text-xs text-slate-400 dark:text-slate-500">
                                    Joined {{ $user->created_at->format('M d, Y') }}
                                </p>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="btn-ghost btn-sm flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    @if ($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Delete {{ addslashes($user->name) }}?')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="btn-ghost btn-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</x-app-layout>

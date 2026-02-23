<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Lexiscope') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 flex flex-col">

    {{-- Ambient gradient orbs --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div
            class="absolute -top-40 -right-32 w-[600px] h-[600px] rounded-full
                    bg-brand-400/20 dark:bg-brand-600/10 blur-3xl">
        </div>
        <div
            class="absolute -bottom-40 -left-32 w-[500px] h-[500px] rounded-full
                    bg-violet-400/15 dark:bg-violet-600/10 blur-3xl">
        </div>
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[400px]
                    bg-indigo-300/10 dark:bg-indigo-800/10 blur-3xl rotate-12">
        </div>
    </div>

    {{-- Top bar --}}
    <header class="relative z-10 flex items-center justify-between px-6 py-5">
        <a href="/" class="flex items-center gap-2.5 group">
            <img src="{{ asset('logo.png') }}"
                class="w-8 h-8 rounded-xl shadow-sm object-cover group-hover:scale-105 transition-transform duration-150"
                alt="Lexiscope Logo" />
            <span class="font-bold text-lg text-slate-800 dark:text-white tracking-tight">Lexiscope</span>
        </a>

        {{-- Dark mode toggle --}}
        <button onclick="toggleDarkMode()"
            class="w-9 h-9 rounded-xl flex items-center justify-center
                       bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700
                       text-slate-500 dark:text-slate-400
                       hover:bg-slate-100 dark:hover:bg-slate-700
                       transition-all duration-200 shadow-sm"
            aria-label="Toggle dark mode">
            {{-- Sun (shown in dark mode) --}}
            <svg class="w-4 h-4 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            {{-- Moon (shown in light mode) --}}
            <svg class="w-4 h-4 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
        </button>
    </header>

    {{-- Main content --}}
    <main class="relative z-10 flex-1 flex items-center justify-center px-4 py-8">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <footer class="relative z-10 text-center py-5 text-xs text-slate-400 dark:text-slate-600">
        &copy; {{ date('Y') }} Lexiscope — Interactive Constitution Platform
    </footer>

</body>

</html>

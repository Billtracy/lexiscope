<nav x-data="{ open: false }"
    class="bg-white dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700 sticky top-0 z-40">
    <!-- ── Desktop Top Bar ───────────────────────────── -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- Logo -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                    <img src="{{ asset('logo.png') }}"
                        class="w-8 h-8 rounded-xl shadow-sm object-cover group-hover:scale-105 transition-transform duration-150"
                        alt="Lexiscope Logo" />
                    <span class="font-bold text-lg text-slate-800 dark:text-white tracking-tight">Lexiscope</span>
                </a>
            </div>

            <!-- Desktop Nav Links (hidden on mobile) -->
            <div class="hidden sm:flex sm:items-center sm:gap-1">
                <a href="{{ route('dashboard') }}"
                    class="{{ request()->routeIs('dashboard') ? 'nav-top-link-active' : 'nav-top-link-inactive' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.drafts.index') }}"
                    class="{{ request()->routeIs('admin.drafts.*') ? 'nav-top-link-active' : 'nav-top-link-inactive' }}">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Drafts Review
                </a>
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users.index') }}"
                        class="{{ request()->routeIs('admin.users.*') ? 'nav-top-link-active' : 'nav-top-link-inactive' }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Contributors
                    </a>
                @endif
            </div>

            <!-- Right: Dark Mode Toggle + User Dropdown (desktop) -->
            <div class="hidden sm:flex sm:items-center sm:gap-2">

                {{-- Dark mode toggle --}}
                <button onclick="toggleDarkMode()"
                    class="w-9 h-9 rounded-xl flex items-center justify-center
                               text-slate-500 dark:text-slate-400
                               hover:bg-slate-100 dark:hover:bg-slate-700/60
                               transition-colors duration-150"
                    title="Toggle dark mode">
                    <svg class="w-4 h-4 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg class="w-4 h-4 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <x-dropdown align="right" width="52">
                    <x-slot name="trigger">
                        <button
                            class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl
                                       text-sm font-medium text-slate-600 dark:text-slate-300
                                       hover:bg-slate-100 dark:hover:bg-slate-700/60
                                       transition-colors duration-150 focus:outline-none">
                            {{-- Avatar initials --}}
                            @php
                                $parts = explode(' ', Auth::user()->name);
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
                                $colorClass = $colors[crc32(Auth::user()->email) % count($colors)];
                            @endphp
                            <span
                                class="avatar w-7 h-7 text-white {{ $colorClass }} text-xs">{{ $initials }}</span>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" fill="currentColor" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                            <p class="text-xs text-slate-500 dark:text-slate-400">Signed in as</p>
                            <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 truncate">
                                {{ Auth::user()->email }}</p>
                        </div>
                        <x-dropdown-link :href="route('profile.edit')">
                            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (mobile only, triggers slide-down) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open" aria-label="Toggle menu" class="btn-icon w-10 h-10 text-slate-500">
                    <svg class="w-5 h-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'block': !open }" class="block" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'block': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- ── Mobile Slide-Down Menu ────────────────────── -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="sm:hidden border-t border-slate-100 dark:border-slate-700 bg-white dark:bg-slate-800">

        <div class="px-4 pt-4 pb-2 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.drafts.index')" :active="request()->routeIs('admin.drafts.*')">
                {{ __('Drafts Review') }}
            </x-responsive-nav-link>
            @if (auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    {{ __('Contributors') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-3 pb-4 border-t border-slate-100 dark:border-slate-700">
            <div class="flex items-center gap-3 px-4 mb-3">
                <span
                    class="avatar text-white {{ $colorClass ?? 'bg-brand-500' }} text-xs">{{ $initials ?? '?' }}</span>
                <div>
                    <div class="text-sm font-semibold text-slate-800 dark:text-white">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <div class="px-4 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- ── Fixed Mobile Bottom Navigation ───────────────── -->
<nav
    class="sm:hidden fixed bottom-0 inset-x-0 z-40
            bg-white dark:bg-slate-800
            border-t border-slate-200 dark:border-slate-700
            safe-bottom">
    <div class="flex items-stretch h-16">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
            class="bottom-nav-item {{ request()->routeIs('dashboard') ? 'bottom-nav-item-active' : '' }}">
            <svg class="w-5 h-5" fill="{{ request()->routeIs('dashboard') ? 'currentColor' : 'none' }}"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-[10px] font-medium leading-tight">Home</span>
        </a>

        {{-- Drafts --}}
        <a href="{{ route('admin.drafts.index') }}"
            class="bottom-nav-item {{ request()->routeIs('admin.drafts.*') ? 'bottom-nav-item-active' : '' }}">
            <svg class="w-5 h-5" fill="{{ request()->routeIs('admin.drafts.*') ? 'currentColor' : 'none' }}"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span class="text-[10px] font-medium leading-tight">Drafts</span>
        </a>

        @if (auth()->user()->role === 'admin')
            {{-- Contributors (admin only) --}}
            <a href="{{ route('admin.users.index') }}"
                class="bottom-nav-item {{ request()->routeIs('admin.users.*') ? 'bottom-nav-item-active' : '' }}">
                <svg class="w-5 h-5" fill="{{ request()->routeIs('admin.users.*') ? 'currentColor' : 'none' }}"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-[10px] font-medium leading-tight">Team</span>
            </a>
        @endif

        {{-- Profile --}}
        <a href="{{ route('profile.edit') }}"
            class="bottom-nav-item {{ request()->routeIs('profile.*') ? 'bottom-nav-item-active' : '' }}">
            <svg class="w-5 h-5" fill="{{ request()->routeIs('profile.*') ? 'currentColor' : 'none' }}"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-[10px] font-medium leading-tight">Profile</span>
        </a>

    </div>
</nav>

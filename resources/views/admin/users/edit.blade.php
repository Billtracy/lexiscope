<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.users.index') }}"
                class="text-brand-600 dark:text-brand-400 hover:underline font-medium text-sm">Contributors</a>
            <svg class="w-4 h-4 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            <h1 class="font-bold text-slate-800 dark:text-white text-base">Edit: {{ $user->name }}</h1>
        </div>
    </x-slot>

    <div class="max-w-lg mx-auto px-4 sm:px-0 py-8">
        <div class="content-card" x-data="{ showPassword: false, showConfirm: false }">

            {{-- User identity header --}}
            <div class="content-card-header">
                @php
                    $parts = explode(' ', $user->name);
                    $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                    $colors = ['bg-violet-500', 'bg-indigo-500', 'bg-emerald-500', 'bg-amber-500', 'bg-rose-500'];
                    $colorClass = $colors[crc32($user->email) % count($colors)];
                @endphp
                <div class="flex items-center gap-3">
                    <span class="avatar text-white {{ $colorClass }} text-sm w-10 h-10">{{ $initials }}</span>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-800 dark:text-white text-sm truncate">{{ $user->name }}</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 truncate">{{ $user->email }}</p>
                    </div>
                </div>
                <span class="{{ $user->role === 'admin' ? 'badge-admin' : 'badge-student' }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>

            <div class="content-card-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div>
                        <label for="name" class="field-label">Full Name</label>
                        <input id="name" type="text" name="name" class="field-input mt-1"
                            value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="field-label">Email Address</label>
                        <input id="email" type="email" name="email" class="field-input mt-1"
                            value="{{ old('email', $user->email) }}" required autocomplete="username">
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role (Radio Cards) --}}
                    <div>
                        <label class="field-label mb-2 block">Role</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="role" value="student" class="peer sr-only"
                                    {{ old('role', $user->role) === 'student' ? 'checked' : '' }}>
                                <div
                                    class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-slate-200 dark:border-slate-600
                                            bg-white dark:bg-slate-800
                                            peer-checked:border-brand-500 peer-checked:bg-brand-50 dark:peer-checked:bg-brand-900/20
                                            transition-all duration-150 group-hover:border-brand-300">
                                    <div
                                        class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path
                                                d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z" />
                                            <path
                                                d="M3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0z" />
                                        </svg>
                                    </div>
                                    <span
                                        class="text-xs font-semibold text-slate-700 dark:text-slate-200 text-center leading-tight">Law
                                        Student</span>
                                </div>
                                <svg class="absolute top-2 right-2 w-4 h-4 text-brand-600 hidden peer-checked:block"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l3-3z"
                                        clip-rule="evenodd" />
                                </svg>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="role" value="admin" class="peer sr-only"
                                    {{ old('role', $user->role) === 'admin' ? 'checked' : '' }}>
                                <div
                                    class="flex flex-col items-center gap-2 p-4 rounded-xl border-2 border-slate-200 dark:border-slate-600
                                            bg-white dark:bg-slate-800
                                            peer-checked:border-brand-500 peer-checked:bg-brand-50 dark:peer-checked:bg-brand-900/20
                                            transition-all duration-150 group-hover:border-brand-300">
                                    <div
                                        class="w-9 h-9 rounded-xl bg-violet-100 dark:bg-violet-900/30 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <span
                                        class="text-xs font-semibold text-slate-700 dark:text-slate-200 text-center leading-tight">Administrator</span>
                                </div>
                                <svg class="absolute top-2 right-2 w-4 h-4 text-brand-600 hidden peer-checked:block"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l3-3z"
                                        clip-rule="evenodd" />
                                </svg>
                            </label>
                        </div>
                        @error('role')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Section --}}
                    <div class="border-t border-slate-100 dark:border-slate-700 pt-1">
                        <p class="section-heading mb-4">Change Password <span
                                class="normal-case font-normal text-slate-400 ml-1">(leave blank to keep current)</span>
                        </p>
                    </div>

                    <div>
                        <label for="password" class="field-label">New Password</label>
                        <div class="relative mt-1">
                            <input id="password" :type="showPassword ? 'text' : 'password'" name="password"
                                class="field-input pr-10" autocomplete="new-password">
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="field-label">Confirm New Password</label>
                        <div class="relative mt-1">
                            <input id="password_confirmation" :type="showConfirm ? 'text' : 'password'"
                                name="password_confirmation" class="field-input pr-10" autocomplete="new-password">
                            <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                                <svg x-show="!showConfirm" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showConfirm" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div
                        class="flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-700">
                        <a href="{{ route('admin.users.index') }}" class="btn-ghost py-2 px-4 text-sm">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary py-2 px-5 text-sm">
                            Save Changes
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<x-guest-layout>

    <div class="w-full max-w-sm mx-auto">

        {{-- Headline --}}
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-1">Welcome back</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Sign in to your Lexiscope account</p>
        </div>

        {{-- Session Status --}}
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

        {{-- Card --}}
        <div
            class="bg-white dark:bg-slate-800/80 backdrop-blur-sm rounded-2xl shadow-card border border-slate-100 dark:border-slate-700/50 overflow-hidden">

            {{-- Decorative top accent --}}
            <div class="h-1 bg-gradient-to-r from-brand-500 via-violet-500 to-brand-400"></div>

            <div class="p-7">
                <form method="POST" action="{{ route('login') }}" x-data="{ showPass: false }">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="field-label">Email address</label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input id="email" type="email" name="email" class="field-input pl-9"
                                value="{{ old('email') }}" required autofocus autocomplete="username"
                                placeholder="you@example.com">
                        </div>
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-5">
                        <label for="password" class="field-label">Password</label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" :type="showPass ? 'text' : 'password'" name="password"
                                class="field-input pl-9 pr-10" required autocomplete="current-password"
                                placeholder="••••••••">
                            <button type="button" @click="showPass = !showPass"
                                class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                                <svg x-show="!showPass" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPass" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
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

                    {{-- Remember + Forgot --}}
                    <div class="flex items-center justify-between mb-6">
                        <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer group">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="rounded border-slate-300 dark:border-slate-600 dark:bg-slate-900
                                          text-brand-600 shadow-sm focus:ring-brand-500 w-3.5 h-3.5">
                            <span
                                class="text-sm text-slate-600 dark:text-slate-400 group-hover:text-slate-800 dark:group-hover:text-slate-200 transition-colors">
                                Remember me
                            </span>
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                                class="text-sm text-brand-600 dark:text-brand-400 hover:underline font-medium">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                        class="w-full btn-primary py-3 text-base font-semibold shadow-sm
                                   bg-gradient-to-r from-brand-600 to-violet-600
                                   hover:from-brand-700 hover:to-violet-700
                                   active:scale-[0.98] transition-all duration-200
                                   focus-visible:ring-2 focus-visible:ring-brand-500 focus-visible:ring-offset-2">
                        Sign In to Lexiscope
                    </button>

                </form>
            </div>
        </div>

        {{-- Help text --}}
        <p class="text-center mt-5 text-xs text-slate-400 dark:text-slate-500">
            Access is by invitation only. Contact admin for an account.
        </p>

    </div>
</x-guest-layout>

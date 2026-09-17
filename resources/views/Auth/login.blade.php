<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login - {{ config('app.name', 'SAKP') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 font-sans antialiased">

    <main class="min-h-screen lg:grid lg:grid-cols-2">

        {{-- LEFT PANEL --}}
        <section class="relative hidden min-h-screen overflow-hidden lg:flex lg:items-end" aria-label="Informasi SAKP">
            {{-- Background Image --}}
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" style="background-image: url('{{ asset('images/flower-mkp.jpg') }}');" aria-hidden="true"></div>

            {{-- Soft Overlay & Gradient --}}
            <div class="absolute inset-0 bg-[#0c2340]/75" aria-hidden="true"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#07182b]/90 via-[#0c2340]/40 to-transparent" aria-hidden="true"></div>

            {{-- Left Content --}}
            <div class="relative z-10 w-full px-12 pb-16 xl:px-20 xl:pb-20">
                <div class="mb-5 flex items-center gap-3">
                    <span class="h-px w-10 bg-white/60"></span>
                    <span class="text-sm font-medium tracking-[0.2em] text-white/80 uppercase">Sistem Informasi</span>
                </div>

                <h1 class="max-w-xl text-4xl font-bold leading-tight tracking-tight text-white xl:text-5xl">
                    Selamat Datang di SAKP
                </h1>

                <p class="mt-5 max-w-xl text-base leading-7 text-white/75 xl:text-lg">
                    Sistem terintegrasi untuk membantu pengelolaan proyek, tim, dan aktivitas operasional secara lebih terstruktur.
                </p>

                <div class="mt-8 flex items-center gap-2">
                    <span class="h-1.5 w-8 rounded-full bg-white"></span>
                    <span class="h-1.5 w-2 rounded-full bg-white/40"></span>
                    <span class="h-1.5 w-2 rounded-full bg-white/40"></span>
                </div>
            </div>
        </section>

        {{-- RIGHT PANEL --}}
        <section class="flex min-h-screen items-center justify-center bg-slate-50 px-5 py-10 sm:px-8 lg:px-12" aria-label="Form login">
            <div class="w-full max-w-md">

                {{-- Mobile Brand --}}
                <div class="mb-10 text-center lg:hidden">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center">
                        <img src="{{ asset('images/logo-mkp.png') }}" alt="Logo MKP" class="h-20 w-20 rounded-2xl object-contain">
                    </div>
                </div>

                {{-- Login Card --}}
                <div class="rounded-2xl border border-slate-200 bg-[#0c2340] p-7 shadow-xl shadow-slate-900/10 sm:p-9">
                    
                    {{-- Logo --}}
                    <div class="mb-7 hidden justify-center lg:flex">
                        <div class="flex h-20 w-20 items-center justify-center overflow-hidden rounded-2xl bg-white p-1 shadow-sm">
                            <img src="{{ asset('images/logo-mkp.png') }}" alt="Logo MKP" class="h-full w-full rounded-xl object-contain">
                        </div>
                    </div>

                    {{-- Header --}}
                    <div class="mb-8 text-center">
                        <h2 class="text-2xl font-semibold tracking-tight text-white">Welcome Back!</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-300">Silakan masuk untuk mengakses sistem</p>
                    </div>

                    {{-- Session Status --}}
                    @if (session('status'))
                        <div class="mb-6 rounded-lg border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-200" role="status">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- Login Form --}}
                    <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                        @csrf

                        {{-- Email Input --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-white">Email</label>
                            <div class="mt-2">
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="{{ old('email') }}"
                                    autocomplete="email"
                                    inputmode="email"
                                    required
                                    autofocus
                                    aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}"
                                    aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}"
                                    placeholder="nama@email.com"
                                    class="block w-full rounded-lg border bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 outline-none transition duration-200 {{ $errors->has('email') ? 'border-red-400 ring-2 ring-red-400/20' : 'border-transparent ring-1 ring-inset ring-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500' }}"
                                >
                            </div>

                            @error('email')
                                <p id="email-error" class="mt-2 flex items-start gap-1.5 text-xs leading-5 text-red-300" role="alert">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        {{-- Password Input --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-white">Password</label>
                            <div class="relative mt-2">
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    autocomplete="current-password"
                                    required
                                    aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}"
                                    aria-describedby="{{ $errors->has('password') ? 'password-error' : '' }}"
                                    placeholder="Masukkan password"
                                    class="block w-full rounded-lg border bg-white px-4 py-3 pr-12 text-sm text-slate-900 placeholder-slate-400 outline-none transition duration-200 {{ $errors->has('password') ? 'border-red-400 ring-2 ring-red-400/20' : 'border-transparent ring-1 ring-inset ring-slate-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-500' }}"
                                >

                                {{-- Password Toggle Button --}}
                                <button
                                    id="toggle-password"
                                    type="button"
                                    class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-slate-400 transition-colors hover:text-[#0c2340] focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-inset"
                                    aria-label="Tampilkan password"
                                    aria-pressed="false"
                                >
                                    {{-- Eye Closed --}}
                                    <svg id="eye-closed" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>

                                    {{-- Eye Open --}}
                                    <svg id="eye-open" class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>

                            @error('password')
                                <p id="password-error" class="mt-2 flex items-start gap-1.5 text-xs leading-5 text-red-300" role="alert">
                                    <svg class="mt-0.5 h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        {{-- Remember Me & Forgot Password --}}
                        <div class="flex items-center justify-between pt-1">
                            <label for="remember_me" class="flex cursor-pointer items-center gap-2.5">
                                <input
                                    id="remember_me"
                                    name="remember_me"
                                    type="checkbox"
                                    value="1"
                                    {{ old('remember_me') ? 'checked' : '' }}
                                    class="h-4 w-4 cursor-pointer rounded border-slate-300 bg-white text-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-0"
                                >
                                <span class="text-sm text-slate-200">Remember me</span>
                            </label>

                            <a href="#" class="text-sm font-medium text-blue-200 transition-colors hover:text-white focus:outline-none focus-visible:underline">
                                Forgot Password?
                            </a>
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-2">
                            <button
                                type="submit"
                                class="flex w-full items-center justify-center rounded-lg bg-white px-4 py-3.5 text-sm font-semibold text-[#0c2340] shadow-sm transition-all duration-200 hover:bg-slate-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 focus-visible:ring-offset-2 focus-visible:ring-offset-[#0c2340] active:scale-[0.99]"
                            >
                                Login
                            </button>
                        </div>
                    </form>

                    {{-- Security Note --}}
                    <div class="mt-7 flex items-center justify-center gap-2 text-center">
                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2h8z" />
                        </svg>
                        <span class="text-xs text-slate-400">Akses sistem dilindungi dan aman</span>
                    </div>

                </div>

                {{-- Mobile Footer --}}
                <p class="mt-6 text-center text-xs text-slate-400 lg:hidden">
                    &copy; {{ date('Y') }} SAKP. All rights reserved.
                </p>

            </div>
        </section>

    </main>

    {{-- Toggle Password Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const passwordInput = document.getElementById('password');
            const toggleButton = document.getElementById('toggle-password');
            const eyeClosed = document.getElementById('eye-closed');
            const eyeOpen = document.getElementById('eye-open');

            if (!passwordInput || !toggleButton) return;

            toggleButton.addEventListener('click', () => {
                const isPassword = passwordInput.type === 'password';

                passwordInput.type = isPassword ? 'text' : 'password';
                eyeClosed.classList.toggle('hidden', isPassword);
                eyeOpen.classList.toggle('hidden', !isPassword);

                toggleButton.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');
                toggleButton.setAttribute('aria-pressed', isPassword ? 'true' : 'false');

                passwordInput.focus();
            });
        });
    </script>

</body>
</html>
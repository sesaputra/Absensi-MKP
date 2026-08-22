<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name', 'Laravel') }}</title>

    <!-- Impor Asset Vite + Tailwind (Versi 4) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-white">
    <div class="flex min-h-screen">

        <!-- KOLOM KIRI: Welcome To + Background Pattern -->
        <div class="relative hidden lg:flex lg:w-1/2 items-center justify-start pl-16 pb-16 bg-[#F9F9F9]">
            <!-- Layer Background Image dengan Opacity 60% -->
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-60"
                style="background-image: url('{{ asset('images/flower-mkp.jpg') }}');">
            </div>
            <!-- Konten Teks Kiri (Harus relative z-10 agar berada di atas layer gambar) -->
            <div class="relative z-10 max-w-lg text-left">
                <h1 class="text-5xl font-bold tracking-tight text-[#0c2340] xl:text-6xl">
                    Welcome to SAKP
                </h1>
                <p class="mt-4 text-base leading-relaxed text-slate-800">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </p>
            </div>
        </div>

        <!-- KOLOM KANAN: Area Login Form -->
        <div class="flex w-full lg:w-1/2 items-center justify-center px-6 py-12 bg-white">
            <!-- KARTU FORM: Warna Navy Blue (#11253E) persis seperti desain -->
            <div class="w-full max-w-md rounded-xl p-8 sm:p-12 shadow-2xl bg-[#0c2340]">
                <!-- Logo Placeholder (Kotak Abu-abu Muda) -->
                <div class="mb-4 flex justify-center"> <!-- Margin bottom diperkecil dari mb-8 ke mb-4 -->
                    <img src="{{ asset('images/logo-mkp.png') }}" alt="Logo MKP" class="h-28 w-28 rounded-2xl object-cover">
                </div>

                <!-- TAMBAHAN TEKS LOGIN -->
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-semibold text-white tracking-wide">
                        Welcome Back!
                    </h2>
                    <p class="mt-2 text-sm text-slate-300">
                        Silakan masuk untuk mengakses sistem
                    </p>
                </div>
                <!-- Form Login -->
                <form class="space-y-5" action="{{ route('login.post') }}" method="POST">
                    @csrf

                    <!-- Input Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium leading-6 text-white">
                            Email
                        </label>
                        <div class="mt-2">
                            <!-- Ubah name, id, dan type menjadi email -->
                            <input id="email" name="email" type="email" required autofocus
                                class="block w-full rounded-lg border-0 py-3 px-4 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 bg-white outline-none">
                        </div>
                    </div>
                    @error('email')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Input Password -->
                    <!-- Input Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium leading-6 text-white">
                            Password
                        </label>
                        <!-- Tambahkan 'relative' pada div ini agar ikon bisa diposisikan di dalam kotak -->
                        <div class="mt-2 relative">
                            <!-- Tambahkan 'pr-12' (padding-right) agar teks yang diketik tidak menabrak ikon -->
                            <input id="password" name="password" type="password" required
                                class="block w-full rounded-lg border-0 py-3 px-4 pr-12 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-blue-500 sm:text-sm sm:leading-6 bg-white outline-none">

                            <!-- Tombol Ikon Mata -->
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-blue-500 focus:outline-none transition-colors">

                                <!-- Ikon Mata Tertutup (Mata Dicoret) - Tampil secara default -->
                                <svg id="eye-closed" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>

                                <!-- Ikon Mata Terbuka - Disembunyikan secara default ('hidden') -->
                                <svg id="eye-open" class="h-5 w-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Opsi Tambahan Flex (Remember Me & Forgot Password) -->
                    <div class="flex items-center justify-between mt-6">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember_me" type="checkbox"
                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500 bg-white cursor-pointer">
                            <label for="remember_me" class="ml-2.5 block text-sm leading-6 text-white cursor-pointer">
                                Remember me
                            </label>
                        </div>

                        <div class="text-sm leading-6">
                            <!-- Teks Italic untuk Forgot Password -->
                            <a href="#" class="font-medium text-white hover:text-slate-200 italic transition-colors">
                                Forgot Password?
                            </a>
                        </div>
                    </div>

                    <!-- Tombol Login (Warna sedikit lebih terang dari background card) -->
                    <div class="mt-8">
                        <button type="submit"
                            class="flex w-full justify-center rounded-lg bg-[#1a365d] px-4 py-3.5 text-base font-medium text-white shadow-sm hover:bg-[#254677] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-500 transition-all duration-200 border border-[#254677]">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</body>
<script>
    function togglePassword() {
        // Mengambil elemen-elemen yang dibutuhkan
        const passwordInput = document.getElementById('password');
        const eyeClosed = document.getElementById('eye-closed');
        const eyeOpen = document.getElementById('eye-open');

        // Cek jika tipe input saat ini adalah 'password' (tersembunyi)
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text'; // Ubah jadi teks biasa agar terlihat
            eyeClosed.classList.add('hidden'); // Sembunyikan ikon mata dicoret
            eyeOpen.classList.remove('hidden'); // Tampilkan ikon mata terbuka
        } else {
            passwordInput.type = 'password'; // Kembalikan menjadi password
            eyeClosed.classList.remove('hidden'); // Tampilkan ikon mata dicoret
            eyeOpen.classList.add('hidden'); // Sembunyikan ikon mata terbuka
        }
    }
</script>

</html>
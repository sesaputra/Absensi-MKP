<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'MKP') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Tema visual MKP --}}
    <style>
        :root {
            --mkp-background: #F3F0E9;
            --mkp-surface: #FFFFFF;
            --mkp-surface-soft: #FAF8F4;
            --mkp-border: #E7E1D6;
            --mkp-text: #292524;
            --mkp-muted: #78716C;
            --mkp-amber: #B77932;
            --mkp-amber-hover: #996127;
            --mkp-charcoal: #292524;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--mkp-background);
            color: var(--mkp-text);
        }

        /* Background utama seluruh halaman */
        .mkp-page-background {
            background-color: var(--mkp-background);
        }

        /* Permukaan panel */
        .mkp-surface {
            background-color: var(--mkp-surface);
            border: 1px solid var(--mkp-border);
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgb(41 37 36 / 3%);
        }

        /* Permukaan sekunder */
        .mkp-surface-soft {
            background-color: var(--mkp-surface-soft);
            border: 1px solid var(--mkp-border);
            border-radius: 0.875rem;
        }

        /* Scrollbar yang lebih halus */
        * {
            scrollbar-width: thin;
            scrollbar-color: #D6D0C5 transparent;
        }

        *::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }

        *::-webkit-scrollbar-track {
            background: transparent;
        }

        *::-webkit-scrollbar-thumb {
            background-color: #D6D0C5;
            border-radius: 999px;
        }

        *::-webkit-scrollbar-thumb:hover {
            background-color: #B8AEA0;
        }

        /* Warna seleksi teks */
        ::selection {
            background-color: #E9D5B5;
            color: #292524;
        }

        /* Fokus aksesibilitas */
        button:focus-visible,
        a:focus-visible,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible {
            outline: 2px solid var(--mkp-amber);
            outline-offset: 3px;
        }

        /* SweetAlert2 */
        .swal2-popup {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            border-radius: 1.25rem !important;
        }

        .swal2-title {
            color: var(--mkp-text) !important;
            font-size: 1.2rem !important;
            font-weight: 700 !important;
        }

        .swal2-html-container {
            color: var(--mkp-muted) !important;
            font-size: 0.875rem !important;
        }

        .swal2-confirm,
        .swal2-cancel {
            border-radius: 0.75rem !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            padding: 0.75rem 1.25rem !important;
        }

        /* Menghindari transisi berlebihan bagi pengguna yang
           memilih pengurangan animasi di perangkatnya. */
        @media (prefers-reduced-motion: reduce) {
            html {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>


<body class="bg-slate-100 font-sans antialiased text-slate-900">

    <div class="flex h-dvh overflow-hidden">

        @include('layouts.sidebar')

        <div class="flex min-w-0 flex-1 flex-col overflow-hidden bg-slate-100">

            @include('layouts.navbar')

            <main class="min-h-0 flex-1 overflow-x-hidden overflow-y-auto bg-slate-100 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>

        </div>
    </div>

    {{-- Modal global berada di luar wrapper agar tidak terpotong overflow --}}
    @stack('modals')

    {{-- Script global --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
             * Dropdown profil.
             * Tetap menggunakan ID yang sudah ada di navbar.
             */
            const profileButton = document.getElementById('profil-button');
            const profileMenu = document.getElementById('profil-menu');

            if (profileButton && profileMenu) {

                profileButton.addEventListener('click', function(event) {
                    event.stopPropagation();
                    profileMenu.classList.toggle('hidden');
                });

                profileMenu.addEventListener('click', function(event) {
                    event.stopPropagation();
                });

                document.addEventListener('click', function() {
                    profileMenu.classList.add('hidden');
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        profileMenu.classList.add('hidden');
                    }
                });
            }
        });

        /*
         * Toggle dropdown profil.
         * Dipertahankan untuk kompatibilitas dengan pemanggilan inline
         * toggleDropdown() yang mungkin masih digunakan.
         */
        function toggleDropdown() {
            const menu = document.getElementById('profil-menu');

            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        /*
         * Konfirmasi penghapusan data menggunakan SweetAlert2.
         * Form hanya dikirim setelah pengguna mengonfirmasi.
         */
        function konfirmasiHapus(formId, namaData) {
            const form = document.getElementById(formId);

            if (!form) {
                console.error('Form penghapusan tidak ditemukan:', formId);
                return;
            }

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: `${namaData} akan dihapus secara permanen!`,
                icon: 'warning',

                showCancelButton: true,
                confirmButtonColor: '#B91C1C',
                cancelButtonColor: '#78716C',

                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal',

                reverseButtons: true,
                focusCancel: true,

                customClass: {
                    confirmButton: 'rounded-xl',
                    cancelButton: 'rounded-xl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>

    {{-- Tempat script tambahan dari halaman --}}
    @stack('scripts')

</body>

</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'MKP') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<body class="font-sans antialiased text-slate-900 bg-white">

    <!-- Wrapper Utama -->
    <div class="flex h-screen overflow-hidden">
        <!-- Memanggil Partial Sidebar -->
        @include('layouts.sidebar')
        <!-- Area Kanan -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Memanggil Partial Navbar -->
            @include('layouts.navbar')
            <!-- Area Konten Utama yang akan diisi oleh view lain -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 lg:p-8 space-y-8">
                @yield('content')
            </main>
        </div>
    </div>

</body>
<!-- Script untuk mengatur Dropdown Profil -->
<script>
    function toggleDropdown() {
        const menu = document.getElementById('profil-menu');
        menu.classList.toggle('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {

        const button = document.getElementById('profil-button');
        const menu = document.getElementById('profil-menu');

        if (!button || !menu) {
            return;
        }

        // Toggle dropdown
        button.addEventListener('click', function(e) {
            e.stopPropagation();

            menu.classList.toggle('hidden');
        });

        // Jangan tutup dropdown ketika klik di dalam menu
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Tutup dropdown ketika klik di luar
        document.addEventListener('click', function() {
            menu.classList.add('hidden');
        });

    });

    // function toggleDropdown() {
    //     const menu = document.getElementById('profil-menu');
    //     menu.classList.toggle('hidden');
    // }

    // // Menutup pop-up jika pengguna mengklik area luar dropdown
    // window.addEventListener('click', function(e) {
    //     const button = document.getElementById('profil-button');
    //     const menu = document.getElementById('profil-menu');

    //     if (!button.contains(e.target) && !menu.contains(e.target)) {
    //         menu.classList.add('hidden');
    //     }
    // });
    // --- SCRIPT SWEETALERT KONFIRMASI HAPUS ---
    function konfirmasiHapus(formId, namaData) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: namaData + " akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', // Warna merah senada dengan tombol
            cancelButtonColor: '#64748b', // Warna abu-abu slate
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true, // Memutar posisi tombol agar 'Batal' di kiri
            customClass: {
                confirmButton: 'rounded-lg',
                cancelButton: 'rounded-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Jika user klik 'Ya', submit formnya secara otomatis
                document.getElementById(formId).submit();
            }
        });
    }
</script>

</html>
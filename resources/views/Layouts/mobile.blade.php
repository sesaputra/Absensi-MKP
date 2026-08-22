<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"> <!-- Mencegah zoom di HP -->
    <title>@yield('title') - CV Mahesa Karya Perdana</title>

    <!-- Impor Tailwind CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Opsional: Font Google (Ganti dengan font pilihan Anda jika ada) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-200 antialiased text-slate-800">
    <!-- Konten utama akan masuk ke sini -->
    @yield('content')
</body>

<script>
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        confirmButtonColor: '#0c2340', // Warna navy brand Anda
        timer: 3000
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: "{{ session('error') }}",
        confirmButtonColor: '#d33'
    });
    @endif
</script>

</html>
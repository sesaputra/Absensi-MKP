<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title') - CV Mahesa Karya Perdana
    </title>


    {{-- =========================================================
        VITE / TAILWIND
    ========================================================== --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    {{-- =========================================================
        GOOGLE FONT
    ========================================================== --}}
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    {{-- =========================================================
        SWEETALERT2
    ========================================================== --}}
    <script
        src="https://cdn.jsdelivr.net/npm/sweetalert2@11"
    ></script>


    {{-- =========================================================
        BASE STYLE
    ========================================================== --}}
    <style>
        html {
            -webkit-text-size-adjust: 100%;
            text-size-adjust: 100%;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>

</head>


<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">

    {{-- =========================================================
        PAGE CONTENT
    ========================================================== --}}
    @yield('content')


    {{-- =========================================================
        SESSION NOTIFICATION
    ========================================================== --}}
    <script>

        @if(session('success'))

            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: @json(session('success')),
                confirmButtonColor: '#0c2340',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });

        @endif


        @if(session('error'))

            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: @json(session('error')),
                confirmButtonColor: '#d33'
            });

        @endif

    </script>

</body>

</html>

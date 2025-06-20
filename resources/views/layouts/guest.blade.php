<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Guest</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        .bg-gradient-custom {
            background:
                /* linear-gradient(135deg, rgba(13, 110, 253, 0.7) 0%, rgba(172, 182, 229, 0.7) 100%), */
                url('/img/login-background.png') no-repeat center center / cover;
        }


        .card {
            background-color: rgba(255, 255, 255, 0.95);
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }
    </style>
</head>

<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">

    <main class="w-100">
        @yield('content')
    </main>


</body>

</html>
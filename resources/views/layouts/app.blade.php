<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- ===================== Fonts & Icons CDN ===================== -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- ===================== Custom Global CSS ===================== -->
    <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">

    <!-- ===================== Bootstrap CSS CDN ===================== -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">


    <!-- ===================== Vite - App CSS & JS ===================== -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- ===================== Extra Page Specific CSS ===================== -->
    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="container-fluid">
        <div class="row min-vh-100">

            <!-- ===================== Sidebar ===================== -->
            <div class="col-md-3 col-lg-2 border-end">
                @include('layouts.sidebar')
            </div>

            <!-- ===================== Main Content ===================== -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 d-flex flex-column min-vh-100">

                <!-- Navigation Bar -->
                <div class="mt-2">
                    @include('layouts.navigation')
                </div>

                <!-- Page Content Area -->
                <div class="mt-4 flex-grow-1 card">
                    @yield('content')
                </div>


                <!-- Footer Section -->
                <div class="mt-auto py-4">
                    @include('layouts.footer')
                </div>
            </main>
        </div>

    </div>



    <!-- ===================== JS CDN Section ===================== -->
    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Popper.js (for Bootstrap tooltips, popovers) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>

    <!-- Bootstrap JS Bundle (Bootstrap + Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- FontAwesome JS (Optional for dynamic icon usage) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- ===================== Extra Page Specific JS ===================== -->
    @stack('scripts')
    @include('layouts._toasts')
</body>

</html>

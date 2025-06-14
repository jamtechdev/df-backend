<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Global & Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <!-- Loader CSS -->
    <style>
        #globalLoader {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255, 255, 255, 0.95);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .loader-circle {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #007bff;
            border-radius: 50%;
            width: 70px;
            height: 70px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="font-sans antialiased">
    <!-- Global Page Loader -->
    <div id="globalLoader">
        <div class="loader-circle"></div>
    </div>

    <div class="container-fluid">
        <div class="row min-vh-100">

            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 border-end">
                @include('layouts.sidebar')
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 d-flex flex-column min-vh-100">
                <div class="mt-2">
                    @include('layouts.navigation')
                </div>
                <div class="mt-4 flex-grow-1 card">
                    @yield('content')
                </div>
                <div class="mt-auto py-4">
                    @include('layouts.footer')
                </div>
            </main>
        </div>
    </div>

    <!-- JS CDN -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Loader JS -->
    <script>
        // Ensure loader stays at least 500ms (or adjust here)
        window.addEventListener('load', function() {
            setTimeout(function() {
                const loader = document.getElementById('globalLoader');
                if(loader){
                    loader.style.display = 'none';
                }
            }, 500); // 500ms delay
        });

        // Ajax Loader Handling
        $(document).on({
            ajaxStart: function() { $('#globalLoader').show(); },
            ajaxStop: function() {
                // same delay for AJAX loader hide
                setTimeout(function() {
                    $('#globalLoader').hide();
                }, 500); // adjust delay if needed
            }
        });
    </script>

    @stack('scripts')
    @include('layouts._toasts')
</body>
</html>

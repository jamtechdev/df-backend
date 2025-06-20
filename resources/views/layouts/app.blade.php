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
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://bootswatch.com/5/zephyr/bootstrap.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <style>
        body {
            background: linear-gradient(to bottom right, #f8f9fa, #e2ebf0);
            font-family: 'Figtree', sans-serif;
            color: #333;
            min-height: 100vh;
        }

        .content {
            margin-left: 280px;
            padding: 2rem;
            transition: margin 0.3s ease;
        }

        @media(max-width: 768px) {
            .content {
                margin-left: 0;
                padding: 1rem;
            }
        }

        /* Header (Navigation) */
        .navigation {
            background: linear-gradient(135deg,rgb(212, 211, 211),rgb(202, 206, 203));
            /* padding: 1rem 2rem; */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-radius: 0.75rem;
            margin-bottom: 2rem;
        }

        /* Footer */
        .footer {
            background: #fff;
            padding: 1rem 2rem;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
            border-radius: 0.75rem;
            text-align: center;
            margin-top: 2rem;
        }

        /* Dropzone Image Fix */
        .dz-preview .dz-image img {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            background: linear-gradient(135deg, #ffffff 0%, #f1f6f2 100%);
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #02afff, #00c6ff);
            border: none;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #00c6ff, #02afff);
            box-shadow: 0 4px 12px rgba(0, 198, 255, 0.4);
        }

        /* Toast */
        .toast {
            border-radius: 0.5rem !important;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content -->
    <main class="content">
        <!-- Navigation (Topbar) -->
        <div class="navigation">
            @include('layouts.navigation')
        </div>

        <!-- Main Yield -->
        <div>
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="footer">
            @include('layouts.footer')
        </div>
    </main>

    <!-- JS CDN -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
    <script src="{{ asset('assets/js/global.js') }}"></script>
    @stack('scripts')
    @include('layouts._toasts')
</body>

</html>

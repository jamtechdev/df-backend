<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 | Page Not Found</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet"> <!-- Laravel Bootstrap CSS -->
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>


<body class="d-flex justify-content-center align-items-center vh-100 bg-light">

    <div class="text-center">
        <h1 class="display-1 fw-bold text-primary">404</h1>
        <p class="fs-3"> <span class="text-danger">Oops!</span> Page not found.</p>
        <p class="lead">
            The page you’re looking for doesn’t exist.
        </p>

    </div>

</body>

</html>

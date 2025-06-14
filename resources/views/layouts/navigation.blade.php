<nav class="navbar navbar-expand-md navbar-light card">
    <div class="container-fluid">
        <!-- Logo Section -->
        <a class="navbar-brand d-flex align-items-center" href="#">

            <h3 class="fs-5 fw-semibold text-dark mb-0">Welcome,
                {{ Auth::user()->first_name .' '.Auth::user()->last_name }}!
            </h3>
        </a>

        <div class="ms-auto d-flex align-items-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
            </form>
        </div>

        <!-- Hamburger Icon for Mobile Navigation -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

        </button>
    </div>
</nav>

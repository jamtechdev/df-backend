<nav class="navbar navbar-expand-md navbar-light bg-light p-3">
    <div class="container-fluid">
        <!-- Logo Section -->
        <a class="navbar-brand d-flex align-items-center" href="#">

            <h3 class="fs-5 fw-semibold text-dark mb-0">Welcome,
                {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}!
            </h3>
        </a>

        <div class="ms-auto d-flex align-items-center">
            

            <!-- Hamburger Icon for Mobile Navigation -->
            <button class="navbar-toggler" id="toggleSidebarBtn" type="button" aria-label="Toggle navigation">
                <i class="fas fa-bars fa-lg" id="toggleIcon"></i>
            </button>

        </div>
    </div>
</nav>
<nav class="navbar navbar-expand-md navbar-light card">
    <div class="container-fluid">
        <!-- Logo Section -->
        <a class="navbar-brand d-flex align-items-center" href="#">

            <h3 class="fs-5 fw-semibold text-dark-blue mb-0">Welcome,
                {{ Auth::user()->first_name .' '.Auth::user()->last_name }}!
            </h3>
        </a>

         <div class="navbar-actions">
                    <div class="profile-dropdown-simple">
                        <button class="profile-button-simple">
                            <i class="fas fa-user-circle"></i> Admin
                        </button>
                        <div class="profile-dropdown-content-simple">
                            <a href="#" class="profile-option-simple"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                            <a href="#" class="profile-option-simple"><i class="fas fa-user-edit"></i> Change Profile</a>
                            <div class="ms-auto d-flex align-items-center">
                                <form class="w-100" method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="profile-option-simple w-100"><i class="fas fa-sign-out-alt"></i> Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

        

        <!-- Hamburger Icon for Mobile Navigation -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

        </button>
    </div>
</nav>

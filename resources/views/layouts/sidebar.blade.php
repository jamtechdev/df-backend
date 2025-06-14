<aside id="layout-menu" class="layout-menu menu-vertical bg-menu-theme">
    <div class="app-brand text-center py-3">
        <a href="/" class="app-brand-link text-decoration-none">
            <span class="app-brand-logo">
                <h5 class="text-primary fw-bold">Delightful Ocean</h5>
            </span>
        </a>
    </div>

    <ul class="menu-inner list-unstyled px-2 mt-4">

        {{-- Dashboard --}}
        <li class="menu-item mb-2">
            <a href="{{ route('dashboard') }}"
                class="menu-link d-flex align-items-center rounded px-3 py-2 text-decoration-none {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt me-2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- Users Accordion --}}
        @php
            $userMenuOpen = request()->routeIs('members.index') || request()->routeIs('visitors.index');
        @endphp
        <li class="menu-item mb-2">
            <input type="checkbox" id="users-menu" class="d-none peer" {{ $userMenuOpen ? 'checked' : '' }}>
            <label for="users-menu"
                   class="menu-link d-flex justify-content-between align-items-center rounded px-3 py-2 text-decoration-none text-dark bg-light cursor-pointer">
                <div class="d-flex align-items-center">
                    <i class="fas fa-users-cog me-2 text-primary"></i>
                    <span class="fw-semibold">Users</span>
                </div>
                <i class="fas fa-chevron-down small text-muted"></i>
            </label>

            <ul class="menu-sub list-unstyled ps-4 mt-1 hidden peer-checked:block">
                <li class="menu-item mb-1">
                    <a href="{{ route('members.index') }}"
                       class="menu-link d-flex align-items-center px-2 py-1 rounded small text-decoration-none text-secondary {{ request()->routeIs('members.index') ? 'bg-primary text-white' : '' }}">
                        <i class="fas fa-user-friends me-2"></i>
                        <span>Members</span>
                    </a>
                </li>
                <li class="menu-item mb-1">
                    <a href="{{ route('visitors.index') }}"
                       class="menu-link d-flex align-items-center px-2 py-1 rounded small text-decoration-none text-secondary {{ request()->routeIs('visitors.index') ? 'bg-primary text-white' : '' }}">
                        <i class="fas fa-user me-2"></i>
                        <span>Visitors</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Project Permission --}}
        <li class="menu-item mb-2">
            <a href="{{ route('projects.permissions.index') }}"
               class="menu-link d-flex align-items-center rounded px-3 py-2 text-decoration-none {{ request()->routeIs('projects.permissions.index') ? 'bg-primary text-white' : 'text-dark bg-light' }}">
                <i class="fas fa-user-shield me-2 {{ request()->routeIs('projects.permissions.index') ? 'text-white' : 'text-primary' }}"></i>
                <span class="fw-semibold">Project Permission</span>
            </a>
        </li>

        {{-- Dynamic Projects --}}
        @foreach (menu() as $index => $project)
            @php
                $isProjectOpen = false;
                if (!empty($project->children)) {
                    foreach ($project->children as $child) {
                        if (Request::is(trim($child->uri, '/'))) {
                            $isProjectOpen = true;
                            break;
                        }
                    }
                }
            @endphp

            <li class="menu-item mb-2">
                @if (!empty($project->children))
                    <input type="checkbox" id="project-menu-{{ $index }}" class="d-none peer" {{ $isProjectOpen ? 'checked' : '' }}>
                    <label for="project-menu-{{ $index }}"
                           class="menu-link d-flex justify-content-between align-items-center rounded px-3 py-2 text-decoration-none text-dark bg-light cursor-pointer">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-folder me-2 text-primary"></i>
                            <span class="fw-semibold">{{ $project->title }}</span>
                        </div>
                        <i class="fas fa-chevron-down small text-muted"></i>
                    </label>

                    <ul class="menu-sub list-unstyled ps-4 mt-1 hidden peer-checked:block">
                        @foreach ($project->children as $menuItem)
                            <li class="menu-item mb-1">
                                <a href="{{ url($menuItem->uri) }}"
                                   class="menu-link d-flex align-items-center px-2 py-1 rounded small text-decoration-none text-secondary {{ Request::is(trim($menuItem->uri, '/')) ? 'bg-primary text-white' : '' }}">
                                    <i class="{{ $menuItem->icon }} me-2"></i>
                                    <span>{{ $menuItem->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <a href="javascript:void(0);"
                       class="menu-link d-flex align-items-center rounded px-3 py-2 text-decoration-none text-dark bg-light">
                        <i class="fas fa-folder me-2 text-primary"></i>
                        <span class="fw-semibold">{{ $project->title }}</span>
                    </a>
                @endif
            </li>
        @endforeach
    </ul>
</aside>

{{-- Sidebar Styling --}}
<style>
    .layout-menu {
        width: 250px;
        background: linear-gradient(145deg, #f0f0f0, #ffffff);
        border-radius: 1rem;
        padding-bottom: 2rem;
    }

    .menu-link {
        transition: background 0.3s, color 0.3s;
        color: #555;
        background: #f9f9f9;
    }

    .menu-link:hover {
        background: #007bff !important;
        color: #fff !important;
    }

    .menu-link i {
        transition: color 0.3s;
    }

    .active {
        background: #007bff !important;
        color: #fff !important;
    }

    .active i {
        color: #fff !important;
    }

    .menu-sub {
        display: none;
    }

    .peer:checked ~ .menu-sub {
        display: block !important;
    }

    .active-sub {
        background: #0069d9 !important;
        color: #fff !important;
    }

    .app-brand {
        border-radius: 1rem;
    }

    .app-brand-link img {
        transition: transform 0.3s ease;
    }

    .app-brand-link:hover img {
        transform: scale(1.05);
    }
</style>

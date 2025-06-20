<aside id="layout-menu" class="layout-menu menu-vertical bg-menu-theme sidebar">
    <!-- Mobile Close Button -->
    <div class="text-end p-2 d-block d-md-none">
        <button id="closeSidebarBtn" class="btn btn-sm btn-danger">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Logo & App Name -->
    <div class="app-brand text-center rounded mt-2">
        <a href="/" class="app-brand-link d-flex justify-content-center align-items-center text-decoration-none">
            <img src="{{ asset('img/df.png') }}" alt="Logo" style="height: 60px;">
            <h5 class="fw-bold mb-0 text-dark">{{ config('app.name', 'My Project') }}</h5>
        </a>
    </div>

    <!-- Menu Items -->
    <ul class="menu-inner list-unstyled px-2 mt-4">

        {{-- Dashboard --}}
        <li class="menu-item mb-3">
            <a href="{{ route('dashboard') }}"
                class="menu-link d-flex align-items-center rounded px-3 py-2 text-decoration-none {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt me-2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        @if(auth()->user()->hasRole('admin'))
        {{-- Users Accordion --}}
        @php
        $userMenuOpen = request()->routeIs('members.index') || request()->routeIs('visitors.index');
        @endphp
        <li class="menu-item mb-3">
            <input type="checkbox" id="users-menu" class="d-none peer" {{ $userMenuOpen ? 'checked' : '' }}>
            <label for="users-menu"
                class="menu-link d-flex justify-content-between align-items-center rounded px-3 py-2 cursor-pointer">
                <div class="d-flex align-items-center">
                    <i class="fas fa-users-cog me-2"></i>
                    <span>Users</span>
                </div>
                <i class="fas fa-chevron-down small"></i>
            </label>

            <ul class="menu-sub list-unstyled ps-4 mt-1 hidden peer-checked:block">
                <li class="menu-item mb-1">
                    <a href="{{ route('members.index') }}"
                        class="menu-link d-flex align-items-center px-2 py-2 rounded small text-decoration-none {{ request()->routeIs('members.index') ? 'active-sub' : '' }}">
                        <i class="fas fa-user-friends me-2"></i>
                        <span>Members</span>
                    </a>
                </li>
                <li class="menu-item mb-1">
                    <a href="{{ route('visitors.index') }}"
                        class="menu-link d-flex align-items-center px-2 py-2 rounded small text-decoration-none {{ request()->routeIs('visitors.index') ? 'active-sub' : '' }}">
                        <i class="fas fa-user me-2"></i>
                        <span>Visitors</span>
                    </a>
                </li>
            </ul>
        </li>
        {{-- Project Permission --}}
        <li class="menu-item mb-3">
            <a href="{{ route('projects.permissions.index') }}"
                class="menu-link d-flex align-items-center rounded px-3 py-2 text-decoration-none {{ request()->routeIs('projects.permissions.index') ? 'active' : '' }}">
                <i class="fas fa-user-shield me-2"></i>
                <span>Project Permission</span>
            </a>
        </li>

        {{-- theme  --}}
        <li class="menu-item mb-3">
            <a href="{{ route('themes.index') }}"
                class="menu-link d-flex align-items-center rounded px-3 py-2 text-decoration-none {{ request()->routeIs('themes.index') ? 'active' : '' }}">
                <i class="fas fa-folder me-2"></i>
                <span>Theme</span>
            </a>
        </li>
        @endif

        {{-- Dynamic Projects --}}
        
        @foreach (menu() as $index => $project)
        @php
        $isProjectOpen = false;
        if (!empty($project->children)) {
        foreach ($project->children as $child) {
        if (Request::is(ltrim($child->uri, '/') . '*')) {
        $isProjectOpen = true;
        break;
        }
        }
        }
        @endphp

        <li class="menu-item mb-3">
            @if (!empty($project->children))
            <input type="checkbox" id="project-menu-{{ $index }}" class="d-none peer" {{ $isProjectOpen ? 'checked' : '' }}>
            <label for="project-menu-{{ $index }}"
                class="menu-link d-flex justify-content-between align-items-center rounded px-3 py-2 cursor-pointer">
                <div class="d-flex align-items-center">
                    <i class="fas fa-folder me-2"></i>
                    <span>{{ $project->title }}</span>
                </div>
                <i class="fas fa-chevron-down small"></i>
            </label>

            <ul class="menu-sub list-unstyled ps-4 mt-1 hidden peer-checked:block">
                @foreach ($project->children as $menuItem)
                <li class="menu-item mb-1">
                    <a href="{{ url($menuItem->uri) }}"
                        class="menu-link d-flex align-items-center px-2 py-2 rounded small text-decoration-none {{ Request::is(ltrim($menuItem->uri, '/') . '*') ? 'active-sub' : '' }}">
                        <i class="{{ $menuItem->icon }} me-2"></i>
                        <span class="text-venilla fs-7 fw-semibold">{{ $menuItem->title }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
            @else
            <a href="javascript:void(0);" class="menu-link d-flex align-items-center rounded px-3 py-2 text-decoration-none">
                <i class="fas fa-folder me-2"></i>
                <span>{{ $project->title }}</span>
            </a>
            @endif
        </li>
        @endforeach
    </ul>

    <!-- Logout Button -->
    <div class="Logout mt-4 px-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-100 fw-bold text-danger btn btn-light border">
                <i class="fas fa-sign-out me-2"></i> Logout
            </button>
        </form>
    </div>
</aside>

<!-- Optional Backdrop -->
<div class="backdrop"></div>
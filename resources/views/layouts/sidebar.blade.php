<aside id="layout-menu" class="layout-menu menu-vertical bg-menu-theme  sidebar">
   <!-- Inside your <aside id="layout-menu"> -->
<div class="text-end p-2 d-block d-md-none">
    <button id="closeSidebarBtn" class="btn btn-sm btn-danger">
        <i class="fas fa-times"></i>
    </button>
</div>

    <div class="app-brand text-center rounded mt-2">
        <a href="/" class="app-brand-link d-flex justify-content-center align-items-center text-decoration-none">
            <img src="{{ asset('img/df.png') }}" alt="Logo" style="height: 60px;">
            <h5 class="fw-bold mb-0 text-dark">{{ config('app.name', 'My Project') }}</h5>
        </a>
    </div>

    <ul class="menu-inner list-unstyled px-2 mt-4">

        {{-- Dashboard --}}
        <li class="menu-item mb-3">
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
                                    class="menu-link d-flex align-items-center px-2 py-2 rounded small text-decoration-none {{ Request::is(trim($menuItem->uri, '/')) ? 'active-sub' : '' }}">
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


        {{-- theme  --}}

        <li class="menu-item mb-3">
            <a href="{{ route('themes.index') }}"
                class="menu-link d-flex align-items-center rounded px-3 py-2 text-decoration-none {{ request()->routeIs('themes.index') ? 'active' : '' }}">
                <i class="fas fa-folder me-2"></i>
                <span>Theme</span>
            </a>
        </li>

    </ul>
      <div class="Logout">
            <form method="POST" action="{{ route('logout') }}">
          @csrf

          <button type="submit" class=" w-100 fw-bold text-danger"><i class="fas fa-sign-out me-2"></i>Logout</button>
         </form>
        </div>
</aside>
<div class="backdrop"></div>
{{-- Sidebar Styling --}}
<style>
    .Logout{
        position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    background: #fff;
    padding: 13px;
    border-top: 1px solid #02afff;
    }
    .backdrop {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.5);
  z-index: 1000;
  display: none;
}

.sidebar-visible .backdrop {
  display: block;
}
    /* .layout-menu {
        width: 250px;
        background: linear-gradient(145deg, #f0f0f0, #ffffff);
        border-radius: 1rem;
        padding-bottom: 2rem;
    } */

    /* .menu-link {
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
    } */

    .menu-sub {
        display: none;
    }

    .peer:checked ~ .menu-sub {
        display: block !important;
    }

    /* .active-sub {
        background: #0069d9 !important;
        color: #fff !important;
    } */

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

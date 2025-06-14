<aside id="layout-menu" class="layout-menu menu-vertical bg-menu-theme">
    <div class="app-brand text-center py-3">
        <a href="/" class="app-brand-link text-decoration-none">
            <span class="app-brand-logo">
                <h5 class="text-venilla fw-bold">Delightful Ocean</h5>
            </span>
        </a>
    </div>

    <ul class="menu-inner list-unstyled px-2 mt-3">

        {{-- Users Accordion Menu --}}
        @php
            $userMenuOpen = request()->routeIs('members.index') || request()->routeIs('visitors.index');
        @endphp
        <li class="menu-item mb-1">
            <input type="checkbox" id="users-menu" class="d-none peer" {{ $userMenuOpen ? 'checked' : '' }}>
            <label for="users-menu"
                   class="menu-link d-flex justify-content-between align-items-center rounded px-3 py-2 text-decoration-none text-venilla cursor-pointer">
                <div class="d-flex align-items-center">
                    <i class="fas fa-users-cog me-2 text-venilla"></i>
                    <span class="fw-semibold">Users</span>
                </div>
                <i class="fas fa-chevron-down small text-venilla"></i>
            </label>

            <ul class="menu-sub list-unstyled ps-4 mt-1 hidden peer-checked:block">
                <li class="menu-item mb-1">
                    <a href="{{ route('members.index') }}"
                       class="menu-link d-flex align-items-center px-2 py-2 rounded small text-decoration-none text-secondary {{ request()->routeIs('members.index') ? 'text-venilla' : '' }}">
                        <i class="fas fa-user-friends me-2 text-venilla"></i>
                        <span class="text-venilla fs-7 fw-semibold">Members</span>
                    </a>
                </li>
                <li class="menu-item mb-1">
                    <a href="{{ route('visitors.index') }}"
                       class="menu-link d-flex align-items-center px-2  py-2 rounded small text-decoration-none text-secondary {{ request()->routeIs('visitors.index') ? 'text-venilla' : '' }}">
                        <i class="fas fa-user me-2 text-venilla"></i>
                        <span class="text-venilla fs-7 fw-semibold">Visitors</span>
                    </a>
                </li>
            </ul>
        </li>

        {{-- Static Project Permission Menu --}}
        <li class="menu-item mb-1">
            <a href="{{ route('projects.permissions.index') }}"
               class="menu-link d-flex align-items-center rounded px-3 py-2 text-decoration-none {{ request()->routeIs('projects.permissions.index') ? 'text-venilla' : '' }}">
                <i class="fas fa-user-shield me-2 {{ request()->routeIs('projects.permissions.index') ? 'text-venilla' : 'text-venilla' }}"></i>
                <span class="fw-semibold text-venilla">Project Permission</span>
            </a>
        </li>

        {{-- Dynamic Project Menus --}}
        @foreach (menu() as $index => $project)
            @php
                // Check if any child menu item is active to auto-open parent
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

            <li class="menu-item mb-1">
                @if (!empty($project->children))
                    <input type="checkbox" id="project-menu-{{ $index }}" class="d-none peer" {{ $isProjectOpen ? 'checked' : '' }}>
                    <label for="project-menu-{{ $index }}"
                           class="menu-link d-flex justify-content-between align-items-center rounded px-3 py-2 text-decoration-none text-venilla cursor-pointer">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-folder me-2 text-venilla"></i>
                            <span class="fw-semibold">{{ $project->title }}</span>
                        </div>
                        <i class="fas fa-chevron-down small text-venilla"></i>
                    </label>

                    <ul class="menu-sub list-unstyled ps-4 mt-1 hidden peer-checked:block">
                        @foreach ($project->children as $menuItem)
                            <li class="menu-item mb-1">
                                <a href="{{ url($menuItem->uri) }}"
                                   class="menu-link d-flex align-items-center px-2 py-2 rounded small text-decoration-none text-venilla {{ Request::is(trim($menuItem->uri, '/')) ? 'text-venilla' : '' }}">
                                    <i class="{{ $menuItem->icon }} me-2"></i>
                                    <span class="text-venilla fs-7 fw-semibold">{{ $menuItem->title }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <a href="javascript:void(0);"
                       class="menu-link d-flex align-items-center rounded px-3 py-2 text-decoration-none text-venilla">
                        <i class="fas fa-folder me-2 text-venilla"></i>
                        <span class="fw-semibold">{{ $project->title }}</span>
                    </a>
                @endif
            </li>
        @endforeach

    </ul>
</aside>

<style>
    .menu-sub {
        display: none;
    }
    .peer:checked ~ .menu-sub {
        display: block !important;
    }
</style>

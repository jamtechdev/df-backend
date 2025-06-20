<?php

use App\Models\Menu;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

// Generate Sidebar Menu
if (!function_exists('menu')) {
    function menu()
    {
        $menu = [];

        // If no user logged in, return empty menu
        if (!Auth::check()) {
            return $menu;
        }

        $user = Auth::user();

        // Admin sees all projects
        if ($user->hasRole('admin')) {
            $projects = Project::all();
        } else {
            // Other roles see only assigned projects via pivot table 'project_user' with role filter
            $projects = $user->projects()
                ->wherePivotIn('role', ['manager', 'content_manager'])
                ->get();
        }

        // Build menu structure per project
        foreach ($projects as $project) {
            $menuData = Menu::where('project_id', $project->id)->get();

            $menu[] = (object)[
                'id' => $project->id,
                'title' => $project->name,
                'icon' => 'fas fa-folder',
                'uri' => '#',
                'children' => buildMenu($menuData)
            ];
        }

        return $menu;
    }
}

// Recursive Menu Builder for Parent-Child structure
if (!function_exists('buildMenu')) {
    function buildMenu($menuItems, $parentId = 0)
    {
        $menu = [];
        foreach ($menuItems as $item) {
            if ($item->parent_id == $parentId) {
                $menu[] = (object)[
                    'id' => $item->id,
                    'title' => $item->title,
                    'icon' => $item->icon,
                    'uri' => $item->uri,
                    'children' => buildMenu($menuItems, $item->id)
                ];
            }
        }
        return $menu;
    }
}

// Add 'NP_' Prefix to Tables
if (!function_exists('np_table')) {
    function np_table(string $tableName): string
    {
        return 'NP_' . $tableName;
    }
}

// Check if User Has Project Access
if (!function_exists('hasProjectAccess')) {
    function hasProjectAccess($projectId, $menuTitle = null)
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        // Admin: always has access
        if ($user->hasRole('admin')) {
            return true;
        }

        // Check in pivot table 'project_user'
        $hasRelation = $user->projects()->where('project_id', $projectId)->exists();

        // Optionally: Check by project name match if needed (menuTitle passed)
        $hasNameMatch = false;
        if ($menuTitle) {
            $userProjectNames = $user->projects()->pluck('name')->toArray(); // assuming 'name' column
            $hasNameMatch = in_array($menuTitle, $userProjectNames);
        }

        return $hasRelation || $hasNameMatch;
    }
}

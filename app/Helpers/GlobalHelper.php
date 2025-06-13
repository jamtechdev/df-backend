<?php

use App\Models\Menu;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

if (!function_exists('menu')) {
    function menu()
    {
        $menu = [];
        $userRoles = Auth::check()
            ? Auth::user()->roles()->pluck('name')->toArray()
            : [];

        $projects = Project::all();

        foreach ($projects as $project) {
            $menuData = Menu::where('project_id', $project->id)
                ->where(function ($query) use ($userRoles) {
                    foreach ($userRoles as $role) {
                        $query->orWhereJsonContains('permission', $role);
                    }
                })->get();


            $menu[] = (object)[
                'id' => 'project_' . $project->id,
                'title' => $project->name,
                'icon' => 'fas fa-folder',
                'uri' => '#',
                'children' => buildMenu($menuData)
            ];
        }

        return $menu;
    }

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


if (!function_exists('np_table')) {
    function np_table(string $tableName): string
    {
        return 'NP_' . $tableName;
    }
}

if (!function_exists('hasProjectAccess')) {
    function hasProjectAccess($projectId)
    {
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->projects()->where('project_id', $projectId)->exists();
    }
}

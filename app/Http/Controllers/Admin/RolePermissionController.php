<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Exception;

class RolePermissionController extends Controller
{
    public function getPermissions($roleName)
    {
        try {
            $role = Role::where('name', $roleName)->firstOrFail();

            $allPermissions = Permission::all();
            $rolePermissions = $role->permissions->pluck('name')->toArray();

            return response()->json([
                'all_permissions' => $allPermissions,
                'role_permissions' => $rolePermissions,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve permissions.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePermissions(Request $request, $roleName)
    {
        try {
            $role = Role::where('name', $roleName)->firstOrFail();

            $request->validate([
                'permissions' => 'array',
                'permissions.*' => 'string|exists:permissions,name',
            ]);

            $role->syncPermissions($request->permissions ?? []);

            return response()->json(['message' => 'Permissions updated successfully']);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to update permissions.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

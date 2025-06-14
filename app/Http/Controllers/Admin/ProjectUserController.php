<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ProjectUserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class ProjectUserController extends Controller
{
    protected $projectUserService;

    public function __construct(ProjectUserService $projectUserService)
    {
        $this->projectUserService = $projectUserService;
    }

    public function index()
    {
        try {
            return view('admin.project.user.index');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load project user index: ' . $e->getMessage()], 500);
        }
    }

    public function fetchData()
    {
        try {
            $data = $this->projectUserService->fetchData();

            $projectUserRoles = \DB::table('project_user')
                ->join('projects', 'projects.id', '=', 'project_user.project_id')
                ->join('users', 'users.id', '=', 'project_user.user_id')
                ->select(
                    'project_user.id',
                    'projects.id as project_id',
                    'projects.name as project_name',
                    'users.id as user_id',
                    \DB::raw("CONCAT(users.first_name, ' ', users.last_name) AS user_name"),
                    'users.email',
                    'project_user.role'
                )
                ->get();

            $users = \DB::table('users')
                ->select('id', 'first_name', 'last_name')
                ->get();

            return response()->json([
                'project_user_roles' => $projectUserRoles,
                'users' => $users,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch project user data: ' . $e->getMessage()], 500);
        }
    }


    public function getUserRoles($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $roles = $user->getRoleNames(); // Spatie roles
            return response()->json(['roles' => $roles]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch user roles: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'project_id' => 'required|exists:projects,id',
                'user_id' => 'required|exists:users,id',
            ]);

            $this->projectUserService->assignUserToProject($validated);
            return response()->json(['message' => 'User assigned successfully']);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') { // Duplicate entry error code
                return response()->json(['message' => 'This user is already assigned to this project.'], 422);
            }
            return response()->json(['message' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'project_id' => 'required|exists:projects,id',
                'user_id' => 'required|exists:users,id',
            ]);

            $this->projectUserService->syncUserToProject($validated, $id);
            return response()->json(['message' => 'User updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }



    public function destroy($project_id, $user_id)
    {
        try {
            $this->projectUserService->removeUserFromProject($project_id, $user_id);
            return response()->json(['message' => 'User removed successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error removing user: ' . $e->getMessage()], 500);
        }
    }
}

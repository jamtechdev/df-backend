<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\MemberDataTable;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Services\MemberService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    protected $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    public function index(MemberDataTable $dataTable)
    {
        try {
            $projects = Project::all();
            return $dataTable->render('admin.member.index', compact('projects'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load members index: ' . $e->getMessage()], 500);
        }
    }

    public function fetchData()
    {
        try {
            $members = $this->memberService->getAllMembers();
            return response()->json(['members' => $members]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch members: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'project_id' => 'required|exists:projects,id',
                'roles' => 'required|in:content_manager,manager',
            ]);
            
            $validated['password'] = Hash::make($validated['password']);
            
            $user = $this->memberService->createMember($validated);
            
         
            // Assign project to user with role
            $user->projects()->attach($validated['project_id'], ['role' => $validated['roles']]);

            return response()->json(['message' => 'Member created successfully', 'member' => $user]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create member: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $member = User::with(['projects' => function ($query) {
                $query->select('projects.id', 'projects.name')->withPivot('role');
            }])->findOrFail($id);

            $projectsWithRoles = $member->projects->map(function ($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'role' => $project->pivot->role ?? null,
                ];
            });

            return response()->json([
                'member' => $member,
                'projects_with_roles' => $projectsWithRoles,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch member data: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|string|min:6',
                'project_id' => 'required|exists:projects,id',
                'roles' => 'required|in:content_manager,manager',
            ]);

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user = $this->memberService->updateMember($user, $validated);

            $user->projects()->sync([$validated['project_id'] => ['role' => $validated['roles']]]);

            return response()->json(['message' => 'Member updated successfully', 'member' => $user]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update member: ' . $e->getMessage()], 500);
        }
    }

   public function destroy($id)
{
    try {
        $user = User::findOrFail($id);

        // First detach related projects from the pivot table
        $user->projects()->detach();

        // Then delete the user
        $this->memberService->deleteMember($user);

        return response()->json(['message' => 'Member deleted successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to delete member: ' . $e->getMessage()], 500);
    }
}

}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Services\MemberService;
use App\Models\User;

class MemberController extends Controller
{
    protected $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }


    public function index()
    {
        $projects = Project::all();
        return view('admin.member.index', compact('projects'));
    }

    public function fetchData()
    {
        $members = $this->memberService->getAllMembers();
        return response()->json(['members' => $members]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'project_id' => 'required|exists:projects,id',
            'roles' => 'required|array',
            'roles.*' => 'in:content_manager,manager',
        ]);

        $user = $this->memberService->createMember($validated);

        // Assign project to user with role
        $user->projects()->attach($validated['project_id'], ['role' => implode(',', $validated['roles'])]);

        return response()->json(['message' => 'Member created successfully', 'member' => $user]);
    }

    public function edit($id)
    {
        $member = User::with(['roles', 'projects' => function ($query) {
            $query->select('projects.id', 'projects.name')->withPivot('role'); // select only what you need and pivot role
        }])->findOrFail($id);

        // Get all projects with pivot roles
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
    }




    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'project_id' => 'required|exists:projects,id',
            'roles' => 'required|array',
            'roles.*' => 'in:content_manager,manager',
        ]);


        $user = $this->memberService->updateMember($user, $validated);

        if (isset($validated['project_id']) && isset($validated['roles'])) {
            // Sync project with role
            $user->projects()->sync([$validated['project_id'] => ['role' => implode(',', $validated['roles'])]]);
        }

        return response()->json(['message' => 'Member updated successfully', 'member' => $user]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->memberService->deleteMember($user);
        return response()->json(['message' => 'Member deleted successfully']);
    }
}

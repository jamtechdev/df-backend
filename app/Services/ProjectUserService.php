<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProjectUserService
{
    public function fetchData()
    {
        $projects = Project::with('users')->get();
        $users = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        })->get();

        return [
            'projects' => $projects,
            'users' => $users
        ];
    }

    public function assignUserToProject(array $data)
    {
        $project = Project::findOrFail($data['project_id']);
        $user = User::findOrFail($data['user_id']);

        $roles = $user->roles->pluck('name')->toArray();

        if (empty($roles)) {
            throw new \Exception('User does not have any role assigned.');
        }

        foreach ($roles as $roleName) {
            $project->users()->attach($user->id, ['role' => $roleName]);
        }
    }

    public function syncUserToProject($data, $id)
    {

        $project = Project::findOrFail($data['project_id']);

        $user = User::findOrFail($data['user_id']);

        $roles = $user->roles->pluck('name')->toArray();

        if (empty($roles)) {
            throw new \Exception('User does not have any role assigned.');
        }

        $pivotRecord = DB::table('project_user')->where('id', $id)->first();

        if (!$pivotRecord) {
            throw new \Exception('Pivot record not found.');
        }
        $roleString = implode(',', $roles);
        DB::table('project_user')->where('id', $id)->update(['user_id' => $user->id, 'project_id' => $project->id, 'role' => $roleString]);
    }




    public function removeUserFromProject($projectId, $userId)
    {
        $project = Project::findOrFail($projectId);
        $project->users()->detach($userId);
    }
}

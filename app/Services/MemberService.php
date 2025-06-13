<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;

class MemberService
{
    // Allowed roles for member management
    protected array $allowedRoles = ['content_manager', 'manager'];

    /**
     * Get all members with allowed roles only.
     */
    public function getAllMembers()
    {
        return User::whereHas('roles', function ($query) {
            $query->whereIn('name', $this->allowedRoles);
        })->with('roles')->get();
    }

    /**
     * Create a new member.
     */
    public function createMember(array $data): User
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => Hash::make($data['password']),
        ]);

        $this->assignRoles($user, $data['roles'] ?? []);

        return $user;
    }

    /**
     * Update an existing member.
     */
    public function updateMember(User $user, array $data): User
    {
        // Only fill allowed fields
        $updateData = Arr::only($data, ['first_name', 'last_name', 'email']);

        // If password is set and not empty, hash and include it
        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        // Sync roles if provided
        if (isset($data['roles'])) {
            $this->assignRoles($user, $data['roles']);
        }

        return $user;
    }

    /**
     * Delete a member.
     */
    public function deleteMember(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Assign only allowed roles to the user.
     */
    protected function assignRoles(User $user, array $roles): void
    {
        $filteredRoles = array_intersect($roles, $this->allowedRoles);
        $user->syncRoles($filteredRoles);
    }
}

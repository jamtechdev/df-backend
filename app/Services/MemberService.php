<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;

class MemberService
{
    /**
     * Allowed roles for members.
     */
    private const ALLOWED_ROLES = ['content_manager', 'manager'];

    /**
     * Get all members with allowed roles.
     */
    public function getAllMembers(): \Illuminate\Support\Collection
    {
        return User::whereHas('roles', function ($query) {
            $query->whereIn('name', self::ALLOWED_ROLES);
        })->with('roles')->get();
    }

    /**
     * Create a new member with validated data and assign roles.
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
     * Update an existing member's data and roles.
     */
    public function updateMember(User $user, array $data): User
    {
        $updateData = Arr::only($data, ['first_name', 'last_name', 'email']);

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        if (array_key_exists('roles', $data)) {
            $this->assignRoles($user, $data['roles']);
        }

        return $user;
    }

    /**
     * Delete the specified member.
     */
    public function deleteMember(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Assign only allowed roles to the user.
     */
    private function assignRoles(User $user, array|string $roles): void
    {
        $roles = (array) $roles; // Ensure $roles is always an array
        $validRoles = array_intersect($roles, self::ALLOWED_ROLES);
        $user->syncRoles($validRoles);
    }
}

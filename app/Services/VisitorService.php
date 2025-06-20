<?php

namespace App\Services;

use App\Models\User;

class VisitorService
{
    public function getVisitors()
    {
        return User::role('reader');
    }

    public function deleteVisitor($id)
    {
        $visitor = User::findOrFail($id);
        $visitor->delete();
    }

    public function toggleIsBlocked($id)
    {
        $visitor = User::findOrFail($id);
        $visitor->is_blocked = !$visitor->is_blocked;
        $visitor->save();
        return $visitor;
    }
}

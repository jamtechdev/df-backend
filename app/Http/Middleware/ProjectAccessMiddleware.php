<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Checks if the authenticated user has access to the project specified in the route parameter.
     * Assumes the route has a 'project' or 'project_id' parameter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        // Admin users have full access
        if ($user->hasRole('admin')) {
            return $next($request);
        }

        // Get project ID from route parameters
        $projectId = $request->route('project') ?? $request->route('project_id');

        if (!$projectId) {
            // If no project ID in route, deny access
            abort(403, 'Project ID not specified.');
        }

        // Check if user has access to the project via pivot table
        $hasAccess = $user->projects()->where('project_id', $projectId)->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized access to this project.');
        }

        return $next($request);
    }
}

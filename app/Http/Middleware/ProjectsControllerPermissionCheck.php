<?php

namespace App\Http\Middleware;

use App\Project;
use Closure;

class ProjectsControllerPermissionCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authUser = auth()->user();
        $project = Project::find($request->route('id'));
        if ($request->route('id') && !$project) {
            return response()->json(['message' => 'No project found with the given id.'], 422);
        }
        if (in_array(strtolower($request->method()), ['patch', 'delete']) && !($project->user_id == $authUser->id || $authUser->is_admin)) {
            return response()->json(['message' => 'Access is not allowed.'], 403);
        }
        return $next($request);
    }
}

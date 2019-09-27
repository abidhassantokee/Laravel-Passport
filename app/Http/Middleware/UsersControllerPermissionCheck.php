<?php

namespace App\Http\Middleware;

use Closure;

class UsersControllerPermissionCheck
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
        if (in_array(strtolower($request->method()), ['post', 'delete']) && !$authUser->is_admin) {
            return response()->json(['message' => 'Access is not allowed.'], 403);
        }
        if ($request->isMethod('patch') && !($request->route('id') == $authUser->id || $authUser->is_admin)) {
            return response()->json(['message' => 'Access is not allowed.'], 403);
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserIsProjectMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
      $project = $request->route()->parameter('project');

      if ($project->user_id != auth()->id() && ! $project->members->contains(auth()->user())) {
        return response()->json([
          'is_error' => true,
          'message' => 'You are not authorized for this action.'
        ]);
      }

      return $next($request);
    }
}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;

class ProjectUserController extends Controller
{
    public function store(Project $project)
    {
      if ($project->user_id != auth()->id()) {
        abort(403); // TODO: make this an error message
      }

      $user = User::where('email', request('email'))->first();

      if (! $user) {
        abort(422); // TODO: make this an error message
      }

      if ($project->members->contains($user)) {
        abort(422); // TODO: make this an error message
      }

      $project->members()->attach($user->id);

      return $project->members;
    }

    public function index(Project $project)
    {
      if (! $project->members->contains(auth()->user())) {
        abort(403); // TODO: make this an error message
      }

      return $project->members;
    }

    public function destroy(Project $project)
    {
      if ($project->user_id != auth()->id()) {
        abort(422); // TODO: make an appropriate error message
      }

      $user = User::where('email', request('email'))->first();

      if (!$user || !$project->members->contains($user)) {
        abort(422); // TODO: make an appropriate error message
      }

      if ($project->user_id == $user->id) {
        abort(422); // // TODO: make an appropriate error message
      }

      $project->members()->detach($user->id);

      return 'Success';
    }
}

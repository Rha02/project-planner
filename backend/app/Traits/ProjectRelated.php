<?php

namespace App\Traits;
use App\Models\Project;

trait ProjectRelated {
  protected function authorized(Project $project)
  {
    if ($project->members->contains(auth()->user())) {
      return response()->json([
        'is_error' => true,
        'message' => 'You are not authorized for this action.'
      ]);
    }

    return;
  }

  protected function isOwner(Project $project)
  {
    if ($this->user->id != $project->user_id) {
      return response()->json([
        'is_error' => true,
        'message' => 'You are not authorized for this action.'
      ]);
    }

    return;
  }
}

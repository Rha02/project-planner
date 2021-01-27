<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Sequence;
use App\Models\Project;
use App\Http\Controllers\Controller;

class SequenceController extends Controller
{
  public function show(Project $project, Task $task)
  {
    $this->authorized($project);

    // pass
  }

  public function store(Project $project)
  {
    $this->authorized($project);

    $attributes = request()->validate([
      'to_task_id' => 'integer|exists:tasks,id',
      'from_task_id' => 'integer|exists:tasks,id'
    ]);

    if ($attributes['to_task_id'] == $attributes['from_task_id']) {
      return response()->json([
        'is_error' => true,
        'errors' => 'Invalid Sequence'
      ]);
    }

    $sequence = Sequence::create($attributes);

    return $sequence->toArray();
  }

  public function destroy(Project $project, Task $task)
  {
    $this->authorized($project);

    // pass
  }

  protected function authorized($project)
  {
    if (! $project->members->contains(auth()->user())) {
      return response()->json([
        'is_error' => true,
        'message' => 'You are not authorized for this action.'
      ]);
    }

    return;
  }
}

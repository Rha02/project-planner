<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Sequence;
use App\Models\Project;
use App\Models\Task;
use App\Http\Controllers\Controller;

class SequenceController extends Controller
{
  public function store(Project $project)
  {
    $this->authorized($project);

    $attributes = request()->validate([
      'to_task_id' => 'integer|exists:tasks,id|different:from_task_id',
      'from_task_id' => 'integer|exists:tasks,id|different:to_task_id'
    ]);

    $isInDatabase = Sequence::whereIn('to_task_id', $attributes)
                              ->whereIn('from_task_id', $attributes)
                              ->get()->toArray();

    if ($isInDatabase) {
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

    Sequence::where('to_task_id', $task->id)->orWhere('from_task_id', $task->id)->delete();

    return 'Success';
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

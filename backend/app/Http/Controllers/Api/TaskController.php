<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Project;
use App\Models\Goal;
use App\Models\Task;
use App\Traits\ProjectRelated;

class TaskController extends Controller
{
    use ProjectRelated;

    public function index(Project $project, Goal $goal)
    {
      $errorRes = $this->authorized($project);

      if ($errorRes) {
        return $errorRes;
      }

      return $goal->tasks->toArray();
    }

    public function store(Project $project, Goal $goal)
    {
      $errorRes = $this->authorized($project);

      if ($errorRes) {
        return $errorRes;
      }

      $validator = Validator::make(request()->all(), [
        'user_id' => ['nullable', Rule::in($project->members->pluck('id'))],
        'body' => 'required|string|max:3000',
        'status' => ['nullable', Rule::in(['unsigned', 'not_started', 'in_progress', 'complete'])]
      ]);

      if ($validator->fails()) {
        return response()->json([
          'is_error' => true,
          'errors' => $validator->errors()
        ]);
      }

      $attributes = $validator->validated();

      $attributes['goal_id'] = $goal->id;

      if (! $attributes['status']) {
        $attributes['status'] = 'unsigned';
      }

      $task = Task::create($attributes);

      return $task->fresh();
    }

    public function destroy(Project $project, Goal $goal, Task $task)
    {
      $errorRes = $this->authorized($project);

      if ($errorRes) {
        return $errorRes;
      }

      $task->delete();

      return 'Success';
    }

    public function update(Project $project, Goal $goal, Task $task)
    {
      $errorRes = $this->authorized($project);

      if ($errorRes) {
        return $errorRes;
      }

      $validator = Validator::make(request()->all(), [
        'user_id' => ['nullable', Rule::in($project->members->pluck('id'))],
        'body' => 'required|string|max:3000',
        'status' => ['nullable', Rule::in(['unsigned', 'not_started', 'in_progress', 'complete'])]
      ]);

      if ($validator->fails()) {
        return response()->json([
          'is_error' => true,
          'errors' => $validator->errors()
        ]);
      }

      $attributes = $validator->validated();

      if (! $attributes['status']) {
        $attributes['status'] = $task->status;
      }

      $task->update($attributes);

      return $task->toArray();
    }
}

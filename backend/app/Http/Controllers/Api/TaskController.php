<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Project;
use App\Models\Task;

class TaskController extends Controller
{
    protected $user;

    public function __construct()
    {
      $this->user = auth()->user();
    }

    public function index(Project $project)
    {
      $this->authorized();

      $tasks = $project->tasks->toArray();

      return $tasks;
    }

    public function store(Project $project)
    {
      $this->authorized();

      $validator = Validator::make(request()->all(), [
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

      $attributes['project_id'] = $project->id;

      if (! $attributes['status']) {
        $attributes['status'] = 'unsigned';
      }

      $task = Task::create($attributes);

      return $task->toArray();
    }

    public function destroy(Project $project, Task $task)
    {
      $this->authorized();

      $task->delete();

      return 'Success';
    }

    public function update(Project $project, Task $task)
    {
      $this->authorized();

      $validator = Validator::make(request()->all(), [
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

    protected function authorized()
    {
      if (! $project->members->contains($this->user)) {
        abort(403);
      }

      return
    }
}

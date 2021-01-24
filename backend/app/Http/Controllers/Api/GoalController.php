<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class GoalController extends Controller
{
    public function index(Project $project)
    {
      $this->authorized($project);

      return $project->goals->toArray();
    }

    public function store(Project $project)
    {
      $this->authorized($project);

      $validator = Validator::make(request()->all(), [
        'title' => 'required|string|max:3000',
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

      $goal = Goal::create($attributes);

      return $goal->toArray();
    }

    public function destroy(Project $project, Goal $goal)
    {
      $this->authorized($project);

      $goal->delete();

      return 'Success';
    }

    public function update(Project $project, Goal $goal)
    {
      $this->authorized($project);

      $validator = Validator::make(request()->all(), [
        'title' => 'required|string|max:3000',
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
        $attributes['status'] = $goal->status;
      }

      $goal->update($attributes);

      return $goal->toArray();
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

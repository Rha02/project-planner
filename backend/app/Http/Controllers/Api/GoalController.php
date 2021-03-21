<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Traits\ProjectRelated;

class GoalController extends Controller
{
    use ProjectRelated;

    public function index(Project $project)
    {
      $errorRes = $this->authorized($project);

      if ($errorRes) {
        return $errorRes;
      }

      return $project->goals->toArray();
    }

    public function store(Project $project)
    {
      $errorRes = $this->authorized($project);

      if ($errorRes) {
        return $errorRes;
      }

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
      $errorRes = $this->authorized($project);

      if ($errorRes) {
        return $errorRes;
      }

      $goal->delete();

      return 'Success';
    }

    public function update(Project $project, Goal $goal)
    {
      $errorRes = $this->authorized($project);

      if ($errorRes) {
        return $errorRes;
      }

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

    public function show(Project $project, Goal $goal)
    {
      $errorRes = $this->authorized($project);

      if ($errorRes) {
        return $errorRes;
      }

      return $goal->toArray();
    }
}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    public function index()
    {
      $projects = Project::all()->filter(function ($project) {
        return $project->members->contains(auth()->id());
      });

      return $projects->toArray();
    }

    public function show(Project $project)
    {
      if (! $project->members->contains(auth()->user())) {
        abort(403, 'This action is forbidden');
      }

      return $project->toArray();
    }

    public function destroy(Project $project)
    {
      if (auth()->id() != $project->user_id) {
        abort(403, 'This action is forbidden');
      }

      $project->delete();

      return 'Success';
    }

    public function store()
    {
      $project = Project::create([
        'title' => 'Untitled',
        'description' => 'No description',
        'user_id' => auth()->id()
      ]);

      $project->members()->attach(auth()->id());

      return $project->toArray();
    }

    public function update(Project $project)
    {
      if (! $project->members->contains(auth()->user())) {
        abort(403, 'This action is forbidden');
      }

      $attributes = $this->validateProjectRequest();

      $project->update($attributes);

      return $project->toArray();
    }

    protected function validateProjectRequest()
    {
      $validator = Validator::make(request()->all(), [
        'title' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:3000'
      ]);

      if ($validator->fails()) {
        return response()->json([
          'is_error' => true,
          'errors' => $validator->errors()
        ]);
      }

      $attributes = $validator->validated();

      if (! $attributes['title']) {
        $attributes['title'] = 'Untitled';
      }

      if (! $attributes['description']) {
        $attributes['description'] = 'No description';
      }

      return $attributes;
    }
}

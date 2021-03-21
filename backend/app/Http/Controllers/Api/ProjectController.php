<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    protected $user;

    public function __construct()
    {
      $this->middleware('member')->only(['show', 'update']);

      $this->user = auth()->user();
    }

    public function index()
    {
      $ownedProjects = $this->user->projects()->with('members:id')->get();

      $sharedProjects = Project::with('members:id')->get()->filter(function ($project) {
        return $project->members->contains($this->user) && $this->user->id != $project->user_id;
      });

      return response()->json([
        'ownedProjects' => $ownedProjects->toArray(),
        'sharedProjects' => $sharedProjects->toArray()
      ]);
    }

    public function show(Project $project)
    {
      return $project->toArray();
    }

    public function destroy(Project $project)
    {
      if ($this->user->id != $project->user_id) {
        return response()->json([
          'is_error' => true,
          'message' => 'You are not authorized for this action.'
        ]);
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

      $project->members()->attach($this->user->id);

      return $project->toArray();
    }

    public function update(Project $project)
    {
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

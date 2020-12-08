<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;

class ProjectUserController extends Controller
{
    protected $user;

    public function __construct()
    {
      $this->user = auth()->user();
    }

    public function store(Project $project)
    {
      $this->authorized();

      $member = User::where('email', request('email'))->first();

      if (! $member) {
        abort(422); // TODO: make this an error message
      }

      if ($project->members->contains($member)) {
        abort(422); // TODO: make this an error message
      }

      $project->members()->attach($member->id);

      return $project->members;
    }

    public function index(Project $project)
    {
      if (! $project->members->contains($this->user->id)) {
        abort(403); // TODO: make this an error message
      }

      return $project->members;
    }

    public function destroy(Project $project)
    {
      $this->authorized();

      $member = User::where('email', request('email'))->first();

      if (!$member || !$project->members->contains($member)) {
        abort(422); // TODO: make an appropriate error message
      }

      if ($project->user_id == $member->id) {
        abort(422); // // TODO: make an appropriate error message
      }

      $project->members()->detach($member->id);

      return 'Success';
    }

    protected function authorized()
    {
      if ($project->user_id != $this->user->id) {
        abort(422); // TODO: make an appropriate error message
      }

      return;
    }
}

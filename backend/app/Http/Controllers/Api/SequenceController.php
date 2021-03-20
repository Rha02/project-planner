<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Sequence;
use App\Models\Project;
use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Traits\ProjectRelated;

class SequenceController extends Controller
{
  use ProjectRelated;

  protected $checkingSequences;

  protected $sequences;

  public function __construct()
  {
    $this->checkingSequences = collect([]);

    $this->sequences = Sequence::all();
  }

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

    if ($this->checkIfLoops($attributes)) {
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

  protected function checkIfLoops($currSequence)
  {
    if ($this->checkingSequences->contains($currSequence)) {
      return True;
    }

    $this->checkingSequences->push($currSequence);

    $this->sequences->push($currSequence);

    foreach ($this->sequences->where('to_task_id', $currSequence['from_task_id']) as $sequence) {
      return $this->checkIfLoops($sequence);
    }

    return False;
  }
}

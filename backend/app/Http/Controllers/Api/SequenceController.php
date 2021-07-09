<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Sequence;
use App\Traits\TaskDepthCalculator;

class SequenceController extends Controller
{
    use TaskDepthCalculator;

    protected $checkingSequences;

    protected $sequences;

    public function __construct()
    {
        $this->middleware('member');

        $this->checkingSequences = collect([]);

        $this->sequences = Sequence::all();
    }

    public function store(Project $project)
    {
        // Validating sequence
        $attributes = request()->validate([
            'to_task_id' => 'integer|exists:tasks,id|different:from_task_id',
            'from_task_id' => 'integer|exists:tasks,id|different:to_task_id'
        ]);

        $isInDatabase = Sequence::whereIn('to_task_id', $attributes)
            ->whereIn('from_task_id', $attributes)->first();

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

        // Storing the sequence and returning it
        $sequence = Sequence::create($attributes);

        // Updating depth field for the affected task
        $this->updateDepth($attributes['to_task_id']);

        return $sequence->toArray();
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

    public function destroy(Project $project, $taskId)
    {
        $this->updateSequence($taskId);

        Sequence::where('to_task_id', $taskId)->orWhere('from_task_id', $taskId)->delete();

        return 'Success';
    }
}

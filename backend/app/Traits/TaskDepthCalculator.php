<?php

namespace App\Traits;
use App\Models\Task;

trait TaskDepthCalculator {
    protected $tasks;

    // Use in case when creating a new sequence between two tasks
    public function updateDepth($taskId) {
        $this->tasks = Task::all()->keyBy('id');

        $task = $this->tasks->get($taskId);

        $task->depth = $task->prevTasks->pluck('depth')->push(0)->max() + 1;

        $this->tasks->forget($taskId);

        $this->tasks->put($taskId, $task);

        $this->updateNextTasksDepth($task);

        Task::find($task->id)->update(['depth' => $task->depth]);

        return $task->depth;
    }

    // Use in case where a task is to be broken from the sequence
    public function updateSequence($taskId) {
        $this->tasks = Task::all()->keyBy('id');

        $task = $this->tasks->get($taskId);

        foreach ($task->nextTasks as $t) {
            $t = $this->tasks->get($t->id);

            $t->prevTasks = $t->prevTasks->filter(function ($prevTask) use ($task) {
                return $prevTask->id != $task->id;
            });

            $this->tasks->forget($t->id);

            $this->tasks->put($t->id, $t);
        }

        $this->updateNextTasksDepth($task);

        $task->depth = 1;

        Task::find($task->id)->update(['depth' => $task->depth]);
    }

    // updates the depth field of next tasks in the sequence
    protected function updateNextTasksDepth($t) {
        foreach ($t->nextTasks as $task) {
            $task = $this->tasks->get($task->id);

            $depth = $task->prevTasks->map(function ($task) {
                return $this->tasks->get($task->id)->depth;
            })->max() + 1;

            $task->depth = $depth;

            $this->tasks->forget($task->id);

            $this->tasks->put($task->id, $task);

            $this->updateNextTasksDepth($task);

            Task::find($task->id)->update(['depth' => $task->depth]);
        }
    }
}

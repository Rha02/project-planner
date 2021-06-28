<?php

namespace App\Traits;
use App\Models\Task;

trait TaskDepthCalculator {
    protected $tasks;

    public function updateDepth($taskId) {
        $this->tasks = Task::all()->keyBy('id');

        $task = $this->tasks->get($taskId);

        return $this->depthCalculator($task);
    }

    protected function depthCalculator($task) {
        foreach ($task->prevTasks as $prevTask) {
            $prevTask = $this->tasks->get($prevTask->id);

            $task->depth = $prevTask->depth;
        }

        $task->depth += 1;

        $task->update();

        return $task->depth;
    }

    public function updateSequence($taskId) {
        $this->tasks = Task::all()->keyBy('id');

        $task = $this->tasks->get($taskId);

        $this->updateNextTasksDepth($task);

        $task->depth = 1;

        $task->update();
    }

    // updates the depth field of next tasks in the sequence
    protected function updateNextTasksDepth($t) {
        foreach ($t->nextTasks as $task) {
            $task = $this->tasks->get($task->id);

            $depth = $task->prevTasks->filter(function ($task) use ($t) {
                return $task->id != $t->id;
            })->pluck('depth')->push(0)->max();

            $task->depth = $depth + 1;

            $task->update();

            $this->updateNextTasksDepth($task);
        }
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use App\Models\Goal;
use App\Models\Project;

class SequenceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreatingSequence()
    {
      $this->signIn();

      $project = Project::factory()->create([
        'user_id' => auth()->id()
      ]);

      $project->members()->attach(auth()->id());

      $goal = Goal::factory()->create([
        'project_id' => $project->id
      ]);

      $tasks = Task::factory()->count(2)->create([
        'goal_id' => $goal->id
      ])->toArray();

      $attributes = [
        'from_task_id' => $tasks[0]['id'],
        'to_task_id' => $tasks[1]['id']
      ];

      $this->assertDatabaseMissing('sequences', $attributes);

      $this->post("/api/projects/{$goal->project_id}/sequence", $attributes);

      $this->assertDatabaseHas('sequences', $attributes);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Project;
use App\Models\Goal;
use App\Models\Task;

class TaskTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function testOwnerCanCreateTask()
    {
      $this->signIn();

      $project = Project::factory()->create([
        'user_id' => auth()->id()
      ]);

      $project->members()->attach(auth()->id());

      $goal = Goal::factory()->create([
        'project_id' => $project->id
      ]);

      $this->post("/api/projects/{$project->id}/goals/{$goal->id}/tasks", [
        'body' => 'body1',
        'status' => 'not_started',
        'user_id' => auth()->id()
      ]);

      $this->assertDatabaseHas('tasks', [
        'body' => 'body1',
        'status' => 'not_started',
        'user_id' => auth()->id()
      ]);
    }

    public function testMemberCanCreateTask()
    {
      $this->signIn();

      $project = Project::factory()->create();

      $project->members()->attach(auth()->id());

      $goal = Goal::factory()->create([
        'project_id' => $project->id
      ]);

      $this->post("/api/projects/{$project->id}/goals/{$goal->id}/tasks", [
        'body' => 'body2',
        'status' => 'in_progress',
        'user_id' => auth()->id()
      ]);

      $this->assertDatabaseHas('tasks', [
        'body' => 'body2',
        'status' => 'in_progress',
        'user_id' => auth()->id()
      ]);
    }

    public function testUnauthorizedUserCannotCreateTask()
    {
      $project = Project::factory()->create();

      $goal = Goal::factory()->create([
        'project_id' => $project->id
      ]);

      $this->signIn();

      $this->post("/api/projects/{$project->id}/goals/{$goal->id}/tasks", [
        'body' => 'body1',
        'status' => 'not_started'
      ]);

      $this->assertDatabaseMissing('tasks', [
        'body' => 'body1',
        'status' => 'not_started'
      ]);
    }

    public function testOwnerCanUpdateTask()
    {
      $this->signIn();

      $project = Project::factory()->create([
        'user_id' => auth()->id()
      ]);

      $project->members()->attach(auth()->id());

      $goal = Goal::factory()->create([
        'project_id' => $project->id
      ]);

      $task = Task::factory()->create([
        'goal_id' => $goal->id
      ]);

      $this->patch("/api/projects/{$project->id}/goals/{$goal->id}/tasks/{$task->id}", [
        'body' => 'task1',
        'status' => ''
      ]);

      $this->assertDatabaseHas('tasks', ['body' => 'task1']);
    }

    public function testMemberCanUpdateTask()
    {
      $this->signIn();

      $project = Project::factory()->create();

      $project->members()->attach(auth()->id());

      $goal = Goal::factory()->create([
        'project_id' => $project->id
      ]);

      $task = Task::factory()->create([
        'goal_id' => $goal->id
      ]);

      $this->patch("/api/projects/{$project->id}/goals/{$goal->id}/tasks/{$task->id}", [
        'body' => 'task1',
        'status' => ''
      ]);

      $this->assertDatabaseHas('tasks', ['body' => 'task1']);
    }

    public function testUnauthorizedUserCannotUpdateTask()
    {
      $project = Project::factory()->create();

      $goal = Goal::factory()->create([
        'project_id' => $project->id
      ]);

      $task = Task::factory()->create([
        'goal_id' => $goal->id
      ]);

      $this->signIn();

      $this->patch("/api/projects/{$project->id}/goals/{$goal->id}/tasks/{$task->id}", [
        'body' => 'task1',
        'status' => 'not_started'
      ]);

      $this->assertDatabaseMissing('tasks', [
        'body' => 'task1',
        'status' => 'not_started'
      ]);
    }

    public function testOwnerCanDeleteTask()
    {
      $this->signIn();

      $project = Project::factory()->create([
        'user_id' => auth()->id()
      ]);

      $project->members()->attach(auth()->id());

      $goal = Goal::factory()->create([
        'project_id' => $project->id
      ]);

      $task = Task::factory()->create([
        'goal_id' => $goal->id
      ]);

      $this->delete("/api/projects/{$project->id}/goals/{$goal->id}/tasks/{$task->id}");

      $this->assertDatabaseCount('tasks', 0);
    }

    public function testMemberCanDeleteTask()
    {
      $this->signIn();

      $project = Project::factory()->create();

      $project->members()->attach(auth()->id());

      $goal = Goal::factory()->create([
        'project_id' => $project->id
      ]);

      $task = Task::factory()->create([
        'goal_id' => $goal->id
      ]);

      $this->delete("/api/projects/{$project->id}/goals/{$goal->id}/tasks/{$task->id}");

      $this->assertDatabaseCount('tasks', 0);
    }

    public function testUnauthorizedUserCannotDeleteTask()
    {
      $project = Project::factory()->create();

      $goal = Goal::factory()->create([
        'project_id' => $project->id
      ]);

      $task = Task::factory()->create([
        'goal_id' => $goal->id
      ]);

      $this->signIn();

      $this->delete("/api/projects/{$project->id}/goals/{$goal->id}/tasks/{$task->id}");

      $this->assertDatabaseCount('tasks', 1);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Project;
use App\Models\Goal;

class GoalTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function testOwnerCanCreateGoal()
    {
      $this->signIn();

      $project = Project::factory()->create([
        'user_id' => auth()->id()
      ]);

      $this->assertDatabaseMissing('goals', [
        'title' => 'goal1',
        'status' => 'not_started'
      ]);

      $this->post("/api/projects/{$project->id}/goals", [
        'title' => 'goal1',
        'status' => 'not_started'
      ]);

      $this->assertDatabaseHas('goals', [
        'title' => 'goal1',
        'status' => 'not_started'
      ]);
    }

    public function testMemberCanCreateGoal()
    {
      $this->signIn();

      $project = Project::factory()->create();

      $project->members()->attach(auth()->id());

      $this->assertDatabaseMissing('goals', [
        'title' => 'goal1',
        'status' => 'not_started'
      ]);

      $this->post("/api/projects/{$project->id}/goals", [
        'title' => 'goal1',
        'status' => 'not_started'
      ]);

      $this->assertDatabaseHas('goals', [
        'title' => 'goal1',
        'status' => 'not_started'
      ]);
    }

    public function testGuestsCannotCreateGoal()
    {
      $project = Project::factory()->create();

      $this->assertDatabaseMissing('goals', [
        'title' => 'goal1',
        'status' => 'not_started'
      ]);

      $this->post("/api/projects/{$project->id}/goals", [
        'title' => 'goal1',
        'status' => 'not_started'
      ]);

      $this->assertDatabaseMissing('goals', [
        'title' => 'goal1',
        'status' => 'not_started'
      ]);
    }

    public function testUnauthorizedUserCannotCreateGoal()
    {
      $project = Project::factory()->create();

      $this->assertDatabaseMissing('goals', [
        'title' => 'goal1',
        'status' => 'not_started'
      ]);

      $this->signIn();

      $this->post("/api/projects/{$project->id}/goals", [
        'title' => 'goal1',
        'status' => 'not_started'
      ]);

      $this->assertDatabaseMissing('goals', [
        'title' => 'goal1',
        'status' => 'not_started'
      ]);
    }

    public function testOwnderCanUpdateGoal()
    {
      $this->signIn();

      $project = Project::factory()->create([
        'user_id' => auth()->id()
      ]);

      $goal = Goal::factory()->create([
        'project_id' => $project->id
      ]);

      $this->patch("/api/projects/{$project->id}/goals/{$goal->id}", [
        'title' => 'goal2',
        'status' => 'in_progress'
      ]);

      $this->assertDatabaseHas('goals', [
        'title' => 'goal2',
        'status' => 'in_progress'
      ]);
    }

    public function testMemberCanUpdateGoal()
    {
      $this->signIn();

      $project = Project::factory()->create();

      $goal = Goal::factory()->create([
        'project_id' => $project->id
      ]);

      $project->members()->attach(auth()->id());

      $this->patch("/api/projects/{$project->id}/goals/{$goal->id}", [
        'title' => 'goal3',
        'status' => 'complete'
      ]);

      $this->assertDatabaseHas('goals', [
        'title' => 'goal3',
        'status' => 'complete'
      ]);
    }

    public function testGuestCannotUpdateGoal()
    {
      $project = Project::factory()->create();

      $goal = Goal::factory()->create();

      $this->patch("/api/projects/{$project->id}/goals/{$goal->id}", [
        'title' => 'goal2',
        'status' => 'in_progress'
      ]);

      $this->assertDatabaseMissing('goals', [
        'title' => 'goal2',
        'status' => 'in_progress'
      ]);
    }

    public function testUnauthorizedUserCannotUpdateGoal()
    {
      $project = Project::factory()->create();

      $goal = Goal::factory()->create();

      $this->signIn();

      $this->patch("/api/projects/{$project->id}/goals/{$goal->id}", [
        'title' => 'goal2',
        'status' => 'in_progress'
      ]);

      $this->assertDatabaseMissing('goals', [
        'title' => 'goal2',
        'status' => 'in_progress'
      ]);
    }

    public function testOwnerCanDeleteGoals()
    {
      $this->signIn();

      $project = Project::factory()->create([
        'user_id' => auth()->id()
      ]);

      $goal = Goal::factory()->create();

      $this->delete("/api/projects/{$project->id}/goals/{$goal->id}");

      $this->assertDatabaseMissing('goals', [
        'title' => $goal->title,
        'status' => $goal->status
      ]);
    }

    public function testMemberCanDeleteGoals()
    {
      $this->signIn();

      $project = Project::factory()->create();

      $goal = Goal::factory()->create();

      $project->members()->attach(auth()->id());

      $goal = Goal::factory()->create();

      $this->delete("/api/projects/{$project->id}/goals/{$goal->id}");

      $this->assertDatabaseMissing('goals', [
        'title' => $goal->title,
        'status' => $goal->status
      ]);
    }

    public function testGuestCannotDeleteGoal()
    {
      $project = Project::factory()->create();

      $goal = Goal::factory()->create([
        'project_id' => $project->id
      ]);

      $this->delete("/api/projects/{$project->id}/goals/{$goal->id}");

      $this->assertDatabaseHas('goals', [
        'title' => $goal->title,
        'status' => $goal->status
      ]);
    }

    public function testUserCannotDeleteGoal()
    {
      $project = Project::factory()->create();

      $goal = Goal::factory()->create([
        'project_id' => $project->id
      ]);

      $this->signIn();

      $this->delete("/api/projects/{$project->id}/goals/{$goal->id}");

      $this->assertDatabaseHas('goals', [
        'title' => $goal->title,
        'status' => $goal->status
      ]);
    }
}

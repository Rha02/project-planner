<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Project;

class ProjectMemberTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function testOwnerCanAddMembers()
    {
      $this->signIn();

      $project = Project::factory()->create([
        'user_id' => auth()->id()
      ]);

      $user = User::factory()->create();

      $this->post("/api/projects/{$project->id}/members", [
        'email' => $user->email
      ]);

      $this->assertDatabaseHas('project_user', [
        'project_id' => $project->id,
        'user_id' => $user->id
      ]);
    }

    public function testUnauthorizedUserCannotAddMembers()
    {
      $project = Project::factory()->create();

      $user = User::factory()->create();

      $this->signIn();

      $this->post("/api/projects/{$project->id}/members", [
        'email' => $user->email
      ]);

      $this->assertDatabaseMissing('project_user', [
        'project_id' => $project->id,
        'user_id' => $user->id
      ]);
    }

    public function testGuestCannotAddMembers()
    {
      $project = Project::factory()->create();

      $user = User::factory()->create();

      $this->post("/api/projects/{$project->id}/members", [
        'email' => $user->email
      ]);

      $this->assertDatabaseMissing('project_user', [
        'project_id' => $project->id,
        'user_id' => $user->id
      ]);
    }

    public function testMemberCannotAddMembers()
    {
      $project = Project::factory()->create();

      $user = User::factory()->create();


      $this->signIn();

      $project->members()->attach(auth()->id());

      $this->post("/api/projects/{$project->id}/members", [
        'email' => $user->email
      ]);

      $this->assertDatabaseMissing('project_user', [
        'project_id' => $project->id,
        'user_id' => $user->id
      ]);
    }

    public function testOwnerCanRemoveMembers()
    {
      $this->signIn();

      $project = Project::factory()->create([
        'user_id' => auth()->id()
      ]);

      $user = User::factory()->create();

      $project->members()->attach($user->id);

      $this->delete("/api/projects/{$project->id}/members", [
        'email' => $user->email
      ]);

      $this->assertDatabaseMissing('project_user', [
        'project_id' => $project->id,
        'user_id' => $user->id
      ]);
    }

    public function testUnauthorizedUserCannotRemoveMembers()
    {
      $project = Project::factory()->create();

      $user = User::factory()->create();

      $project->members()->attach($user->id);

      $this->signIn();

      $this->delete("/api/projects/{$project->id}/members", [
        'email' => $user->email
      ]);

      $this->assertDatabaseHas('project_user', [
        'project_id' => $project->id,
        'user_id' => $user->id
      ]);
    }

    public function testGuestCannotRemoveMembers()
    {
      $project = Project::factory()->create();

      $user = User::factory()->create();

      $project->members()->attach($user->id);

      $this->delete("/api/projects/{$project->id}/members", [
        'email' => $user->email
      ]);

      $this->assertDatabaseHas('project_user', [
        'project_id' => $project->id,
        'user_id' => $user->id
      ]);
    }

    public function testMemberCannotRemoveMembers()
    {
      $project = Project::factory()->create();

      $user = User::factory()->create();

      $project->members()->attach($user->id);

      $project->members()->attach(auth()->id());

      $this->delete("/api/projects/{$project->id}/members", [
        'email' => $user->email
      ]);

      $this->assertDatabaseHas('project_user', [
        'project_id' => $project->id,
        'user_id' => $user->id
      ]);
    }

    public function testOwnerCanSeeMembers()
    {
      $this->signIn();

      $project = Project::factory()->create([
        'user_id' => auth()->id()
      ]);

      $project->members()->attach(auth()->id());

      $this->get("/api/projects/{$project->id}/members")
        ->assertJson($project->members->toArray());
    }

    public function testMemberCanSeeMembers()
    {
      $this->signIn();

      $project = Project::factory()->create();

      $project->members()->attach(auth()->id());

      $this->get("/api/projects/{$project->id}/members")
        ->assertJson($project->members->toArray());
    }

    public function testGuestsCannotSeeMembers()
    {
      $this->withoutExceptionHandling();

      $project = Project::factory()->create();

      $response = $this->get("/api/projects/{$project->id}/members")
        ->assertJson(["error" => "Unauthorized"]);
    }
}

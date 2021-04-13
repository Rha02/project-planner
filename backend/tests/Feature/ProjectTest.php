<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Project;

class ProjectTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function testCreateProject()
    {
      $this->signIn();

      $this->assertDatabaseCount('projects', 0);

      $this->post("/api/projects");

      $this->assertDatabaseCount('projects', 1);
    }

    public function testOwnerCanDeleteProject()
    {
      $this->signIn();

      $project = Project::factory()->create([
        'user_id' => auth()->id()
      ]);

      $this->assertDatabaseCount('projects', 1);

      $this->delete("/api/projects/{$project->id}");

      $this->assertDatabaseCount('projects', 0);
    }

    public function testMemberCannotDeleteProject()
    {
      $this->signIn();

      $project = Project::factory()->create();

      $project->members()->attach(auth()->id());

      $this->assertDatabaseCount('projects', 1);

      $this->delete("/api/projects/{$project->id}");

      $this->assertDatabaseCount('projects', 1);
    }

    public function testOwnerCanUpdateProject()
    {
      $this->signIn();

      $project = Project::factory()->create([
        'user_id' => auth()->id()
      ]);

      $project->members()->attach(auth()->id());

      $this->patch("/api/projects/{$project->id}", [
        "title" => "test",
        "description" => "testing"
      ]);

      $this->assertSame("test", $project->fresh()->title);

      $this->assertSame("testing", $project->fresh()->description);
    }

    public function testMemberCanUpdateProject()
    {
      $this->signIn();

      $project = Project::factory()->create();

      $project->members()->attach(auth()->id());

      $this->patch("/api/projects/{$project->id}", [
        "title" => "test",
        "description" => "testing"
      ]);

      $this->assertSame("test", $project->fresh()->title);

      $this->assertSame("testing", $project->fresh()->description);
    }

    public function testUnauthorizedUserCannotUpdateProject()
    {
      $project = Project::factory()->create();

      $this->signIn();

      $this->patch("/api/projects/{$project->id}", [
        "title" => "test2",
        "description" => "testing2"
      ]);

      $this->assertSame($project->title, $project->fresh()->title);
    }
}

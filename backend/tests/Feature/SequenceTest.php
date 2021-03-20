<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use App\Models\Goal;

class SequenceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateSequence()
    {
      $this->signIn();

      $goal = Goal::factory()->create();

      // TODO: CREATE MORE TESTS FOR SEQUENCE API
    }
}

<?php

namespace Tests\Feature\API\V1;

use Tests\TestCase;
use App\Models\Project;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    /**
     @test
     */
    public function unlogged_user_cannot_access_to_project()
    {
        /* $this->withExceptionHandling(); */
        $this->getJson('/api/projects')
            ->assertUnauthorized();
    }
    /**
     @test
     */
    public function list_projects()
    {
        /* $this->withExceptionHandling(); */
        $project = Project::factory()->create();
        $user = $this->getLoggedUser();
        $project->users()->attach($user);
        $this->getJson('/api/projects')
            ->dump()
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }
}

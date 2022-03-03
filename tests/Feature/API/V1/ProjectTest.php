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
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }
    /** @test */
    public function logged_user_can_create_project()
    {
        $this->withoutExceptionHandling();
        $this->getLoggedUser();
        $payload = [
            'name' => 'project',
            'status' => 1,
            'description' => 'description project',
            'duration' => 2,
            'level' => 3,
        ];
        $this->postJson('api/projects', $payload)
            ->assertStatus(201);
        $this->assertDatabaseCount('projects', 1);
        $this->assertDatabaseHas('projects', [
            'id' => 1,
            'name' => $payload['name']
        ]);
    }
}

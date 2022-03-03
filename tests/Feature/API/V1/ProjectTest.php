<?php

namespace Tests\Feature\API\V1;

use Tests\TestCase;
use App\Models\Project;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /**
     @test
     */
    public function unlogged_user_cannot_access_to_project()
    {
        $this->getJson('/api/projects')
            ->assertUnauthorized();
    }
    /**
     @test
     */
    public function list_projects()
    {
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
        $this->withExceptionHandling();
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
            'name' => $payload['name'],
            'description' => $payload['description']
        ]);
    }
    /** @test */
    public function validation_for_creating_project()
    {
        /* $this->withoutExceptionHandling(); */
        $this->getLoggedUser();

        $this->postJson('/api/projects')
            ->assertJsonValidationErrors(['name', 'description']);
    }
    /** @test */
    public function logged_user_can_read_project()
    {

        $user = $this->getLoggedUser();
        $project = Project::factory()->create();
        $this->getJson('/api/projects/' . $project->id)
            ->assertOk()
            ->assertJsonPath('data.name', $project->name);
    }
    /** @test */
    public function logged_user_can_update_project()
    {
        $this->withoutExceptionHandling();
        $this->getLoggedUser();
        $project1 = Project::factory()->create();
        $data = ['name' => $this->faker->sentence()];
        $this->putJson('/api/projects/' . $project1->id, $data)
            ->assertOK()
            ->assertJsonPath('data.name', $data['name']);
        $this->assertDatabaseHas('projects', ['name' => $data['name'], 'id' => 1]);
    }
}

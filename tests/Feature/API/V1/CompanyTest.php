<?php

namespace Tests\Feature\API\V1;

use Tests\TestCase;
use App\Models\Company;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    /**
     @test
     */
    public function unlogged_user_cannot_access_to_company()
    {
        $this->withExceptionHandling();
        $this->getJson('/api/companies')
            ->assertUnauthorized();
    }
    /**
     @test
     */
    public function logged_user_can_get_list_companies()
    {
        $this->withExceptionHandling();
        $user = $this->getLoggedUser();
        $company = Company::factory(['user_id' => $user->id])->create();
        $this->getJson('/api/companies')
            ->assertOk()
            ->assertJsonCount(1, 'data');
    }
    /**
     @test
     */
    public function logged_user_can_create_company()
    {

        $user = $this->getLoggedUser();
        $payload = [
            'name' => $this->faker->sentence(),
        ];
        $this->postJson('api/companies', $payload)
            ->dump()
            ->assertJsonPath('data.user.name', $user->name)
            ->assertStatus(201);
        $this->assertDatabaseCount('companies', 1);
        $this->assertDatabaseHas('companies', [
            'id' => 1,
            'name' => $payload['name'],
            'user_id' => $user->id,
        ]);
    }
}

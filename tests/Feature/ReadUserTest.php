<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ReadUserTest extends TestCase
{
    public function test_success(): void
    {
        $user = User::factory()->create(['is_admin' => 1]);

        $newUser = User::factory()->create();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->get('/api/users/'.$newUser->id);
        
        $response->assertStatus(200);

        $response->assertJson(
            [
                'data' => [
                    [
                        'id' => $newUser->id,
                        'full_name' => $newUser->full_name,
                        'is_admin' => $newUser->is_admin,
                        'email' => $newUser->email,
                        'orders' => []
                    ]
                ]
            ]
        );
    }

    public function test_read_user_with_no_admin_user(): void
    {
        $user = User::factory()->create(['is_admin' => 0]);

        $newUser = User::factory()->create();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->get('/api/users/'.$newUser->id);
        
        $response->assertStatus(403);

        $response->assertJson(['message' => "You don't have permission to do it."]);
    }

    public function test_read_user_with_id_does_not_exist(): void
    {
        $user = User::factory()->create(['is_admin' => 1]);

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->get('/api/users/999999999999999999');
        
        $response->assertStatus(200);

        $response->assertJson(['data' => []]);
    }
}

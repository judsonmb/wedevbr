<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class DeleteUserTest extends TestCase
{
    public function test_success(): void
    {
        $user = User::factory()->create(['is_admin' => 1]);

        $this->assertDatabaseHas('users', [
            'full_name' => $user->full_name,
            'is_admin' => $user->is_admin,
            'email' => $user->email,
        ]);

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->delete('/api/users/'.$user->id);
        
        $response->assertStatus(200);

        $response->assertJson(['message' => 'deleted successfully!']);

        $this->assertDatabaseMissing('users', [
            'full_name' => $user->full_name,
            'is_admin' => $user->is_admin,
            'email' => $user->email,
        ]);
    }

    public function test_delete_user_with_no_admin_user(): void
    {
        $user = User::factory()->create(['is_admin' => 0]);

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->delete('/api/users/'.$user->id);
        
        $response->assertStatus(403);

        $response->assertJson(['message' => "You don't have permission to do it."]);
    }

    public function test_delete_user_with_id_does_not_exist(): void
    {
        $user = User::factory()->create(['is_admin' => 1]);

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->delete('/api/users/99999999999999');
        
        $response->assertStatus(404);

        $response->assertJson(['message' => 'Record not found.']);
    }
}

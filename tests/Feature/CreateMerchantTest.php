<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Merchant;

class CreateMerchantTest extends TestCase
{
    public function test_success(): void
    {
        $user = User::factory()->create();

        $body = Merchant::factory()->make()->toArray();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->post('/api/merchants', $body);
        
        $response->assertStatus(200);

        $response->assertJson(['message' => 'created successfully!']);

        $this->assertDatabaseHas('merchants', [
            'merchant_name' => $body['merchant_name'],
            'user_id' => $body['user_id'],
        ]);
    }

    public function test_create_merchant_without_required_parameters(): void
    {
        $user = User::factory()->create();

        $body = [];

        $response = $this->actingAs($user)
                         ->withHeaders([
                            'Accept' => 'application/json',
                         ])
                         ->post('/api/merchants', $body);
        
        $response->assertStatus(422);

        $response->assertJson(
            [
                'message' => 'The merchant name field is required. (and 1 more error)',
                'errors' => [
                    'merchant_name' => [
                        'The merchant name field is required.'
                    ],
                    'user_id' => [
                        'The user id field is required.'
                    ]
                ]  
            ]
        );
    }

    public function test_create_merchant_with_user_doesnt_exist(): void
    {
        $user = User::factory()->create();

        $body = Merchant::factory()->make(['user_id' => 9999999999])->toArray();

        $response = $this->actingAs($user)
                         ->withHeaders([
                            'Accept' => 'application/json',
                         ])
                         ->post('/api/merchants', $body);
        
        $response->assertStatus(422);

        $response->assertJson(
            [
                'message' => 'The selected user id is invalid.',
                'errors' => [
                    'user_id' => [
                        'The selected user id is invalid.'
                    ]
                ]  
            ]
        );
    }
}

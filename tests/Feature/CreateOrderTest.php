<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;

class CreateOrderTest extends TestCase
{
    public function test_success(): void
    {
        $user = User::factory()->create();

        $body = Order::factory()->toCreateOrderItem()->make()->toArray();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->post('/api/orders', $body);
        
        $response->assertStatus(200);

        $response->assertJson(['message' => 'created successfully!']);

        $this->assertDatabaseHas('orders', [
            'status' => $body['status'],
            'user_id' => $body['user_id'],
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => Order::latest()->get()[0]->id,
            'product_id' => $body['product_id'],
            'quantity' => $body['quantity']
        ]);
    }

    public function test_create_order_without_required_parameters(): void
    {
        $user = User::factory()->create();

        $body = [];

        $response = $this->actingAs($user)
                         ->withHeaders([
                            'Accept' => 'application/json',
                         ])
                         ->post('/api/orders', $body);
        
        $response->assertStatus(422);

        $response->assertJson(
            [
                'message' => 'The status field is required. (and 3 more errors)',
                'errors' => [
                    'status' => [
                        'The status field is required.'
                    ],
                    'user_id' => [
                        'The user id field is required.'
                    ],
                    'product_id' => [
                        'The product id field is required.'
                    ],
                    'quantity' => [
                        'The quantity field is required.'
                    ]
                ]  
            ]
        );
    }

    public function test_create_order_with_user_doesnt_exist(): void
    {
        $user = User::factory()->create();

        $body = Order::factory()->toCreateOrderItem()->make(['user_id' => 9999999999])->toArray();

        $response = $this->actingAs($user)
                         ->withHeaders([
                            'Accept' => 'application/json',
                         ])
                         ->post('/api/orders', $body);
        
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

    public function test_create_order_with_product_doesnt_exist(): void
    {
        $user = User::factory()->create();

        $body = Order::factory()->toCreateOrderItem()->make(['product_id' => 9999999999])->toArray();

        $response = $this->actingAs($user)
                         ->withHeaders([
                            'Accept' => 'application/json',
                         ])
                         ->post('/api/orders', $body);
        
        $response->assertStatus(422);

        $response->assertJson(
            [
                'message' => 'The selected product id is invalid.',
                'errors' => [
                    'product_id' => [
                        'The selected product id is invalid.'
                    ]
                ]  
            ]
        );
    }
}

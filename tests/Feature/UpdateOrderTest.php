<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;

class UpdateOrderTest extends TestCase
{
    public function test_success(): void
    {
        $user = User::factory()->create();

        $order = Order::factory()->create();

        $orderItem = OrderItem::factory()->create(['order_id' => $order->id]);

        $body = Order::factory()->toCreateOrderItem()->make()->toArray();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->put('/api/orders/'.$order->id, $body);
        
        $response->assertStatus(200);

        $response->assertJson(['message' => 'updated successfully!']);

        $this->assertDatabaseHas('orders', [
            'status' => $body['status'],
            'user_id' => $body['user_id'],
        ]);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $body['product_id'],
            'quantity' => $body['quantity']
        ]);
    }

    public function test_update_order_with_user_doesnt_exist(): void
    {
        $user = User::factory()->create();

        $order = Order::factory()->create();

        $body = Order::factory()->make(['user_id' => 9999999999])->toArray();

        $response = $this->actingAs($user)
                         ->withHeaders([
                            'Accept' => 'application/json',
                         ])
                         ->put('/api/orders/'.$order->id, $body);
        
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

    public function test_update_order_with_product_doesnt_exist(): void
    {
        $user = User::factory()->create();

        $order = Order::factory()->create();

        $orderItem = OrderItem::factory()->create(['order_id' => $order->id]);

        $body = Order::factory()->toCreateOrderItem()->make(['product_id' => 9999999999])->toArray();

        $response = $this->actingAs($user)
                         ->withHeaders([
                            'Accept' => 'application/json',
                         ])
                         ->put('/api/orders/'.$order->id, $body);
        
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

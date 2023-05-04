<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;

class ReadOrderTest extends TestCase
{
    public function test_success(): void
    {
        $user = User::factory()->create();

        $newOrder = Order::factory()->create();

        $orderItem = OrderItem::factory()->create(['order_id' => $newOrder->id]);

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->get('/api/orders/'.$newOrder->id);
        
        $response->assertStatus(200);

        $response->assertJson(
            [
                'data' => [
                    [
                        'status' => $newOrder->status,
                        'user' => [
                            'id' => $newOrder->user->id,
                            'full_name' => $newOrder->user->full_name,
                            'is_admin' => $newOrder->user->is_admin,
                            'email' => $newOrder->user->email,
                        ],
                        'order_items' => [
                            [
                                'id' => $orderItem->id,
                                'order_id' => $newOrder->id,
                                'product_id' => $orderItem->product_id,
                                'quantity' => $orderItem->quantity
                            ]
                        ]
                    ]
                ]
            ]
        );
    }

    public function test_read_order_with_id_does_not_exist(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->get('/api/orders/999999999999999999');
        
        $response->assertStatus(200);

        $response->assertJson(['data' => []]);
    }
}

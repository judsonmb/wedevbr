<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;

class DeleteOrderTest extends TestCase
{
    public function test_success(): void
    {
        $user = User::factory()->create();

        $order = Order::factory()->create();

        $this->assertDatabaseHas('orders', [
            'status' => $order->status,
            'user_id' => $order->user_id,
        ]);

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->delete('/api/orders/'.$order->id);
        
        $response->assertStatus(200);

        $response->assertJson(['message' => 'deleted successfully!']);

        $this->assertDatabaseMissing('orders', [
            'status' => $order->status,
            'user_id' => $order->user_id,
        ]);
    }

    public function test_delete_order_with_id_does_not_exist(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->delete('/api/orders/99999999999999');
        
        $response->assertStatus(404);

        $response->assertJson(['message' => 'Record not found.']);
    }
}

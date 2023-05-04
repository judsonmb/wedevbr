<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class ReadProductTest extends TestCase
{
    public function test_success(): void
    {
        $user = User::factory()->create();

        $newProduct = Product::factory()->create();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->get('/api/products/'.$newProduct->id);
        
        $response->assertStatus(200);

        $response->assertJson(
            [
                'data' => [
                    [
                        'id' => $newProduct->id,
                        'name' => $newProduct->name,
                        'price' => $newProduct->price,
                        'status' => $newProduct->status,
                        'merchant' => [
                            'id' => $newProduct->merchant->id,
                            'merchant_name' => $newProduct->merchant->merchant_name,
                            'user_id' => $newProduct->merchant->user_id,
                        ],
                        'order_items' => []
                    ]
                ]
            ]
        );
    }

    public function test_read_product_with_id_does_not_exist(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->get('/api/products/999999999999999999');
        
        $response->assertStatus(200);

        $response->assertJson(['data' => []]);
    }
}

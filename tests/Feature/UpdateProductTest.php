<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Merchant;

class UpdateProductTest extends TestCase
{
    public function test_success(): void
    {
        $user = User::factory()->create(['is_admin' => 1]);

        $merchant = Merchant::factory()->create(['user_id' => $user->id]);

        $product = Product::factory()->create();

        $body = Product::factory()->make()->toArray();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->put('/api/products/'.$product->id, $body);

        
        $response->assertStatus(200);

        $response->assertJson(['message' => 'updated successfully!']);

        $this->assertDatabaseHas('products', [
            'name' => $body['name'],
            'price' => $body['price'],
            'status' => $body['status'],
            'merchant_id' => $body['merchant_id'] 
        ]);
    }

    public function test_update_product_with_admin_user_without_merchant(): void
    {
        $user = User::factory()->create(['is_admin' => 1]);

        $product = Product::factory()->create();

        $body = Product::factory()->make()->toArray();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->put('/api/products/'.$product->id, $body);
        
        $response->assertStatus(403);

        $response->assertJson(['message' => 'Only merchants with admin user can do it.']);
    }


    public function test_update_product_with_user_without_merchant(): void
    {
        $user = User::factory()->create();

        $product = Product::factory()->create();

        $body = Product::factory()->make()->toArray();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->put('/api/products/'.$product->id, $body);
        
        $response->assertStatus(403);

        $response->assertJson(['message' => 'Only merchants with admin user can do it.']);
    }

    public function test_update_product_with_merchant_doesnt_exist(): void
    {
        $user = User::factory()->create(['is_admin' => 1]);

        $merchant = Merchant::factory()->create(['user_id' => $user->id]);

        $product = Product::factory()->create();

        $body = Product::factory()->make(['merchant_id' => 9999999999])->toArray();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->put('/api/products/'.$product->id, $body);
        
        $response->assertStatus(422);

        $response->assertJson(
            [
                'message' => 'The selected merchant id is invalid.',
                'errors' => [
                    'merchant_id' => [
                        'The selected merchant id is invalid.'
                    ]
                ]  
            ]
        );
    }

    public function test_create_product_with_invalid_status(): void
    {
        $user = User::factory()->create(['is_admin' => 1]);

        $merchant = Merchant::factory()->create(['user_id' => $user->id]);

        $product = Product::factory()->create();

        $body = Product::factory()->make(['status' => 'test'])->toArray();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->put('/api/products/'.$product->id, $body);
        
        $response->assertStatus(422);
        
        $response->assertJson(
            [
                'message' => 'The selected status is invalid.',
                'errors' => [
                    'status' => [
                        'The selected status is invalid.'
                    ]
                ]  
            ]
        );
    }
}

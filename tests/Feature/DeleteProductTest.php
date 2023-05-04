<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class DeleteProductTest extends TestCase
{
    public function test_success(): void
    {
        $user = User::factory()->create();

        $product = Product::factory()->create();

        $this->assertDatabaseHas('products', [
            'name' => $product->name,
            'price' => $product->price,
            'status' => $product->status,
            'merchant_id' => $product->merchant_id,
        ]);

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->delete('/api/products/'.$product->id);
        
        $response->assertStatus(200);

        $response->assertJson(['message' => 'deleted successfully!']);

        $this->assertDatabaseMissing('products', [
            'name' => $product->product_name,
            'price' => $product->user_id,
            'status' => $product->status,
            'merchant_id' => $product->merchant_id,
        ]);
    }

    public function test_delete_product_with_id_does_not_exist(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->delete('/api/products/99999999999999');
        
        $response->assertStatus(404);

        $response->assertJson(['message' => 'Record not found.']);
    }
}

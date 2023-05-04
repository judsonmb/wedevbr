<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Merchant;

class UpdateMerchantTest extends TestCase
{
    public function test_success(): void
    {
        $user = User::factory()->create();

        $merchant = Merchant::factory()->create();

        $body = Merchant::factory()->make()->toArray();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->put('/api/merchants/'.$merchant->id, $body);
        
        $response->assertStatus(200);

        $response->assertJson(['message' => 'updated successfully!']);

        $this->assertDatabaseHas('merchants', [
            'merchant_name' => $body['merchant_name'],
            'user_id' => $body['user_id'],
        ]);
    }

    public function test_update_merchant_with_id_does_not_exist(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->put('/api/merchants/999999999999999999');
        
        $response->assertStatus(404);

        $response->assertJson(['message' => 'Record not found.']);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Merchant;

class DeleteMerchantTest extends TestCase
{
    public function test_success(): void
    {
        $user = User::factory()->create();

        $merchant = Merchant::factory()->create();

        $this->assertDatabaseHas('merchants', [
            'merchant_name' => $merchant->merchant_name,
            'user_id' => $merchant->user_id,
        ]);

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->delete('/api/merchants/'.$merchant->id);
        
        $response->assertStatus(200);

        $response->assertJson(['message' => 'deleted successfully!']);

        $this->assertDatabaseMissing('merchants', [
            'merchant_name' => $merchant->full_name,
            'user_id' => $merchant->user_id,
        ]);
    }

    public function test_delete_merchant_with_id_does_not_exist(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->delete('/api/merchants/99999999999999');
        
        $response->assertStatus(404);

        $response->assertJson(['message' => 'Record not found.']);
    }
}

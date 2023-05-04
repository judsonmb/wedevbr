<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Merchant;

class ReadMerchantTest extends TestCase
{
    public function test_success(): void
    {
        $user = User::factory()->create();

        $newMerchant = Merchant::factory()->create();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->get('/api/merchants/'.$newMerchant->id);
        
        $response->assertStatus(200);

        $response->assertJson(
            [
                'data' => [
                    [
                        'id' => $newMerchant->id,
                        'merchant_name' => $newMerchant->merchant_name,
                        'user' => [
                            'id' => $newMerchant->user->id,
                            'full_name' => $newMerchant->user->full_name,
                            'is_admin' => $newMerchant->user->is_admin,
                            'email' => $newMerchant->user->email
                        ]
                    ]
                ]
            ]
        );
    }

    public function test_read_merchant_with_id_does_not_exist(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                          ->withHeaders([
                                'Accept' => 'application/json',
                            ])
                          ->get('/api/merchants/999999999999999999');
        
        $response->assertStatus(200);

        $response->assertJson(['data' => []]);
    }
}

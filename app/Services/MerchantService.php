<?php 

namespace App\Services;

use App\Models\Merchant;

class MerchantService
{
    public function createMerchant(array $data)
    {
        $newMerchant = new Merchant();
        $newMerchant->merchant_name = $data['merchant_name'];
        $newMerchant->user_id = $data['user_id'];
        $newMerchant->save();
    }

    public function readMerchant(int $id)
    {
        return Merchant::where('id', $id)
                ->with('admin')
                ->with('products')
                ->get();
    }

    public function updateMerchant(array $data, Merchant $merchant)
    {
        $merchant->merchant_name = $data['merchant_name'] ?? $merchant->merchant_name;
        $merchant->user_id = $data['user_id'] ?? $merchant->user_id;
        $merchant->save();
    }

    public function deleteMerchant(Merchant $merchant)
    {
        $merchant->delete();
    }
}
<?php 

namespace App\Services;

use App\Models\Merchant;

class MerchantService
{
    public function createMerchant(array $data)
    {
        $newMerchant = new Merchant();
        $newMerchant->merchant_name = $data['merchant_name'];
        $newMerchant->admin_id = $data['admin_id'];
        $newMerchant->save();
    }

    public function readMerchant(int $id)
    {
        return Merchant::where('id', $id)
                ->with('user')
                ->with('products')
                ->get();
    }

    public function updateMerchant(array $data, Merchant $merchant)
    {
        $merchant->merchant_name = $data['merchant_name'] ?? $merchant->merchant_name;
        $merchant->admin_id = $data['admin_id'] ?? $merchant->admin_id;
        $merchant->save();
    }

    public function deleteMerchant(Merchant $merchant)
    {
        $merchant->delete();
    }
}
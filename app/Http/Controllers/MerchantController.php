<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MerchantService;
use App\Models\Merchant;
use App\Http\Requests\CreateMerchantRequest;
use App\Http\Requests\UpdateMerchantRequest;

class MerchantController extends Controller
{
    public function create(CreateMerchantRequest $request)
    {
        (new MerchantService)->createMerchant($request->all());
        return response()->json(['message' => 'created successfully!'], 200);
    }

    public function read(Request $request, int $id)
    {
        $merchant = (new MerchantService)->readMerchant($id);
        return response()->json(['data' => $merchant], 200);
    }

    public function update(UpdateMerchantRequest $request, Merchant $merchant)
    {
        (new MerchantService)->updateMerchant($request->all(), $merchant);
        return response()->json(['message' => 'updated successfully!'], 200);
    }

    public function delete(Request $request, Merchant $merchant)
    {
        (new MerchantService)->deleteMerchant($merchant);
        return response()->json(['message' => 'deleted successfully!'], 200);
    }
}

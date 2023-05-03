<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Models\Order;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use DB;

class OrderController extends Controller
{
    public function create(CreateOrderRequest $request)
    {
        DB::beginTransaction();
        (new OrderService)->createOrder($request->all());
        try {
            DB::commit();
            return response()->json(['message' => 'created successfully!'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'An error occurred.'], 500);
        }
    }

    public function read(Request $request, int $id)
    {
        $order = (new OrderService)->readOrder($id);
        return response()->json(['data' => $order], 200);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        DB::beginTransaction();
        (new OrderService)->updateOrder($request->all(), $order);
        try {
            DB::commit();
            return response()->json(['message' => 'updated successfully!'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'An error occurred.'], 500);
        }
    }

    public function delete(Request $request, Order $order)
    {
        (new OrderService)->deleteOrder($order);
        return response()->json(['message' => 'deleted successfully!'], 200);
    }
}

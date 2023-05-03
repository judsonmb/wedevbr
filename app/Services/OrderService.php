<?php 

namespace App\Services;

use App\Models\Order;

class OrderService
{
    public function createOrder(array $data)
    {
        $newOrder = new Order();
        $newOrder->status = $data['status'];
        $newOrder->user_id = $data['user_id'];
        $newOrder->save();
    }

    public function readOrder(int $id)
    {
        return Order::where('id', $id)
                ->with('user')
                ->with('order_items')
                ->get();
    }

    public function updateOrder(array $data, Order $order)
    {
        $order->status = $data['status'] ?? $order->status;
        $order->user_id = $data['user_id'] ?? $order->user_id;
        $order->save();
    }

    public function deleteOrder(Order $order)
    {
        $order->delete();
    }
}
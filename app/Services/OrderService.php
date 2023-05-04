<?php 

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;

class OrderService
{
    public function createOrder(array $data)
    {
        $newOrder = new Order();
        $newOrder->status = $data['status'];
        $newOrder->user_id = $data['user_id'];
        $newOrder->save();

        $newOrderItem = new OrderItem();
        $newOrderItem->order_id = $newOrder->id;
        $newOrderItem->product_id = $data['product_id'];
        $newOrderItem->quantity = $data['quantity'];
        $newOrderItem->save();
    }

    public function readOrder(int $id)
    {
        return Order::where('id', $id)
                ->with('user')
                ->with('order_items')
                ->with('order_items.product')
                ->get();
    }

    public function updateOrder(array $data, Order $order)
    {
        $order->status = $data['status'] ?? $order->status;
        $order->user_id = $data['user_id'] ?? $order->user_id;
        $order->save();

        $orderItem = OrderItem::where('order_id', $order->id)->first();
        $orderItem->product_id = $data['product_id'] ?? $orderItem->product_id;
        $orderItem->quantity = $data['quantity'] ?? $orderItem->quantity;
        $orderItem->save();
    }

    public function deleteOrder(Order $order)
    {
        $order->delete();
    }
}
<?php

namespace Modules\Order\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderEnum;
use Illuminate\Support\Facades\Log;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Order\Actions\Order\SendOrderToOllopsAction;

class OllopsWebhookController extends BasicController
{
    public function handleOrderUpdate(Request $request)
    {
        if($request->has('event') && $request->event === 'ordersUpdate')
        {
            // Handle multiple orders
            foreach($request->orders as $orderData)
            {
                $order = Order::findOrFail($orderData['orderId']);
                // Check if the order already has an ollops_order_id
                if(!$order->ollops_order_id)
                {
                    Log::channel('ollops')->info('order token: ' . $orderData['token']);
                    Log::channel('ollops')->info('order ID: ' . $orderData['_id']);
                    // Update the fields if ollops_order_id is not present
                    $order->ollops_token = $orderData['token'] ?? $order->ollops_token;
                    $order->ollops_order_id = $orderData['_id'] ?? $order->ollops_order_id;
                    if(isset($orderData['createdAt']))
                    {
                        $order->sent_to_ollops_at = \Carbon\Carbon::parse($orderData['createdAt'])
                            ->format('Y-m-d H:i:s');
                    }else
                    {
                        $order->sent_to_ollops_at = now();
                    }
                    $order->ollops_confirmation_status = $orderData['confirmationStatus'] ?? $order->ollops_confirmation_status;
                    $order->save();
                    // if($orderData['confirmationStatus'] == 'confirmed') {
                    //     $this->handleConfirmedOrder($order);
                    // }
                }
            }
            return response()->json(['message' => 'All orders updated successfully']);
        }
        if($request->has('event') && $request->event === 'orderStatusUpdate')
        {
            // Handle multiple orders for orderStatusUpdate event
            foreach($request->orders as $orderData)
            {
                $order = Order::findOrFail($orderData['orderId']);
                // Update the fields based on the incoming data
                $order->validated_by = $orderData['validated_by'] ?? 'system';
                $order->first_message_time = $orderData['firstMessageTime'] ? Carbon::parse($orderData['firstMessageTime']) : null;
                $order->second_message_time = $orderData['secondMessageTime'] ? Carbon::parse($orderData['secondMessageTime']) : null;
                $order->third_message_time = $orderData['thirdMessageTime'] ? Carbon::parse($orderData['thirdMessageTime']) : null;
                $order->save();
            }
            return response()->json(['message' => 'Order status updated successfully']);
        }
        // Handle order cancellation event
        if($request->event === 'orderCancellation')
        {
            // Retrieve the order by orderId (single order)
            $order = Order::findOrFail($request->orderId);
            // Update order status to 'canceled', sub_status_id, and remark_id
            $order->status_id = OrderEnum::CANCELED_STATUS; // Set the status to 'canceled'
            $order->sub_status_id = 9; // Set sub_status_id to 9
            $order->remark_id = $request->remark_id; // Set the remark_id from the request
            if(isset($request->remark_id) && in_array($request->remark_id, [118, 123, 124, 128, 126, 131]))
            {
                $order->status_id = OrderEnum::ONHOLD_STATUS;
            }
            // Save the updated order
            $order->save();
            if($request->notes)
            {
                // save reason to order notes
                $order->notes()->create([
                    'content' => $request->notes,
                ]);
            }
            return response()->json(['message' => 'Order cancellation processed successfully']);
        }
        if($request->has('event') && $request->event === 'order_attempts_updated')
        {
            // update ollops_attempts
            // Find the order by orderId and update ollops_attempts
            $order = Order::find($request->orderId);
            if(!$order)
            {
                return response()->json(['message' => 'Order not found'], 404);
            }
            $order->ollops_attempts = $request->attempts;
            $order->save();
            return response()->json(['message' => 'Order attempts updated successfully']);
        }
        // Retrieve the order based on the orderId
        $order = Order::findOrFail($request->orderId);
        (new SendOrderToOllopsAction(
            order: $order,
            request: $request
        ))->execute();
        // Respond with a success message
        return response()->json(['message' => 'Order status and address updated successfully']);
    }

    protected function handleConfirmedOrder($order)
    {
        // Update order status and validation timestamp
        if($order->status_id == OrderEnum::PENDING_STATUS)
        {
            $order->validated = now();
        }else
        {
            $order->status_id = OrderEnum::PENDING_STATUS;
            $order->validated = now();
        }
    }
}

<?php

namespace Modules\Order\Actions\Order;

use Modules\Order\Entities\Order;
use App\Services\OllopsClientService;
use Modules\Order\Http\Resources\Order\Admin\OrderResource;

class StartValidationFlowAction
{
    protected $updatedOrders = [];
    protected $ordersNotUpdated = [];
    protected $errorMsg = "";
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function execute()
    {
        try {
            // Retrieve the order IDs from the request
            $orderIds = $this->request->input('orderIds');

            // Retrieve order data for the selected orders (assuming you have a method to fetch order data)
            $orders = $this->fetchOrdersByIds($orderIds);
            // Iterate through each order and send it to OLLOPS
            foreach ($orders as $order) {
                $this->sendOrderToOLLOPS($order);
            }

            return response()->json([
                'updatedOrders' => $this->updatedOrders,
                'notUpdatedOrders' => $this->ordersNotUpdated,
                'errorMsg' => $this->errorMsg
            ]);
        } catch (\Exception $e) {
            // Handle any errors and return error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // Method to fetch order data by IDs (replace with your actual implementation)
    private function fetchOrdersByIds(array $orderIds)
    {
        return Order::whereIn('id', $orderIds)->get();
    }

    // Method to send order data to OLLOPS
    private function sendOrderToOLLOPS($order)
    {

        // Construct the request payload
        $payload = [
            'orderId' => $order->id,
            'customer' => [
                'name' => $order->customerName,
                'phoneNumber' => $order->customerPhone,
            ],
            'orderDetails' => [
                'order_items' => $order->orderItems->map(function ($item) {
                    $thumbnail = getFile($item->product?->thumbnail[0]['file'], pathType()['ip'], getFileNameServer($item->product->thumbnail[0]));

                    return [
                        'product_name' => $item->product?->name?->value,
                        'description' => $item->product?->description?->value,
                        'price' => $item->totalPrice,
                        'image' => $thumbnail,
                        'quantity' => $item->quantity,
                    ];
                }),
                'dropshipper' => [
                    'name' => $order->dropshipper->first_name,
                    'email' => $order->dropshipper->email
                ],
                'location' => [
                    'city' => [
                        'name' => $order->city?->name?->value,
                        'id' => $order->customerCity
                    ],
                    'district' => $order->district,
                    'customerAddress' => $order->customerAddress,
                    'customerLocation' => $order->customerLocation
                ]
            ],
            'orderTotal' => $order->grandTotal,
            'token' => $order->token
        ];

        $response = App(OllopsClientService::class)->sendOrder($payload);

        if (isset($response)) {
            $statusCode = $response->getStatusCode();
            // Get the response body
            $body = $response->getBody()->getContents();

            $responseData = json_decode($body, true);
            if ($statusCode == 201) {
                $status = $responseData['data']['confirmationStatus'];
                $token = $responseData['data']['token'];
                $ollops_order_id = $responseData['data']['_id'];

                $order->update([
                    'ollops_order_id' => $ollops_order_id,
                    'ollops_token' => $token,
                    'sent_to_ollops_at' => now(),
                    'ollops_confirmation_status' => $status
                ]);

                // add to updated array
                $this->updatedOrders[] = new OrderResource($order->refresh());
            } else {
                $this->ordersNotUpdated[] = $order;
                $this->errorMsg = $responseData['message'];
            }
        } else {
            $this->ordersNotUpdated[] = $order;
        }
    }

}

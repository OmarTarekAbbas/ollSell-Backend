<?php

namespace Modules\Order\Actions\Order;

use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderEnum;

class CheckSubmitDuplicatedOrderAction
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function execute()
    {
        // Get orders with the same customer phone number
        $orders = Order::where('customerPhone', $this->request->customerPhone)->where('dropshipper_id', user()->id)->get();

        if ($orders->isEmpty()) {
            return [
                'is_duplicated' => false,
                'duplicated_order_ids' => null,
            ];
        }

        // Initialize arrays to store similar orders and their indicators
        $similarOrders = [];
        $isDuplicated = false;
        $freshDuplicate = false;

        // Check if any of those orders has the same items as the request items
        foreach ($orders as $order) {
            if ($this->areOrderItemsEqual($order)) {
                // Add the similar order to the collection
                array_push($similarOrders, $order->id);
                $isDuplicated = true;
                $freshDuplicate = $this->getFreshDuplicate($order);
            }
        }

        // Convert the array of order IDs to a JSON string
        $duplicatedOrderIdsJson = $similarOrders ? json_encode($similarOrders) : null;

        // Return the similar orders along with the indicator
        return [
            'is_duplicated' => $isDuplicated,
            'duplicated_order_ids' => $duplicatedOrderIdsJson,
            'fresh_duplicated' => $freshDuplicate
        ];
    }

    protected function getFreshDuplicate($order)
    {
        return $order->status_id == OrderEnum::NEW_STATUS || $order->status_id == OrderEnum::PAY_PENDING_STATUS || $order->status_id == OrderEnum::PENDING_STATUS;
    }

    protected function areOrderItemsEqual(Order $order)
    {
        // Get the items of the current order
        $orderItems = $order->orderItems->map(function ($item) {
            return [
                'product' => $item->product_id,
                'quantity' => $item->quantity,
                'sellingPrice' => $item->selling_price,
                'variant' => [$item->variant_id],
            ];
        })->toArray();

        // Get the items of the request
        $requestItems = $this->request->items;

        // Check if the arrays are equal
        return $this->arrayValuesEqual($orderItems, $requestItems);
    }

    protected function arrayValuesEqual($arr1, $arr2)
    {
        // Extract the "product" values from each array
        $products1 = array_map('strval', array_column($arr1, 'product'));
        $products2 = array_map('strval', array_column($arr2, 'product'));

        // Sort the arrays to ensure consistent comparison
        sort($products1);
        sort($products2);

        // Check if the sorted arrays are equal
        return $products1 === $products2;
    }
}

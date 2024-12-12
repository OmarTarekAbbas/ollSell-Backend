<?php

namespace Modules\Order\Actions\Order;

use Exception;
use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Order\Repositories\OrderRepository;

class CreateOrderAction
{
    private $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * This function executes an order by calculating the total quantity, total price, total VAT, cost
     * price, weight, and shipping fees, and then saves the order data.
     *
     * param Request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request. It contains information such as the
     * request method, headers, and input data.
     *
     * @return a boolean value. If the data is successfully saved, it will return true. Otherwise, it
     * will return false.
     */
    public function execute(Request $request)
    {
        if ($request->fresh_duplicated) {
            return throw new Exception(trans('orders.duplicated'));
        }

        $items = $request->items;
        $checkOrderAndCalculatorPrice =  App(MergeRequestOrderAction::class)->checkOrderAndCalculatorPrice($items);
        $totalQuantity = collect($items)->sum('quantity');
        $countOrderItem = collect($items)->count();
        $totalPrice = collect($checkOrderAndCalculatorPrice['totalPriceForProduct'])->sum();
        $totalVat = collect($checkOrderAndCalculatorPrice['totalProductVat'])->sum();
        $netProfit = collect($checkOrderAndCalculatorPrice['netProfit'])->sum();
        $costPrice = collect($checkOrderAndCalculatorPrice['totalSellingPriceForProduct'])->sum();
        $weight = $this->collectWeight($checkOrderAndCalculatorPrice);
        $shippingFees = setting('shipping_fee') ?? 25;
        $request =  App(MergeRequestOrderAction::class)->mergeAllRequest($request, $shippingFees, $totalQuantity, $totalPrice, $countOrderItem, $costPrice, $totalVat, $netProfit, $weight);
        $data = $this->repo->save($request);
        if ($data) {
            
            if ($data->is_duplicated) {
                // Retrieve the IDs of duplicated orders
                $duplicatedOrderIds = json_decode($data->duplicated_order_ids, true);

                // Update duplicated orders
                foreach ($duplicatedOrderIds as $orderId) {
                    // Retrieve the duplicated order
                    $duplicatedOrder = Order::find($orderId);

                    $duplicatedOrderDuplicatedIds = json_decode($duplicatedOrder->duplicated_order_ids, true);

                    // Add the ID of the current order to the duplicated order's list of duplicated_order_ids
                    $duplicatedOrderDuplicatedIds[] = $data->id;

                    // Encode the array back to JSON and assign it to the property
                    $duplicatedOrder->duplicated_order_ids = json_encode($duplicatedOrderDuplicatedIds);

                    $duplicatedOrder->is_duplicated = true;

                    // Save the changes
                    $duplicatedOrder->save();
                }
            }

            return $data;
        }

        // TODO :: check ollops settings
        // if automatic -> start validation

        return false;
    }

    private function collectWeight($checkOrderAndCalculatorPrice)
    {
        $totalWeights = $checkOrderAndCalculatorPrice['totalWeight'];

        $sum = '0';

        foreach ($totalWeights as $weight) {
            $sum = (float)($sum +  $weight);
        }

        return floatval($sum);
    }
}

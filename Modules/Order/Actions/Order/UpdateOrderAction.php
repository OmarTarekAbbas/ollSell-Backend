<?php

namespace Modules\Order\Actions\Order;

use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\OrderEnum;
use Modules\Order\Enums\PaymentEnum;
use Modules\Acl\Service\DropshipperService;
use Modules\Order\Service\OrderItemService;
use Modules\Order\Repositories\OrderRepository;

class UpdateOrderAction
{
    protected $repo;

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
    public function execute(Request $request, $id)
    {
        $items = $request->items;
        $is_admin = $request->is_admin ?? false;
        $order = Order::find($id);

        $checkOrderAndCalculatorPrice =  App(MergeRequestOrderAction::class)->checkOrderAndCalculatorPrice($items ,$order,$is_admin);

        $totalQuantity = collect($items)->sum('quantity');
        $countOrderItem = collect($items)->count();
        $totalPrice = collect($checkOrderAndCalculatorPrice['totalPriceForProduct'])->sum();
        $totalVat = collect($checkOrderAndCalculatorPrice['totalProductVat'])->sum();
        $netProfit = collect($checkOrderAndCalculatorPrice['netProfit'])->sum();
        $costPrice = collect($checkOrderAndCalculatorPrice['totalSellingPriceForProduct'])->sum();
        $weight = collect($checkOrderAndCalculatorPrice['totalWeight'])->sum();
        $shippingFees = setting('shipping_fee') ?? 25;

        $request =  App(MergeRequestOrderAction::class)->mergeAllRequest($request, $shippingFees, $totalQuantity, $totalPrice, $countOrderItem, $costPrice, $totalVat, $netProfit, $weight, $order);

        if ($order->paymentMethod == PaymentEnum::WALLET_METHOD_ID) {

            if ($order->status_id == OrderEnum::NEW_STATUS) {
                app()->make(DropshipperService::class)->updateWalletBalanceByRefundBalance($order, $order->dropshipper_id);
            }
        }

        $data = $this->repo->save($request, $id);

        if ($data) {
            app()->make(OrderItemService::class)->store($data);

            return $order;
        }

        return false;
    }
}

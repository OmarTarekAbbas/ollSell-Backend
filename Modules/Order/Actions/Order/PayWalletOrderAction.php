<?php

namespace Modules\Order\Actions\Order;

use Modules\Acl\Service\DropshipperService;
use Modules\Order\Repositories\OrderRepository;
use Modules\Order\Http\Resources\Order\OrderResource;

class PayWalletOrderAction
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
    public function execute($order)
    {
        $order->validated = now();
        $order->validated_by = 'prepaid';
        $order->save();
        $data = App(CreateShipmentOrderAction::class)->execute($order);
        if ($data) {
            app()->make(DropshipperService::class)->updateWalletBalanceByPayWallet($order);

            return new OrderResource($order->refresh());
        }
    }
}

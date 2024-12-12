<?php

namespace Modules\Order\Actions\Order;

use Modules\Order\Enums\OrderEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Order\Enums\PaymentEnum;
use Modules\Order\Repositories\OrderRepository;

class CancelOrderAction
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
        $request->merge([
            'status_id' => OrderEnum::CANCELED_STATUS,
            'cancelDate' => Carbon::now(),
        ]);
        // return fund
        $order = $this->repo->find($id);
        if($order->paymentMethod == PaymentEnum::WALLET_METHOD_ID)
        {
            user()->update([
                'walletBalance' => user()->walletBalance + $order->grandTotal
            ]);
        }
        $data = $this->repo->save($request, $id);
        if($data)
        {
            return true;
        }
        return false;
    }
}

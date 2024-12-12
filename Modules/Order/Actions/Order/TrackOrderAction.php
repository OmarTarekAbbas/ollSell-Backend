<?php

namespace Modules\Order\Actions\Order;

use Illuminate\Http\Request;
use Modules\Order\Entities\OrderRefund;
use Modules\Order\Repositories\OrderRepository;

class TrackOrderAction
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
        if ($request->type === 'orderId') {
            $data =  $this->repo->findOne($id);
        } else {
            $data = OrderRefund::find($id);
        }

        if ($data) {
            $trackShipment = App(TrackShipmentOrderAction::class)->execute($data);

            return $trackShipment['data'];
        }

        return false;
    }
}

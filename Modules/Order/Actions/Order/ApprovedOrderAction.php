<?php

namespace Modules\Order\Actions\Order;

use Modules\Order\Enums\OrderEnum;

use Illuminate\Http\Request;
use Modules\Order\Repositories\OrderRepository;
use App\Jobs\SendOrderToAymakanJob;

class ApprovedOrderAction
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
            'status_id' => OrderEnum::SHIPPING_STATUS,
        ]);

        $data = $this->repo->findOne($id);

        if ($data) {
            SendOrderToAymakanJob::dispatch($data);
            return true;
        }

        return false;
    }
}

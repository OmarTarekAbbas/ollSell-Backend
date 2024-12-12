<?php

namespace Modules\Order\Actions\Order;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderStatus;
use Modules\CoreData\Service\StatusService;
use Modules\Order\Repositories\OrderRepository;

class WebhooksShippingDashboardOrderAction
{
    protected $repo;
    protected $statusService;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(OrderRepository $repository, StatusService $statusService)
    {
        $this->repo = $repository;
        $this->statusService = $statusService;
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
    public function execute($request)
    {
        $order = Order::find($request->id);
        $status_id = $this->statusService->findBy(new Request(['name' => 'delivered']), get: 'first');

        if (!$status_id) {
            $request->merge([
                'name' => ['en' => 'delivered', 'ar' => 'delivered'],
                'status' =>  1,
            ]);
            $data = $this->statusService->store($request);
        }

        $order = Order::where('tracking_number', $order['tracking_number'])->first();
        if (!$order) {
            return false;
        }

        $orderStatus = OrderStatus::where('order_id', $order->id)->latest()->first();
        $request->merge([
            'status_id' =>  $status_id->id,
            'deliveryDate' =>  Carbon::parse($orderStatus->created_at)->format('Y-m-d'),
        ]);

        $data = $this->repo->save($request, $order->id);
        if ($data) {

            return true;
        }
        
        return false;
    }
}

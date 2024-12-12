<?php

namespace Modules\Order\Actions\OrderRefund;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Order\Entities\OrderRefund;
use Modules\CoreData\Service\StatusService;
use Modules\Order\Service\OrderStatusRefundService;
use Modules\Order\Repositories\OrderRefundRepository;
use Modules\Order\Entities\OrderStatusRefund;

class WebhooksShippingOrderRefundAction
{
    protected $repo;
    protected $statusService;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(OrderRefundRepository $repository, StatusService $statusService)
    {
        $this->repo = $repository;
        $this->statusService = $statusService;
    }

    /**
     * This function executes an orderRefund by calculating the total quantity, total price, total VAT, cost
     * price, weight, and shipping fees, and then saves the orderRefund data.
     * 
     * param Request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request. It contains information such as the
     * request method, headers, and input data.
     * 
     * @return a boolean value. If the data is successfully saved, it will return true. Otherwise, it
     * will return false.
     */
    public function execute($request, $getDataAymakanArray)
    {
        $getDataAymakanStatus = strtolower($getDataAymakanArray['description']);
        $status_id = $this->checkStatus($getDataAymakanStatus);

        if (!$status_id) {
            $request->merge([
                'name' => ['en' => $getDataAymakanStatus, 'ar' => $getDataAymakanStatus],
                'status' =>  1,
            ]);
            $data = $this->statusService->store($request);
        }

        $orderRefund = OrderRefund::where('tracking_number', $getDataAymakanArray['tracking'])->first();

        if (!$orderRefund) {
            return false;
        }

        if ($getDataAymakanStatus === 'delivered') {
            $status_id = $this->checkStatus($getDataAymakanStatus);
            $orderStatus = OrderStatusRefund::where('order_refund_id', $orderRefund->id)->latest()->first();
            $request->merge([
                'status_id' =>  $status_id->id,
                'deliveryDate' =>  Carbon::parse($orderStatus->created_at)->format('Y-m-d'),
            ]);
        } else {
            $status_id = $this->checkStatus($getDataAymakanStatus);
            $request->merge([
                'status_id' =>   $status_id->id,
            ]);
        }
        $data = $this->repo->save($request, $orderRefund->id);
        app()->make(OrderStatusRefundService::class)->store($data);

        if ($data) {

            return true;
        }
        
        return false;
    }

    /**
     * This PHP function checks the status of a request using a status service and returns the first
     * result.
     *
     * param getDataAymakanStatus It is a variable that contains the name of the status that needs to
     * be checked. It is passed as an argument to the function checkStatus().
     *
     * @return the result of a query to the statusService, which is looking for a record with a name
     * that matches the value of the  parameter. The query is using the findBy
     * method with a Request object and the 'first' option to return only the first matching record.
     * The function is returning the result of this query.
     */
    public function checkStatus($getDataAymakanStatus)
    {
        return $this->statusService->findBy(new Request(['name' => $getDataAymakanStatus]), get: 'first');
    }
}

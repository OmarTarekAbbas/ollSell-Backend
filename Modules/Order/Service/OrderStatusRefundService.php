<?php

namespace Modules\Order\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\Order\Repositories\OrderStatusRefundRepository;
use Modules\Order\Http\Resources\Order\OrderStatusRefundResource;
use Modules\Order\Actions\OrderStatusRefund\CreateOrderStatusRefundAction;

class OrderStatusRefundService extends BasicService
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(OrderStatusRefundRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * It takes a request, a boolean for pagination, and a number for the number of items per page
     * 
     * param Request request The request object
     * param pagination true or false
     * param perPage The number of items to show per page.
     * 
     * return A collection of objects.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 10)
    {
        return $this->repo->findBy($request, $pagination,  $perPage);
    }

    /**
     * The store function executes the CreateOrderStatusRefundAction class with the given orderRefund
     * parameter.
     * 
     * param orderRefund The parameter "orderRefund" is an object that represents the details of a
     * refund for an order. It likely contains information such as the order ID, the amount to be
     * refunded, and any additional notes or details related to the refund.
     * 
     * return the result of executing the `CreateOrderStatusRefundAction` class with the
     * `` parameter.
     */
    public function store($orderRefund)
    {
        return App(CreateOrderStatusRefundAction::class)->execute($orderRefund);
    }

    /**
     * It takes a request and an id, and returns the data if it exists, or false if it doesn't
     * 
     * param Request request The request object
     * param id The id of the record you want to update
     * 
     * return The data is being returned.
     */
    public function update(Request $request, $id)
    {
        return ($this->repo->save($request, $id)) ?? false;
    }

    /**
     * It returns a collection of OrderResource
     * 
     * param Request request This is the request object that is passed to the controller.
     * param pagination true/false
     * param perPage The number of items to show per page.
     * 
     * return A collection of OrderResource
     */
    public function list(Request $request, $pagination = false, $perPage = 10)
    {
        return OrderStatusRefundResource::collection($this->repo->list($request, $pagination, $perPage));
    }

    /**
     * It returns a new OrderResource object, which is a collection of
     * OrderResource objects
     * 
     * param id The id of the master catalog list you want to retrieve
     * 
     * return A new instance of the OrderResource class.
     */
    public function show($id)
    {
        return new OrderStatusRefundResource($this->repo->findOne($id));
    }
}

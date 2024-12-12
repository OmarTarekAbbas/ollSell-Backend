<?php

namespace Modules\Order\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\Order\Repositories\OrderStatusRepository;
use Modules\Order\Actions\OrderStatus\CreateOrderStatusAction;
use Modules\Order\Http\Resources\Order\OrderStatusResource;

class OrderStatusService extends BasicService
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(OrderStatusRepository $repository)
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
     * It takes a request, passes it to the repo, and returns true if the repo returns true
     *
     * param Request request The request object
     *
     * return A boolean value.
     */
    public function store($order)
    {
        return App(CreateOrderStatusAction::class)->execute($order);
    }

    /**
     * The update function saves the request data using the repository and returns true if successful,
     * otherwise false.
     *
     * param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request made to the server.
     *
     * param id The  parameter is the identifier of the resource that needs to be updated. It is
     * used to specify which resource should be updated in the database.
     *
     * return The code is returning the result of the `save` method from the repository
     * (`->repo->save(, )`). If the `save` method returns
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
        return OrderStatusResource::collection($this->repo->list($request, $pagination, $perPage));
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
        return new OrderStatusResource($this->repo->findOne($id));
    }

    /**
     * @param $orderId
     * @return OrderStatusResource
     */
    public function getOrderStatuses($orderId)
    {
        return new OrderStatusResource($this->repo->getOrderHistory($orderId));
    }
}

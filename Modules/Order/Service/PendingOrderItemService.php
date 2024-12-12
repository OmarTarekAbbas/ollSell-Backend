<?php

namespace Modules\Order\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\Order\Actions\PendingOrder\CreatePendingOrderItemAction;
use Modules\Order\Http\Resources\Order\OrderItemResource;
use Modules\Order\Repositories\PendingOrderItemRepository;

class PendingOrderItemService extends BasicService
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(PendingOrderItemRepository $repository)
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
    public function findBy(Request $request, $pagination = false, $perPage = 10, $get = '')
    {
        return $this->repo->findBy($request, $pagination,  $perPage, $get);
    }

    /**
     * The store function executes the CreateOrderItemAction class to create a new order item.
     *
     * param order The parameter "order" is the data representing an order that needs to be stored. It
     * could be an array, object, or any other data structure that contains the necessary information
     * for creating an order item.
     *
     * return the result of executing the `CreateOrderItemAction` class with the `` parameter.
     */
    public function store($pendingOrder)
    {
        return (new CreatePendingOrderItemAction(
            pendingOrder: $pendingOrder
        ))->execute();
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
        return OrderItemResource::collection($this->repo->list($request, $pagination, $perPage));
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
        return new OrderItemResource($this->repo->findOne($id));
    }


}
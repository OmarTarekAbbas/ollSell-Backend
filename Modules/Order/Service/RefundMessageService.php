<?php

namespace Modules\Order\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\Order\Repositories\RefundMessageRepository;
use Modules\Order\Http\Resources\Order\RefundMessageResource;
use Modules\Order\Actions\OrderRefundMessage\OrderSendMessagesAction;

class RefundMessageService extends BasicService
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(RefundMessageRepository $repository)
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
        $request->merge(['order_refund_id' => $request->order_refund_id]);
        return RefundMessageResource::collection($this->repo->list($request, $pagination, $perPage));
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
        return new RefundMessageResource($this->repo->findOne($id));
    }

    /**
     * The function sends a message with refund details and saves it in the database.
     * 
     * param request The `` parameter is an object that contains the data sent in the HTTP
     * request. It typically includes information such as form inputs, query parameters, and headers.
     * param id The "id" parameter is the ID of the order refund for which the message is being sent.
     * 
     * return the result of the `save()` method, which is a boolean value indicating whether the
     * message was successfully saved or not.
     */
    public function sendMessages($request, $id)
    {
        return App(OrderSendMessagesAction::class)->execute($request, $id);
    }
}

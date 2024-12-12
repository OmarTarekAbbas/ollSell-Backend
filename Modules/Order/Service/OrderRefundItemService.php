<?php

namespace Modules\Order\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\CoreData\Service\StatusService;
use Modules\Order\Repositories\OrderRefundItemRepository;
use Modules\Order\Http\Resources\Order\OrderRefundResource;
class OrderRefundItemService extends BasicService
{
    protected $repo;
    protected $statusService;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(OrderRefundItemRepository $repository, StatusService $statusService)
    {
        $this->repo = $repository;
        $this->statusService = $statusService;
    }

    /**
     * The index function returns a list of items from the repository with optional pagination and a
     * specified number of items per page.
     * 
     * param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request made to the server. 
     * param pagination The pagination parameter determines whether or not to enable pagination for
     * the list of items. If set to true, the list will be paginated. If set to false, all items will
     * be returned without pagination.
     * param perPage The `` parameter is used to specify the number of items to be displayed
     * per page in the pagination. By default, it is set to 10, but you can change it to any desired
     * value.
     * 
     * return the result of calling the `list` method on the `` object, passing in the
     * ``, ``, and `` arguments.
     */
    public function index(Request $request, $pagination = false, $perPage = 10)
    {
        return $this->repo->list($request, $pagination, $perPage);
    }

    /**
     * The function returns a collection of OrderRefundResource objects based on the provided request.
     * 
     * param request The  parameter is an object that contains the data and information needed
     * to process the refund order. It could include things like the user's authentication credentials,
     * the order ID, the reason for the refund, and any other relevant details.
     * 
     * return a collection of OrderRefundResource objects.
     */
    public function list($request)
    {
        return OrderRefundResource::collection($this->repo->list($request));
    }
}

<?php

namespace Modules\Finance\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\Finance\Entities\DepositRequest;
use Modules\Finance\Http\Resources\DepositRequestResource;
use Modules\Finance\Repositories\DepositRequestRepository;
use Modules\Finance\Actions\DepositRequest\StoreDepositRequestAction;
use Modules\Finance\Actions\DepositRequest\IndexDepositRequestAction;
use Modules\Finance\Actions\DepositRequest\RefusedDepositRequestAction;
use Modules\Finance\Actions\DepositRequest\ApprovedDepositRequestAction;

class DepositRequestService extends BasicService
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(DepositRequestRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * This function finds data based on a request and returns it with optional pagination and
     * filtering.
     *
     * param Request request  is an instance of the Request class in Laravel, which contains
     * all the data that was sent with the HTTP request. 
     * 
     * param pagination A boolean value that determines whether the results should be paginated or
     * not. If set to true, the results will be paginated based on the  parameter.
     * 
     * param perPage The number of results to be displayed per page in case of pagination.
     * 
     * param get The "get" parameter is a string that specifies which relationships to eager load when
     * retrieving the data.
     *
     * return The function `findBy` is being returned, which is called on the `->repo` object
     * with the parameters ``, ``, ``, and ``.
     */
    public function findBy(Request $request, $pagination = false, $perPage = 10, $get = "")
    {
        return $this->repo->findBy($request, $pagination, $perPage, get: $get);
    }

    /**
     * The function stores a withdrawal request with the pending status and returns true if successful.
     *
     * param request  is a parameter that is passed to the store() function. It is likely an
     * instance of a Request class.
     *
     * return If the `` is truthy, the function returns `true`. Otherwise, it
     * returns `false`.
     */
    public function store($request)
    {//todo change
        return App(StoreDepositRequestAction::class)->execute($request);
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
        return DepositRequestResource::collection($this->repo->list($request->merge(['dropshipper_id' => user()->id]), $pagination, $perPage));
    }

    /**
     * It takes a request, passes it to the repo, and returns true if the repo returns true
     *
     * param Request request The request object
     *
     * return A boolean value.
     */
    public function approved(Request $request, $id)
    {
        $data = $this->repo->findOne($id);
        if($data && $data->status == DepositRequest::PENDING_STATUS)
        {
            return App(ApprovedDepositRequestAction::class)->execute($request, $id);
        }
        return false;
    }

    /**
     * This PHP function updates the status of an order to "refused" and saves the changes.
     *
     * param Request request an instance of the Request class, which contains the HTTP request data
     * sent by the client.
     * param id  is an optional parameter that represents the ID of the order that needs to be
     * refused. If it is not provided, a new order will be created with the refused status.
     *
     * return a boolean value - `true` if the data is saved successfully, and `false` if it is not.
     */
    public function refused(Request $request, $id)
    {
        return App(RefusedDepositRequestAction::class)->execute($request, $id);
    }

    /**
     * This function returns a list of items based on the given request, pagination, and number of
     * items per page.
     *
     * param Request request  is an instance of the Request class which contains the HTTP
     * request information such as the request method, headers, and input data.
     * 
     * param pagination A boolean value that determines whether or not to paginate the results. If set
     * to true, the results will be paginated based on the  parameter.
     * 
     * param perPage The number of items to be displayed per page in the list.
     *
     * return The `index` function is returning the result of calling the `list` method on the
     * repository (`->repo`) with the provided ``, ``, and ``
     * parameters.
     */
    public function index(Request $request, $pagination = false, $perPage = 10)
    {
        return App(IndexDepositRequestAction::class)->execute($request, $pagination, $perPage);
    }
}

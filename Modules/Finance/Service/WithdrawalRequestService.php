<?php

namespace Modules\Finance\Service;

use Illuminate\Http\Request;
use Modules\Basic\Service\BasicService;
use Modules\Finance\Entities\WithdrawalRequest;
use Modules\Finance\Http\Resources\WithdrawalRequestResource;
use Modules\Finance\Repositories\WithdrawalRequestRepository;
use Modules\Finance\Actions\WithdrawalRequest\IndexWithdrawalRequestAction;
use Modules\Finance\Actions\WithdrawalRequest\StoreWithdrawalRequestAction;
use Modules\Finance\Actions\WithdrawalRequest\ApprovedWithdrawalRequestAction;
use Modules\Finance\Actions\WithdrawalRequest\EarningsWithdrawalRequestAction;
use Modules\Finance\Actions\WithdrawalRequest\InProgressWithdrawalRequestAction;
use Modules\Finance\Actions\WithdrawalRequest\RefusedWithdrawalRequestAction;
use Modules\Order\Service\OrderService;

class WithdrawalRequestService extends BasicService
{
    protected $repo, $orderService;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(WithdrawalRequestRepository $repository, OrderService $orderService)
    {
        $this->repo = $repository;
        $this->orderService = $orderService;
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
     * instance of a Request class, which contains data submitted by a user through a form or an API
     * request. The data may include information about a withdrawal request, such as the amount to be
     * withdrawn. The function uses this
     *
     * return If the `` is truthy, the function returns `true`. Otherwise, it
     * returns `false`.
     */
    public function store($request)
    {
        return App(StoreWithdrawalRequestAction::class)->execute($request);
    }

    /**
     * The function `earningsWithdrawal` processes a request for earnings withdrawal using the
     * `EarningsWithdrawalRequestAction` class.
     *
     * @param request The `earningsWithdrawal` function appears to be a method that handles earnings
     * withdrawal requests. It takes a parameter named ``, which is likely an object or an
     * array containing the necessary data for processing the withdrawal.
     *
     * @return The `earningsWithdrawal` function is returning the result of executing the
     * `EarningsWithdrawalRequestAction` class with the provided `` parameter.
     */
    public function earningsWithdrawal($request)
    {
        return App(EarningsWithdrawalRequestAction::class)->execute($request);
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
        if(user())
        {
            return WithdrawalRequestResource::collection($this->repo->list($request->merge(['dropshipper_id' => user()->id]),
                $pagination, $perPage));
        }
        return false;
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
        return App(ApprovedWithdrawalRequestAction::class)->execute($request, $id);
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
        return App(RefusedWithdrawalRequestAction::class)->execute($request, $id);
    }

    /**
     * The function `inProgress` executes an action for an in-progress withdrawal request using the
     * provided request and ID.
     * 
     * @param Request request The `` parameter in the `inProgress` function is of type
     * `Request`, which is typically an instance of the Illuminate\Http\Request class in Laravel. This
     * parameter is used to access and handle the incoming HTTP request data.
     * @param id The `` parameter in the `inProgress` function likely represents the unique
     * identifier of the withdrawal request that is currently in progress. This identifier is used to
     * retrieve the specific withdrawal request from the database or perform any necessary actions
     * related to it.
     * 
     * @return The `inProgress` function is returning the result of executing the `execute` method of
     * the `InProgressWithdrawalRequestAction` class with the provided `` and `` parameters.
     */
    public function inProgress(Request $request, $id)
    {
        return App(InProgressWithdrawalRequestAction::class)->execute($request, $id);
    }

    /**
     * This function returns a list of items based on the given request, pagination, and number of
     * items per page.
     *
     * param Request request  is an instance of the Request class which contains the HTTP
     * request information such as the request method, headers, and input data.
     * param pagination A boolean value that determines whether or not to paginate the results. If set
     * to true, the results will be paginated based on the  parameter.
     * param perPage The number of items to be displayed per page in the list.
     *
     * return The `index` function is returning the result of calling the `list` method on the
     * repository (`->repo`) with the provided ``, ``, and ``
     * parameters.
     */
    public function index(Request $request, $pagination = false, $perPage = 10)
    {
        return App(IndexWithdrawalRequestAction::class)->execute($request, $pagination, $perPage);
    }

    public function export(Request $request)
    {
        return App(IndexWithdrawalRequestAction::class)->execute($request, false, 0);
    }

    /**
     * This PHP function checks the total pending withdrawal amount for a specific user.
     *
     * return the sum of the amount of all pending withdrawal requests for the current user
     * (identified by their ID as a dropshipper) in the WithdrawalRequest model.
     */
    public function checkBalance()
    {
        return WithdrawalRequest::where('dropshipper_id', user()->id)
            ->where('status', WithdrawalRequest::PENDING_STATUS)->sum('amount');
    }

    public function getOrder($ids)
    {
        $orders = json_decode($ids, true);
        if($orders)
        {
            return $this->orderService->findBy(new Request(['id' => $orders]));
        }
        return [];
    }
}

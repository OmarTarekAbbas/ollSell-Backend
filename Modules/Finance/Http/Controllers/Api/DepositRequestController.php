<?php

namespace Modules\Finance\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Finance\Service\DepositRequestService;

class DepositRequestController extends BasicController
{
    /* `private ;` is declaring a private class property called ``. This property is
    being used to store an instance of the `DepositRequestService` class, which is being passed to the
    constructor of the `WithdrawalRequestController` class. This allows the
    `WithdrawalRequestController` class to access the methods and properties of the
    `DepositRequestService` class. */
    private $service;

    /**
     * This is a constructor function that initializes a class property with an instance of the
     * DepositRequestService class.
     * 
     * param DepositRequestService Service The parameter "Service" is an instance of the
     * "DepositRequestService" class that is being passed to the constructor of another class. This is a
     * common practice in object-oriented programming where one class depends on another class to
     * perform certain tasks. By passing an instance of the "DepositRequestService" class
     */
    public function __construct(DepositRequestService $Service)
    {
        $this->service = $Service;
    }

    /**
     * This PHP function processes a withdrawal request from a wallet, checking that the requested
     * amount is not zero or negative before updating the wallet balance.
     * 
     * param Request request  is an instance of the Request class which contains the data sent
     * in the HTTP request. It is used to retrieve data from the request such as form data, query
     * parameters, and request headers. In this case, it is used to retrieve the withdrawal amount from
     * the request data.
     * 
     * return If the value of the 'amount' parameter in the request is less than or equal to 0, an
     * error message is returned. Otherwise, the 'update' method of the 'service' object is called with
     * the request object as a parameter and the result of this method call is returned.
     */
    public function store(Request $request)
    {//todo change
        $request->validate([
            'avatar' => 'required|image',
            'amount' => 'required|integer|min:0'
        ]);

        if ($request['amount'] <= 0) {
            return $this->unKnowError(trans('orders.The value must not be 0'));
        }
        $depositRequest = $this->service->store($request);
        if ($depositRequest) {
            return $this->updateResponse($depositRequest, trans('orders.Deposit Request has create successfully'));
        }
        return $this->unKnowError(trans('orders.An error occurred, please try again later'));
    }

    /**
     * This function returns an API response for a list of items based on the given request,
     * pagination, and number of items per page.
     * 
     * param Request request  is an instance of the Request class which contains the HTTP
     * request information such as headers, parameters, and body. It is used to retrieve data from the
     * client-side and pass it to the server-side for processing.
     * 
     * return The `list` function is returning the result of calling the `apiResponse` method with the
     * result of calling the `list` method of the `` object, passing in the `` object,
     * the result of calling the `pagination` method, and the result of calling the `perPage` method as
     * arguments.
     */
    public function list(Request $request)
    {
        return $this->apiResponse($this->service->list($request, $this->pagination(), $this->perPage()));
    }
}

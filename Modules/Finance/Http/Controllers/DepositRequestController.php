<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Finance\Http\Requests\WithdrawalRequest\RefusedRequest;
use Modules\Finance\Service\DepositRequestService;

class DepositRequestController extends BasicController
{
    protected $service;

    /**
     * This is a constructor function that sets middleware and permissions for withdrawal request actions
     * and initializes a DepositRequestService object.
     *
     * param DepositRequestService Service DepositRequestService is a dependency injection of the
     * DepositRequestService class. It is used to access the methods and properties of the
     * DepositRequestService class within the constructor and other methods of the class where it is
     * injected.
     */
    public function __construct(DepositRequestService $Service)
    {//todo change
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_RedeemRequest')->only(['index','show']);
        $this->middleware('permission:update_RedeemRequest')->only(['refused','approved']);
        $this->service = $Service;
    }

    /**
     * This PHP function returns a view with withdrawal requests data.
     *
     * param Request request  is an instance of the Request class, which is used to retrieve
     * data from the HTTP request. It contains information such as the HTTP method, headers, and any
     * data submitted in the request. In this case, it is being passed to the index method to retrieve
     * any data needed for the view.
     *
     * return a view named 'Finance::withdrawalRequest.index' with the variable
     *  passed to it. The value of  is obtained by calling the
     * index method of the service object with the  and true as parameters.
     */
    public function index(Request $request)
    {
        $depositRequests = $this->service->index($request, true);

        if ($request->ajax()) {
            return $this->getDashboardView(
                'finance::depositRequest.table',
                compact('depositRequests', 'request')
            );
        }

        return $this->getDashboardView('finance::depositRequest.index', compact('depositRequests', 'request'));
    }

    public function show($id)
    {
        $data = $this->service->show($id);

        return $this->getDashboardView('finance::depositRequest.show', compact('data'));
    }

    /**
     * This function returns a view of approved orders based on the request input.
     *
     * param Request request  is an instance of the Request class which contains the data sent
     * by the client in the HTTP request. It can contain data from the URL, form data, headers,
     * cookies, and more. In this case, it is being passed to the approved() method of a service class
     * to retrieve a list
     *
     * return a view named 'order::order.index' with a variable named 'orders' that contains the
     * result of calling the 'approved' method of the 'service' property with the 'request' parameter.
     */
    public function approved(Request $request, $id)
    {
       $data = $this->service->approved($request, $id);
        if ($data) {
            return redirect(url('/depositRequest/list'))->with('message','Done');
        }
        return redirect(url('/depositRequest/list'))->with('message_false', 'Check Request');
    }

    /**
     * This function returns a view of refused orders based on the request input.
     *
     * param Request request  is an instance of the Request class which contains the data sent
     * by the client in the HTTP request. It can contain information such as form data, query
     * parameters, headers, and more. In this code snippet,  is being passed as a parameter to
     * the refused() method of a service class
     *
     * return a view named 'order::order.index' with the variable  passed to it.
     */
    public function refused(RefusedRequest $request, $id)
    {
        $this->service->refused($request, $id);
        return redirect(url('/depositRequest/list'));
    }
}

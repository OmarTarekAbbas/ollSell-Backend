<?php

namespace Modules\Finance\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Finance\Http\Resources\WalletResource;
use Modules\Finance\Service\TransactionService;
use Modules\Order\Exports\Order\WalletExport;
use Maatwebsite\Excel\Facades\Excel;

class FinanceController extends BasicController
{
    private $service;

    /**
     * This is a constructor function that initializes a TransactionService object.
     *
     * param TransactionService Service The parameter "Service" is an instance of the TransactionService
     * class, which is being injected into the constructor of the current class. This is a common
     * practice in dependency injection, where the dependencies of a class are passed in as constructor
     * parameters rather than being instantiated within the class itself. This allows for better
     */
    public function __construct(TransactionService $Service)
    {
        $this->service = $Service;
    }

    /**
     * This function returns an API response for the PaymentProfit service.
     *
     * param Request request  is an instance of the Request class which is used to retrieve
     * data from the HTTP request. It contains information such as the request method, headers, and
     * parameters. In this case, it is being passed as a parameter to the PaymentProfit method of a
     * service class.
     *
     * return the result of the `PaymentProfit` method of the `` object, which is being passed
     * the `` object as a parameter. The result is then being passed to the `apiResponse`
     * method, which is likely formatting the result in a specific way for an API response.
     */
    public function PaymentProfit(Request $request)
    {
        return $this->apiResponse($this->service->PaymentProfit($request));
    }

    public function walletExport(Request $request)
    {
        $apiResponse = $this->apiResponse($this->service->PaymentProfit($request));
        $isStatus = $request->get('isStatus');
        $walletExport = new WalletExport($apiResponse, $isStatus);
        $file = "Pending.xlsx";
        if($isStatus)
        {
            $file = "Outstanding.xlsx";
        }
        return Excel::download($walletExport, $file, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function listWallet(Request $request)
    {
        return $this->apiResponse(WalletResource::collection($this->service->listWallet($request, $this->pagination(),
            $this->perPage())));
    }
}

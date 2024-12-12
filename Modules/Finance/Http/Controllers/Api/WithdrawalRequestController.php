<?php

namespace Modules\Finance\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Finance\Actions\Transaction\CheckOrderTransactionAction;
use Modules\Finance\Entities\WithdrawalRequest;
use Modules\Finance\Http\Requests\WithdrawalRequest\Api\CreateRequest;
use Modules\Finance\Service\WithdrawalRequestService;

class WithdrawalRequestController extends BasicController
{
    /* `private ;` is declaring a private class property called ``. This property is
    being used to store an instance of the `WithdrawalRequest` class, which is being passed to the
    constructor of the `WithdrawalRequestController` class. This allows the
    `WithdrawalRequestController` class to access the methods and properties of the
    `WithdrawalRequest` class. */
    private $service;

    /**
     * This is a constructor function that initializes a class property with an instance of the
     * WithdrawalRequest class.
     *
     * param WithdrawalRequest Service The parameter "Service" is an instance of the
     * "WithdrawalRequest" class that is being passed to the constructor of another class. This is a
     * common practice in object-oriented programming where one class depends on another class to
     * perform certain tasks. By passing an instance of the "WithdrawalRequest" class
     */
    public function __construct(WithdrawalRequestService $Service)
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
    public function store(CreateRequest $request)
    {
        if (user()->earningsWithdrawal < $request['amount']) {
            return $this->unKnowError(trans('orders.Sorry! Your balance is not enough'));
        }
        $transaction = App(CheckOrderTransactionAction::class)->execute(user(), $request['amount']);
        if ($transaction['check'] == false) {
            if ($transaction['min_amount'] == 0) {
                return $this->unKnowError(trans('orders.amount must max', ['max' => $transaction['max_amount']]));
            }
            return $this->unKnowError(trans('orders.amount must', ['min' => $transaction['min_amount'], 'max' => $transaction['max_amount']]));
        }
        $request->merge(['order_id' => $transaction['order_ids']]);
        $withdrawalRequest = $this->service->store($request);
        if ($withdrawalRequest) {
            return $this->updateResponse(
                $withdrawalRequest,
                trans('orders.withdrawal Request has create successfully')
            );
        }
        return $this->unKnowError(trans('orders.An error occurred, please try again later'));
    }

    /**
     * The function `earningsWithdrawal` processes a withdrawal request, checking if the requested
     * amount is valid and if the user's balance is sufficient before proceeding with the withdrawal.
     *
     * @param Request request The `earningsWithdrawal` function you provided seems to handle the
     * withdrawal of earnings based on the amount requested by the user. Here's a breakdown of the
     * function:
     *
     * @return The function `earningsWithdrawal` returns different responses based on certain
     * conditions:
     */
    public function earningsWithdrawal(Request $request)
    {
        if ($request['amount'] <= 0) {
            return $this->unKnowError(trans('orders.The value must not be 0'));
        }
        if (user()->earningsWithdrawal < $request['amount']) {
            return $this->unKnowError(trans('orders.Sorry! Your balance is not enough'));
        }
        $transaction = App(CheckOrderTransactionAction::class)->execute(user(), $request['amount']);
        if ($transaction['check'] == false) {
            if ($transaction['min_amount'] == 0) {
                return $this->unKnowError(trans('orders.amount must max', ['max' => $transaction['max_amount']]));
            }
            return $this->unKnowError(trans('orders.amount must', ['min' => $transaction['min_amount'], 'max' => $transaction['max_amount']]));
        }
        $request->merge(['order_id' => $transaction['order_ids']]);
        $earningsWithdrawal = $this->service->earningsWithdrawal($request);
        if ($earningsWithdrawal) {
            return $this->updateResponse(
                $earningsWithdrawal,
                trans('orders.withdrawal Request has create successfully')
            );
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
        $data = $this->service->list($request, $this->pagination(), $this->perPage());
        if ($data) {
            return $this->apiResponse($data);
        }
        return $this->unKnowError();
    }

    /**
     * The function `storeChat` stores a chat message related to a withdrawal request if the chat
     * session is open; otherwise, it returns an error response.
     * 
     * @param Request request The `storeChat` function is responsible for storing a chat message
     * related to a withdrawal request. Here is a breakdown of the parameters used in the function:
     * @param withdrawalRequestId The `withdrawalRequestId` parameter in the `storeChat` function is
     * used to identify the specific Withdrawal Request for which the chat message is being stored. It
     * is used to retrieve the Withdrawal Request from the database and perform operations related to
     * that specific request, such as checking if a chat session
     * 
     * @return The function `storeChat` is returning a JSON response. If the chat session for the
     * withdrawal request can be opened, it creates a new chat message associated with the withdrawal
     * request and returns a JSON response containing the created chat message with a status code of
     * 201 (Created). If the chat session for the request has been closed, it returns a JSON response
     * with an error message indicating that the chat session
     */
    public function storeChat(Request $request, $withdrawalRequestId)
    {
        $withdrawalRequest = WithdrawalRequest::findOrFail($withdrawalRequestId);

        if (!$withdrawalRequest->canOpenChat()) {
            return $this->unKnowError('The chat session for this request has been closed.');
        }

        $chat = $withdrawalRequest->chats()->create([
            'message' => $request->message,
            'messagable_type' => user()->getMorphClass(),
            'messagable_id' => user()->id,
        ]);

        return response()->json(['chat' => $chat], 201);
    }

    /**
     * The function `listChat` retrieves and returns all messages related to a specific withdrawal
     * request, ensuring user authorization.
     * 
     * @param withdrawalRequestId The `listChat` function takes a `withdrawalRequestId` as a parameter.
     * This parameter is used to retrieve a specific withdrawal request from the database. The function
     * then checks if the authenticated user has permission to access the withdrawal request based on
     * certain conditions.
     * 
     * @return The function `listChat` returns a JSON response containing an array of chats associated
     * with a specific withdrawal request. The chats are retrieved from the database, sorted by
     * creation date in ascending order, and include related messages. If the user does not have
     * permission to access the withdrawal request, an unauthorized error response with status code 403
     * is returned.
     */
    public function listChat($withdrawalRequestId)
    {
        $withdrawalRequest = WithdrawalRequest::findOrFail($withdrawalRequestId);

        if ($withdrawalRequest->dropshipper_id !== user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $chats = $withdrawalRequest->chats()->with('messagable')->orderBy('created_at', 'asc')->get();

        return $this->apiResponse($chats);
    }
}

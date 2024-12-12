<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Basic\Http\Requests\DateRequest;
use Modules\Finance\Entities\WithdrawalRequest;
use Modules\Finance\Http\Requests\WithdrawalRequest\ApproveRequest;
use Modules\Finance\Http\Requests\WithdrawalRequest\RefusedRequest;
use Modules\Finance\Service\WithdrawalRequestService;

class WithdrawalRequestController extends BasicController
{
    protected $service;

    /**
     * This is a constructor function that sets middleware and permissions for withdrawal request actions
     * and initializes a WithdrawalRequestService object.
     *
     * param WithdrawalRequestService Service WithdrawalRequestService is a dependency injection of the
     * WithdrawalRequestService class. It is used to access the methods and properties of the
     * WithdrawalRequestService class within the constructor and other methods of the class where it is
     * injected.
     */
    public function __construct(WithdrawalRequestService $Service)
    { //todo change
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_withdrawal_request')->only(['index', 'show']);
        $this->middleware('permission:update_withdrawal_request')->only(['showUploadForm', 'refused', 'uploadImage']);
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
    public function index(DateRequest $request)
    {
        $withdrawalRequests = $this->service->index($request, true);
        if ($request->ajax()) {
            return view(
                checkView('finance::withdrawalRequest.table'),
                compact('withdrawalRequests', 'request')
            );
        }
        return $this->getDashboardView('finance::withdrawalRequest.index', compact('withdrawalRequests', 'request'));
    }

    public function show($id)
    {
        $request = new Request();
        $request->merge(['id' => $id]);

        $data = $this->service->show($id);
        $orders = $this->service->getOrder($data->order_id);

        $withdrawalRequest = WithdrawalRequest::findOrFail($id);


        $chats = $withdrawalRequest->chats()->with('messagable')->orderBy('created_at', 'asc')->get();

        return $this->getDashboardView('finance::withdrawalRequest.show', compact('data', 'request', 'orders', 'chats', 'withdrawalRequest'));
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
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $withdrawalRequest = WithdrawalRequest::findOrFail($withdrawalRequestId);

        if (!$withdrawalRequest->canOpenChat()) {
            return response()->json(['error' => 'The chat session for this request has been closed.'], 403);
        }

        $chat = $withdrawalRequest->chats()->create([
            'message' => $request->message,
            'messagable_type' => user()->getMorphClass(),
            'messagable_id' => user()->id,
        ]);

        return response()->json([
            'success' => true,
            'chat' => [
                'messagable_id' => $chat->messagable_id,
                'username' => ($chat->messagable_type == 'App\Models\User') ? 'Admin' : user()->first_name . ' ' . user()->last_name,
                'message' => $chat->message,
                'created_at' => $chat->created_at->format('d M Y, h:i A'),
                'bubbleStyle' => ($chat->messagable_type == 'App\Models\User') ? 'background-color: #e8f4fd; border-left: 4px solid #4759b8;' : '',
                'directionStyle' => ($chat->messagable_type == 'App\Models\User') ? 'direction: rtl;' : '',
            ],
        ], 201);
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
        return redirect(url('/withdrawalRequest/list'));
    }

    /**
     * The `inProgress` function processes a withdrawal request by calling the `refused` method of a
     * service class and then redirects to the withdrawal request list page.
     * 
     * @param Request request The `` parameter in the `inProgress` function is an instance of
     * the `Illuminate\Http\Request` class in Laravel. It represents the HTTP request that is being
     * made to the server.
     * @param id The `` parameter in the `inProgress` function likely represents the unique
     * identifier of a withdrawal request or some other entity. It is used to identify the specific
     * request that is being processed or updated within the function.
     * 
     * @return A redirect response to the URL '/withdrawalRequest/list' is being returned.
     */
    public function inProgress(Request $request, $id)
    {
        $this->service->inProgress($request, $id);
        return redirect(url('/withdrawalRequest/list'));
    }

    /**
     * The function `showUploadForm` returns a view for uploading withdrawal requests in a finance
     * dashboard.
     * 
     * @return The function `showUploadForm()` is returning a view named
     * `'finance::withdrawalRequest.upload'`.
     */
    public function showUploadForm()
    {
        return $this->getDashboardView('finance::withdrawalRequest.upload');
    }

    /**
     * The function `uploadImage` processes an approval request for an image upload and redirects to a
     * specific URL upon completion.
     * 
     * @param ApproveRequest request The `uploadImage` function takes an `ApproveRequest` object as a
     * parameter. This object likely contains data related to an approval request, such as an ID or
     * other information needed to process the request.
     * 
     * @return The function `uploadImage` is returning a redirect response to the URL
     * `/withdrawalRequest/list`.
     */
    public function uploadImage(ApproveRequest $request)
    {
        $id = $request->id;
        $this->service->approved($request, $id);
        return redirect(url('/withdrawalRequest/list'));
    }
}

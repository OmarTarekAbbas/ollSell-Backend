<?php

namespace Modules\Acl\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Acl\Http\Resources\Dropshipper\DropshipperPaymentResource;
use Modules\Acl\Service\DropshipperPaymentService;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Acl\Http\Requests\DropshipperPayment\Api\CreateRequest;
use Modules\Acl\Http\Requests\DropshipperPayment\Api\UpdateRequest;

/**
 * @group Dropshipper management
 *
 * APIs for managing dropshippers
 */
class DropshipperPaymentController extends BasicController
{
    private $service;

    public function __construct(DropshipperPaymentService $Service)
    {
        $this->service = $Service;
    }

    public function list(Request $request)
    {
        $data = $this->service->list($request, $this->pagination(), $this->perPage());
        if($data)
        {
            return $this->apiResponse($data);
        }
        return $this->unKnowError('failed');
    }

    /**
     * Create Account
     *
     * The Create Account endpoint allows users to register and create a new account in the system.
     * This endpoint handles the registration process, including sending a verification code to
     * the user's email address for account activation.
     *
     * This endpoint receives the necessary information to create a new account.
     * The user needs to provide the required details, such as their name,
     * email address, and password, as per the request parameters. Upon successful registration,
     * a verification code will be sent to the user's email address.
     *
     */
    public function store(CreateRequest $request)
    {
        $data = $this->service->store($request);
        if($data)
        {
            return $this->createResponse($data, 'done');
        }
        return $this->unKnowError();
    }

    public function show($id)
    {
        $data = $this->service->show($id);
        if($data)
        {
            return $this->apiResponse(new DropshipperPaymentResource($data), 'done');
        }
        return $this->unKnowError();
    }

    public function update(UpdateRequest $request)
    {
        $data = $this->service->update($request);
        if($data)
        {
            return $this->createResponse($data, 'done');
        }
        return $this->unKnowError();
    }

    /**
     * Delete Dropshipper
     *
     * The Delete Dropshipper endpoint allows users to delete their dropshipper account from the system.
     * This endpoint provides a way for users to permanently remove their account and associated data.
     *
     * This endpoint deletes the dropshipper account based on the provided request parameters.
     * The user needs to provide the necessary details or confirmation to initiate the account deletion process.
     *
     * @authenticated
     */
    public function delete(Request $request)
    {
        $data = $this->service->destroy($request);
        if($data)
        {
            return $this->deleteResponse('done');
        }
        return $this->unKnowError('failed');
    }

    public function isMain($id)
    {
        $data = $this->service->isMain($id);
        if($data)
        {
            return $this->apiResponse('done');
        }
        return $this->unKnowError('failed');
    }
}

<?php

namespace Modules\Acl\Http\Controllers\Api;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Illuminate\Support\Facades\Hash;
use Modules\Acl\Service\DropshipperService;

/**
 * @group Authentication
 *
 * APIs for managing forgetting password
 */
class ForgetPasswordController extends BasicController
{
    private $service;

    /**
     * This is a constructor function that initializes a DropshipperService object.
     *
     * param DropshipperService Service The parameter "Service" is an instance of the
     * "DropshipperService" class that is being injected into the constructor of another class. This is
     * a common practice in object-oriented programming and is known as dependency injection. By
     * injecting the service as a dependency, the class can use its methods and properties
     */
    public function __construct(DropshipperService $Service)
    {
        $this->service = $Service;
    }

    /**
     * This function changes the password of a user if the email and password are valid.
     *
     * param Request request  is an instance of the Request class, which is used to retrieve
     * data from HTTP requests. It contains information about the current request, such as the HTTP
     * method, headers, and any data submitted in the request body. In this function,  is used
     * to retrieve the email and password submitted by
     *
     * return If the email is set and not empty, the function will return either an update response
     * with an empty array and the message "change done" if the password was successfully updated, or
     * an API validation error with the message "wrong password" if the user was not found. If the
     * email is not set or empty, nothing will be returned.
     */
    public function changePassword(Request $request)
    {
        if(isset($request->email) && !empty($request->email))
        {
            $data = $this->service->findBy($request, get: "first");
            if($data)
            {
                //todo change
                user()->update(['password' => Hash::make($request->password)]);
                return $this->updateResponse([], trans('auth.change done'));
            }
            return $this->apiValidation(trans('auth.Wrong password'));
        }
    }
}

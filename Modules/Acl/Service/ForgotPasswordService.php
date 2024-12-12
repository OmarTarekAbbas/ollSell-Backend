<?php

namespace Modules\Acl\Service;

use Illuminate\Http\Request;
use Modules\Acl\Repositories\UserRepository;
use Modules\Basic\Service\BasicService;
class ForgotPasswordService extends BasicService
{

    protected  $repo;
    protected  $userService;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(UserRepository $repository, UserService $userService)
    {
        $this->repo = $repository;
        $this->userService = $userService;
    }

    /**
     * This function calls the sendLink method of the userService object with a request parameter and
     * returns the result.
     * 
     * param Request request  is an instance of the Request class, which is a class in the
     * Laravel framework used to represent an HTTP request. It contains information about the request
     * such as the HTTP method, URL, headers, and any data sent in the request body. In this code
     * snippet, the  parameter is passed
     * 
     * return The `sendLink` method of the `userService` object is being called with the ``
     * parameter, and the result of that method call is being returned.
     */
    public function sendLink(Request $request)
    {
        return $this->userService->sendLink($request);
    }

    /**
     * This function calls the ResetPassword method of a userService object with a request parameter.
     * 
     * param Request request  is an object of the Request class which contains the data sent
     * by the client in the HTTP request. It can contain information such as form data, query
     * parameters, headers, and more. In this case, it is being passed as a parameter to the
     * ResetPassword function of a UserService class.
     * 
     * return The function `ResetPassword` is returning the result of calling the `ResetPassword`
     * method of the `userService` object with the `` parameter. The specific return value
     * depends on the implementation of the `ResetPassword` method in the `userService` class.
     */
    public function ResetPassword(Request $request)
    {
        return $this->userService->ResetPassword($request);
    }
}

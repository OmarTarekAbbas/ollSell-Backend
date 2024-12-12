<?php

namespace Modules\Acl\Service;

use Illuminate\Http\Request;
use Modules\Acl\Repositories\PermissionRepository;
use Modules\Basic\Service\BasicService;

class PermissionService extends BasicService
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(PermissionRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * This function returns the result of a query based on the parameters passed in the request
     * object.
     * 
     * param Request request  is an instance of the Request class in Laravel. It represents an
     * HTTP request that has been sent to the server and contains information such as the request
     * method, URL, headers, and any data that was sent with the request. In this context, it is being
     * passed as a parameter to a function
     * 
     * return The `findBy` method is being called on the repository object with the ``
     * parameter, and the result of that method call is being returned. The specific result will depend
     * on the implementation of the `findBy` method in the repository class.
     */
    public function findBy(Request $request)
    {
        return $this->repo->findBy($request);
    }
}

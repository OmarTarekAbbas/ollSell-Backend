<?php

namespace Modules\Acl\Service;

use Illuminate\Http\Request;
use Modules\Acl\Repositories\RoleRepository;
use Modules\Acl\Repositories\UserRepository;
use Modules\Basic\Service\BasicService;

class RoleService extends BasicService
{
    protected $repo;
    protected $permissionService;
    protected $userRepository;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(RoleRepository $repository, PermissionService $permissionService, UserRepository $userRepository)
    {
        $this->repo = $repository;
        $this->permissionService = $permissionService;
        $this->userRepository = $userRepository;
    }

    /**
     * This function returns the result of a search query based on the parameters passed in the request
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


    /**
     * This PHP function saves a role and adds permissions to it.
     *
     * param Request request  is an instance of the Request class which contains the data
     * submitted by the user through a form or an HTTP request. It is used to retrieve input data,
     * files, cookies, and other information sent along with the request. In this case, it is used to
     * save the role data submitted by the
     *
     * return the result of calling the `addRole` method on the `` object with the ``
     * and `` parameters.
     */
    public function store(Request $request)
    {
        $role = $this->repo->save($request);
        $permissions = $this->permissionList(new Request);
        return $this->repo->addRole($permissions, $role);
    }

    /**
     * This PHP function updates a role with new permissions based on a given request.
     *
     * param Request request  is an instance of the Request class, which is a class in the
     * Laravel framework used to handle HTTP requests. It contains information about the current
     * request, such as the HTTP method, URL, headers, and any data sent in the request body. In this
     * case, it is being used to retrieve
     *
     * return the result of calling the `updateRole` method on the repository object (`->repo`)
     * with the ``, ``, and `` parameters.
     */
    public function update(Request $request, $id = null)
    {
        $role = $this->repo->findOne($request->role_id);
        $permissions = $this->permissionService->findBy(new Request);
        return $this->repo->updateRole($permissions, $role, $request);
    }

    /**
     * This function returns a list of permissions based on the request parameters.
     *
     * param Request request  is an instance of the Request class, which is a class in the
     * Laravel framework used to represent an HTTP request. It contains information about the request
     * such as the HTTP method, URL, headers, and any data sent in the request body. In this code
     * snippet, the  parameter is being
     *
     * return the result of the `findBy` method of the `permissionService` object, which is being
     * passed the `` object as a parameter. The specific result being returned depends on the
     * implementation of the `findBy` method.
     */
    public function permissionList(Request $request)
    {
        return $this->permissionService->findBy($request);
    }

    /**
     * This PHP function returns a collection of RoleListResource based on the parameters passed to it.
     *
     * param Request request The HTTP request object that contains information about the current
     * request being made.
     * param pagination A boolean value that determines whether the results should be paginated or
     * not. If set to true, the results will be paginated based on the  parameter.
     * param perPage The number of items to be displayed per page in the paginated list.
     * param recursiveRel The  parameter is an array that specifies the relationships
     * that should be recursively loaded when retrieving the list of roles. This means that not only
     * the roles will be retrieved, but also their related models that are specified in the
     *  array.
     *
     * return a collection of RoleListResource objects. The objects are obtained by calling the "list"
     * method of the repository object with the provided parameters. The "list" method returns a
     * collection of roles, which are then transformed into RoleListResource objects using the
     * RoleListResource::collection method.
     */
    public function list(Request $request, $pagination = false, $perPage = 10, $recursiveRel = [],$moreConditionForFirstLevel=[])
    {
        return $this->repo->list($request,$moreConditionForFirstLevel, $recursiveRel, $pagination, $perPage);
    }
}

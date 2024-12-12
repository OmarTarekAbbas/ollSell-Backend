<?php

namespace Modules\Acl\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Acl\Service\RoleService;
use Modules\Basic\Http\Controllers\BasicController;

class RoleController extends BasicController
{
    protected $service;

    /**
     * @extends BasicController
     * controller user about web function
     * @required user login
     */
    public function __construct(RoleService $Service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_roles')->only('index');
        $this->middleware('permission:create_roles')->only('create');
        $this->middleware('permission:create_roles')->only('store');
        $this->middleware('permission:update_roles')->only('edit');
        $this->middleware('permission:update_roles')->only('update');
        $this->middleware('permission:delete_roles')->only('destroy');
        $this->service = $Service;
        $this->models = modelPermission();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->getDashboardView('acl::roles.index', [
            'roles' => $this->service->findBy($request),
            'models' => $this->models,
            'permissions' => $this->service->permissionList($request),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * param Request $request
     */
    //todo change function
    public function store(Request $request)
    {
        $role = $this->service->store($request);
        return response()->json($role);
    }

    /**
     * Show the specified resource.
     * param int $id
     */
    //todo change function
    public function show($id)
    {
        can('show_roles');
        $role = $this->service->show($id);
        return $this->getDashboardView('acl::roles.show', [
            'role' => $role,
            'models' => $this->models,
            'permissions' => $this->service->permissionList(new Request),
            'role_permissions' => $role->permissions,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * param Request $request
     */
    //todo change function
    public function edit(Request $request)
    {
        $role = $this->service->show($request->id);

     
        return $this->getDashboardView('acl::roles.edit', [
            'role' => $role,
            'models' => $this->models,
            'permissions' => $this->service->permissionList(new Request),
            'role_permissions' => $role->permissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * param Request $request
     */
    //todo change function
    public function update(Request $request)
    {
        $role = $this->service->update($request, $id = null);
        return $role;
    }
}

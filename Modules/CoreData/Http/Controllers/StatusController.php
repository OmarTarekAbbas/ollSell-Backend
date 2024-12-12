<?php

namespace Modules\CoreData\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\Http\Requests\Status\CreateRequest;
use Modules\CoreData\Http\Requests\Status\EditRequest;
use Modules\CoreData\Service\StatusService;

class StatusController extends BasicController
{
    protected $service;

    /**
     * This function sets up middleware and permissions for a StatusService object in a PHP
     * application.
     * 
     * param StatusService Service The  parameter is an instance of the StatusService class,
     * which is likely a service class responsible for handling business logic related to status
     * entities in the application. It is being injected into the constructor using dependency
     * injection.
     */
    public function __construct(StatusService $Service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_status')->only('index');
        $this->middleware('permission:create_status')->only(['create','store']);
        $this->middleware('permission:update_status')->only(['edit','update']);
        $this->middleware('permission:delete_status')->only('destroy');
        $this->service = $Service;
    }

    /**
     * Display a listing of the resource.
     * return Renderable
     */
    public function index(Request $request)
    {
        $status = $this->service->findBy($request);
        return $this->getDashboardView('coredata::status.index', compact('status'));
    }

    /**
     * Display a listing of the resource.
     * return Renderable
     */
    public function edit($id)
    {
        $data = $this->service->show($id);
        return $this->getDashboardView('coredata::status.edit', compact('data'));
    }

    /**
     * Display a listing of the resource.
     * return Renderable
     */
    public function update(EditRequest $request, $id)
    {
        $data = $this->service->update($request, $id);
        if ($data) {
            return redirect(route('status.index'))->with("message", 'Done');
        }
        return redirect(route('status.edit', $id))->with('problem');
    }

    /**
     * Display a listing of the resource.
     * return Renderable
     */
    public function create()
    {
        return $this->getDashboardView('coredata::status.create');
    }

    /**
     * Display a listing of the resource.
     * return Renderable
     */
    public function store(CreateRequest $request)
    {
        $data = $this->service->store($request);
        if ($data) {
            return redirect(route('status.index'))->with("message", 'Done');
        }
        return redirect(route('status.create'))->with('problem');
    }
}

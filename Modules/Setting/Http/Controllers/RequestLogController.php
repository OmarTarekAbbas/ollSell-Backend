<?php

namespace Modules\Setting\Http\Controllers;

use Modules\Basic\Http\Requests\DateRequest;
use Modules\Setting\Service\RequestLogService;
use Modules\Basic\Http\Controllers\BasicController;

/**
 * @extends BasicController
 * controller user about web function
 */
class RequestLogController extends BasicController
{
    protected $service;

    /**
     * controller user about web function
     * @required user login
     */
    public function __construct(RequestLogService $service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:request_log')->only('index');
        $this->service = $service;
    }

    /**
     * param Request $request
     * get all user to manage it
     */
    public function index(DateRequest $request)
    {
        $data = $this->service->indexDashboard($request);
        if ($request->ajax()) {
            return view('setting::log.request.table')->with(['data' => $data]);
        }
        return $this->getDashboardView('setting::log.request.index', ['data' => $data]);
    }
}

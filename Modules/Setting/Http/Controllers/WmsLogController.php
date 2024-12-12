<?php

namespace Modules\Setting\Http\Controllers;

use Modules\Basic\Http\Requests\DateRequest;
use Modules\MasterCatalog\Service\ProductLogService;
use Modules\Basic\Http\Controllers\BasicController;

/**
 * @extends BasicController
 * controller user about web function
 */
class WmsLogController extends BasicController
{
    protected $service;

    /**
     * controller user about web function
     * @required user login
     */
    public function __construct(ProductLogService $service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:wms_log')->only('index');
        $this->service = $service;
    }

    /**
     * param Request $request
     * get all user to manage it
     */
    public function index(DateRequest $request)
    {
        $data = $this->service->index($request,pagination:true);
        if ($request->ajax()) {
            return view('setting::log.wms.table')->with(['data' => $data]);
        }
        return $this->getDashboardView('setting::log.wms.index', ['data' => $data]);
    }
}

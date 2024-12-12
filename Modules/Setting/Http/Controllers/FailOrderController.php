<?php

namespace Modules\Setting\Http\Controllers;

use Modules\Basic\Http\Requests\DateRequest;
use Modules\Setting\Service\FailOrderService;
use Modules\Basic\Http\Controllers\BasicController;

/**
 * @extends BasicController
 * controller user about web function
 */
class FailOrderController extends BasicController
{
    protected $service;

    /**
     * controller user about web function
     * @required user login
     */
    public function __construct(FailOrderService $service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
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
            return view('setting::fail_order.table')->with(['data' => $data]);
        }
        return $this->getDashboardView('setting::fail_order.index', ['data' => $data]);
    }

        
    public function changeActive($id = null)
    {
        if (is_null($id)) {
            $id = request('id');
        }
        return response()->json(['active' => $this->service->changeStatus(
            $id,
            'active'
        )->active ? 'true' : 'false']);
    }
}

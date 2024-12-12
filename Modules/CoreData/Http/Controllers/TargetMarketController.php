<?php

namespace Modules\CoreData\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\Http\Requests\TargetMarket\CreateRequest;
use Modules\CoreData\Http\Requests\TargetMarket\EditRequest;
use Modules\CoreData\Service\TargetMarketService;

class TargetMarketController extends BasicController
{
    protected $service;

    /**
     * This function constructs a TargetMarketService object and sets middleware permissions for
     * various actions.
     * 
     * param TargetMarketService Service This is an instance of the TargetMarketService class that is
     * injected into the constructor using dependency injection. It is likely used to perform various
     * operations related to the target market, such as retrieving data from a database or updating
     * records.
     */
    public function __construct(TargetMarketService $Service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_target_market')->only('index');
        $this->middleware('permission:create_target_market')->only(['create','store']);
        $this->middleware('permission:update_target_market')->only(['edit','update']);
        $this->middleware('permission:delete_target_market')->only('destroy');
        $this->service = $Service;
    }

    /**
     * This function returns a view for the target market index and uses DataTables to display data if
     * the request is made through AJAX.
     * 
     * param Request request  is an instance of the Request class, which represents an HTTP
     * request made to the application. It contains information about the request, such as the HTTP
     * method, URL, headers, and any data sent in the request body. In this code,  is used to
     * check if the request is an
     * 
     * return If the request is an AJAX request, a DataTables response is returned with the data from
     * the `` variable. If the request is not an AJAX request, the view
     * `coredata::target_market.index` is returned within the dashboard view.
     */
    public function index(Request $request)
    {
        $target_markets = $this->service->index($request);

        if ($request->ajax()) {
            return view('coredata::target_market.table')->with(['target_markets' => $target_markets]);
        }


        return $this->getDashboardView('coredata::target_market.index', compact('target_markets'));
    }

    /**
     * This PHP function returns a view for creating a target market in a dashboard.
     * 
     * return a view named 'coredata::target_market.create' which is likely a form for creating a new
     * target market.
     */
    public function create()
    {
        return $this->getDashboardView('coredata::target_market.create');
    }

    /**
     * This PHP function stores data from a CreateRequest object and redirects to the target_market
     * index page with a success or error message.
     * 
     * param CreateRequest request  is an instance of the CreateRequest class, which is a
     * custom request class that extends the base Laravel request class. It contains the data submitted
     * by the user through a form or an API request, and it also includes validation rules and messages
     * for the data. The  parameter is passed to the
     * 
     * return If the `` variable is truthy, the function will return a redirect to the
     * `target_market.index` route with a flash message "Done". Otherwise, it will return a redirect to
     * the `target_market.create` route with a flash message "problem".
     */
    public function store(CreateRequest $request)
    {
        $data = $this->service->store($request);
        if ($data) {
            return redirect(route('target_market.index'))->with('Done');
        }
        return redirect(route('target_market.create'))->with('problem');
    }

    /**
     * This PHP function retrieves data for a target market and returns a view for editing it.
     * 
     * param id The parameter "id" is the identifier of the target market that needs to be edited. It
     * is used to retrieve the data of the target market from the database and pass it to the view for
     * editing.
     * 
     * return a view named 'coredata::target_market.edit' with the data of the target market to be
     * edited.
     */
    public function edit($id)
    {
        $data = $this->service->show($id);
        return $this->getDashboardView('coredata::target_market.edit', compact('data'));
    }

    /**
     * This function updates data and redirects the user to the target market index page with a success
     * message or back to the edit page with an error message.
     * 
     * param EditRequest request  is an instance of the EditRequest class, which is a custom
     * request class that contains validation rules and messages for updating a target market. It is
     * used to validate the incoming request data before processing it further.
     * param id  is a parameter that represents the unique identifier of the target market that
     * needs to be updated. It is used to retrieve the specific target market from the database and
     * update its information based on the data provided in the EditRequest object.
     * 
     * return If the update is successful, the function will return a redirect to the index page with
     * a success message. If the update fails, the function will return a redirect to the edit page
     * with a problem message.
     */
    public function update(EditRequest $request, $id)
    {
        $data = $this->service->update($request, $id);
        if ($data) {
            return redirect(route('target_market.index'))->with("message", 'Done');
        }
        return redirect(route('target_market.edit', $id))->with('problem');
    }
}

<?php

namespace Modules\CoreData\Http\Controllers;

use Illuminate\Http\Request;
use Modules\CoreData\Service\RegionService;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\Http\Requests\Region\EditRequest;
use Modules\CoreData\Http\Requests\Region\CreateRequest;

class RegionController extends BasicController
{
    protected $service;

    /**
     * This function sets up middleware and assigns a StateService object to the class property.
     * 
     * param RegionService Service The RegionService class instance that is injected into the
     * constructor. It is likely used to perform business logic related to states in the application.
     */
    public function __construct(RegionService $Service)
    {
        $this->middleware('auth');
        $this->middleware('permission:view_region')->only('index');
        $this->middleware('permission:create_region')->only(['create','store']);
        $this->middleware('permission:update_region')->only(['edit','update']);
        $this->middleware('permission:delete_region')->only('destroy');
        $this->service = $Service;
    }

    /**
     * This function returns a view for the state index page or a DataTables response if the request is
     * made via AJAX.
     * 
     * param Request request  is an instance of the Request class which represents an HTTP
     * request. It contains information about the request such as the HTTP method, headers, and
     * parameters. In this code snippet, it is used to check if the request is an AJAX request or not
     * and to pass the request parameters to the service's
     * 
     * @return If the request is an AJAX request, the function returns a DataTables instance with the
     * data obtained from the service's `findBy` method. If the request is not an AJAX request, the
     * function returns the dashboard view for the state index page.
     */
    public function index(Request $request)
    {
        $states = $this->service->index($request);

        if ($request->ajax()) {
            return view('coredata::state.table')->with(['states' => $states]);
        }


        return $this->getDashboardView('coredata::region.index', compact('regions'));
    }

    /**
     * This PHP function returns a view for creating a new region with a list of countries.
     * 
     * @return a view called 'coredata::region.create' with a variable called 'countries' that contains
     * a list of countries obtained from the 'countryList' method of the 'service' object.
     */
    public function create()
    {
        $countries = $this->service->countryList();
        $cities = $this->service->cityList();
        return $this->getDashboardView('coredata::region.create', compact('countries', 'cities'));
    }

    /**
     * This PHP function stores data from a CreateRequest object and redirects to the region index page
     * with a success or error message.
     * 
     * param CreateRequest request  is an instance of the CreateRequest class, which is a
     * custom request class that extends the base Laravel request class. It contains the data submitted
     * by the user through a form or an API request, and it also includes any validation rules and
     * messages defined in the class. The  parameter is passed
     * 
     * @return If the `` variable is truthy, the function will return a redirect to the
     * `region.index` route with a flash message "Done". Otherwise, it will return a redirect to the
     * `region.create` route with a flash message "problem".
     */
    public function store(CreateRequest $request)
    {
        $data = $this->service->store($request);
        if ($data) {
            return redirect(route('region.index'))->with('Done');
        }
        return redirect(route('region.create'))->with('problem');
    }

    /**
     * This PHP function retrieves data for editing a region and returns a view with the data and a list
     * of countries.
     * 
     * param id The parameter "id" is the identifier of the region that needs to be edited. It is used
     * to retrieve the data of the region from the service layer and pass it to the view for editing.
     * 
     * @return a view named 'coredata::region.edit' with the variables  and  passed to
     * it.
     */
    public function edit($id)
    {
        $data = $this->service->show($id);
        $countries = $this->service->countryList();
        $cities = $this->service->cityList();
        return $this->getDashboardView('coredata::region.edit', compact('data', 'countries', 'cities'));
    }

    /**
     * This PHP function updates data and redirects to the index page with a success message or back to
     * the edit page with a problem message.
     * 
     * param EditRequest request  is an instance of the EditRequest class, which is a custom
     * request class that contains validation rules and messages for updating a region in the
     * application. It is used to validate the incoming request data before processing it further.
     * param id  is a parameter that represents the unique identifier of the region that needs to be
     * updated. It is used to identify the specific region record in the database that needs to be
     * updated.
     * 
     * @return If the update is successful, the function will return a redirect to the index page with
     * a success message. If the update fails, the function will return a redirect to the edit page
     * with a problem message.
     */
    public function update(EditRequest $request, $id)
    {
        $data = $this->service->update($request, $id);
        if ($data) {
            return redirect(route('region.index'))->with("message", 'Done');
        }
        return redirect(route('region.edit', $id))->with('problem');
    }
}

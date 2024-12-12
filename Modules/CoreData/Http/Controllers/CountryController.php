<?php

namespace Modules\CoreData\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\Http\Requests\Country\CreateRequest;
use Modules\CoreData\Http\Requests\Country\EditRequest;
use Modules\CoreData\Service\CountryService;

class CountryController extends BasicController
{
    protected $service;

    /**
     * This function constructs a CountryService object and sets middleware permissions for various
     * actions.
     * 
     * param CountryService Service The  parameter is an instance of the CountryService class,
     * which is likely used to handle business logic related to countries in the application. It is
     * being injected into the constructor using dependency injection.
     */
    public function __construct(CountryService $Service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_country')->only('index');
        $this->middleware('permission:create_country')->only(['create','store']);
        $this->middleware('permission:update_country')->only(['edit','update']);
        $this->middleware('permission:delete_country')->only('destroy');
        $this->service = $Service;
    }

    /**
     * This function returns a view for the country index page or a DataTables response if the request
     * is made via AJAX.
     * 
     * param Request request  is an instance of the Request class, which represents an HTTP
     * request made to the application. It contains information about the request, such as the HTTP
     * method, URL, headers, and any data sent in the request body. In this code, it is used to check
     * if the request is an AJAX
     * 
     * return If the request is an AJAX request, the function returns a DataTables instance of the
     * data retrieved by the `` object. If the request is not an AJAX request, the function
     * returns the view for the country index page.
     */
    public function index(Request $request)
    {
        $countries = $this->service->index($request);

        if ($request->ajax()) {
            return view('coredata::country.table')->with(['countries' => $countries]);
        }


        return $this->getDashboardView('coredata::country.index', compact('countries'));
    }

    /**
     * This PHP function returns a view for creating a new country in a dashboard.
     * 
     * return a view named 'coredata::country.create' which is related to the creation of a country.
     */
    public function create()
    {
        return $this->getDashboardView('coredata::country.create');
    }

    /**
     * This PHP function stores data from a CreateRequest object and redirects to the index page with a
     * success or error message.
     * 
     * param CreateRequest request  is an instance of the CreateRequest class, which is a
     * custom request class that extends the base Laravel request class. It contains the data submitted
     * by the user through a form or an API request, and it also includes any validation rules and
     * messages defined in the class. The  parameter is passed
     * 
     * return a redirect response to either the 'country.index' route with a 'Done' message in the
     * session data if the data was successfully stored, or to the 'country.create' route with a
     * 'problem' message in the session data if there was an issue with storing the data.
     */
    public function store(CreateRequest $request)
    {
        $data = $this->service->store($request);
        if ($data) {
            return redirect(route('country.index'))->with('Done');
        }
        return redirect(route('country.create'))->with('problem');
    }

    /**
     * This PHP function retrieves data for a country with a specific ID and returns a view for editing
     * that data.
     * 
     * param id The parameter `` is the identifier of the country that needs to be edited. It is
     * used to retrieve the country data from the database using the `show` method of the `service`
     * object. The retrieved data is then passed to the view for editing.
     * 
     * return a view named 'coredata::country.edit' with the data of a specific country that is
     * retrieved using the 'show' method of a service. The data is passed to the view using the
     * 'compact' function.
     */
    public function edit($id)
    {
        $data = $this->service->show($id);
        return $this->getDashboardView('coredata::country.edit', compact('data'));
    }

    /**
     * This PHP function updates a record and redirects the user to the index page with a success
     * message or back to the edit page with an error message.
     * 
     * param EditRequest request  is an instance of the EditRequest class, which is a custom
     * request class that contains validation rules and messages for updating a country record. It is
     * used to validate the incoming request data before processing it further.
     * param id  is a parameter that represents the ID of the country that needs to be updated. It
     * is used to identify the specific country record in the database that needs to be updated.
     * 
     * return If the update is successful, the function will return a redirect to the index page with
     * a success message. If the update fails, the function will return a redirect to the edit page
     * with a problem message.
     */
    public function update(EditRequest $request, $id)
    {
        $data = $this->service->update($request, $id);
        if ($data) {
            return redirect(route('country.index'))->with("message", 'Done');
        }
        return redirect(route('country.edit', $id))->with('problem');
    }
}

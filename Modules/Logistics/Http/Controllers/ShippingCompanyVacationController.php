<?php

namespace Modules\Logistics\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Logistics\Http\Requests\ShippingCompanyVacation\CreateRequest;
use Modules\Logistics\Http\Requests\ShippingCompanyVacation\EditRequest;
use Modules\Logistics\Service\ShippingCompanyVacationService;
class ShippingCompanyVacationController extends BasicController
{
    protected $service;
    protected $regionService;
    /**
     * This function sets up middleware and assigns a ShippingCompanyVacationServiceService object to a class property.
     * 
     * param ShippingCompanyVacationServiceService Service An instance of the ShippingCompanyVacationServiceService class, which is likely responsible for
     * handling business logic related to shipping_company_vacation (e.g. retrieving, creating, updating, and deleting
     * city data).
     */
    public function __construct(ShippingCompanyVacationService $Service)
    {
   
        $this->middleware('auth');
        $this->middleware('permission:view_shipping_company_vacation')->only('index');
        $this->middleware('permission:create_shipping_company_vacation')->only(['create','store']);
        $this->middleware('permission:update_shipping_company_vacation')->only(['edit','update']);
        $this->middleware('permission:delete_shipping_company_vacation')->only('destroy');
       
        $this->service = $Service;
    
     
    }

    /**
     * This function returns a view for the city index page or a DataTables response if the request is
     * made via AJAX.
     * 
     * param Request request  is an instance of the Request class, which represents an HTTP
     * request made to the application. It contains information about the request such as the HTTP
     * method, URL, headers, and any data sent in the request body. In this code, it is used to check
     * if the request is an AJAX request
     * 
     * return If the request is an AJAX request, the function returns a DataTables instance of the
     * data retrieved by the `` object. If the request is not an AJAX request, the function
     * returns the dashboard view for the city index page.
     */
    public function index(Request $request)
    {
       
        $shipping_company_vacation = $this->service->index($request);
        if ($request->ajax()) {
            return view('logistics::shipping_company_vacation.table')->with(['shipping_company_vacation' => $shipping_company_vacation]);
        }

        return $this->getDashboardView('logistics::shipping_company_vacation.index', compact('shipping_company_vacation'));
    }

    /**
     * This PHP function returns a view for creating a city with a list of countries.
     * 
     * return a view named 'logistics::city.create' with a variable named 'countries' that contains the
     * list of countries obtained from the 'countryList' method of the 'service' object.
     */
    public function create()
    {
        $shippingcompanies = $this->service->shippingCompanyList();
    
        return $this->getDashboardView('logistics::shipping_company_vacation.create', compact('shippingcompanies'));
    }

    /**
     * This function stores data from a CreateRequest object and redirects to the city index page with
     * a success or error message.
     * 
     * param CreateRequest request  is an instance of the CreateRequest class, which is a
     * custom request class that extends the base Laravel request class. It contains the data submitted
     * by the user through a form or an API request, and it also includes validation rules and messages
     * for the data. The  parameter is passed to the
     * 
     * return If the `` variable is truthy, the function will return a redirect to the
     * `city.index` route with a flash message "Done". Otherwise, it will return a redirect to the
     * `city.create` route with a flash message "problem".
     */
    public function store(CreateRequest $request)
    {
        $data = $this->service->store($request);
        if ($data) {
            return redirect(route('shipping_company_vacation.index'))->with('Done');
        }
        return redirect(route('shipping_company_vacation.create'))->with('problem');
    }

    /**
     * This PHP function retrieves data for editing a city and returns a view with the data and a list
     * of countries.
     * 
     * param id The ID of the city that needs to be edited.
     * 
     * return a view named "logistics::shipping_company_vacation.edit" with the data and shippingcompanies passed as compact
     * variables.
     */
    public function edit($id)
    {
        $data = $this->service->show($id);
        $shippingcompanies = $this->service->shippingCompanyList();
       
        return $this->getDashboardView('logistics::shipping_company_vacation.edit', compact('data','shippingcompanies'));
    }

    /**
     * This function updates a city record and redirects the user to the city index page with a success
     * message or back to the edit page with an error message.
     * 
     * param EditRequest request  is an instance of the EditRequest class, which is a custom
     * request class that contains validation rules and messages for updating a city record in the
     * database. It contains the data submitted by the user through a form or an API request.
     * param id  is a parameter that represents the ID of the city that needs to be updated. It is
     * used to identify the specific city record in the database that needs to be updated.
     * 
     * return If the update is successful, the function will return a redirect to the index page with
     * a success message. If the update fails, the function will return a redirect to the edit page
     * with a problem message.
     */
    public function update(EditRequest $request, $id)
    {
        $data = $this->service->update($request, $id);
        if ($data) {
            return redirect(route('shipping_company_vacation.index'))->with("message", 'Done');
        }
        return redirect(route('shipping_company_vacation.edit', $id))->with('problem');
    }
}

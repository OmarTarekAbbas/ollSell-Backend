<?php

namespace Modules\Logistics\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Logistics\Http\Requests\ShippingCompany\CreateRequest;
use Modules\Logistics\Http\Requests\ShippingCompany\EditRequest;
use Modules\Logistics\Service\ShippingCompanyService;

class ShippingCompanyController extends BasicController
{
    protected $service;

    /**
     * This function constructs a ShippingCompanyService object and sets middleware permissions for various
     * actions.
     * 
     * param ShippingCompanyService Service The  parameter is an instance of the ShippingCompanyService class,
     * which is likely used to handle business logic related to countries in the application. It is
     * being injected into the constructor using dependency injection.
     */
    public function __construct(ShippingCompanyService $Service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_shipping_companies')->only('index');
        $this->middleware('permission:create_shipping_companies')->only(['create','store']);
        $this->middleware('permission:update_shipping_companies')->only(['edit','update']);
        $this->middleware('permission:delete_shipping_companies')->only('destroy');
        $this->service = $Service;
    }

    /**
     * This function returns a view for the shipping_companies index page or a DataTables response if the request
     * is made via AJAX.
     * 
     * param Request request  is an instance of the Request class, which represents an HTTP
     * request made to the application. It contains information about the request, such as the HTTP
     * method, URL, headers, and any data sent in the request body. In this code, it is used to check
     * if the request is an AJAX
     * 
     * return If the request is an AJAX request, the function returns a DataTables instance of the
     * data retrieved by the `` object. If the request is not an AJAX request, the function
     * returns the view for the shipping_companies index page.
     */
    public function index(Request $request)
    {
        $shipping_companies = $this->service->index($request);

        if ($request->ajax()) {
            return view('logistics::shipping_companies.table')->with(['shipping_companies' => $shipping_companies]);
        }


        return $this->getDashboardView('logistics::shipping_companies.index', compact('shipping_companies'));
    }

    /**
     * This PHP function returns a view for creating a new shipping_companies in a dashboard.
     * 
     * return a view named 'logistics::shipping_companies.create' which is related to the creation of a shipping_companies.
     */
    public function create()
    {
        return $this->getDashboardView('logistics::shipping_companies.create');
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
     * return a redirect response to either the 'shipping_companies.index' route with a 'Done' message in the
     * session data if the data was successfully stored, or to the 'shipping_companies.create' route with a
     * 'problem' message in the session data if there was an issue with storing the data.
     */
    public function store(CreateRequest $request)
    {
        $weekend='';
        if(!empty($request['weekend'])){
            for($i=0; $i < count($request['weekend']);$i++){
                $weekend .= $request['weekend'][$i];
                if($i+1 < count($request['weekend'])){
                    $weekend .= ',';
                }
            }
        }

        $request['weekend']= $weekend;
        $data = $this->service->store($request);
        if ($data) {
            return redirect(route('shipping_companies.index'))->with('Done');
        }
        return redirect(route('shipping_companies.create'))->with('problem');
    }

    /**
     * This PHP function retrieves data for a shipping_companies with a specific ID and returns a view for editing
     * that data.
     * 
     * param id The parameter `` is the identifier of the shipping_companies that needs to be edited. It is
     * used to retrieve the shipping_companies data from the database using the `show` method of the `service`
     * object. The retrieved data is then passed to the view for editing.
     * 
     * return a view named 'logistics::shipping_companies.edit' with the data of a specific shipping_companies that is
     * retrieved using the 'show' method of a service. The data is passed to the view using the
     * 'compact' function.
     */
    public function edit($id)
    {
        $data = $this->service->show($id);
        return $this->getDashboardView('logistics::shipping_companies.edit', compact('data'));
    }

    /**
     * This PHP function updates a record and redirects the user to the index page with a success
     * message or back to the edit page with an error message.
     * 
     * param EditRequest request  is an instance of the EditRequest class, which is a custom
     * request class that contains validation rules and messages for updating a shipping_companies record. It is
     * used to validate the incoming request data before processing it further.
     * param id  is a parameter that represents the ID of the shipping_companies that needs to be updated. It
     * is used to identify the specific shipping_companies record in the database that needs to be updated.
     * 
     * return If the update is successful, the function will return a redirect to the index page with
     * a success message. If the update fails, the function will return a redirect to the edit page
     * with a problem message.
     */
    public function update(EditRequest $request, $id)
    {
        $weekend='';
        if(!empty($request['weekend'])){
        for($i=0; $i < count($request['weekend']);$i++){
            $weekend .= $request['weekend'][$i];
            if($i+1 < count($request['weekend'])){
                $weekend .= ',';
            }
        }
    }
        $request['weekend']= $weekend;
        $data = $this->service->update($request, $id);
        if ($data) {
            return redirect(route('shipping_companies.index'))->with("message", 'Done');
        }
        return redirect(route('shipping_companies.edit', $id))->with('problem');
    }
}

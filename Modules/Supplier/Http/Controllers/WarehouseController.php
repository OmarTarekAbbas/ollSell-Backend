<?php

namespace Modules\Supplier\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Supplier\Service\WarehouseService;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Supplier\Http\Requests\WarehouseRequest;
use Modules\Supplier\Http\Requests\UpdateWarehouseRequest;
//todo change
class WarehouseController extends BasicController
{
    protected $service;

    /**
     * This function constructs a WarehouseService object and sets middleware permissions for various
     * actions related to warehouse management.
     *
     * param WarehouseService Service The WarehouseService class instance that will be injected into the
     * constructor of the current class. This is likely used to perform operations related to warehouses,
     * such as retrieving or updating them.
     *
     * param CountryService Service The CountryService class instance that will be injected into the
     * constructor of the current class. This is likely used to perform operations related to countries,
     * such as retrieving or updating them.
     *
     * param CityService Service The CityService class instance that will be injected into the
     * constructor of the current class. This is likely used to perform operations related to cities,
     * such as retrieving or updating them.
     */
    public function __construct(WarehouseService $service)
    {
        $this->middleware('auth:supplier');
        $this->service = $service;
    }

    /**
     * This PHP function returns a view of a warehouse table or a dashboard view depending on whether the
     * request is AJAX or not.
     *
     * param Request request  is an instance of the Request class, which represents an HTTP
     * request made to the application. It contains information about the request such as the HTTP
     * method, URL, headers, and any data sent in the request body. In this code,  is used to
     * pass data to the index() method
     *
     * return If the request is an AJAX request, a view called 'mastercatalog::warehouse.table' with the
     * warehouses passed as a parameter is being returned. Otherwise, the 'mastercatalog::warehouse.index'
     * view with the warehouses passed as a parameter is being returned within the dashboard view.
     */
    public function index(Request $request)
    {
        $warehouses = $this->service->index($request);
        $warehouseIsInternal=[];
        if(!isset($request->is_report))
        {
            $warehouseIsInternal = $this->service->getWarehouseIsInternal();
        }
        if ($request->ajax()) {
            return view('supplier::warehouses.table')->with(['warehouses' => $warehouses, 'warehouseIsInternal' => $warehouseIsInternal]);
        }

        return  $this->getDashboardView('supplier::warehouses.index', ['warehouses' => $warehouses,'warehouseIsInternal' => $warehouseIsInternal]);
    }

    /**
     * This PHP function returns a view for creating a new warehouse with categories and target markets
     * as options.
     *
     * return a view for creating a new warehouse with the categories and target markets passed as
     * variables.
     */
    public function show($id)
    {
        $data = $this->service->show($id);
        return $this->getDashboardView('supplier::warehouses.show', compact('data'));
    }

    /**
     * This PHP function returns a view for creating a new warehouse with categories and target markets
     * as options.
     *
     * return a view for creating a new warehouse with the categories and target markets passed as
     * variables.
     */
    public function create()
    {
        $countries = $this->service->countryList(request());
        return $this->getDashboardView('supplier::warehouses.create', get_defined_vars());
    }

    /**
     * This function stores data from a WarehouseRequest and redirects to the warehouse index page with a
     * success or error message.
     *
     * param WarehouseRequest request  is an instance of the WarehouseRequest class, which is a
     * custom request class that extends the base Laravel request class. It contains the data submitted
     * by the user through a form when creating a new warehouse. This data is validated against the rules
     * defined in the WarehouseRequest class before being passed to the store
     *
     * return a redirect response to either the 'warehouse.index' route with a 'Done' message in the
     * session data if the data was successfully stored, or to the 'warehouse.create' route with a
     * 'problem' message in the session data if there was an issue with storing the data.
     */
    public function store(WarehouseRequest $request)
    {
        $data = $this->service->store($request);
        if ($data) {
            return redirect(route('supplier.warehouse.index'))->with('Done');
        }
        return redirect(route('supplier.warehouse.create'))->with('problem');
    }

    /**
     * This PHP function retrieves data and lists of categories and target markets to be used in
     * editing a warehouse.
     *
     * param id The ID of the warehouse that needs to be edited.
     *
     * return a view for editing a warehouse with data, categories, and target markets passed as
     * variables to the view.
     */
    public function edit(Request $request, $id)
    {
        $data = $this->service->show($id);
        $countries = $this->service->countryList(request());
        $request['country_id'] = $data->country_id;
        //todo get by ajax
        $cities = $this->service->cityList($request);
        return $this->getDashboardView('supplier::warehouses.edit', get_defined_vars());
    }

    /**
     * This PHP function updates a warehouse and redirects the user to the warehouse index page with a
     * success message or back to the edit page with an error message.
     *
     * param EditRequest request  is an instance of the EditRequest class, which is a custom
     * request class that contains validation rules and messages for updating a warehouse. It is used to
     * validate the incoming request data before processing it further.
     * param id  is a parameter that represents the unique identifier of the warehouse that needs to
     * be updated. It is used to identify the specific warehouse record in the database that needs to be
     * updated.
     *
     * return If the update is successful, the function will return a redirect to the index page with
     * a success message. If the update fails, the function will return a redirect to the edit page
     * with a problem message.
     */
    public function update(UpdateWarehouseRequest $request, $id)
    {
        $data = $this->service->update($request, $id);
        if ($data) {
            return redirect(route('supplier.warehouse.index'))->with("message", 'Done');
        }
        return redirect(route('supplier.warehouse.edit', $id))->with('problem');
    }
}

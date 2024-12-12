<?php

namespace Modules\Acl\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Acl\Entities\Supplier;
use Modules\Acl\Service\SupplierService;
use Modules\Acl\Http\Requests\Supplier\EditRequest;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Acl\Http\Requests\Supplier\CreateRequest;

class SupplierController extends BasicController
{
    protected $service;

    /**
     * This function constructs a ProductService object and sets middleware permissions for various
     * actions related to supplier management.
     *
     * param ProductService Service The ProductService class instance that will be injected into the
     * constructor of the current class. This is likely used to perform operations related to suppliers,
     * such as retrieving or updating them.
     */
    public function __construct(SupplierService $service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_suppliers')->only('index');
        $this->middleware('permission:create_suppliers')->only(['create', 'store']);
        $this->middleware('permission:update_suppliers')->only(['edit', 'update']);
        $this->middleware('permission:delete_suppliers')->only('destroy');
        $this->service = $service;
    }

    /**
     * This PHP function returns a view of a supplier table or a dashboard view depending on whether the
     * request is AJAX or not.
     *
     * param Request request  is an instance of the Request class, which represents an HTTP
     * request made to the application. It contains information about the request such as the HTTP
     * method, URL, headers, and any data sent in the request body. In this code,  is used to
     * pass data to the index() method
     *
     * return If the request is an AJAX request, a view called 'mastercatalog::supplier.table' with the
     * suppliers passed as a parameter is being returned. Otherwise, the 'mastercatalog::supplier.index'
     * view with the suppliers passed as a parameter is being returned within the dashboard view.
     */
    public function index(Request $request)
    {
        $suppliers = $this->service->index($request);
        //todo use ajax
        $suppliersList = Supplier::get();
        if($request->ajax())
        {
            return view('acl::suppliers.table')->with(['suppliers' => $suppliers, 'suppliersList' => $suppliersList]);
        }
        return $this->getDashboardView('acl::suppliers.index',
            ['suppliers' => $suppliers, 'suppliersList' => $suppliersList]);
    }

    /**
     * This PHP function returns a view for creating a new supplier with categories and target markets
     * as options.
     *
     * return a view for creating a new supplier with the categories and target markets passed as
     * variables.
     */
    public function show($id)
    {
        $data = $this->service->show($id);
        return $this->getDashboardView('acl::suppliers.show', compact('data'));
    }

    /**
     * This PHP function returns a view for creating a new supplier with categories and target markets
     * as options.
     *
     * return a view for creating a new supplier with the categories and target markets passed as
     * variables.
     */
    public function create()
    {
        return $this->getDashboardView('acl::suppliers.create');
    }

    /**
     * This function stores data from a CreateRequest and redirects to the supplier index page with a
     * success or error message.
     *
     * param CreateRequest request  is an instance of the CreateRequest class, which is a
     * custom request class that extends the base Laravel request class. It contains the data submitted
     * by the user through a form when creating a new supplier. This data is validated against the rules
     * defined in the CreateRequest class before being passed to the store
     *
     * return a redirect response to either the 'supplier.index' route with a 'Done' message in the
     * session data if the data was successfully stored, or to the 'supplier.create' route with a
     * 'problem' message in the session data if there was an issue with storing the data.
     */
    public function store(CreateRequest $request)
    {
        //todo move to request file
        $request->validate([
            'password' => 'required|confirmed'
        ]);
        $data = $this->service->store($request);
        if($data)
        {
            return redirect(route('suppliers.index'))->with('Done');
        }
        return redirect(route('suppliers.create'))->with('problem');
    }

    /**
     * This PHP function retrieves data and lists of categories and target markets to be used in
     * editing a supplier.
     *
     * param id The ID of the supplier that needs to be edited.
     *
     * return a view for editing a supplier with data, categories, and target markets passed as
     * variables to the view.
     */
    public function edit($id)
    {
        $data = $this->service->show($id);
        return $this->getDashboardView('acl::suppliers.edit', compact('data'));
    }

    /**
     * This PHP function updates a supplier and redirects the user to the supplier index page with a
     * success message or back to the edit page with an error message.
     *
     * param EditRequest request  is an instance of the EditRequest class, which is a custom
     * request class that contains validation rules and messages for updating a supplier. It is used to
     * validate the incoming request data before processing it further.
     * param id  is a parameter that represents the unique identifier of the supplier that needs to
     * be updated. It is used to identify the specific supplier record in the database that needs to be
     * updated.
     *
     * return If the update is successful, the function will return a redirect to the index page with
     * a success message. If the update fails, the function will return a redirect to the edit page
     * with a problem message.
     */
    public function update(EditRequest $request, $id)
    {
        $data = $this->service->update($request, $id);
        if($data)
        {
            return redirect(route('suppliers.index'))->with("message", 'Done');
        }
        return redirect(route('suppliers.edit', $id))->with('problem');
    }
}

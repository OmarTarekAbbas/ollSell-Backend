<?php

namespace Modules\Basic\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Basic\Http\Requests\CustomTranslation\CreateRequest;
use Modules\Basic\Http\Requests\CustomTranslation\EditRequest;
use Modules\Basic\Service\CustomTranslationService;
use Yajra\DataTables\Facades\DataTables;

class CustomTranslationController extends BasicController
{
    protected $service;

    /**
     * This function constructs an object and sets middleware permissions for various actions related
     * to custom translations.
     * 
     * param CustomTranslationService Service The parameter `` is an instance of the
     * `CustomTranslationService` class that is injected into the constructor of the current class.
     * This is a dependency injection technique used to provide the necessary dependencies to the
     * class. The `CustomTranslationService` class is likely responsible for handling the business
     * logic related to
     */
    public function __construct(CustomTranslationService $Service)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_custom_translation')->only('index');
        $this->middleware('permission:create_custom_translation')->only('create');
        $this->middleware('permission:create_custom_translation')->only('store');
        $this->middleware('permission:update_custom_translation')->only('edit');
        $this->middleware('permission:update_custom_translation')->only('update');
        $this->middleware('permission:delete_custom_translation')->only('destroy');
        $this->service = $Service;
    }

    /**
     * This function returns a view for the custom translation index or a DataTables response if the
     * request is made via AJAX.
     * 
     * param Request request  is an instance of the Request class, which represents an HTTP
     * request made to the application. It contains information about the request such as the HTTP
     * method, URL, headers, and any data sent in the request body. In this code snippet, the 
     * object is used to check if the request
     * 
     * return If the request is an AJAX request, the function returns a DataTables instance with the
     * data obtained from the service's `findBy` method. If the request is not an AJAX request, the
     * function returns a view for the `basic::custom_translation.index` page.
     */
    public function index(Request $request)
    { //todo change
        if ($request->ajax()) {
            $builder = $this->service->findBy($request);
            return DataTables::of($builder)->make(true);
        }
        return $this->getDashboardView('basic::custom_translation.index');
    }

    /**
     * This PHP function returns a dashboard view for creating a custom translation.
     * 
     * return a view named 'basic::custom_translation.create' which is being retrieved using the
     * 'getDashboardView' method.
     */
    public function create()
    {
        return $this->getDashboardView('basic::custom_translation.create');
    }

    /**
     * This PHP function stores data and redirects the user to either the custom_translation index page
     * with a success message or the custom_translation create page with an error message.
     * 
     * param CreateRequest request  is an instance of the CreateRequest class, which is a
     * custom request class that extends the base Laravel request class. It contains the data submitted
     * by the user through a form or an API request, and it also includes any validation rules and
     * messages defined in the class. The  parameter is passed
     * 
     * return If the `` variable is truthy, the function will return a redirect to the
     * `custom_translation.index` route with a success message. Otherwise, it will return a redirect to
     * the `custom_translation.create` route with a problem message.
     */
    public function store(CreateRequest $request)
    {
        $data = $this->service->store($request);
        if ($data) {
            return redirect(route('custom_translation.index'))->with('Done');
        }
        return redirect(route('custom_translation.create'))->with('problem');
    }

    /**
     * This PHP function retrieves data for editing a custom translation and returns a view for the
     * dashboard.
     * 
     * param id The parameter `` is the identifier of the data that needs to be edited. It is used
     * to retrieve the data from the database using the `show` method of the `service` object. The
     * retrieved data is then passed to the view for editing.
     * 
     * return a view called 'basic::custom_translation.edit' with the data of the record with the
     * given  fetched from the service.
     */
    public function edit($id)
    {
        $data = $this->service->show($id);
        return $this->getDashboardView('basic::custom_translation.edit', compact('data'));
    }

    /**
     * This PHP function updates data and redirects the user with a success or error message.
     * 
     * param EditRequest request  is an instance of the EditRequest class, which is a custom
     * request class that contains validation rules and messages for updating a resource. It is used to
     * validate the incoming request data before processing it further.
     * param id  is a parameter that represents the unique identifier of the resource being
     * updated. It is used to identify the specific resource that needs to be updated in the database.
     * 
     * return If the update is successful, the function will return a redirect to the index page with
     * a success message. If the update fails, the function will return a redirect to the edit page
     * with a problem message.
     */
    public function update(EditRequest $request, $id)
    {
        $data = $this->service->update($request, $id);
        if ($data) {
            return redirect(route('custom_translation.index'))->with("message", 'Done');
        }
        return redirect(route('custom_translation.edit', $id))->with('problem');
    }
}

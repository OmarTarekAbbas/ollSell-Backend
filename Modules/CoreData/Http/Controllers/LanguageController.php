<?php

namespace Modules\CoreData\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\Http\Requests\Language\CreateRequest;
use Modules\CoreData\Http\Requests\Language\EditRequest;
use Modules\CoreData\Service\LanguageService;
use Yajra\DataTables\Facades\DataTables;

class LanguageController extends BasicController
{
    protected $service;

    /**
     * This function sets up middleware and assigns a LanguageService object to a class property.
     * 
     * param LanguageService Service An instance of the LanguageService class that provides
     * functionality for managing languages in the application.
     */
    public function __construct(LanguageService $Service)
    {
        $this->middleware('auth')->except(['language']);
        $this->middleware('admin')->except(['language']);
        $this->middleware('permission:view_language')->only('index');
        $this->middleware('permission:create_language')->only(['create','store']);
        $this->middleware('permission:update_language')->only(['edit','update']);
        $this->middleware('permission:delete_language')->only('destroy');
        $this->service = $Service;
    }

    /**
     * This function returns a view for the language index page or a DataTables response if the request
     * is made via AJAX.
     * 
     * param Request request  is an instance of the Request class, which represents an HTTP
     * request made to the application. It contains information about the request, such as the HTTP
     * method, URL, headers, and any data sent in the request body. In this code,  is used to
     * check if the request is an
     * 
     * return If the request is an AJAX request, the function returns a DataTables instance with the
     * data obtained from the service's `findBy` method. If the request is not an AJAX request, the
     * function returns the dashboard view for the `coredata::language.index` page.
     */
    public function index(Request $request)
    {
        //todo change
        if ($request->ajax()) {
            $builder = $this->service->findBy($request);
            return DataTables::of($builder)->make(true);
        }
        return $this->getDashboardView('coredata::language.index');
    }

    /**
     * This PHP function returns a view for creating a new language in a dashboard.
     * 
     * return a view named 'coredata::language.create' which is likely a form for creating a new
     * language.
     */
    public function create()
    {
        return $this->getDashboardView('coredata::language.create');
    }

    /**
     * This PHP function stores data from a CreateRequest and redirects to the language index page with
     * a success or error message.
     * 
     * param CreateRequest request  is an instance of the CreateRequest class, which is a
     * custom request class that extends the base Laravel request class. It contains the data submitted
     * by the user through a form or an API request, and it also includes validation rules and messages
     * for the data. The  parameter is passed to the
     * 
     * return If the `` variable is truthy, the function will return a redirect to the
     * `language.index` route with a success message. Otherwise, it will return a redirect to the
     * `language.create` route with a problem message.
     */
    public function store(CreateRequest $request)
    {
        $data = $this->service->store($request);
        if ($data) {
            return redirect(route('language.index'))->with('Done');
        }
        return redirect(route('language.create'))->with('problem');
    }

    /**
     * This PHP function retrieves data for a language and returns a view for editing it.
     * 
     * param id The parameter `` is the identifier of the language that needs to be edited. It is
     * used to retrieve the language data from the database and pass it to the view for editing.
     * 
     * return a view named "coredata::language.edit" with the data of the language to be edited passed
     * as a parameter.
     */
    public function edit($id)
    {
        $data = $this->service->show($id);
        return $this->getDashboardView('coredata::language.edit', compact('data'));
    }

    /**
     * This PHP function updates language data and redirects the user to the language index page with a
     * success message or back to the edit page with an error message.
     * 
     * param EditRequest request  is an instance of the EditRequest class, which is a custom
     * request class that contains validation rules and messages for updating a language record. It is
     * used to validate the incoming request data before processing it further.
     * param id  is a parameter that represents the unique identifier of the language record that
     * needs to be updated. It is used to identify the specific record in the database and update its
     * values based on the data provided in the EditRequest object.
     * 
     * return If the update is successful, the function will return a redirect to the index page with
     * a success message. If the update fails, the function will return a redirect to the edit page
     * with a problem message.
     */
    public function update(EditRequest $request, $id)
    {
        $data = $this->service->update($request, $id);
        if ($data) {
            return redirect(route('language.index'))->with("message", 'Done');
        }
        return redirect(route('language.edit', $id))->with('problem');
    }

    /**
     * The function sets a cookie for the selected language for a duration of one month and redirects
     * back to the previous page.
     * 
     * param Request request  is an instance of the Request class, which represents an HTTP
     * request made to the application. It contains information about the request, such as the HTTP
     * method, URL, headers, and any data sent in the request body.
     * 
     * return A redirect response with a cookie named "language" that contains the value of the "lang"
     * parameter from the request. The cookie will expire in 45000 seconds (or 12.5 hours).
     */
    public function language(Request $request)
    {
        //save for 1 month
        return redirect()->back()->withCookie('language', $request->lang, 45000);
    }
}

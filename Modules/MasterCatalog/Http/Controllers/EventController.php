<?php

namespace Modules\MasterCatalog\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\MasterCatalog\Http\Requests\Event\CreateRequest;
use Modules\MasterCatalog\Http\Requests\Event\EditRequest;
use Modules\MasterCatalog\Service\EventService;


class EventController extends BasicController
{
    protected $service;

    /**
     * This function constructs a EventService object and sets middleware permissions for various
     * actions related to event management.
     * 
     * param EventService Service The EventService class instance that will be injected into the
     * constructor of the current class. This is likely used to perform operations related to events,
     * such as retrieving or updating them.
     */
    public function __construct(EventService $Service)
    {//todo change
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_event')->only('index');
        $this->middleware('permission:create_event')->only('create');
        $this->middleware('permission:create_event')->only('store');
        $this->middleware('permission:update_event')->only('edit');
        $this->middleware('permission:update_event')->only('update');
        $this->middleware('permission:delete_event')->only('destroy');
        $this->service = $Service;
    }

    /**
     * This PHP function returns a view of a event table or a dashboard view depending on whether the
     * request is AJAX or not.
     * 
     * param Request request  is an instance of the Request class, which represents an HTTP
     * request made to the application. It contains information about the request such as the HTTP
     * method, URL, headers, and any data sent in the request body. In this code,  is used to
     * pass data to the index() method
     * 
     * return If the request is an AJAX request, a view called 'mastercatalog::event.table' with the
     * events passed as a parameter is being returned. Otherwise, the 'mastercatalog::event.index'
     * view with the events passed as a parameter is being returned within the dashboard view.
     */
    public function index(Request $request)
    {
        $events = $this->service->index($request);
        if ($request->ajax()) {
            return view('mastercatalog::event.table')->with(['events' => $events]);
        }
        return $this->getDashboardView('mastercatalog::event.index', ['events' => $events]);
    }

    /**
     * This PHP function returns a view for creating a new event with categories and target markets
     * as options.
     * 
     * return a view for creating a new event with the categories and target markets passed as
     * variables.
     */
    public function create()
    {
        return $this->getDashboardView('mastercatalog::event.create');
    }

    /**
     * This function stores data from a CreateRequest and redirects to the event index page with a
     * success or error message.
     * 
     * param CreateRequest request  is an instance of the CreateRequest class, which is a
     * custom request class that extends the base Laravel request class. It contains the data submitted
     * by the user through a form when creating a new event. This data is validated against the rules
     * defined in the CreateRequest class before being passed to the store
     * 
     * return a redirect response to either the 'event.index' route with a 'Done' message in the
     * session data if the data was successfully stored, or to the 'event.create' route with a
     * 'problem' message in the session data if there was an issue with storing the data.
     */
    public function store(CreateRequest $request)
    {
        $data = $this->service->store($request);
        if ($data) {
            return redirect(route('event.index'))->with('Done');
        }
        return redirect(route('event.create'))->with('problem');
    }

    /**
     * This PHP function retrieves data and lists of categories and target markets to be used in
     * editing a event.
     * 
     * param id The ID of the event that needs to be edited.
     * 
     * return a view for editing a event with data, categories, and target markets passed as
     * variables to the view.
     */
    public function edit($id)
    {
        $data = $this->service->show($id);
        return $this->getDashboardView('mastercatalog::event.edit', compact('data'));
    }

    /**
     * This PHP function updates a event and redirects the user to the event index page with a
     * success message or back to the edit page with an error message.
     * 
     * param EditRequest request  is an instance of the EditRequest class, which is a custom
     * request class that contains validation rules and messages for updating a event. It is used to
     * validate the incoming request data before processing it further.
     * param id  is a parameter that represents the unique identifier of the event that needs to
     * be updated. It is used to identify the specific event record in the database that needs to be
     * updated.
     * 
     * return If the update is successful, the function will return a redirect to the index page with
     * a success message. If the update fails, the function will return a redirect to the edit page
     * with a problem message.
     */
    public function update(EditRequest $request, $id)
    {
        $data = $this->service->update($request, $id);
        if ($data) {
            return redirect(route('event.index'))->with("message", 'Done');
        }
        return redirect(route('event.edit', $id))->with('problem');
    }

}

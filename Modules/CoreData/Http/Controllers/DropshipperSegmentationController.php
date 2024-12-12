<?php

namespace Modules\CoreData\Http\Controllers;

use Illuminate\Http\Request;
use Modules\CoreData\Service\DropshipperSegmentationService;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\CoreData\Http\Requests\DropshipperSegmentation\EditRequest;
use Modules\CoreData\Http\Requests\DropshipperSegmentation\CreateRequest;

class DropshipperSegmentationController extends BasicController
{
    protected $service;

    /**
     * This function sets up middleware and assigns a DropshipperSegmentationService object to the class property.
     * 
     * param DropshipperSegmentationService Service The RegionService class instance that is injected into the
     * constructor. It is likely used to perform business logic related to states in the application.
     */
    public function __construct(DropshipperSegmentationService $Service)
    {
        $this->middleware('auth');
        $this->middleware('permission:view_dropshipper_segmentation')->only('index');
        $this->middleware('permission:create_dropshipper_segmentation')->only(['create','store']);
        $this->middleware('permission:update_dropshipper_segmentation')->only(['edit','update']);
        $this->middleware('permission:delete_dropshipper_segmentation')->only('destroy');
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
        $dropshipper_segmentations = $this->service->index($request);
     //   return  $dropshipper_segmentations;

        if ($request->ajax()) {
            return view('coredata::dropshipper_segmentation.table')->with(['dropshipper_segmentations' => $dropshipper_segmentations]);
        }


        return $this->getDashboardView('coredata::dropshipper_segmentation.index', compact('dropshipper_segmentations'));
    }

    /**
     * This PHP function returns a view for creating a new dropshipper_segmentation with a list of countries.
     * 
     * @return a view called 'coredata::dropshipper_segmentation.create' with a variable called 'countries' that contains
     * a list of countries obtained from the 'countryList' method of the 'service' object.
     */
    public function create()
    {
        return $this->getDashboardView('coredata::dropshipper_segmentation.create');
    }

    /**
     * This PHP function stores data from a CreateRequest object and redirects to the dropshipper_segmentation index page
     * with a success or error message.
     * 
     * param CreateRequest request  is an instance of the CreateRequest class, which is a
     * custom request class that extends the base Laravel request class. It contains the data submitted
     * by the user through a form or an API request, and it also includes any validation rules and
     * messages defined in the class. The  parameter is passed
     * 
     * @return If the `` variable is truthy, the function will return a redirect to the
     * `dropshipper_segmentation.index` route with a flash message "Done". Otherwise, it will return a redirect to the
     * `dropshipper_segmentation.create` route with a flash message "problem".
     */
    public function store(CreateRequest $request)
    {
        $data = $this->service->store($request);
        if ($data) {
            return redirect(route('dropshipper_segmentation.index'))->with('Done');
        }
        return redirect(route('dropshipper_segmentation.create'))->with('problem');
    }

    /**
     * This PHP function retrieves data for editing a dropshipper_segmentation and returns a view with the data and a list
     * of countries.
     * 
     * param id The parameter "id" is the identifier of the dropshipper_segmentation that needs to be edited. It is used
     * to retrieve the data of the dropshipper_segmentation from the service layer and pass it to the view for editing.
     * 
     * @return a view named 'coredata::dropshipper_segmentation.edit' with the variables  and  passed to
     * it.
     */
    public function edit($id)
    {
        $data = $this->service->show($id);
   
        return $this->getDashboardView('coredata::dropshipper_segmentation.edit', compact('data'));
    }

    /**
     * This PHP function updates data and redirects to the index page with a success message or back to
     * the edit page with a problem message.
     * 
     * param EditRequest request  is an instance of the EditRequest class, which is a custom
     * request class that contains validation rules and messages for updating a dropshipper_segmentation in the
     * application. It is used to validate the incoming request data before processing it further.
     * param id  is a parameter that represents the unique identifier of the dropshipper_segmentation that needs to be
     * updated. It is used to identify the specific dropshipper_segmentation record in the database that needs to be
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
            return redirect(route('dropshipper_segmentation.index'))->with("message", 'Done');
        }
        return redirect(route('dropshipper_segmentation.edit', $id))->with('problem');
    }
}

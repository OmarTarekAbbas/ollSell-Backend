<?php

namespace Modules\MasterCatalog\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\MasterCatalog\Http\Requests\Attribute\CreateRequest;
use Modules\MasterCatalog\Http\Requests\Attribute\EditRequest;
use Maatwebsite\Excel\Facades\Excel;
use Modules\MasterCatalog\Imports\AttributeImport;
use Illuminate\Support\Facades\Storage;
use Modules\MasterCatalog\Service\AttributeService;

class AttributeController extends BasicController
{
    protected $service;

    /**
     * This function constructs a AttributeService object and sets middleware permissions for various
     * actions related to attribute management.
     * 
     * param AttributeService Service The AttributeService class instance that will be injected into the
     * constructor of the current class. This is likely used to perform operations related to attributes,
     * such as retrieving or updating them.
     */
    public function __construct(AttributeService $Service)
    {//todo change
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_attribute')->only('index');
        $this->middleware('permission:create_attribute')->only('create');
        $this->middleware('permission:create_attribute')->only('store');
        $this->middleware('permission:update_attribute')->only('edit');
        $this->middleware('permission:update_attribute')->only('update');
        $this->middleware('permission:delete_attribute')->only('destroy');
        $this->service = $Service;
    }

    /**
     * This PHP function returns a view of a attribute table or a dashboard view depending on whether the
     * request is AJAX or not.
     * 
     * param Request request  is an instance of the Request class, which represents an HTTP
     * request made to the application. It contains information about the request such as the HTTP
     * method, URL, headers, and any data sent in the request body. In this code,  is used to
     * pass data to the index() method
     * 
     * return If the request is an AJAX request, a view called 'mastercatalog::attribute.table' with the
     * attributes passed as a parameter is being returned. Otherwise, the 'mastercatalog::attribute.index'
     * view with the attributes passed as a parameter is being returned within the dashboard view.
     */
    public function index(Request $request)
    {
        $attributes = $this->service->index($request);
        if ($request->ajax()) {
            return view('mastercatalog::attribute.table')->with(['attributes' => $attributes]);
        }
        return $this->getDashboardView('mastercatalog::attribute.index', ['attributes' => $attributes]);
    }

    /**
     * This PHP function returns a view for creating a new attribute with categories and target markets
     * as options.
     * 
     * return a view for creating a new attribute with the categories and target markets passed as
     * variables.
     */
    public function create()
    {
        return $this->getDashboardView('mastercatalog::attribute.create');
    }

    /**
     * This function stores data from a CreateRequest and redirects to the attribute index page with a
     * success or error message.
     * 
     * param CreateRequest request  is an instance of the CreateRequest class, which is a
     * custom request class that extends the base Laravel request class. It contains the data submitted
     * by the user through a form when creating a new attribute. This data is validated against the rules
     * defined in the CreateRequest class before being passed to the store
     * 
     * return a redirect response to either the 'attribute.index' route with a 'Done' message in the
     * session data if the data was successfully stored, or to the 'attribute.create' route with a
     * 'problem' message in the session data if there was an issue with storing the data.
     */
    public function store(CreateRequest $request)
    {
        $data = $this->service->store($request);
        if ($data) {
            return redirect(route('attribute.index'))->with('Done');
        }
        return redirect(route('attribute.create'))->with('problem');
    }

    /**
     * This PHP function retrieves data and lists of categories and target markets to be used in
     * editing a attribute.
     * 
     * param id The ID of the attribute that needs to be edited.
     * 
     * return a view for editing a attribute with data, categories, and target markets passed as
     * variables to the view.
     */
    public function edit($id)
    {
        $data = $this->service->show($id);
        return $this->getDashboardView('mastercatalog::attribute.edit', compact('data'));
    }

    /**
     * This PHP function updates a attribute and redirects the user to the attribute index page with a
     * success message or back to the edit page with an error message.
     * 
     * param EditRequest request  is an instance of the EditRequest class, which is a custom
     * request class that contains validation rules and messages for updating a attribute. It is used to
     * validate the incoming request data before processing it further.
     * param id  is a parameter that represents the unique identifier of the attribute that needs to
     * be updated. It is used to identify the specific attribute record in the database that needs to be
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
            return redirect(route('attribute.index'))->with("message", 'Done');
        }
        return redirect(route('attribute.edit', $id))->with('problem');
    }

    /**
     * This PHP function imports an Excel file and checks if the file extension is valid before
     * importing.
     *
     * param Request request  is an instance of the Illuminate\Http\Request class which
     * represents an incoming HTTP request. It contains information about the request such as the HTTP
     * method, headers, and any data that was sent with the request. In this case, it is used to
     * retrieve the uploaded file from the request.
     *
     * return either a success message if the file extension is valid and the Excel file is
     * successfully imported using the OrderImport class, or a bad request message if the file
     * extension is invalid or there is an exception thrown during the import process.
     */
    public function import(Request $request)
    {

        $extensions = ["xls", "xlsx", "csv", "xlm", "xla", "xlc", "xlt", "xlw"];
        if (!empty($request->file('excelFile'))) {
            $fileExtension = $request->file('excelFile')->getClientOriginalExtension();
            $files = Storage::disk('public_missings')->allFiles();
            Storage::delete($files);
            Storage::delete('attributes_importing_counts.json');
            Storage::delete('attributes_failed_rows.json');

            Storage::put('attributes_import_file.json', $request->file('excelFile')->store('files'));
            $id = now()->unix();
            $data = ["id" => $id];
            Storage::put('attributes_import_data.json', json_encode($data));
            if (in_array($fileExtension, $extensions)) {
                Excel::import(new AttributeImport, $request->file('excelFile'));
                return redirect(route('attribute.importfile'))->with('message', 'Success Upload Excel File.');
            } else {
                return redirect(route('attribute.importfile'))->with('message', 'Please Upload Excel File.');
            }
        }
        return redirect(route('attribute.importfile'))->with('message', 'Please Upload Excel File.');
    }

    /**
     * Method getDownload
     *
     * return void
     */
    public function getDownload()
    {
        $file = base_path() . "/attribute-excel/AttributeImport.xlsx";
        $headers = [
            'Content-Type: application/xlsx',
        ];
        return response()->download($file, 'AttributeImport.xlsx', $headers);
    }

    /**
     * This function calls the download method of a service object and returns its result.
     * 
     * return The `download()` method of the `` object is being returned.
     */
    public function download()
    {
        return $this->service->download();
    }
}

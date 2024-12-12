<?php

namespace Modules\MasterCatalog\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\MasterCatalog\Http\Requests\Bundle\CreateRequest;
use Modules\MasterCatalog\Http\Requests\Bundle\EditRequest;
use Modules\MasterCatalog\Service\BundleService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Modules\MasterCatalog\Service\BundleProductService;


class BundleController extends BasicController
{
    protected $service;
    protected $bundleProductService;

    /**
     * This function constructs a ProductService object and sets middleware permissions for various
     * actions related to bundle management.
     *
     * param ProductService Service The ProductService class instance that will be injected into the
     * constructor of the current class. This is likely used to perform operations related to bundles,
     * such as retrieving or updating them.
     */
    public function __construct(BundleService $Service, BundleProductService $bundleProductService)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->middleware('permission:view_bundle')->only('index');
        $this->middleware('permission:create_bundle')->only(['create', 'store']);
        $this->middleware('permission:update_bundle')->only(['edit', 'update']);
        $this->middleware('permission:delete_bundle')->only('destroy');
        $this->middleware('permission:extract_bundle')->only('extract');
        $this->service = $Service;
        $this->bundleProductService = $bundleProductService;
    }

    /**
     * This PHP function returns a view of a bundle table or a dashboard view depending on whether the
     * request is AJAX or not.
     *
     * param Request request  is an instance of the Request class, which represents an HTTP
     * request made to the application. It contains information about the request such as the HTTP
     * method, URL, headers, and any data sent in the request body. In this code,  is used to
     * pass data to the index() method
     *
     * return If the request is an AJAX request, a view called 'mastercatalog::bundle.table' with the
     * bundles passed as a parameter is being returned. Otherwise, the 'mastercatalog::bundle.index'
     * view with the bundles passed as a parameter is being returned within the dashboard view.
     */
    public function index(Request $request)
    {

        $bundles = $this->service->index($request,pagination:true,perPage:$this->perPage());

        if($request->ajax())
        {
            return view('mastercatalog::bundle.table')->with(['bundles' => $bundles]);
        }
  
        return $this->getDashboardView('mastercatalog::bundle.index',
            ['bundles' => $bundles]);
    }


    /**
     * This PHP function returns a view for creating a new bundle with categories and target markets
     * as options.
     *
     * return a view for creating a new bundle with the categories and target markets passed as
     * variables.
     */
    public function create()
    {
        $availableProducts = $this->service->getAvailableProducts();

        return $this->getDashboardView('mastercatalog::bundle.create', [
            'availableProducts' => $availableProducts,
        ]);
    }

    /**
     * This function stores data from a CreateRequest and redirects to the bundle index page with a
     * success or error message.
     *
     * param CreateRequest request  is an instance of the CreateRequest class, which is a
     * custom request class that extends the base Laravel request class. It contains the data submitted
     * by the user through a form when creating a new bundle. This data is validated against the rules
     * defined in the CreateRequest class before being passed to the store
     *
     * return a redirect response to either the 'bundle.index' route with a 'Done' message in the
     * session data if the data was successfully stored, or to the 'bundle.create' route with a
     * 'problem' message in the session data if there was an issue with storing the data.
     */
    public function store(CreateRequest $request)
    {
        $bundleDiscount = (float) setting('bundle_discount');
        if ($request->discount > $bundleDiscount) {
            return redirect()->back()->withErrors(['discount' => 'The discount cannot exceed the maximum allowed discount of ' . $bundleDiscount . '%.']);
        }


        $bundle = $this->service->store($request);

        if ($bundle) {
            return redirect(route('bundles.index'))->with('Done');
        }
        return redirect(route('bundles.create'))->with('problem');
    }

    /**
     * This PHP function retrieves data and lists of categories and target markets to be used in
     * editing a bundle.
     *
     * param id The ID of the bundle that needs to be edited.
     *
     * return a view for editing a bundle with data, categories, and target markets passed as
     * variables to the view.
     */
    public function edit($id)
    {
        $data = $this->service->show($id);

        return $this->getDashboardView('mastercatalog::bundle.edit', ['data' => $data]);
    }

    /**
     * This PHP function updates a bundle and redirects the user to the bundle index page with a
     * success message or back to the edit page with an error message.
     *
     * param EditRequest request  is an instance of the EditRequest class, which is a custom
     * request class that contains validation rules and messages for updating a bundle. It is used to
     * validate the incoming request data before processing it further.
     * param id  is a parameter that represents the unique identifier of the bundle that needs to
     * be updated. It is used to identify the specific bundle record in the database that needs to be
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
            return redirect(route('bundles.index'))->with("message", 'Done');
        }
        return redirect(route('bundles.edit', $id))->with('problem');
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
        if(!empty($request->file('excelFile')))
        {
            $fileExtension = $request->file('excelFile')->getClientOriginalExtension();
            Storage::put('bundles_import_file.json', $request->file('excelFile')->store('files'));
            $id = now()->unix();
            $data = ["id" => $id];
            Storage::put('bundles_import_data.json', json_encode($data));
            if(in_array($fileExtension, $extensions))
            {
                $files = public_path('/missings/bundles_failed_rows.xlsx');
                $files1 = public_path('/missings/bundles_failed_rows.json');
                $files2 = public_path('/missings/bundles_importing_counts.json');
                if(is_file($files))
                {
                    unlink($files);
                    unlink($files1);
                    unlink($files2);
                }
              
                updateSettings('uploading_bundles', 'true');
                Excel::queueImport(new BundleImport, $request->file('excelFile'));
                return redirect(route('bundle.importfile'))->with('message', 'Success Upload Excel File.');
            }else
            {
                return redirect(route('bundle.importfile'))->with('message', 'Please Upload Excel File.');
            }
        }
        return redirect(route('bundle.importfile'))->with('message', 'Please Upload Excel File.');
    }

    /**
     * Method getDownload
     *
     * return void
     */
    public function getDownload()
    {
        $file = base_path() . "/bundle-excel/BundleImport.xlsx";
        $headers = [
            'Content-Type: application/xlsx',
        ];
        return response()->download($file, 'BundleImport.xlsx', $headers);
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

    /**
     * @throws Exception
     */
    public function checkUploadingStatus()
    {
        Artisan::call('queue:work --stop-when-empty');
        $id = 0;
        if(Storage::has('import.json'))
        {
            $import_file = Storage::get("import.json");
            $import_file = json_decode($import_file, true);
            $id = $import_file['id'];
        }
        return response([
            'started' => filled(cache("start_date_$id")),
            'finished' => filled(cache("end_date_$id")),
            'current_row' => (int)cache("current_row_$id"),
            'total_rows' => (int)cache("total_rows_$id"),
        ]);
    }

    public function deleteImage(Request $request)
    {
       $this->service->deleteBundleImage($request);
        return response()->json(['data' => 'Deleted successfully'], 200);
    }

    /**
     * The function checks if a bundle variant with a specific SKU exists in the database.
     *
     * param Request request The  parameter is an instance of the Request class, which is used
     * to retrieve data from the HTTP request. In this case, it is used to retrieve the value of the
     * 'sku' parameter from the request.
     *
     * return a boolean value. If a bundle variant with the specified SKU exists, it will return true.
     * Otherwise, it will return false.
     */
    public function checkVariantSku(Request $request)
    {
        $bundleVariant = BundleVariant::where('sku', $request->sku)->first();
        if($bundleVariant)
        {
            return true;
        }
        return false;
    }

    /**
     * The function "listBundlesSupplier" retrieves a list of bundles from a supplier and returns a
     * view with the bundles and an import file.
     *
     * param Request request The  parameter is an instance of the Request class, which is used
     * to retrieve information from the HTTP request made to the server. It contains data such as the
     * request method, headers, query parameters, form data, and more. In this case, it is used to check
     * if the request is
     *
     * return If the request is an AJAX request, the function will return a view called
     * 'mastercatalog::bundle.table' with the 'bundles' variable passed to it.
     */
    public function listBundlesSupplier(Request $request)
    {
        if(isset($request->success) && $request->success == 'true')
        {
            //todo fix typo
            updateSettings('uploading_bundles', 'false');
        }
        $import_file = "";
        if(Storage::disk('public_missings')->has('bundles_importing_counts.json'))
        {
            $import_file = Storage::disk('public_missings')->get('bundles_importing_counts.json');
        }
        $bundles = $this->service->listBundlesSupplier($request);
        if($request->ajax())
        {
            return view('mastercatalog::bundle.tableListBundlesSupplier')->with(['bundles' => $bundles]);
        }
        return $this->getDashboardView('mastercatalog::bundle.listBundlesSupplier',
            ['bundles' => $bundles, 'import_file' => $import_file]);
    }

    /**
     * This function updates the isApproved field of a bundle to 1 and redirects the user to the
     * bundle index page with a success message if the update is successful, otherwise it redirects the
     * user to the bundle edit page with an error message.
     *
     * param Request request The  parameter is an instance of the Request class, which contains
     * all the data that was sent with the HTTP request.
     * param id The  parameter is the identifier of the supplier whose approved bundles are being
     * updated.
     *
     * return a redirect response. If the data is successfully updated, it will redirect to the
     * 'bundle.index' route with a success message. If the data update fails, it will redirect to the
     * 'bundle.edit' route with a problem message.
     */
    public function approvedBundlesSupplier(Request $request, $id)
    {
        $data = $this->service->approvedBundlesSupplier($request, $id);
        if($data)
        {
            return redirect(route('bundle.listBundlesSupplier'))->with("message", 'Done');
        }
        return redirect(route('bundle.approvedBundlesSupplier', $id))->with('problem');
    }

    /**
     * The function "getCommissionCategory" retrieves the commission values of categories based on
     * their IDs, sorts them in descending order, and returns the highest commission value.
     *
     * param Request request The  parameter is an instance of the Request class, which is used
     * to retrieve data from the HTTP request. In this case, it is used to retrieve the 'ids' parameter
     * from the request.
     *
     * return the highest commission value from the categories that match the given IDs.
     */
    public function getCommissionCategory(Request $request)
    {
        $categoryIds = $request->input('categoryIds');
        //todo
        $commissions = Category::whereIn('id', $categoryIds)->pluck('commission')->toArray();
        $highestCommission = max($commissions);
        return response()->json(['highestCommission' => $highestCommission]);
    }

    /**
     * The function exports bundle data by an admin user in Excel format.
     *
     * return a download of an Excel file.
     */
    public function exportBundleByAdmin()
    {
        return Excel::download(new BundleExportByAdmin, 'BundleExportByAdmin.xlsx');
    }

    public function search(Request $request)
    {
        return $this->service->search($request);
    }

    public function relatedBundle($id)
    {
        return $this->service->relatedBundle($id);
    }
}

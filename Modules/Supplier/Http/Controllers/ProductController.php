<?php

namespace Modules\Supplier\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Modules\Supplier\Service\ProductService;
use Modules\MasterCatalog\Entities\Attribute;
use Modules\Basic\Http\Controllers\BasicController;
use Modules\Supplier\Imports\ProductSupplierImport;
use Modules\Supplier\Imports\ProductSupplierImportBasic;
use Modules\Supplier\Http\Requests\StoreProductRequest;
use Modules\Supplier\Http\Requests\UpdateProductRequest;
use Modules\Supplier\Http\Requests\CategorySuggestRequest;
use Modules\MasterCatalog\Exports\Supplier\ProductExportBySupplier;
//todo change
class ProductController extends BasicController
{
    protected $service;

    /**
     * This function constructs a ProductService object and sets middleware permissions for various
     * actions related to product management.
     *
     * param ProductService Service The ProductService class instance that will be injected into the
     * constructor of the current class. This is likely used to perform operations related to products,
     * such as retrieving or updating them.
     */
    public function __construct(ProductService $Service)
    {
        $this->service = $Service;
    }

    /**
     * This PHP function returns a view of a product table or a dashboard view depending on whether the
     * request is AJAX or not.
     *
     * param Request request  is an instance of the Request class, which represents an HTTP
     * request made to the application. It contains information about the request such as the HTTP
     * method, URL, headers, and any data sent in the request body. In this code,  is used to
     * pass data to the index() method
     *
     * return If the request is an AJAX request, a view called 'supplier::product.table' with the
     * products passed as a parameter is being returned. Otherwise, the 'supplier::product.index'
     * view with the products passed as a parameter is being returned within the dashboard view.
     */
    public function index(Request $request)
    {
        
        if (isset($request->success) && $request->success == 'true') {
            //todo fix typo
            updateSettings('uploading_products', 'false');
        }
        $import_file = "";
        if (Storage::disk('public_missings')->has('products/' . auth()->id() . '/products_importing_counts.json')) {
            $import_file = Storage::disk('public_missings')->get('products/' . auth()->id() . '/products_importing_counts.json');
        }
        $products = $this->service->index($request,pagination:true,perPage:$this->perPage());
        if ($request->ajax()) {
            return view('supplier::product.table')->with(['products' => $products]);
        }
        return  $this->getDashboardView('supplier::product.index', ['products' => $products, 'import_file' => $import_file]);
    }

    /**
     * This function retrieves and returns a view with data from a JSON file and a file URL.
     *
     * return a view with the variables `` and `` passed to it. The view being
     * returned is `supplier::product.importfile`.
     */
    public function importFile()
    {
        $import_file = "";
        if (Storage::disk('public_missings')->has('products_importing_counts.json')) {
            $import_file = Storage::disk('public_missings')->get('products_importing_counts.json');
        }
        $fileUrl = null;
        $files = Storage::disk('public_missings')->allFiles();
        if ($files) {
            $fileUrl = route('supplier.product.files.download');
        }
        return $this->getDashboardView('supplier::product.importfile', compact('import_file', 'fileUrl'));
    }

    public function importbasicfile()
    {
        $import_file = "";
        if (Storage::disk('public_missings')->has('products_importing_counts.json')) {
            $import_file = Storage::disk('public_missings')->get('products_importing_counts.json');
        }
        $fileUrl = null;
        $files = Storage::disk('public_missings')->allFiles();
        if ($files) {
            $fileUrl = route('supplier.product.files.download');
        }
        return $this->getDashboardView('supplier::product.importbasicfile', compact('import_file', 'fileUrl'));
    }
    

    /**
     * This PHP function returns a view for creating a new product with categories and target markets
     * as options.
     *
     * return a view for creating a new product with the categories and target markets passed as
     * variables.
     */
    public function create()
    {
        $categories = $this->service->categoryList();
        //todo change
        $attributes = Attribute::limit(3)->get();
        $events = $this->service->getEventsList();
        $warehouses = $this->service->getWarehouses();
        $warehouseIsInternal = $this->service->getWarehouseIsInternal();

        return $this->getDashboardView('supplier::product.create', compact('categories', 'attributes', 'events', 'warehouses', 'warehouseIsInternal'));
    }

    /**
     * This function stores data from a CreateRequest and redirects to the product index page with a
     * success or error message.
     *
     * param CreateRequest request  is an instance of the CreateRequest class, which is a
     * custom request class that extends the base Laravel request class. It contains the data submitted
     * by the user through a form when creating a new product. This data is validated against the rules
     * defined in the CreateRequest class before being passed to the store
     *
     * return a redirect response to either the 'product.index' route with a 'Done' message in the
     * session data if the data was successfully stored, or to the 'product.create' route with a
     * 'problem' message in the session data if there was an issue with storing the data.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $this->service->store($request);
        if ($data) {
            return redirect(route('supplier.product.index'))->with('Done');
        }
        return redirect(route('supplier.product.create'))->with('problem');
    }

    /**
     * This PHP function retrieves data and lists of categories and target markets to be used in
     * editing a product.
     *
     * param id The ID of the product that needs to be edited.
     *
     * return a view for editing a product with data, categories, and target markets passed as
     * variables to the view.
     */
    public function edit($id)
    {
        $data = $this->service->show($id);
        $categories = $this->service->categoryList();
        //todo change
        $attributes = Attribute::limit(3)->get();
        $events = $this->service->getEventsList();
        $warehouses = $this->service->getWarehouses();
        $warehouseIsInternal = $this->service->getWarehouseIsInternal();
        $hasVariations = $data->attributes->count() ? true : false;
        // Prepare variants data
        $variants = $data->variants_data;
        // Prepare attributes data
        $existingAttributes = $data->attributes_data ?? '';

        return $this->getDashboardView('supplier::product.edit', compact('data', 'categories', 'attributes', 'events', 'warehouses', 'warehouseIsInternal', 'variants', 'existingAttributes', 'hasVariations'));
    }

    /**
     * This PHP function updates a product and redirects the user to the product index page with a
     * success message or back to the edit page with an error message.
     *
     * param EditRequest request  is an instance of the EditRequest class, which is a custom
     * request class that contains validation rules and messages for updating a product. It is used to
     * validate the incoming request data before processing it further.
     * param id  is a parameter that represents the unique identifier of the product that needs to
     * be updated. It is used to identify the specific product record in the database that needs to be
     * updated.
     *
     * return If the update is successful, the function will return a redirect to the index page with
     * a success message. If the update fails, the function will return a redirect to the edit page
     * with a problem message.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $data = $this->service->update($request, $id);
        if ($data) {
            return redirect(route('supplier.product.index'))->with("message", 'Done');
        }
        return redirect(route('supplier.product.edit', $id))->with('problem');
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

            $filePath = $request->file('excelFile')->store('files');
            $fileName = basename($filePath);
            $id = now()->unix();
            $data = [
                "id" => $id,
                "path" => $filePath,
                "file_name" => $fileName
            ];
            Storage::put('products_import_data.json', json_encode($data));
            $request->merge(['path' => $filePath]);
            if (in_array($fileExtension, $extensions)) {

                $files = public_path('/missings/products_failed_rows.xlsx');
                $files1 = public_path('/missings/products_failed_rows.json');
                $files2 = public_path('/missings/products_importing_counts.json');

                if (is_file($files)) {
                    unlink($files);
                }

                if (is_file($files1)) {
                    unlink($files1);
                }

                if (is_file($files2)) {
                    unlink($files2);
                }

                updateSettings('uploading_products', 'true');
                Excel::queueImport(new ProductSupplierImport, $request->file('excelFile'));

                return redirect(route('supplier.product.importfile'))->with('message', 'Success Upload Excel File.');
            } else {
                return redirect(route('supplier.product.importfile'))->with('message', 'Please Upload Excel File.');
            }
        }
        return redirect(route('supplier.product.importfile'))->with('message', 'Please Upload Excel File.');
    }

    public function importBasic(Request $request)
    {
        $extensions = ["xls", "xlsx", "csv", "xlm", "xla", "xlc", "xlt", "xlw"];
        if(!empty($request->file('excelFile')))
        {
            $fileExtension = $request->file('excelFile')->getClientOriginalExtension();
            Storage::put('products_import_file.json', $request->file('excelFile')->store('files'));
            $id = now()->unix();
            $data = ["id" => $id];
            Storage::put('products_import_data.json', json_encode($data));
            if(in_array($fileExtension, $extensions))
            {
                $files = public_path('/missings/products/' . auth()->id() . '/products_failed_rows.xlsx');
                $files1 = public_path('/missings/products/' . auth()->id() . '/products_failed_rows.json');
                $files2 = public_path('/missings/products/' . auth()->id() . '/products_importing_counts.json');
                if (is_file($files)) {
                    unlink($files);
                }

                if (is_file($files1)) {
                    unlink($files1);
                }

                if (is_file($files2)) {
                    unlink($files2);
                }
                updateSettings('uploading_products', 'true');
                Excel::queueImport(new ProductSupplierImportBasic, $request->file('excelFile'));

                return redirect(route('supplier.product.importbasicfile'))->with('message', 'Success Upload Excel File.');
            } else {
                return redirect(route('supplier.product.importbasicfile'))->with('message', 'Please Upload Excel File.');
            }
        }
        return redirect(route('supplier.product.importbasicfile'))->with('message', 'Please Upload Excel File.');
    }
    

    /**
     * Method getDownload
     *
     * return void
     */
    public function getDownload($type=null)
    {
       if($type == 'Basic'){
        $file = base_path() . "/product-excel/ProductBasicImport.xlsx";
        $headers = [
            'Content-Type: application/xlsx',
        ];
        return response()->download($file, 'ProductBasicImport.xlsx', $headers);
       }else{
        $file = base_path() . "/product-excel/ProductImport.xlsx";
        $headers = [
            'Content-Type: application/xlsx',
        ];
        return response()->download($file, 'ProductImport.xlsx', $headers);
       }

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
        if (Storage::has('import.json')) {
            $import_file = Storage::get("import.json");
            $import_file = json_decode($import_file, true);
            $id = $import_file['id'];
        }
        return response([
            'started' => filled(cache("start_date_$id")),
            'finished' => filled(cache("end_date_$id")),
            'current_row' => (int) cache("current_row_$id"),
            'total_rows' => (int) cache("total_rows_$id"),
        ]);
    }

    public function deleteImage(Request $request)
    {
        $response = $this->service->deleteProductImage($request);
        return response()->json(['data' => 'Deleted successfully'], 200);
    }

    /**
     * The function "storeCategoryBySupplier" stores a category by supplier and redirects to the
     * supplier's product index page with a success message.
     *
     * param Request request The  parameter is an instance of the Request class, which contains
     * all the data that was sent with the HTTP request. It is used to retrieve the input data from the
     * form or any other data sent in the request.
     *
     * return a redirect response to the "supplier.product.index" route with a success message.
     */
    public function storeCategoryBySupplier(CategorySuggestRequest $request)
    {
        $data = $this->service->storeCategoryBySupplier($request);
        if ($data) {
            return redirect(route('supplier.product.index'))->with("message", 'Done');
        }
    }

    /**
     * The function exports product data by an admin user in Excel format.
     *
     * return a download of an Excel file.
     */
    public function exportProductBySuppler()
    {
        return Excel::download(new ProductExportBySupplier,  'ProductExportBySupplier.xlsx');
    }

    public function search(Request $request)
    {
        return $this->service->search($request);
    }
}
